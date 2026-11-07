@extends('layouts.website')

@section('title', 'آفاق | متابعة الطلب')

@section('content')

      <div class="page-bg" id="lookup-panel">
        <div class="wrap lookup-shell">
          <div class="breadcrumb">
            <a href="{{ url('/') }}">الرئيسية</a> / متابعة طلب
          </div>
          <div class="form-card">
            <div class="card-head">
              <h2>متابعة طلب استئذان</h2>
              <p>أدخل رقم الطلب ورقم الجوال المسجل للاطّلاع على حالته.</p>
            </div>
            <form id="lookup-form" action="{{ url('/tracking') }}" class="card-body">
              <div class="fields">
                <div class="field full">
                  <label for="request-number">رقم الطلب</label
                  ><input
                    id="request-number"
                    dir="ltr"
                    placeholder="AF-2026-0482"
                    required
                  />
                </div>
                <div class="field full">
                  <label for="lookup-phone">رقم الجوال</label
                  ><input
                    id="lookup-phone"
                    inputmode="tel"
                    dir="ltr"
                    placeholder="05xxxxxxxx"
                    required
                  />
                </div>
              </div>
              <div id="lookup-error" class="error" role="alert" hidden>
                لم نعثر على طلب مطابق. تأكد من رقم الطلب ورقم الجوال.
              </div>
              <button type="submit" class="btn lookup-submit" disabled>
                متابعة الطلب
              </button>
              <div class="hint">
                جرّب الطلب AF-2026-0482 ورقم الجوال 0500000000.
              </div>
              <p class="demo-note">
                هذا نموذج بصري بدون تحقق حقيقي أو حفظ دائم للطلبات.
              </p>
            </form>
          </div>
        </div>
      </div>
      <div id="tracking-panel" class="page-bg">
        <div class="wrap track-shell">
          <div class="breadcrumb">
            <a href="{{ url('/') }}">الرئيسية</a><span>/</span
            ><span>متابعة طلب الاستئذان</span>
          </div>
          <div class="form-title">
            <div>
              <h1>متابعة طلبك</h1>
              <p>كل التفاصيل والتحديثات، في مكان واحد.</p>
            </div>
            <button class="text-btn" type="button" data-action="refresh">
              <span data-refresh-idle
                ><svg
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
                تحديث الحالة</span
              ><span data-refresh-busy hidden
                ><span class="spinner"></span> جارٍ التحديث</span
              >
            </button>
          </div>
          <x-website.alert id="connection-error" role="alert" hidden>
            تعذّر الاتصال. بيانات طلبك ما زالت ظاهرة أدناه.
            <button type="button" class="text-btn" data-action="retry">
              إعادة المحاولة
            </button>
          </x-website.alert>
          <article class="form-card" id="tracking-loading" hidden>
            <div class="card-body" role="status">
              <span class="spinner"></span> جارٍ تحميل بيانات الطلب…
              <div class="skeleton"></div>
              <div class="skeleton"></div>
              <div class="skeleton"></div>
              <div class="skeleton"></div>
              <div class="skeleton"></div>
              <div class="skeleton"></div>
            </div>
          </article>
          @include('website.tracking.pending')
          @include('website.tracking.approved')
          @include('website.tracking.rejected')
          @include('website.tracking.completed')
          <details class="demo-panel" open>
            <summary>محاكاة حالات الطلب — للعرض التجريبي</summary>
            <div class="demo-controls">
              <button type="button" data-status="pending">قيد المراجعة</button
              ><button type="button" data-status="approved">الموافقة</button
              ><button type="button" data-status="rejected">الرفض</button
              ><button type="button" data-status="completed">
                تم تسليم الطالب</button
              ><button type="button" data-action="connection">
                خطأ الاتصال</button
              ><button type="button" data-action="loading">حالة التحميل</button>
            </div>
            <p class="demo-note">
              اختر الحالة لتجربة رحلة ولي الأمر. القرارات والإشعارات بيانات
              تجريبية.
            </p>
          </details>
          <div class="tracking-links">
            <button type="button" class="text-btn" data-action="new-request">
              بدء طلب جديد
            </button>
            · <a class="text-btn" href="{{ url('/') }}">العودة للرئيسية</a>
          </div>
        </div>
      </div>

@endsection

@section('dialogs')
<dialog id="reset-dialog" aria-labelledby="reset-title">
      <button
        type="button"
        class="dialog-close"
        data-close-dialog
        aria-label="إغلاق"
      >
        ×
      </button>
      <h2 id="reset-title">بدء طلب جديد؟</h2>
      <p>سيتم مسح بيانات الطلب التجريبي الحالي من هذه الجلسة.</p>
      <div class="dialog-actions">
        <button type="button" class="btn secondary" data-close-dialog>
          إلغاء</button
        ><button type="button" class="btn" data-action="reset-request">
          بدء طلب جديد
        </button>
      </div>
    </dialog>
@endsection
