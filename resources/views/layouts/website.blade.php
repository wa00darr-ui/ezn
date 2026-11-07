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
        @yield('title', 'استأذن | خدمة استئذان الطالب')
    </title>

    <meta
        name="description"
        content="@yield('description', 'خدمة استئذان الطالب ومتابعة الطلبات إلكترونيًا بطريقة منظمة وآمنة.')"
    >

    <!-- أيقونة الموقع -->
    <link
        rel="icon"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' rx='10' fill='%230c5143'/%3E%3Cpath d='M9 28V14l11-5 11 5v14l-11-5zM20 9v14' fill='none' stroke='white' stroke-width='2'/%3E%3C/svg%3E"
    >

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

    <!-- ملف CSS الرئيسي -->
    <link
        rel="stylesheet"
        href="{{ asset('css/website/website.css') }}"
    >

    <!-- تنسيقات إضافية خاصة بالصفحات -->
    @stack('head')

</head>

<body
    data-frontend="website"
    data-page="@yield('page', 'home')"
    data-tracking-url="{{ url('/tracking') }}"
    data-request-url="{{ url('/request') }}"
>

    <!-- القائمة العلوية -->
    <x-website.navbar />

    <!-- المحتوى الرئيسي -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- تذييل الموقع -->
    <x-website.footer />

    <!-- نافذة سياسة الخصوصية -->
    <x-website.privacy-dialog />

    <!-- النوافذ المنبثقة الخاصة بكل صفحة -->
    @yield('dialogs')

    <!-- إشعارات الموقع -->
    <x-website.toasts />

    <!-- JavaScript الرئيسي للموقع -->
    <script
        src="{{ asset('js/website/website.js') }}"
        type="module"
        defer
    ></script>

    <!-- سكربتات نموذج طلب الاستئذان -->
    @hasSection('page')
        @if(trim($__env->yieldContent('page')) === 'request')

            <!-- رفع الهوية ومعاينة الصورة -->
            <script
                src="{{ asset('js/website/identity-upload.js') }}"
                defer
            ></script>

            <!-- التوقيع الإلكتروني -->

            <!-- مراجعة بيانات الطلب -->
            <script
                src="{{ asset('js/website/request-review.js') }}"
                defer
            ></script>

        @endif
    @endif

    <!-- JavaScript إضافي خاص بالصفحات -->
    @stack('scripts')

</body>
</html>
