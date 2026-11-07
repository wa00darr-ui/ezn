<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Student;
use App\Models\User;

class PermissionRequest extends Model
{
    protected $table = 'permission_requests';

    protected $fillable = [
        'student_id',
        'parent_name',
        'parent_phone',
        'guardian_id',
        'guardian_relation',
        'reason',
        'notes',
        'identity_path',
        'signature_path',
        'status',
        'requested_at',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'pickup_code_digest',
        'pickup_code_expires_at',
        'identity_verified_at',
        'identity_verified_by',
        'checked_out_at',
        'checked_out_by',
    ];

    protected $hidden = [
        'pickup_code_digest',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'pickup_code_expires_at' => 'datetime',
        'identity_verified_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    // الطالب المرتبط بطلب الاستئذان
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // الموظف الذي راجع الطلب
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // الموظف الذي سلّم الطالب
    public function checkoutEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    // الموظف الذي تحقق من الهوية
    public function identityVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'identity_verified_by');
    }
}