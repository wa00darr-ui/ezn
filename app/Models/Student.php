<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'name',
        'student_number',
        'national_id',
        'grade',
        'classroom',
        'guardian_name',
        'guardian_phone',
        'status',
    ];

    public function permissionRequests(): HasMany
    {
        return $this->hasMany(
            PermissionRequest::class,
            'student_id'
        );
    }
}