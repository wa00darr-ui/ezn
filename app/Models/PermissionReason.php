<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionReason extends Model
{
    protected $table = 'permission_reasons';

    protected $fillable = [
        'name',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}