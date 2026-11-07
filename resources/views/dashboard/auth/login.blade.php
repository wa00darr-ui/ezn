
@extends('layouts.auth')

@section('title', 'تسجيل الدخول')
@section('page', 'login')

@section('content')

<section class="login-brand">

    <a class="brand" href="{{ url('/') }}">
        <span class="brand-mark">إ</span>
        <span>
            استأذن
            <small>عناية بالطالب، واطمئنان للأسرة</small>
        </span>
    </a>

    <div>
        <span class="eyebrow">مساحة عمل فريق المدرسة</span>

        <h1>
            إدارة أوضح.
            <br>
            استلام أكثر اطمئنانًا.
        </h1>

        <p>
            من وصول طلب الاستئذان إلى تسليم الطالب،
            تابع كل خطوة مع فريق مدرستك.
        </p>

        <div class="login-illustration">
            <span class="success-icon">✓</span>
            <b>رحلة آمنة، بخطوات واضحة</b>
            <span>
                طلب الاستئذان ← المراجعة ← التسليم
            </span>
        </div>
    </div>

    <small>
        © {{ now()->year }} استأذن ·
        {{ config('school.name', 'إدارة المدرسة') }}
    </small>

</section>

<section class="login-content">

    <div class="login-card">

        <!-- المدرسة -->
        <div class="login-school">
            <span class="school-icon">
                {{ config('school.logo_letter', 'آ') }}
            </span>

            <b>
                {{ config('school.name', 'إدارة المدرسة') }}
            </b>

            <small>
                {{ config('school.city', 'الرياض') }}،
                المملكة العربية السعودية
            </small>
        </div>

        <section data-auth-panel="login">

            <h2>أهلًا بعودتك</h2>

            <p class="muted">
                سجّل الدخول إلى مساحة عمل المدرسة.
            </p>

            <!-- رسائل النظام -->
            @if(session('status'))
                <div class="success-box" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error-box" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- نموذج تسجيل الدخول -->
            <form
                method="POST"
                action="{{ route('dashboard.login.submit') }}"
                id="real-login-form"
            >
                @csrf

                <!-- البريد الإلكتروني -->
                <label class="field">
                    <span>البريد الإلكتروني</span>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        placeholder="name@example.com"
                        required
                        autofocus
                    >
                </label>

                <!-- كلمة المرور -->
                <label class="field">

                    <span>كلمة المرور</span>

                    <span class="password-wrap">

                        <input
                            id="login-password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="أدخل كلمة المرور"
                            required
                        >

                        <button
                            type="button"
                            class="text-link"
                            id="toggle-login-password"
                            aria-label="عرض أو إخفاء كلمة المرور"
                            aria-controls="login-password"
                            aria-pressed="false"
                        >
                            إظهار
                        </button>

                    </span>
                </label>

                <div class="login-options">

                    <label>
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        تذكرني
                    </label>

                    <a
                        class="text-link"
                        href="{{ route('dashboard.password.request') }}"
                    >
                        نسيت كلمة المرور؟
                    </a>

                </div>

                <button
                    class="btn full-width"
                    type="submit"
                    id="login-submit-button"
                >
                    تسجيل الدخول
                </button>

            </form>

        </section>

        <a class="back-public" href="{{ url('/') }}">
            العودة إلى موقع أولياء الأمور ←
        </a>

    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const password = document.getElementById('login-password');
    const toggle = document.getElementById('toggle-login-password');
    const form = document.getElementById('real-login-form');
    const submitButton = document.getElementById('login-submit-button');

    if (password && toggle) {
        toggle.addEventListener('click', function () {

            const isHidden = password.type === 'password';

            password.type = isHidden ? 'text' : 'password';
            toggle.textContent = isHidden ? 'إخفاء' : 'إظهار';
            toggle.setAttribute('aria-pressed', String(isHidden));

        });
    }

    if (form && submitButton) {
        form.addEventListener('submit', function () {
            submitButton.disabled = true;
            submitButton.textContent = 'جارٍ تسجيل الدخول…';
        });
    }

});
</script>

@endsection
