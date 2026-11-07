<?php

namespace App\Providers;

use App\Models\SchoolSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | صلاحيات الموظفين
        |--------------------------------------------------------------------------
        */

        $permissions = array_keys(
            config('employee_permissions.permissions', [])
        );

        // المدير يحصل على الصلاحيات المعرفة في الإعدادات
        Gate::before(
            function (User $user, string $ability) use ($permissions) {

                if (! in_array($ability, $permissions, true)) {
                    return null;
                }

                if (! $user->is_active) {
                    return false;
                }

                if ($user->role === 'admin') {
                    return true;
                }

                return null;
            }
        );

        // صلاحيات بقية الموظفين
        foreach ($permissions as $permission) {

            Gate::define(
                $permission,
                function (User $user) use ($permission) {

                    if (! $user->is_active) {
                        return false;
                    }

                    return DB::table('role_permissions')
                        ->where('role', $user->role)
                        ->where('permission', $permission)
                        ->exists();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | بيانات المدرسة
        |--------------------------------------------------------------------------
        */

        if (
            ! app()->runningInConsole() &&
            Schema::hasTable('school_settings')
        ) {
            $school = SchoolSetting::query()->find(1);

            if ($school) {
                config([
                    'school.name' => $school->name,

                    'school.city' => $school->city
                        ?: config('school.city'),
                ]);
            }
        }
    }
}
