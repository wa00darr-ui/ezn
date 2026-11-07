
@extends('layouts.auth')

@section('title', 'استعادة كلمة المرور')
@section('page', 'forgot-password')

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
        <span class="eyebrow">أمان حسابك</span>

        <h1>استعادة كلمة المرور</h1>

        <p>
            يمكنك استعادة الوصول إلى حسابك
            باستخدام البريد الإلكتروني المسجل.
        </p>
    </div>

    <small>
        © {{ now()->year }} استأذن
    </small>

</section>

<section class="login-content">

    <div class="login-card">

        <div class="login-school">
            <span class="school-icon">
                {{ config('school.logo_letter', 'آ') }}
            </span>

            <b>
                {{ config('school.name', 'إدارة المدرسة') }}
            </b>
        </div>

        <h2>نسيت كلمة المرور؟</h2>

        <p class="muted">
            أدخل بريدك الإلكتروني لإرسال
            تعليمات استعادة كلمة المرور.
        </p>

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

        <form
            method="POST"
            action="{{ route('dashboard.password.email') }}"
        >
            @csrf

            <label class="field">
                <span>البريد الإلكتروني</span>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                >
            </label>

            <button class="btn full-width" type="submit">
                إرسال رابط الاستعادة
            </button>

        </form>

        <a
            class="back-public"
            href="{{ route('dashboard.login') }}"
        >
            العودة إلى تسجيل الدخول
        </a>

    </div>

</section>

@endsection
