<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $table = 'school_settings';

    protected $fillable = [
        'name',
        'ministerial_number',
        'phone',
        'email',
        'city',
        'address',
        'logo_path',
        'open_time',
        'close_time',
        'code_validity_minutes',
        'daily_limit',
        'require_identity_image',
        'require_signature',
        'allow_requests',
        'notify_staff',
        'notify_decision',
        'notify_checkout',
    ];

    protected $casts = [
        'require_identity_image' => 'boolean',
        'require_signature' => 'boolean',
        'allow_requests' => 'boolean',
        'notify_staff' => 'boolean',
        'notify_decision' => 'boolean',
        'notify_checkout' => 'boolean',
        'code_validity_minutes' => 'integer',
        'daily_limit' => 'integer',
    ];

    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'name' => config('school.name', 'مدرستي'),
                'city' => config('school.city', 'الرياض'),
                'open_time' => '07:00',
                'close_time' => '13:30',
                'code_validity_minutes' => 30,
                'daily_limit' => 1,
                'require_identity_image' => true,
                'require_signature' => true,
                'allow_requests' => true,
                'notify_staff' => true,
                'notify_decision' => true,
                'notify_checkout' => true,
            ]
        );
    }
}

