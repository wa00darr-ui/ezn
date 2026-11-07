
@extends('layouts.dashboard', [
    'activePage' => 'checkout',
    'pageTitle' => 'تسليم الطلاب'
])

@section('title', 'تسليم الطلاب')
@section('page', 'checkout')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ config('school.name', 'إدارة المدرسة') }}
        </p>
        <h1>تسليم الطلاب</h1>
    </div>
    <div class="page-actions"></div>
</div>

<div data-page-content>

    <p class="page-description">
        تحقق من رمز الاستلام، وطابق هوية المستلم
        قبل تأكيد خروج الطالب.
    </p>

    @if(session('success'))
        <div class="success-box" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error-box" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-box" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('checkout_success'))

        @php
            $checkout = session('checkout_success');
        @endphp

        <section class="panel checkout-success">
            <span class="success-icon">✓</span>

            <h2>تم تسليم الطالب بنجاح</h2>

            <p>
                الطلب رقم #{{ $checkout['request_id'] }}
            </p>

            <dl class="data-grid">
                <div>
                    <dt>تاريخ ووقت الخروج</dt>
                    <dd>{{ $checkout['checked_out_at'] }}</dd>
                </div>

                <div>
                    <dt>موظف التسليم</dt>
                    <dd>{{ $checkout['employee'] }}</dd>
                </div>

                <div>
                    <dt>حالة الطلب</dt>
                    <dd>
                        <x-dashboard.status-badge status="completed" />
                    </dd>
                </div>

                <div>
                    <dt>رمز الاستلام</dt>
                    <dd>مستخدم وتم إبطاله</dd>
                </div>
            </dl>

            <form
                method="POST"
                action="{{ route('dashboard.checkout.reset') }}"
            >
                @csrf
                <button type="submit" class="btn secondary">
                    تسليم طالب آخر
                </button>
            </form>

            <a href="{{ url('/dashboard/history') }}" class="btn">
                عرض سجل الاستئذانات
            </a>
        </section>

    @else

        <div class="checkout-layout">

            <!-- التحقق من رمز الاستلام -->
            <section class="panel scanner-panel">

                <div class="panel-head">
                    <h2>التحقق من رمز الاستلام</h2>
                </div>

                <div class="scan-area">
                    <div class="scan-frame">
                        <svg
                            class="icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            aria-hidden="true"
                        >
                            <path d="M4 8V4h4M16 4h4v4M4 16v4h4M20 16v4h-4M8 12h8M12 8v8"/>
                        </svg>
                    </div>

                    <h3>التحقق من رمز الاستلام</h3>

                    <p>
                        أدخل رمز الاستلام الصادر
                        بعد موافقة المدرسة.
                    </p>
                </div>

                <div class="or">
                    <span>إدخال الرمز</span>
                </div>

                <form
                    method="POST"
                    action="{{ route('dashboard.checkout.verify') }}"
                >
                    @csrf

                    <label class="field">
                        <span>كود الاستلام</span>

                        <input
                            name="pickup_code"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                            maxlength="6"
                            dir="ltr"
                            placeholder="أدخل الرمز المكون من 6 أرقام"
                            autocomplete="off"
                            required
                            aria-describedby="code-help"
                        >

                        <small id="code-help">
                            يجب أن يكون الرمز صالحًا
                            ولم يسبق استخدامه.
                        </small>
                    </label>

                    <button type="submit" class="btn full-width">
                        تحقق من الكود
                    </button>
                </form>

            </section>

            @if(!$permissionRequest)

                <!-- انتظار التحقق -->
                <section class="panel checkout-placeholder">
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
                        <path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6zM8 12l3 3 5-6"/>
                    </svg>

                    <h2>الاستلام يبدأ بالتحقق</h2>

                    <p>
                        ستظهر بيانات الطلب والمستلم
                        بعد التحقق من رمز الاستلام.
                    </p>

                    <ul class="check-list">
                        <li>تحقق من موافقة المدرسة.</li>
                        <li>طابق الهوية الأصلية مع بيانات الطلب المعتمدة.</li>
                        <li>أكد خروج الطالب بعد إتمام التسليم.</li>
                    </ul>

                    <button type="button" class="btn" disabled>
                        تأكيد خروج الطالب
                    </button>
                </section>

            @else

                <!-- تفاصيل الطلب الفعلي -->
                <section class="panel checkout-result">

                    <div class="student-hero">
                        <span class="avatar large">
                            {{ mb_substr($permissionRequest->student?->name ?? 'ط', 0, 1) }}
                        </span>

                        <div>
                            <x-dashboard.status-badge status="approved">
                                تم التحقق · موافق عليه
                            </x-dashboard.status-badge>

                            <h2>
                                {{ $permissionRequest->student?->name ?? 'طالب مرتبط بالطلب' }}
                            </h2>

                            <p>
                                طلب استئذان رقم
                                #{{ $permissionRequest->id }}
                            </p>
                        </div>
                    </div>

                    <dl class="data-grid">

                        <div>
                            <dt>ولي الأمر / مقدم الطلب</dt>
                            <dd>{{ $permissionRequest->parent_name }}</dd>
                        </div>

                        <div>
                            <dt>رقم التواصل</dt>
                            <dd dir="ltr">
                                {{ $permissionRequest->parent_phone }}
                            </dd>
                        </div>

                        <div>
                            <dt>رقم الطلب</dt>
                            <dd>#{{ $permissionRequest->id }}</dd>
                        </div>

                        <div>
                            <dt>وقت الموافقة</dt>
                            <dd>
                                {{ $permissionRequest->reviewed_at?->format('Y-m-d H:i') ?? 'غير مسجل' }}
                            </dd>
                        </div>

                        <div>
                            <dt>موظف الموافقة</dt>
                            <dd>
                                {{ $permissionRequest->reviewer?->name ?? 'غير مسجل' }}
                            </dd>
                        </div>

                        <div>
                            <dt>صلاحية رمز الاستلام</dt>
                            <dd>
                                {{ $permissionRequest->pickup_code_expires_at?->format('Y-m-d H:i') }}
                            </dd>
                        </div>

                    </dl>

                    <p class="page-description">
                        يجب التأكد من أن الشخص الحاضر
                        هو المستلم المخول وفق بيانات المدرسة.
                        عرض اسم مقدم الطلب وحده لا يثبت
                        صلاحية الاستلام.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('dashboard.checkout.confirm') }}"
                        id="checkout-confirm-form"
                    >
                        @csrf

                        <label class="checkbox-line">
                            <input
                                type="checkbox"
                                name="identity_match"
                                value="1"
                                id="identity-match"
                                required
                            >
                            أؤكد أنني تحققت من الهوية الأصلية
                            ومن صلاحية الشخص الحاضر لاستلام الطالب.
                        </label>

                        <button
                            type="button"
                            class="btn"
                            id="open-checkout-confirmation"
                            disabled
                        >
                            تأكيد خروج الطالب
                        </button>
                    </form>
                </section>

            @endif

        </div>

    @endif

