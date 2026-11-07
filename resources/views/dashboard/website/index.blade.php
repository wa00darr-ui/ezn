@extends('layouts.dashboard', ['activePage' => 'website', 'pageTitle' => 'إدارة الموقع'])

@section('title', 'إدارة الموقع')
@section('page', 'website')

@section('content')

        <div class="page-heading">
          <div>
            <p class="eyebrow">مدارس آفاق الأهلية</p>
            <h1>إدارة الموقع</h1>
          </div>
          <div class="page-actions">
            <a
              class="text-link"
              href="{{ url('/') }}"
              target="_blank"
              rel="noopener"
              >عرض الموقع الحالي</a
            ><button type="button" class="btn secondary" data-preview-website>
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
                  d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0M3 12h18M12 3c5 5 5 13 0 18-5-5-5-13 0-18"
                />
              </svg>
              معاينة الموقع
            </button>
          </div>
        </div>
        <x-dashboard.page-states />

        <div data-page-content>
          <p class="page-description">
            عدّل المحتوى المعروض لأولياء الأمور، وراجع ترتيب الأقسام قبل النشر.
          </p>
          <form data-cms-form novalidate>
            <div class="cms-layout">
              <div>
                <section class="panel">
                  <div class="panel-head"><h2>البيانات العامة</h2></div>
                  <div class="form-grid">
                    <label class="field"
                      ><span>اسم المدرسة <b class="required">*</b></span
                      ><input
                        name="schoolName"
                        type="text"
                        value="مدارس آفاق الأهلية"
                        required
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field"
                      ><span>عنوان البريد الإلكتروني</span
                      ><input
                        name="schoolEmail"
                        type="email"
                        value="info@afaq.example"
                        dir="ltr"
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field full"
                      ><span>شعار المدرسة</span
                      ><span class="logo-preview"
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
                          <path d="M3 10 12 3l9 7v11h-6v-7H9v7H3z" /></svg
                        ><b>مدارس آفاق الأهلية</b></span
                      ><input
                        type="file"
                        accept="image/png,image/jpeg,image/webp,image/gif"
                        data-image-input="school-logo" /><small
                        class="field-error"
                        hidden
                        >اختر صورة PNG أو JPG أو WebP أو GIF لا تتجاوز ٥
                        ميجابايت.</small
                      ><img
                        class="upload-preview"
                        id="school-logo"
                        alt="معاينة شعار المدرسة"
                        hidden
                    /></label>
                  </div>
                </section>
                <section class="panel">
                  <div class="panel-head"><h2>القسم الرئيسي — Hero</h2></div>
                  <div class="form-grid">
                    <label class="field full"
                      ><span>العنوان الرئيسي <b class="required">*</b></span
                      ><input
                        name="heroTitle"
                        type="text"
                        value="استئذان أبنائكم، بخطوات مطمئنة."
                        required
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field full"
                      ><span>الوصف</span
                      ><textarea name="heroDescription" required>
قدّم طلب استئذان الطالب، وتابع موافقة المدرسة حتى لحظة الاستلام. بكل سهولة، ومن أي مكان.</textarea
                      ><small class="field-error" hidden
                        >الوصف مطلوب.</small
                      ></label
                    ><label class="field"
                      ><span>نص زر الإجراء <b class="required">*</b></span
                      ><input
                        name="cta"
                        type="text"
                        value="تقديم طلب استئذان"
                        required
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field full"
                      ><span>صورة القسم الرئيسي</span
                      ><img
                        id="hero-image"
                        class="hero-preview"
                        src="{{ asset('shared/images/school/school.jpg') }}"
                        alt="صورة المدرسة الحالية"
                      /><input
                        type="file"
                        accept="image/png,image/jpeg,image/webp,image/gif"
                        data-image-input="hero-image"
                      /><small class="field-error" hidden
                        >اختر صورة PNG أو JPG أو WebP أو GIF لا تتجاوز ٥
                        ميجابايت.</small
                      ></label
                    >
                  </div>
                </section>
                <section class="panel">
                  <div class="panel-head"><h2>عن الخدمة</h2></div>
                  <div class="form-grid">
                    <label class="field full"
                      ><span>العنوان <b class="required">*</b></span
                      ><input
                        name="aboutTitle"
                        type="text"
                        value="وقتكم ثمين، وسلامة أبنائكم أمانة."
                        required
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field full"
                      ><span>الوصف</span
                      ><textarea name="aboutDescription">
