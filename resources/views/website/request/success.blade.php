@extends('layouts.website')

@section('title', 'استأذن | تم إرسال الطلب')
@section('page', 'request-success')

@section('content')
<div class="page-bg">
    <div class="wrap lookup-shell">
        <div class="breadcrumb">
            <a href="{{ url('/') }}">الرئيسية</a> / تأكيد إرسال الطلب
        </div>

        <article class="form-card">
            <div class="status-banner approved" role="status">
                <div class="status-icon" aria-hidden="true">✓</div>
                <h1>تم إرسال طلب استئذان برقم <b dir="ltr">{{ $requestNumber }}</b></h1>
                <p>تم استلام طلبك بنجاح وإرساله إلى إدارة المدرسة للمراجعة.</p>
                <x-website.status-badge>بانتظار مراجعة المدرسة</x-website.status-badge>
            </div>

            <div class="card-body">
                <div class="summary-grid">
                    <div>
                        <small>رقم الطلب</small>
                        <b dir="ltr">{{ $requestNumber }}</b>
                    </div>
                    <div>
                        <small>الخطوة التالية</small>
                        <b>متابعة قرار إدارة المدرسة</b>
                    </div>
                </div>

                <div class="hint">
                    احتفظ برقم الطلب للرجوع إليه. يمكنك متابعة الحالة باستخدام الرقم الدراسي للطالب
                    أو رقم جوال ولي الأمر. يرجى انتظار الموافقة قبل الحضور لاستلام الطالب.
                </div>
            </div>

            <div class="card-actions">
                <a href="{{ route('website.tracking.index') }}" class="btn">متابعة الطلب</a>
                <a href="{{ url('/') }}" class="btn secondary">العودة للرئيسية</a>
            </div>
        </article>
    </div>
</div>
@endsection
