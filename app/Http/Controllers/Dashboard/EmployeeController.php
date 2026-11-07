<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('manage-employees');

        $roles = array_keys(config('employee_permissions.roles'));
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in($roles)],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'tab' => ['nullable', Rule::in(['employees', 'permissions'])],
        ]);

        $employees = User::query()
            ->whereIn('role', $roles)
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($filters['role'] ?? null, function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $enabled = DB::table('role_permissions')
            ->get()
            ->groupBy('role')
            ->map(fn ($rows) => $rows->pluck('permission')->all())
            ->all();

        return view('dashboard.employees.index', [
            'employees' => $employees,
            'enabled' => $enabled,
            'filters' => $filters,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-employees');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'role' => ['required', Rule::in(['approver', 'gate'])],
            'is_active' => ['required', 'boolean'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ]);

        DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);

            $employee = User::create($data);

            ActivityLog::record(
                'أضاف موظفًا',
                'موظف',
                (string) $employee->id,
                $employee->name
            );
        });

        return redirect()
            ->route('dashboard.employees.index')
            ->with('success', 'تمت إضافة الموظف بنجاح.');
    }

    public function update(Request $request, User $employee): RedirectResponse
    {
        Gate::authorize('manage-employees');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee->id),
            ],
            'phone' => ['required', 'string', 'max:25'],
            'role' => [
                'required',
                Rule::in(['admin', 'approver', 'gate']),
            ],
            'is_active' => ['required', 'boolean'],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ]);

        // منع تعديل صلاحية مدير النظام أو تعطيل حسابه
        if (
            $employee->role === 'admin' &&
            (
                $data['role'] !== 'admin' ||
                !$data['is_active']
            )
        ) {
            throw ValidationException::withMessages([
                'role' => 'لا يمكن تعطيل مدير النظام أو تغيير دوره.',
            ]);
        }

        // منع إنشاء مدير جديد من نموذج الموظفين
        if ($data['role'] === 'admin' && $employee->role !== 'admin') {
            throw ValidationException::withMessages([
                'role' => 'لا يمكن منح دور مدير النظام من هذه الصفحة.',
            ]);
        }

        // منع تعطيل الحساب الحالي
        if (
            $employee->id === $request->user()->id &&
            !$data['is_active']
        ) {
            throw ValidationException::withMessages([
                'is_active' => 'لا يمكنك تعطيل حسابك الحالي.',
            ]);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        DB::transaction(function () use ($employee, $data) {
            $employee->update($data);

            ActivityLog::record(
                'عدّل بيانات موظف',
                'موظف',
                (string) $employee->id,
                $employee->name
            );
        });

        return redirect()
            ->route('dashboard.employees.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }

    public function permissions(Request $request): RedirectResponse
    {
        Gate::authorize('manage-employees');

        $values = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.approver' => ['nullable', 'array'],
            'permissions.gate' => ['nullable', 'array'],
            'permissions.approver.*' => ['string'],
            'permissions.gate.*' => ['string'],
        ]);

        $allowed = array_keys(config('employee_permissions.permissions'));

        foreach (['approver', 'gate'] as $role) {
            $selected = $values['permissions'][$role] ?? [];

            if (array_diff($selected, $allowed)) {
                throw ValidationException::withMessages([
                    'permissions' => 'توجد صلاحية غير معروفة.',
                ]);
            }
        }

        DB::transaction(function () use ($values) {
            foreach (['approver', 'gate'] as $role) {
                $selected = array_unique(
                    $values['permissions'][$role] ?? []
                );

                DB::table('role_permissions')
                    ->where('role', $role)
                    ->delete();

                foreach ($selected as $permission) {
                    DB::table('role_permissions')->insert([
                        'role' => $role,
                        'permission' => $permission,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            ActivityLog::record(
                'عدّل صلاحيات الأدوار',
                'صلاحيات'
            );
        });

        return redirect()
            ->route('dashboard.employees.index', [
                'tab' => 'permissions',
            ])
            ->with('success', 'تم حفظ الصلاحيات بنجاح.');
    }
}