خدمة استئذان تربطكم بالمدرسة مباشرة. أرسل البيانات المطلوبة، ودع فريق المدرسة يراجع طلبك ويوافيك بالقرار.</textarea
                      >
                    </label>
                  </div>
                </section>
                <section class="panel">
                  <div class="panel-head">
                    <h2>تعليمات أولياء الأمور</h2>
                    <button
                      type="button"
                      class="btn secondary"
                      data-add-instruction
                    >
                      إضافة تعليمات
                    </button>
                  </div>
                  <div class="instruction-fields">
                    <label class="field"
                      ><span>التعليمات ١</span
                      ><input value="تأكد من صحة بيانات الطالب." /></label
                    ><label class="field"
                      ><span>التعليمات ٢</span
                      ><input
                        value="أحضر الهوية الأصلية عند الاستلام." /></label
                    ><label class="field"
                      ><span>التعليمات ٣</span
                      ><input value="انتظر موافقة المدرسة قبل الحضور." /></label
                    ><label class="field" data-extra-instruction hidden
                      ><span>تعليمات إضافية</span
                      ><input placeholder="اكتب التعليمات الجديدة" /></label
                    ><label class="field" data-extra-instruction hidden
                      ><span>تعليمات إضافية</span
                      ><input placeholder="اكتب التعليمات الجديدة"
                    /></label>
                  </div>
                </section>
                <section class="panel">
                  <div class="panel-head"><h2>بيانات التواصل</h2></div>
                  <div class="form-grid">
                    <label class="field"
                      ><span>هاتف المدرسة</span
                      ><input
                        name="phone"
                        type="tel"
                        value="0112345678"
                        dir="ltr"
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field"
                      ><span>واتساب</span
                      ><input
                        name="whatsapp"
                        type="tel"
                        value="0501234567"
                        dir="ltr"
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field full"
                      ><span>العنوان</span
                      ><input
                        name="address"
                        type="text"
                        value="حي الياسمين، الرياض، المملكة العربية السعودية"
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field full"
                      ><span>ساعات العمل</span
                      ><input
                        name="hours"
                        type="text"
                        value="الأحد – الخميس، ٧:٠٠ ص – ٢:٠٠ م"
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    >
                  </div>
                </section>
                <section class="panel">
                  <div class="panel-head"><h2>روابط التواصل الاجتماعي</h2></div>
                  <div class="form-grid">
                    <label class="field"
                      ><span>منصة X</span
                      ><input name="socialX" type="url" value="" /><small
                        class="field-error"
                        hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field"
                      ><span>Instagram</span
                      ><input
                        name="socialInstagram"
                        type="url"
                        value=""
                      /><small class="field-error" hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    ><label class="field"
                      ><span>YouTube</span
                      ><input name="socialYoutube" type="url" value="" /><small
                        class="field-error"
                        hidden
                        >يرجى إدخال قيمة صحيحة لهذا الحقل.</small
                      ></label
                    >
                  </div>
                </section>
              </div>
              <aside>
                <section class="panel sticky-panel">
                  <div class="panel-head"><h2>ظهور الأقسام</h2></div>
                  <label class="switch-line"
                    ><span>القسم الرئيسي</span
                    ><input
                      type="checkbox"
                      role="switch"
                      checked
                      data-section-toggle="hero" /></label
                  ><label class="switch-line"
                    ><span>كيف تعمل الخدمة</span
                    ><input
                      type="checkbox"
                      role="switch"
                      checked
                      data-section-toggle="how" /></label
                  ><label class="switch-line"
                    ><span>عن الخدمة</span
                    ><input
                      type="checkbox"
                      role="switch"
                      checked
                      data-section-toggle="about" /></label
                  ><label class="switch-line"
                    ><span>التعليمات</span
                    ><input
                      type="checkbox"
                      role="switch"
                      checked
                      data-section-toggle="instructions" /></label
                  ><label class="switch-line"
                    ><span>التواصل</span
                    ><input
                      type="checkbox"
                      role="switch"
                      checked
                      data-section-toggle="contact"
                  /></label>
                  <div class="notice">
                    معاينة التعديلات محلية داخل اللوحة. الحفظ تجريبي.
                  </div>
                  <button class="btn full-width" type="submit">
                    حفظ التغييرات
                  </button>
                </section>
              </aside>
            </div>
          </form>
        </div>

@endsection

@section('dialogs')
<x-dashboard.modal id="website-preview" title="معاينة الموقع"

      class="wide-dialog"

    >
      <div class="site-preview">
        <div class="mini-header">
          <img
            data-preview-logo
            class="preview-logo"
            src="{{ asset('shared/images/placeholders/guardian-identity.svg') }}"
            alt="شعار المدرسة في المعاينة"
            hidden
          />
          <b data-preview-school>مدارس آفاق الأهلية</b
          ><span>الرئيسية · عن الخدمة · تواصل معنا</span>
        </div>
        <section data-preview-section="hero">
          <img
            data-preview-image
            src="{{ asset('shared/images/school/school.jpg') }}"
            alt="صورة المدرسة في المعاينة"
          />
          <h2 data-preview-title>استئذان أبنائكم، بخطوات مطمئنة.</h2>
          <p data-preview-description>
            قدّم طلب استئذان الطالب، وتابع موافقة المدرسة حتى لحظة الاستلام.
          </p>
          <span class="btn" data-preview-cta>تقديم طلب استئذان</span>
        </section>
        <section data-preview-section="how">
          <h3>كيف تعمل الخدمة؟</h3>
          <p>أدخل البيانات ← أرسل الطلب ← تابع الحالة ← استلم الطالب</p>
        </section>
        <section data-preview-section="about">
          <h3 data-preview-about-title>عن الخدمة</h3>
          <p data-preview-about-description>
            خدمة استئذان تربطكم بالمدرسة مباشرة.
          </p>
        </section>
        <section data-preview-section="instructions">
          <h3>قبل تقديم الطلب</h3>
          <p data-preview-instruction="0">تأكد من صحة بيانات الطالب.</p>
          <p data-preview-instruction="1">أحضر الهوية الأصلية.</p>
          <p data-preview-instruction="2">انتظر موافقة المدرسة.</p>
          <p data-preview-instruction="3" hidden></p>
          <p data-preview-instruction="4" hidden></p>
        </section>
        <section data-preview-section="contact">
          <h3>تواصل معنا</h3>
          <p data-preview-phone>0112345678</p>
          <p>واتساب: <span data-preview-whatsapp>0501234567</span></p>
          <p data-preview-address>الرياض</p>
          <p data-preview-hours>الأحد – الخميس</p>
          <div class="preview-social">
            <p data-preview-social="socialX" hidden></p>
            <p data-preview-social="socialInstagram" hidden></p>
            <p data-preview-social="socialYoutube" hidden></p>
          </div>
        </section>
      </div>
      <div class="dialog-actions">
        <button type="button" class="btn secondary" data-close>إلغاء</button
        ><button type="button" class="btn" data-close>إغلاق المعاينة</button>
      </div>

</x-dashboard.modal>
@endsection
