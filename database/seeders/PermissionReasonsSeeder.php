<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionReasonsSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            'موعد طبي',
            'ظرف عائلي طارئ',
            'تعب أو وعكة صحية',
            'موعد لدى جهة رسمية',
            'أخرى',
        ];

        foreach ($reasons as $index => $name) {
            DB::table('permission_reasons')->insertOrIgnore([
                'name' => $name,
                'is_active' => true,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
