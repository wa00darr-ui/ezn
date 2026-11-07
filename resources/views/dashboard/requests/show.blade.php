
@extends('layouts.dashboard', [
    'activePage' => 'requests',
    'pageTitle' => 'تفاصيل طلب الاستئذان'
])

@section('title', 'تفاصيل طلب الاستئذان')
@section('page', 'requests')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ config('school.name', 'إدارة المدرسة') }}
        </p>

        <h1>
            تفاصيل الطلب #{{ $permissionRequest->id }}
        </h1>
    </div>

    <div class="page-actions">
        <a
            href="{{ route('dashboard.requests.index') }}"
            class="btn secondary"
        >
            العودة إلى الطلبات
        </a>
    </div>
</div>

<div data-page-content>

    {{-- رسائل النجاح --}}
    @if(session('success'))
        <div class="notice" role="status">
            {{ session('success') }}
        </div>
    @endif

    {{-- رسائل الخطأ --}}
    @if($errors->any())
        <div class="notice" role="alert">
            <strong>تعذر إكمال العملية:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- بيانات طلب الاستئذان --}}
    <section class="panel">

        <div class="panel-head">
            <h2>بيانات طلب الاستئذان</h2>

            <x-dashboard.status-badge
                :status="$permissionRequest->status"
            >
                @switch($permissionRequest->status)

                    @case('pending')
                        بانتظار المراجعة
                        @break

                    @case('approved')
                        موافق عليه
                        @break

                    @case('rejected')
                        مرفوض
                        @break

                    @case('completed')
                        مكتمل
                        @break

                    @case('cancelled')
                        ملغى
                        @break

                    @default
                        {{ $permissionRequest->status }}

                @endswitch
            </x-dashboard.status-badge>
        </div>

        <dl class="data-grid">

            <div>
                <dt>رقم الطلب</dt>
                <dd>
                    #{{ $permissionRequest->id }}
                </dd>
            </div>

            <div>
                <dt>اسم الطالب</dt>
                <dd>
                    {{ $permissionRequest->student?->name ?? 'غير محدد' }}
                </dd>
            </div>

            <div>
                <dt>الصف والفصل</dt>
                <dd>
                    @if($permissionRequest->student)

                        {{ $permissionRequest->student->grade }}
                        -
                        {{ $permissionRequest->student->classroom }}

                    @else
                        غير محدد
                    @endif
                </dd>
            </div>

            <div>
                <dt>الرقم الطلابي</dt>
                <dd>
                    {{ $permissionRequest->student?->student_number ?? '—' }}
                </dd>
            </div>

            <div>
                <dt>ولي الأمر / مقدم الطلب</dt>
                <dd>
                    {{ $permissionRequest->parent_name ?? 'غير محدد' }}
                </dd>
            </div>

            <div>
                <dt>رقم الجوال</dt>
                <dd dir="ltr">
                    {{ $permissionRequest->parent_phone ?? '—' }}
                </dd>
            </div>

            <div>
                <dt>صلة القرابة</dt>
                <dd>
                    {{ $permissionRequest->guardian_relation ?? '—' }}
                </dd>
            </div>

            <div>
                <dt>سبب الاستئذان</dt>
                <dd>
                    {{ $permissionRequest->reason ?: 'غير مذكور' }}
                </dd>
            </div>

            <div>
                <dt>ملاحظات ولي الأمر</dt>
                <dd>
                    {{ $permissionRequest->notes ?: 'لا توجد ملاحظات' }}
                </dd>
            </div>

            <div>
                <dt>تاريخ تقديم الطلب</dt>
                <dd>
                    {{
                        $permissionRequest->requested_at?->format('Y-m-d H:i')
                        ?? $permissionRequest->created_at?->format('Y-m-d H:i')
                        ?? '—'
                    }}
                </dd>
            </div>

            <div>
                <dt>تاريخ المراجعة</dt>
                <dd>
                    {{
                        $permissionRequest->reviewed_at?->format('Y-m-d H:i')
                        ?? 'لم يُراجع بعد'
                    }}
                </dd>
            </div>

            <div>
                <dt>موظف المراجعة</dt>
                <dd>
                    {{ $permissionRequest->reviewer?->name ?? '—' }}
                </dd>
            </div>

            @if($permissionRequest->status === 'rejected')

                <div>
                    <dt>سبب الرفض</dt>
                    <dd>
                        {{ $permissionRequest->rejection_reason ?: 'غير مذكور' }}
                    </dd>
                </div>

            @endif

            @if($permissionRequest->status === 'completed')

                <div>
                    <dt>وقت تسليم الطالب</dt>
                    <dd>
                        {{
                            $permissionRequest->checked_out_at?->format('Y-m-d H:i')
                            ?? '—'
                        }}
                    </dd>
                </div>

                <div>
                    <dt>موظف التسليم</dt>
                    <dd>
                        {{ $permissionRequest->checkoutEmployee?->name ?? '—' }}
                    </dd>
                </div>

            @endif

        </dl>

    </section>

    {{-- قسم اتخاذ القرار --}}
    @if($permissionRequest->status === 'pending')

        <section class="panel">

            <div class="panel-head">
                <h2>مراجعة طلب الاستئذان</h2>
            </div>

            <p class="page-description">
                يجب التحقق من بيانات الطالب وهوية مقدم الطلب
                وصلاحيته للاستئذان وفق سجلات المدرسة،
                قبل الموافقة أو الرفض.
            </p>

            @if(!$permissionRequest->student_id || !$permissionRequest->student)

                <div class="notice" role="alert">
                    لا يمكن الموافقة على هذا الطلب قبل ربطه
                    بطالب مسجل لدى المدرسة.
                </div>

            @endif

            @canany(['requests.approve', 'requests.reject'])

                {{-- الموافقة --}}
                @can('requests.approve')

                    @if(Route::has('dashboard.requests.approve'))

                        <form
                            method="POST"
                            action="{{ route(
                                'dashboard.requests.approve',
                                $permissionRequest
                            ) }}"
                        >
                            @csrf

                            <div class="field">

                                <label class="check-row">

                                    <input
                                        type="checkbox"
                                        id="authorization_verified"
                                        name="authorization_verified"
                                        value="1"
                                        required
                                        @checked(old('authorization_verified'))
                                    >

                                    <span>
                                        أؤكد أنني تحققت من بيانات الطالب،
                                        وهوية مقدم الطلب وصلاحيته للاستئذان
                                        وفق سجلات المدرسة، وليس اعتمادًا على
                                        صورة الهوية أو إقرار مقدم الطلب وحدهما.
                                    </span>

                                </label>

                                @error('authorization_verified')
                                    <p class="error" role="alert">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <div class="page-actions">

                                <button
                                    type="submit"
                                    class="btn"
                                    @disabled(
                                        !$permissionRequest->student_id
                                        || !$permissionRequest->student
                                    )
                                    onclick="return confirm('هل تأكدت من بيانات الطالب وتفويض مقدم الطلب، وتريدين قبول الاستئذان؟')"
                                >
                                    قبول الطلب
                                </button>

                            </div>

                        </form>

                    @else

                        <p class="notice">
                            مسار الموافقة على الطلبات غير مفعّل بعد.
                        </p>

                    @endif

                @endcan

                {{-- الرفض --}}
                @can('requests.reject')

                    @if(Route::has('dashboard.requests.reject'))

                        <form
                            method="POST"
                            action="{{ route(
                                'dashboard.requests.reject',
                                $permissionRequest
                            ) }}"
                        >
                            @csrf

                            <div class="field">

                                <label for="rejection_reason">
                                    سبب الرفض
                                    <span class="required">*</span>
                                </label>

                                <textarea
                                    id="rejection_reason"
                                    name="rejection_reason"
                                    rows="3"
                                    minlength="5"
                                    maxlength="1000"
                                    required
                                    placeholder="اكتبي سبب رفض طلب الاستئذان"
                                >{{ old('rejection_reason') }}</textarea>

                                @error('rejection_reason')
                                    <p class="error" role="alert">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <div class="page-actions">

                                <button
                                    type="submit"
                                    class="btn secondary"
                                    onclick="return confirm('هل أنت متأكدة من رفض هذا الطلب؟')"
                                >
                                    رفض الطلب
                                </button>

                            </div>

                        </form>

                    @else

                        <p class="notice">
                            مسار رفض الطلبات غير مفعّل بعد.
                        </p>

                    @endif

                @endcan

            @else

                <p class="notice">
                    لا تملكين صلاحية قبول الطلبات أو رفضها.
                </p>

            @endcanany

        </section>

    @elseif($permissionRequest->status === 'approved')

        <section class="panel">

            <div class="panel-head">
                <h2>تمت الموافقة على الطلب</h2>
            </div>

            <p class="page-description">
                تمت الموافقة على طلب الاستئذان.
                لا يعني ذلك أن الطالب قد غادر المدرسة.
                يجب استكمال إجراءات رمز الاستلام
                والتحقق من هوية المستلم المخوّل قبل التسليم.
            </p>

        </section>

    @elseif($permissionRequest->status === 'rejected')

        <section class="panel">

            <div class="panel-head">
                <h2>تم رفض الطلب</h2>
            </div>

            <p class="page-description">
                تم تسجيل قرار الرفض وسببه.
                لا يمكن اتخاذ قرار جديد على الطلب نفسه
                باستخدام إجراءات المراجعة العادية.
            </p>

        </section>

    @endif

    {{-- العودة إلى السجلات --}}
    <section class="panel">

        <div class="panel-head">
            <h2>الرجوع للسجلات</h2>
        </div>

        <div class="page-actions">

            <a
                class="btn secondary"
                href="{{ route('dashboard.requests.index') }}"
            >
                جميع الطلبات
            </a>

            @if(
                in_array(
                    $permissionRequest->status,
                    ['completed', 'rejected'],
                    true
                )
            )

                @can('requests.view')

                    <a
                        class="btn"
                        href="{{ route('dashboard.history.index') }}"
                    >
                        سجل الاستئذانات
                    </a>

                @endcan

            @endif

        </div>

    </section>

</div>

@endsection