</div>

@endsection

@section('dialogs')

@if($permissionRequest && !session('checkout_success'))

<x-dashboard.modal
    id="checkout-dialog"
    title="تأكيد خروج الطالب"
>
    <p>
        هل تم تسليم الطالب المرتبط بالطلب
        <b>#{{ $permissionRequest->id }}</b>
        إلى الشخص المخول بعد مطابقة الهوية؟
    </p>

    <p>
        بعد التأكيد، سيُسجل وقت الخروج
        ويصبح رمز الاستلام غير صالح للاستخدام.
    </p>

    <div class="dialog-actions">
        <button
            type="button"
            class="btn secondary"
            data-close
        >
            إلغاء
        </button>

        <button
            type="submit"
            class="btn"
            form="checkout-confirm-form"
        >
            تأكيد التسليم
        </button>
    </div>
</x-dashboard.modal>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkbox = document.getElementById('identity-match');
    const confirmButton = document.getElementById('open-checkout-confirmation');
    const dialog = document.getElementById('checkout-dialog');

    if (!checkbox || !confirmButton || !dialog) {
        return;
    }

    checkbox.addEventListener('change', function () {
        confirmButton.disabled = !checkbox.checked;
    });

    confirmButton.addEventListener('click', function () {
        if (checkbox.checked && !dialog.open) {
            dialog.showModal();
        }
    });

    dialog.querySelectorAll('[data-close]').forEach(function (button) {
        button.addEventListener('click', function () {
            dialog.close();
        });
    });

});
</script>

@endif

@endsection
