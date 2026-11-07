<?php


namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        // التحقق من قيم البحث والفلاتر
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'employee' => ['nullable', 'string', 'max:255'],
            'entity' => [
                'nullable',
                Rule::in([
                    'طلب',
                    'طالب',
                    'موظف',
                    'صلاحيات',
                    'الموقع',
                ]),
            ],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:from',
            ],
        ]);

        // الحصول على السجلات
        $query = ActivityLog::query();

        // البحث بالاسم أو الإجراء أو رقم السجل
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('record_reference', 'like', "%{$search}%")
                    ->orWhere('record_name', 'like', "%{$search}%");
            });
        }

        // فلترة الموظف
        if (!empty($filters['employee'])) {
            $query->where(
                'employee_name',
                $filters['employee']
            );
        }

        // فلترة نوع السجل
        if (!empty($filters['entity'])) {
            $query->where(
                'entity',
                $filters['entity']
            );
        }

        // من تاريخ
        if (!empty($filters['from'])) {
            $query->whereDate(
                'created_at',
                '>=',
                $filters['from']
            );
        }

        // إلى تاريخ
        if (!empty($filters['to'])) {
            $query->whereDate(
                'created_at',
                '<=',
                $filters['to']
            );
        }

        // ترتيب النتائج: الأحدث أولاً
        $activities = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // أسماء الموظفين للفلاتر
        $employees = ActivityLog::query()
            ->select('employee_name')
            ->distinct()
            ->orderBy('employee_name')
            ->pluck('employee_name');

        return view('dashboard.activity.index', [
            'activities' => $activities,
            'employees' => $employees,
            'filters' => $filters,
        ]);
    }
}
