<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'employee_name',
        'action',
        'entity',
        'record_reference',
        'record_name',
        'ip_address',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(
        string $action,
        string $entity,
        ?string $reference = null,
        ?string $recordName = null,
        array $details = []
    ): self {
        $user = Auth::user();

        if (!$user) {
            throw new \RuntimeException(
                'يجب تسجيل الدخول قبل تسجيل نشاط الموظف.'
            );
        }

        return self::create([
            'user_id' => $user->getAuthIdentifier(),
            'employee_name' => $user->name ?? 'مستخدم النظام',
            'action' => $action,
            'entity' => $entity,
            'record_reference' => $reference,
            'record_name' => $recordName,
            'ip_address' => request()->ip(),
            'details' => $details,
        ]);
    }
}

