<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'approver' => [
                'requests.view',
                'requests.approve',
                'requests.reject',
                'students.manage',
                'reports.view',
            ],

            'gate' => [
                'requests.view',
                'checkout-students',
            ],
        ];

        foreach ($defaults as $role => $permissions) {
            foreach ($permissions as $permission) {
                DB::table('role_permissions')->updateOrInsert(
                    [
                        'role' => $role,
                        'permission' => $permission,
                    ],
                    [
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}

