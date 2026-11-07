<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HistoryController extends Controller
{
    private function filteredQuery(Request $request): Builder
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in(['completed', 'rejected']),
            ],
            'student' => ['nullable', 'integer', 'min:1'],
            'guardian' => ['nullable', 'string', 'max:255'],
            'approver' => ['nullable', 'integer', 'min:1'],
            'checkout' => ['nullable', 'integer', 'min:1'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:from',
            ],
        ]);

        $query = PermissionRequest::query()
            ->whereIn('status', ['completed', 'rejected']);

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function (Builder $q) use ($search) {
                $q->where('parent_name', 'like', "%{$search}%")
                    ->orWhere('parent_phone', 'like', "%{$search}%");

                if (ctype_digit($search)) {
                    $q->orWhereKey((int) $search);
                }
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['student'])) {
            $query->where('student_id', $filters['student']);
        }

        if (!empty($filters['guardian'])) {
            $query->where('parent_name', $filters['guardian']);
        }

        if (!empty($filters['approver'])) {
            $query->where('reviewed_by', $filters['approver']);
        }

        if (!empty($filters['checkout'])) {
            $query->where('checked_out_by', $filters['checkout']);
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

        return $query;
    }

    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'student',
            'guardian',
            'approver',
            'checkout',
            'from',
            'to',
        ]);

        $history = $this->filteredQuery($request)
            ->with(['reviewer', 'checkoutEmployee'])
            ->latest('created_at')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $archived = PermissionRequest::query()
            ->whereIn('status', ['completed', 'rejected']);

        $guardians = (clone $archived)
            ->whereNotNull('parent_name')
            ->distinct()
            ->orderBy('parent_name')
            ->pluck('parent_name');

        // نعرض أرقام الطلاب لحين ربط جدول students
        $students = (clone $archived)
            ->whereNotNull('student_id')
            ->distinct()
            ->orderBy('student_id')
            ->pluck('student_id');

        $approvers = DB::table('users')
            ->whereIn('id', (clone $archived)
                ->whereNotNull('reviewed_by')
                ->select('reviewed_by'))
            ->orderBy('name')
            ->get(['id', 'name']);

        $checkoutEmployees = DB::table('users')
            ->whereIn('id', (clone $archived)
                ->whereNotNull('checked_out_by')
                ->select('checked_out_by'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('dashboard.history.index', compact(
            'history',
            'filters',
            'guardians',
            'students',
            'approvers',
            'checkoutEmployees'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filteredQuery($request)
            ->with(['reviewer', 'checkoutEmployee'])
            ->orderBy('id');

        $filename = 'permission-history-' .
            now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $output = fopen('php://output', 'w');

            // UTF-8 BOM لدعم العربية في Excel
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'رقم الطلب',
                'رقم الطالب الداخلي',
                'ولي الأمر',
                'الجوال',
                'السبب',
                'تاريخ التقديم',
                'الحالة',
                'موظف الموافقة',
                'موظف التسليم',
                'وقت التسليم',
            ]);

            // منع تفسير محتوى الخلايا كصيغ Excel
            $safe = static function ($value): string {
                $text = (string) ($value ?? '');

                if (preg_match('/^[\s]*[=+\-@]/u', $text)) {
                    return "'" . $text;
                }

                return $text;
            };

            foreach ($query->cursor() as $item) {
                fputcsv($output, [
                    $safe($item->id),
                    $safe($item->student_id),
                    $safe($item->parent_name),
                    $safe($item->parent_phone),
                    $safe($item->reason),
                    $item->created_at?->format('Y-m-d H:i'),
                    $item->status === 'completed'
                        ? 'مكتمل'
                        : 'مرفوض',
                    $safe($item->reviewer?->name),
                    $safe($item->checkoutEmployee?->name),
                    $item->checked_out_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, private',
        ]);
    }
}
