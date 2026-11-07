<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('dashboard.login');
        }

        if (! (bool) Auth::user()->is_active) {
            Auth::logout();

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