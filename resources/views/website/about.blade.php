@extends('layouts.website')

@section('title', 'عن الخدمة | استأذن')
@section('page', 'about')

@section('description', 'تعرّف على خدمة استأذن لتقديم طلبات استئذان الطلاب ومتابعة إجراءات استلامهم.')

@section('content')

<!-- مقدمة الصفحة -->
<div class="page-intro">
    <div class="wrap">

        <div class="breadcrumb">
            <a href="{{ route('website.home') }}">الرئيسية</a>
            <span>/</span>
            <span>عن الخدمة</span>
        </div>

        <p class="section-label">خدمات أولياء الأمور</p>

        <h1>عن الخدمة</h1>

        <p class="muted">
            تعرّف على خدمة «استأذن» وخطوات تقديم طلب
            استئذان الطالب ومتابعته حتى الاستلام.
        </p>

    </div>
</div>

<!-- خطوات الخدمة -->
<section class="section wrap" id="how">

    <div class="section-head">
        <p class="section-label">رحلة واضحة من البداية</p>
        <h2>كيف تعمل الخدمة؟</h2>
        <p class="muted">
            أربع مراحل لتنظيم طلب الاستئذان والمحافظة على سلامة الطالب.
        </p>
    </div>

    <div class="steps">

        <!-- الخطوة الأولى -->
        <div class="step-card">
            <div class="step-icon">
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
                    <path d="M14 3H5v18h14V8zM14 3v6h5M8 13h8M8 17h5" />
                </svg>
                <small>1</small>
            </div>

            <h3>أدخل بيانات الطلب</h3>
            <p>
                أدخل بيانات الطالب وولي الأمر،
                وحدد سبب الاستئذان وأكمل المتطلبات.
            </p>
        </div>

        <!-- الخطوة الثانية -->
        <div class="step-card">
            <div class="step-icon">
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
                    <path d="m3 11 18-8-8 18-2-8zM11 13 21 3" />
                </svg>
                <small>2</small>
            </div>

            <h3>أرسل الطلب للإدارة</h3>
            <p>
                راجع المعلومات قبل الإرسال،
                ثم يُحال الطلب إلى المدرسة للمراجعة.
            </p>
        </div>

        <!-- الخطوة الثالثة -->
        <div class="step-card">
            <div class="step-icon">
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
                <small>3</small>
            </div>

            <h3>تابع حالة الطلب</h3>
            <p>
                يمكنك متابعة حالة الطلب وقرار المدرسة
                عند اكتمال تفعيل نظام المتابعة.
            </p>
        </div>

        <!-- الخطوة الرابعة -->
        <div class="step-card">
            <div class="step-icon">
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
                    <path d="M10 4H4v16h6M10 12h11m-4-4 4 4-4 4" />
                </svg>
                <small>4</small>
            </div>

            <h3>استلم الطالب</h3>
            <p>
                بعد الموافقة، يحضر المستلم المخوّل
                للتحقق من هويته وإتمام إجراءات التسليم.
            </p>
        </div>

    </div>
</section>

<!-- التعريف بالخدمة -->
<section class="wrap section section-service" id="about">

    <div class="service-panel">

        <div class="service-copy">
            <p class="section-label">
                أقرب إليكم، أينما كنتم
            </p>

            <h2>
                وقتكم ثمين،
                <br>
                وسلامة أبنائكم أمانة.
            </h2>

            <p>
                تساعد خدمة «استأذن» على تنظيم التواصل
                بين أولياء الأمور و
                {{ config('school.name', 'المدرسة') }}
                من خلال تقديم طلبات الاستئذان
                ومراجعتها ومتابعة إجراءات تسليم الطلاب.
            </p>

            <a
                class="btn"
                href="{{ route('website.request.create') }}"
            >
                ابدأ طلب الاستئذان
            </a>
        </div>

        <div class="service-features">

            <div class="feature">
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
                    <path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0M4 21v-3a8 8 0 0 1 16 0v3" />
                </svg>

                <div>
                    <b>بدون إنشاء حساب</b>
                    <p>
                        يمكنك تعبئة الطلب دون إنشاء حساب،
                        مع ضرورة التحقق من بيانات مقدم الطلب وتفويضه.
                    </p>
                </div>
            </div>

            <div class="feature">
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
                    <path d="M21 11a9 9 0 0 1-9 9H3l2-5a9 9 0 1 1 16-4M8 10h8M8 14h5" />
                </svg>

                <div>
                    <b>متابعة حالة الطلب</b>
                    <p>
                        تهدف الخدمة إلى تمكين ولي الأمر
                        من معرفة حالة طلبه وقرار المدرسة.
                    </p>
                </div>
            </div>

            <div class="feature">
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

                <div>
                    <b>بياناتكم محل عنايتنا</b>
                    <p>
                        تستخدم بيانات الهوية والتوقيع
                        ضمن إجراءات التحقق من مقدم الطلب،
                        مع ضرورة حماية البيانات وتقييد الوصول إليها.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- تعليمات مهمة -->
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
            <h3>قبل تقديم الطلب</h3>

            <ul>
                <li>تأكد من صحة بيانات الطالب ومطابقتها لسجلات المدرسة.</li>
                <li>تأكد من أن مقدم الطلب مخوّل بطلب الاستئذان.</li>
                <li>أحضر أصل الهوية عند استلام الطالب.</li>
                <li>انتظر موافقة المدرسة قبل الحضور للاستلام.</li>
            </ul>
        </div>
    </div>

</section>

@endsection
