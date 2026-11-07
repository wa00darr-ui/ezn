
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        // تعريف Middleware الخاص بالموظفين النشطين
        $middleware->alias([
            'active.user' => \App\Http\Middleware\EnsureActiveUser::class,
        ]);

        // توجيه الزائر غير المسجل إلى صفحة الدخول
        $middleware->redirectGuestsTo(
            fn (Request $request) => route('dashboard.login')
        );

        // توجيه الموظف المسجل بعيدًا عن صفحات الدخول
        $middleware->redirectUsersTo(
            fn (Request $request) => route('dashboard.home')
        );

    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) =>
                $request->is('api/*') || $request->expectsJson(),
        );

    })

    ->create();
