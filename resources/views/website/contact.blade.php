@extends('layouts.website')

@section('title', 'تواصل معنا | استأذن')
@section('page', 'contact')

@section('description', 'تواصل مع إدارة المدرسة للاستفسار عن خدمات الاستئذان واستلام الطلاب.')

@section('content')

<!-- مقدمة الصفحة -->
<div class="page-intro">
    <div class="wrap">

        <div class="breadcrumb">
            <a href="{{ route('website.home') }}">
                الرئيسية
            </a>

            <span>/</span>
            <span>تواصل معنا</span>
        </div>

        <p class="section-label">
            خدمات أولياء الأمور
        </p>

        <h1>تواصل معنا</h1>

        <p class="muted">
            يسعد فريق {{ $school?->name ?? config('school.name', 'المدرسة') }}
            بمساعدتك في الاستفسارات المتعلقة
            بطلب الاستئذان واستلام الطالب.
        </p>

    </div>
</div>

<!-- نموذج التواصل -->
<section class="section wrap contact-message">

    @if(session('success'))
        <div
            id="contact-success"
            class="form-card contact-success"
            role="status"
            tabindex="-1"
        >
            <span class="circle">
                <svg
                    class="icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="m5 12 4 4L19 6" />
                </svg>
            </span>

            <h2>تم استلام رسالتك</h2>

            <p class="muted">
                {{ session('success') }}
            </p>

            <a
                class="btn"
                href="{{ route('website.contact') }}"
            >
                كتابة رسالة جديدة
            </a>
        </div>
    @else

        <form
            action="{{ route('website.contact.store') }}"
            method="POST"
            id="contact-form"
            class="form-card"
        >
            @csrf

            <div class="card-head">
                <h2>أرسل لنا رسالة</h2>

                <p>
                    اكتب استفسارك وساعدنا بالتفاصيل
                    التي نحتاجها للرد عليك.
                </p>
            </div>

            <div class="card-body">

                @if($errors->any())
                    <div class="error-box" role="alert">
                        <strong>يرجى مراجعة البيانات:</strong>

                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="fields">

                    <!-- الاسم -->
                    <div class="field">
                        <label for="contact-name">
                            الاسم بالكامل
                            <span class="required">*</span>
                        </label>

                        <input
                            id="contact-name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            maxlength="100"
                            placeholder="مثال: محمد سعد العتيبي"
                            autocomplete="name"
                            @if($errors->has('name'))
                                aria-invalid="true"
                            @endif
                        >

                        @error('name')
                            <p class="error" role="alert">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- الجوال -->
                    <div class="field">
                        <label for="contact-phone">
                            رقم الجوال
                            <span class="required">*</span>
                        </label>

                        <input
                            id="contact-phone"
                            name="phone"
                            type="tel"
                            value="{{ old('phone') }}"
                            required
                            pattern="05[0-9]{8}"
                            minlength="10"
                            maxlength="10"
                            placeholder="05xxxxxxxx"
                            inputmode="tel"
                            dir="ltr"
                            autocomplete="tel"
                            @if($errors->has('phone'))
                                aria-invalid="true"
                            @endif
                        >

                        @error('phone')
                            <p class="error" role="alert">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- موضوع الرسالة -->
                    <div class="field full">
                        <label for="contact-subject">
                            موضوع الرسالة
                            <span class="required">*</span>
                        </label>

                        <input
                            id="contact-subject"
                            name="subject"
                            type="text"
                            value="{{ old('subject') }}"
                            required
                            maxlength="120"
                            placeholder="اكتب عنوانًا مختصرًا لرسالتك"
                            @if($errors->has('subject'))
                                aria-invalid="true"
                            @endif
                        >

                        @error('subject')
                            <p class="error" role="alert">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- نص الرسالة -->
                    <div class="field full">
                        <label for="contact-message">
                            نص الرسالة
                            <span class="required">*</span>
                        </label>

                        <textarea
                            id="contact-message"
                            name="message"
                            required
                            maxlength="2000"
                            placeholder="كيف يمكننا مساعدتك؟"
                            rows="5"
                            @if($errors->has('message'))
                                aria-invalid="true"
                            @endif
                        >{{ old('message') }}</textarea>

                        @error('message')
                            <p class="error" role="alert">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                <div class="hint">
                    <svg
                        class="icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M12 11v6M12 7v1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
                    </svg>

                    تُحفظ الرسالة في نظام المدرسة لمراجعتها.
                    يرجى عدم إرسال صور الهويات أو كلمات المرور
                    أو بيانات شخصية غير ضرورية في نص الرسالة.
                </div>

            </div>

            <div class="card-actions">

                <button
                    type="button"
                    class="text-btn"
                    data-action="privacy"
                >
                    سياسة الخصوصية
                </button>

                <button
                    type="submit"
                    class="btn"
                    id="contact-submit"
                >
                    إرسال الرسالة
                </button>

            </div>
        </form>

    @endif

