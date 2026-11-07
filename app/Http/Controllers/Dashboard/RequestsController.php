<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RequestsController extends Controller
{
    /**
     * عرض الطلبات والبحث والتصفية.
     */
    public function index(Request $request): View
    {
        Gate::authorize('requests.view');

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],

            'status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                    'completed',
                    'cancelled',
                ]),
            ],

            'student' => ['nullable', 'integer', 'min:1'],
            'from' => ['nullable', 'date_format:Y-m-d'],

            'to' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:from',
            ],
        ]);

        $query = PermissionRequest::query()
            ->with('student');

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('parent_name', 'like', "%{$search}%")
                    ->orWhere('parent_phone', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($students) use ($search) {
                        $students->where('name', 'like', "%{$search}%");
                    });

                if (ctype_digit($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['student'])) {
            $query->where('student_id', $filters['student']);
        }

        if (!empty($filters['from'])) {
            $query->whereDate(
                'created_at',
                '>=',
                $filters['from']
            );
        }

        if (!empty($filters['to'])) {
            $query->whereDate(
                'created_at',
                '<=',
                $filters['to']
            );
        }

        $requests = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $students = PermissionRequest::query()
            ->whereNotNull('student_id')
            ->distinct()
            ->orderBy('student_id')
            ->pluck('student_id');

        $totalRequests = PermissionRequest::query()->count();

        return view('dashboard.requests.index', [
            'requests' => $requests,
            'filters' => $filters,
            'students' => $students,
            'totalRequests' => $totalRequests,
        ]);
    }

    /**
     * عرض تفاصيل الطلب.
     */
    public function show(
        PermissionRequest $permissionRequest
    ): View {
        Gate::authorize('requests.view');

        $permissionRequest->load([
            'student',
            'reviewer',
            'checkoutEmployee',
        ]);

        return view('dashboard.requests.show', [
            'permissionRequest' => $permissionRequest,
        ]);
    }

    /**
     * قبول طلب استئذان بعد التحقق من بياناته.
     */
    public function approve(
        Request $request,
        PermissionRequest $permissionRequest
    ): RedirectResponse {
        Gate::authorize('requests.approve');

        $request->validate([
            'authorization_verified' => ['accepted'],
        ], [
            'authorization_verified.accepted' =>
                'يجب التحقق من هوية مقدم الطلب وتفويضه وفق سجلات المدرسة قبل الموافقة.',
        ]);

        DB::transaction(function () use (
            $permissionRequest,
            $request
        ) {
            $record = PermissionRequest::query()
                ->lockForUpdate()
                ->findOrFail($permissionRequest->id);

            if ($record->status !== 'pending') {
                throw ValidationException::withMessages([
                    'request' =>
                        'تم اتخاذ قرار بشأن هذا الطلب مسبقًا.',
                ]);
            }

            if (!$record->student_id || !$record->student()->exists()) {
                throw ValidationException::withMessages([
                    'request' =>
                        'لا يمكن قبول الطلب قبل ربطه بطالب مسجل لدى المدرسة.',
                ]);
            }

            $record->update([
                'status' => 'approved',
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);
        });

        return redirect()
            ->route(
                'dashboard.requests.show',
                $permissionRequest
            )
            ->with(
                'success',
                'تمت الموافقة على الطلب وتسجيل قرار المراجعة.'
            );
    }

    /**
     * رفض طلب الاستئذان مع تسجيل السبب.
     */
    public function reject(
        Request $request,
        PermissionRequest $permissionRequest
    ): RedirectResponse {
        Gate::authorize('requests.reject');

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ], [
            'rejection_reason.required' =>
                'يرجى كتابة سبب رفض الطلب.',
            'rejection_reason.min' =>
                'يرجى توضيح سبب الرفض بشكل كافٍ.',
        ]);

        DB::transaction(function () use (
            $permissionRequest,
            $request,
            $validated
        ) {
            $record = PermissionRequest::query()
                ->lockForUpdate()
                ->findOrFail($permissionRequest->id);

            if ($record->status !== 'pending') {
                throw ValidationException::withMessages([
                    'request' =>
                        'تم اتخاذ قرار بشأن هذا الطلب مسبقًا.',
                ]);
            }

            $record->update([
                'status' => 'rejected',
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'rejection_reason' =>
                    $validated['rejection_reason'],

                // إلغاء أي رمز استلام سابق احتياطيًا.
                'pickup_code_digest' => null,
                'pickup_code_expires_at' => null,
            ]);
        });

        return redirect()
            ->route(
                'dashboard.requests.show',
                $permissionRequest
            )
            ->with(
                'success',
                'تم رفض الطلب وحفظ سبب الرفض.'
            );
    }
}
