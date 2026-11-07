
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveUser
{
    public function handle(Request $request, Closure $next): Response
    {
        // منع الدخول إذا لم يكن المستخدم مسجلًا
        if (! auth()->check()) {
            return redirect()->route('dashboard.login');
        }

        // منع الحسابات غير النشطة
        if (! (bool) auth()->user()->is_active) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('dashboard.login')
                ->withErrors([
                    'email' => 'هذا الحساب غير نشط. يرجى التواصل مع مدير النظام.',
                ]);
        }

        return $next($request);
    }
}
