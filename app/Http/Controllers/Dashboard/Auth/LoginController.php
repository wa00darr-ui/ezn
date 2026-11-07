<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLogin(): View
    {
        return view('dashboard.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $email = Str::lower(trim($credentials['email']));

        $authenticated = Auth::attempt([
            'email' => $email,
            'password' => $credentials['password'],
            'is_active' => true,
        ], $request->boolean('remember'));

        if (! $authenticated) {
            return back()
                ->withErrors([
                    'email' => 'بيانات الدخول غير صحيحة أو الحساب غير مفعّل.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $request->user()->forceFill([
            'last_login_at' => now(),
        ])->save();

        return redirect()->intended(
            route('dashboard.home')
        );
    }

    public function showForgotPassword(): View
    {
        return view('dashboard.auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        Password::sendResetLink([
            'email' => Str::lower(trim($request->input('email'))),
        ]);

        return back()->with(
            'status',
            'إذا كان البريد مرتبطًا بحساب، فستصلك تعليمات استعادة كلمة المرور.'
        );
    }

    public function showResetPassword(
        Request $request,
        string $token
    ): View {
        return view('dashboard.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->letters()->numbers(),
            ],
        ]);

        $status = Password::reset(
            [
                ...$request->only(
                    'password',
                    'password_confirmation',
                    'token'
                ),
                'email' => Str::lower(trim($request->input('email'))),
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('dashboard.login')
                ->with('status', 'تم تغيير كلمة المرور بنجاح.');
        }

        return back()
            ->withErrors([
                'email' => __($status),
            ])
            ->onlyInput('email');
    }
}