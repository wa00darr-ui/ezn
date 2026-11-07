<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Sidebar extends Component
{
    public string $activePage;

    public function __construct(string $activePage = 'dashboard')
    {
        $this->activePage = $activePage;
    }

    public function render(): View
    {
        $user = Auth::user();

        $schoolName = config('school.name', 'مدارس آفاق الأهلية');
        $schoolCity = config('school.city', 'الرياض');
        $academicYear = config('school.academic_year', '١٤٤٨ هـ');

        $displayName = $user?->name ?? 'مستخدم النظام';
        $displayRole = 'مستخدم';

        if ($user) {
            if (method_exists($user, 'getRoleNames')) {
                $displayRole = $user->getRoleNames()->first()
                    ?? 'مستخدم';
            } else {
                $displayRole = $user->role ?? 'مستخدم';
            }
        }

        $nameParts = preg_split('/\s+/u', trim($displayName));

        $avatarInitials = '';

        foreach (array_slice($nameParts ?: [], 0, 2) as $part) {
            $avatarInitials .= mb_substr($part, 0, 1);
        }

        $pendingRequestsCount = 0;

        if (
            Schema::hasTable('permission_requests') &&
            Schema::hasColumn('permission_requests', 'status')
        ) {
            $pendingRequestsCount = DB::table('permission_requests')
                ->where('status', 'pending')
                ->count();
        }

        return view('components.dashboard.sidebar', [
            'schoolName' => $schoolName,
            'schoolCity' => $schoolCity,
            'academicYear' => $academicYear,
            'displayName' => $displayName,
            'displayRole' => $displayRole,
            'avatarInitials' => $avatarInitials,
            'pendingRequestsCount' => $pendingRequestsCount,
        ]);
    }
}
