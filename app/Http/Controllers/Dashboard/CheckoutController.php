<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PermissionRequest;
use App\Services\PickupCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    // عرض صفحة التسليم
    public function index(Request $request): View
    {
        $permissionRequest = null;

        $requestId = $request->session()
            ->get('checkout_verified_request_id');

        if ($requestId) {
            $permissionRequest = PermissionRequest::query()
                ->with(['student', 'reviewer'])
                ->whereKey($requestId)
                ->where('status', 'approved')
                ->whereNull('checked_out_at')
                ->where('pickup_code_expires_at', '>', now())
                ->first();

            if (!$permissionRequest) {
                $request->session()
                    ->forget('checkout_verified_request_id');
            }
        }

        return view('dashboard.checkout.index', [
            'permissionRequest' => $permissionRequest,
        ]);
    }

    // التحقق من رمز الاستلام
    public function verify(
        Request $request,
        PickupCodeService $codes
    ): RedirectResponse {
        $validated = $request->validate([
            'pickup_code' => [
                'required',
                'regex:/^[0-9]{6}$/',
            ],
        ]);

        // إزالة نتيجة أي تحقق سابق
        $request->session()
            ->forget('checkout_verified_request_id');

        $digest = $codes->digest($validated['pickup_code']);

        $permissionRequest = PermissionRequest::query()
            ->where('pickup_code_digest', $digest)
            ->where('status', 'approved')
            ->whereNull('checked_out_at')
            ->where('pickup_code_expires_at', '>', now())
            ->first();

        if (!$permissionRequest) {
            throw ValidationException::withMessages([
                'pickup_code' =>
                    'الرمز غير صحيح أو منتهي الصلاحية أو مستخدم.',
            ]);
        }

        $request->session()->put(
            'checkout_verified_request_id',
            $permissionRequest->id
        );

        return redirect()
            ->route('dashboard.checkout.index')
            ->with('success', 'تم التحقق من الرمز. طابق هوية المستلم قبل التسليم.');
    }

    // تأكيد خروج الطالب
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'identity_match' => ['accepted'],
        ], [
            'identity_match.accepted' =>
                'يجب تأكيد مطابقة الهوية الأصلية قبل التسليم.',
        ]);

        $requestId = $request->session()
            ->get('checkout_verified_request_id');

        if (!$requestId) {
            return redirect()
                ->route('dashboard.checkout.index')
                ->with('error', 'يرجى التحقق من رمز الاستلام أولًا.');
        }

        $completedRequest = DB::transaction(function () use (
            $request,
            $requestId
        ) {
            // قفل السجل لمنع تأكيد التسليم مرتين
            $permissionRequest = PermissionRequest::query()
                ->lockForUpdate()
                ->findOrFail($requestId);

            if (
                $permissionRequest->status !== 'approved' ||
                $permissionRequest->checked_out_at !== null ||
                !$permissionRequest->pickup_code_digest ||
                !$permissionRequest->pickup_code_expires_at ||
                $permissionRequest->pickup_code_expires_at->isPast()
            ) {
                throw ValidationException::withMessages([
                    'checkout' =>
                        'لم يعد هذا الطلب صالحًا للتسليم. يرجى إعادة التحقق.',
                ]);
            }

            $permissionRequest->update([
                'status' => 'completed',
                'identity_verified_at' => now(),
                'identity_verified_by' => $request->user()->id,
                'checked_out_at' => now(),
                'checked_out_by' => $request->user()->id,
                'pickup_code_digest' => null,
                'pickup_code_expires_at' => null,
            ]);

            ActivityLog::record(
                'أكد خروج طالب بعد مطابقة هوية المستلم',
                'طلب',
                (string) $permissionRequest->id
            );

            return $permissionRequest;
        });

        $request->session()->forget(
            'checkout_verified_request_id'
        );

        return redirect()
            ->route('dashboard.checkout.index')
            ->with('checkout_success', [
                'request_id' => $completedRequest->id,
                'checked_out_at' => $completedRequest
                    ->checked_out_at->format('Y-m-d H:i'),
                'employee' => $request->user()->name,
            ]);
    }

    // بدء عملية تسليم جديدة
    public function reset(Request $request): RedirectResponse
    {
        $request->session()->forget(
            'checkout_verified_request_id'
        );

        return redirect()
            ->route('dashboard.checkout.index');
    }
}