</section>

<!-- معلومات التواصل -->
<section id="contact" class="section contact">

    <div class="wrap contact-grid">

        <div>
            <p class="section-label">
                يسعدنا تواصلكم
            </p>

            <h2>نحن هنا لمساعدتكم</h2>

            <p class="muted contact-description">
                فريق المدرسة بجانبك في كل خطوة.
                يمكنك التواصل عبر بيانات المدرسة المعتمدة
                أو إرسال استفسارك من النموذج أعلاه.
            </p>
        </div>

        <div class="contact-items">

            <!-- هاتف المدرسة -->
            @if($school?->phone)
                <div class="contact-item">
                    <svg
                        class="icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M5 3h4l2 5-3 2a15 15 0 0 0 6 6l2-3 5 2v4c0 2-3 2-5 1C9 18 6 15 3 8c-1-2 0-5 2-5" />
                    </svg>

                    <div>
                        <small>هاتف المدرسة</small>
                        <b dir="ltr">{{ $school->phone }}</b>
                    </div>
                </div>
            @endif

            <!-- البريد الإلكتروني -->
            @if($school?->email)
                <div class="contact-item">
                    <svg
                        class="icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M3 5h18v14H3zM3 7l9 7 9-7" />
                    </svg>

                    <div>
                        <small>البريد الإلكتروني</small>
                        <b dir="ltr">{{ $school->email }}</b>
                    </div>
                </div>
            @endif

            <!-- العنوان -->
            @if($school?->address || $school?->city)
                <div class="contact-item">
                    <svg
                        class="icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M19 10c0 6-7 11-7 11S5 16 5 10a7 7 0 1 1 14 0M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                    </svg>

                    <div>
                        <small>العنوان</small>
                        <b dir="rtl">
                            {{ collect([$school->address, $school->city])->filter()->unique()->implode('، ') }}
                        </b>
                    </div>
                </div>
            @endif

            <!-- أوقات استقبال طلبات الاستئذان -->
            @if($school?->open_time && $school?->close_time)
                <div class="contact-item">
                    <svg
                        class="icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
                    </svg>

                    <div>
                        <small>أوقات استقبال طلبات الاستئذان</small>
                        <b dir="rtl">
                            من
                            {{ substr($school->open_time, 0, 5) }}
                            إلى
                            {{ substr($school->close_time, 0, 5) }}
                        </b>
                    </div>
                </div>
            @endif

        </div>
    </div>

</section>

<!-- تنبيه الاستفسارات عن الطلبات -->
<section class="section wrap">

    <div class="notice">

        <svg
            class="icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <path d="M12 11v6M12 7v1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
        </svg>

        <div>
            <h3>للاستفسار عن طلب قائم</h3>

            <p class="muted">
                جهّز رقم الطلب لتسهيل مساعدة
                فريق المدرسة، دون مشاركة
                معلومات حساسة في الرسالة.
            </p>

            <a
                href="{{ route('website.contact') }}"
                class="btn secondary"
            >
                أرسل استفسارًا
            </a>
        </div>
    </div>

</section>

@endsection