
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

    <title>@yield('title', 'لوحة التحكم') | استأذن</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- ملفات التنسيق -->
    <link
        rel="stylesheet"
        href="{{ asset('css/dashboard/dashboard.css') }}"
    >

    @stack('head')
</head>

<body
    data-frontend="dashboard"
    data-page="@yield('page', 'dashboard')"
>

    <!-- رابط الوصول السريع -->
    <a class="skip-link" href="#main">
        انتقل إلى المحتوى
    </a>

    <!-- القائمة الجانبية -->
    <x-dashboard.sidebar :active-page="$activePage ?? 'dashboard'" />

    <!-- خلفية القائمة في الشاشات الصغيرة -->
    <button
        type="button"
        class="sidebar-backdrop"
        data-sidebar-close
        aria-label="إغلاق القائمة"
        hidden
    ></button>

    <!-- مساحة لوحة التحكم -->
    <div class="workspace">

        <!-- شريط العنوان -->
        <x-dashboard.header :title="$pageTitle ?? 'لوحة التحكم'" />

        <!-- المحتوى الرئيسي -->
        <main id="main">

            <noscript>
                <p class="notice">
                    بعض الوظائف التفاعلية تحتاج إلى تفعيل JavaScript.
                </p>
            </noscript>

            @yield('content')

            <!-- نافذة تسجيل الخروج -->
            <x-dashboard.logout-dialog />

            <!-- الإشعارات -->
            <x-dashboard.toasts />

        </main>

        <!-- التذييل -->
        <x-dashboard.footer />

    </div>

    <!-- النوافذ المنبثقة الخاصة بكل صفحة -->
    @yield('dialogs')

    <!-- JavaScript -->
    <script
        src="{{ asset('js/dashboard/dashboard.js') }}"
        defer
    ></script>

    @stack('scripts')

</body>
</html>
