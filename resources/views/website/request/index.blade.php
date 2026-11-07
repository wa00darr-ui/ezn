
@extends('layouts.website')

@section('title', 'استأذن | طلب استئذان طالب')
@section('page', 'request')

@section('content')

<div class="page-bg">
    <div class="wrap">

        <!-- مسار التنقل -->
        <div class="breadcrumb">
            <a href="{{ url('/') }}">الرئيسية</a>
            <span>/</span>
            <span>طلب استئذان طالب</span>
        </div>

        <!-- عنوان الصفحة -->
        <div class="form-title">
            <div>
                <h1>طلب استئذان طالب</h1>

                <p>
                    خطوات بسيطة لرعاية أفضل.
                    الحقول المشار إليها بـ * مطلوبة.
                </p>
            </div>

            <span class="privacy-pill">
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
                    <path d="M6 10h12v11H6zM8 10V7a4 4 0 0 1 8 0v3" />
                </svg>

                بياناتك في عناية
            </span>
        </div>

        <div class="form-layout">

            <!-- مؤشر الخطوات -->
            <aside>
                <div class="progress-panel">

                    <div
                        data-progress
                        class="progress-step active"
                        aria-current="step"
                    >
                        <span data-step-number>1</span>
                        <span data-step-check hidden>✓</span>

                        <div>
                            <b>بيانات الطالب</b>
                            <small>ابدأ ببيانات الطالب</small>
                        </div>
                    </div>

                    <div data-progress class="progress-step">
                        <span data-step-number>2</span>
                        <span data-step-check hidden>✓</span>

                        <div>
                            <b>بيانات ولي الأمر</b>
                            <small>خطوة 2</small>
                        </div>
                    </div>

                    <div data-progress class="progress-step">
                        <span data-step-number>3</span>
                        <span data-step-check hidden>✓</span>

                        <div>
                            <b>تفاصيل الاستئذان</b>
                            <small>خطوة 3</small>
                        </div>
                    </div>

                    <div data-progress class="progress-step">
                        <span data-step-number>4</span>
                        <span data-step-check hidden>✓</span>

                        <div>
                            <b>التحقق والإقرار</b>
                            <small>خطوة 4</small>
                        </div>
                    </div>

                    <div data-progress class="progress-step">
                        <span data-step-number>5</span>
                        <span data-step-check hidden>✓</span>

                        <div>
                            <b>مراجعة الطلب</b>
                            <small>تأكد قبل الإرسال</small>
                        </div>
                    </div>

                </div>

                <!-- بطاقة الخصوصية -->
                <div class="help-card">
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
                        <path
                            d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6zM8 12l3 3 5-6"
                        />
                    </svg>

                    <b>خصوصيتك محفوظة</b>

                    <p>
                        نستخدم البيانات للتحقق من هوية
                        مقدم الطلب والمستلم المخوّل
                        والمحافظة على سلامة الطالب.
                    </p>

                    <button
                        type="button"
                        class="text-btn"
                        data-action="privacy"
                    >
                        اطّلع على سياسة الخصوصية
                    </button>
                </div>
            </aside>

            <!-- نموذج طلب الاستئذان -->
            <form
                id="request-form"
                class="form-card"
                method="POST"
                action="{{ url('/request') }}"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf

                <!-- أخطاء التحقق من Laravel -->
                @if($errors->any())
                    <div
                        class="error-box"
                        role="alert"
                        id="server-errors"
                    >
                        <strong>يرجى مراجعة البيانات:</strong>

                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- خطأ مطابقة بيانات الطالب -->
                <div
                    id="invalid-student"
                    class="error-box"
                    role="alert"
                    hidden
                >
                    تعذّر مطابقة بيانات الطالب.
                    تأكد من الاسم ورقم الهوية والصف
                    ثم أعد المحاولة.
                </div>

                <!-- الخطوة الأولى: بيانات الطالب -->
                @include('website.request.student-info')

                <!-- الخطوة الثانية: بيانات ولي الأمر -->
                @include('website.request.guardian-info')

                <!-- الخطوة الثالثة: تفاصيل الاستئذان -->
                @include('website.request.permission-details')

                <!-- الخطوة الرابعة: التحقق والإقرار -->
                @include('website.request.signature')

                <!-- الخطوة الخامسة: مراجعة الطلب -->
                @include('website.request.review')

            </form>

        </div>
    </div>
</div>

@endsection

@section('dialogs')

<!-- نافذة تأكيد إرسال الطلب -->
<dialog
    id="submit-dialog"
    aria-labelledby="submit-title"
>
    <button
        class="dialog-close"
        type="button"
        data-close-dialog
        aria-label="إغلاق"
    >
        ×
    </button>

    <h2 id="submit-title">
        تأكيد إرسال الطلب
    </h2>

    <p>
        هل تريد إرسال طلب استئذان الطالب
        <b data-bind="studentName">الطالب المحدد</b>
        إلى إدارة المدرسة للمراجعة؟
    </p>

    <div class="hint">
        بعد إرسال الطلب، ستتم مراجعته من الموظف المختص.
        يرجى انتظار الموافقة قبل الحضور لاستلام الطالب.
    </div>

    <div class="dialog-actions">

        <button
            class="btn secondary"
            type="button"
            data-close-dialog
        >
            العودة للمراجعة
        </button>

        <button
            class="btn"
            type="button"
            id="confirm-request-submit"
        >
            <span data-submit-idle>
                تأكيد الإرسال
            </span>

            <span data-submit-busy hidden>
                <span class="spinner"></span>
                جارٍ إرسال الطلب…
            </span>
        </button>

    </div>
</dialog>

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('request-form');
    const dialog = document.getElementById('submit-dialog');
    const confirmButton = document.getElementById(
        'confirm-request-submit'
    );

    if (!form || !dialog || !confirmButton) {
        return;
    }

    let submitting = false;

    confirmButton.addEventListener('click', function () {

        if (submitting) {
            return;
        }

        // التحقق من الحقول المطلوبة في جميع الخطوات
        if (!form.checkValidity()) {
            dialog.close();

            const invalidField = form.querySelector(':invalid');

            if (invalidField) {
                const step = invalidField.closest('.request-step');

                if (step) {
                    // العودة للخطوة التي تحتوي على الخطأ
                    const stepNumber = Number(step.dataset.step);

                    if (
                        window.requestFormGoToStep &&
                        typeof window.requestFormGoToStep === 'function'
                    ) {
                        window.requestFormGoToStep(stepNumber);
                    }
                }

                setTimeout(() => {
                    invalidField.focus();
                    invalidField.reportValidity();
                }, 0);
            }

            return;
        }

        submitting = true;
        confirmButton.disabled = true;

        const idle = confirmButton.querySelector(
            '[data-submit-idle]'
        );

        const busy = confirmButton.querySelector(
            '[data-submit-busy]'
        );

        if (idle) idle.hidden = true;
        if (busy) busy.hidden = false;

        // الإرسال إلى Laravel
        HTMLFormElement.prototype.submit.call(form);
    });

    dialog.querySelectorAll('[data-close-dialog]')
        .forEach(function (button) {
            button.addEventListener('click', function () {
                dialog.close();
            });
        });

});
</script>

@endpush
