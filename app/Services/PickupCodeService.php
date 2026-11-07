<?php

namespace App\Services;

use App\Models\PermissionRequest;
use App\Models\SchoolSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use DomainException;

class PickupCodeService
{
    public function digest(string $code): string
    {
        return hash_hmac(
            'sha256',
            $code,
            config('app.key')
        );
    }

    public function issue(PermissionRequest $request): string
    {
        return DB::transaction(function () use ($request) {

            $permissionRequest = PermissionRequest::query()
                ->lockForUpdate()
                ->findOrFail($request->id);

            if (
                $permissionRequest->status !== 'approved' ||
                $permissionRequest->checked_out_at !== null
            ) {
                throw new DomainException(
                    'لا يمكن إصدار رمز استلام لهذا الطلب.'
                );
            }

            // مدة الرمز وفق إعدادات المدرسة
            $validityMinutes = 30;

            if (Schema::hasTable('school_settings')) {
                $school = SchoolSetting::query()->find(1);

                if ($school && (int) $school->code_validity_minutes > 0) {
                    $validityMinutes = (int) $school->code_validity_minutes;
                }
            }

            do {
                $code = (string) random_int(100000, 999999);

                $digest = $this->digest($code);

                $exists = PermissionRequest::query()
                    ->where('pickup_code_digest', $digest)
                    ->exists();

            } while ($exists);

            $permissionRequest->update([
                'pickup_code_digest' => $digest,
                'pickup_code_expires_at' => now()->addMinutes($validityMinutes),
            ]);

            return $code;
        });
    }
}