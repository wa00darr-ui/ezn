<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveUser
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        // الحصول على الموظف المسجل دخوله
        $user = $request->user();

        // التأكد من تسجيل الدخول
        if (!$user) {
            return redirect()
                ->route('dashboard.login');
        }

        // التحقق من أن حساب الموظف نشط
        if (!(bool) $user->is_active) {

            Auth::logout();

            // إلغاء الجلسة القديمة
            $request->session()->invalidate();

            // تجديد رمز الحماية
            $request->session()->regenerateToken();

            return redirect()
                ->route('dashboard.login')
                ->withErrors([
                    'email' => 'هذا الحساب غير نشط. يرجى التواصل مع إدارة المدرسة.',
                ]);
        }

        // السماح للموظف النشط بالمتابعة
        return $next($request);
    }
}