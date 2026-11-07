
<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'تسجيل الدخول') | استأذن
    </title>

    <!-- خطوط Google -->
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- تنسيق لوحة التحكم -->
    <link
        rel="stylesheet"
        href="{{ asset('css/dashboard/dashboard.css') }}"
    >

    @stack('head')
</head>

<body
    data-frontend="dashboard"
    class="login-page"
    data-page="@yield('page', 'login')"
    data-dashboard-url="{{ route('dashboard.home') }}"
>

    <main class="login-layout">
        @yield('content')
    </main>

    <!-- ملف JavaScript -->
    <script
        src="{{ asset('js/dashboard/dashboard.js') }}"
        defer
    ></script>

    @stack('scripts')

</body>
</html>
