<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PermissionRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        // طلبات اليوم
        $todayRequests = PermissionRequest::query()
            ->whereDate('created_at', $today);

        $todayTotal = (clone $todayRequests)->count();

        $pendingToday = (clone $todayRequests)
            ->where('status', 'pending')
            ->count();

        $approvedToday = (clone $todayRequests)
            ->where('status', 'approved')
            ->count();

        $rejectedToday = (clone $todayRequests)
            ->where('status', 'rejected')
            ->count();

        $completedToday = (clone $todayRequests)
            ->where('status', 'completed')
            ->count();

        $cancelledToday = (clone $todayRequests)
            ->where('status', 'cancelled')
            ->count();

        // جميع الطلبات التي تنتظر قرارًا
        $pendingCount = PermissionRequest::query()
            ->where('status', 'pending')
            ->count();

        // عدد الطلاب الذين تم تسليمهم اليوم
        $checkoutCount = PermissionRequest::query()
            ->where('status', 'completed')
            ->whereDate('checked_out_at', $today)
            ->count();

        // أحدث الطلبات
        $latestRequests = collect();

        if (Gate::allows('requests.view')) {
            $latestRequests = PermissionRequest::query()
                ->latest('created_at')
                ->limit(5)
                ->get();
        }

        // عمليات التسليم الأخيرة
        $recentCheckouts = collect();

        if (Gate::allows('requests.view')) {
            $recentCheckouts = PermissionRequest::query()
                ->with('checkoutEmployee')
                ->where('status', 'completed')
                ->whereNotNull('checked_out_at')
                ->latest('checked_out_at')
                ->limit(3)
                ->get();
        }

        // سجل النشاط
        $recentActivities = collect();

        if (
            Gate::allows('view-activity-logs') &&
            Schema::hasTable('activity_logs')
        ) {
            $recentActivities = ActivityLog::query()
                ->latest('created_at')
                ->limit(3)
                ->get();
        }

        // الطلبات بحسب الساعة
        $hourlyCounts = (clone $todayRequests)
            ->get(['created_at'])
            ->groupBy(
                fn ($item) => (int) $item->created_at->format('G')
            )
            ->map(fn ($items) => $items->count());

        $hourlyChart = [];

        for ($hour = 7; $hour <= 14; $hour++) {
            $hourlyChart[] = [
                'hour' => $hour,
                'label' => $hour < 12
                    ? $hour . ' ص'
                    : ($hour === 12 ? '12 م' : ($hour - 12) . ' م'),
                'count' => $hourlyCounts->get($hour, 0),
            ];
        }

        $maxHourlyCount = max(
            1,
            ...array_column($hourlyChart, 'count')
        );

        $statusDistribution = [
            ['label' => 'بانتظار المراجعة', 'count' => $pendingToday, 'color' => '#d8a344'],
            ['label' => 'موافق عليها', 'count' => $approvedToday, 'color' => '#3f9d78'],
            ['label' => 'مكتملة', 'count' => $completedToday, 'color' => '#256c9d'],
            ['label' => 'مرفوضة', 'count' => $rejectedToday, 'color' => '#cb6262'],
            ['label' => 'ملغاة', 'count' => $cancelledToday, 'color' => '#9298a3'],
        ];

        return view('dashboard.home.index', compact(
            'todayTotal',
            'pendingCount',
            'approvedToday',
            'rejectedToday',
            'completedToday',
            'checkoutCount',
            'latestRequests',
            'recentCheckouts',
            'recentActivities',
            'hourlyChart',
            'maxHourlyCount',
            'statusDistribution'
        ));
    }
}
