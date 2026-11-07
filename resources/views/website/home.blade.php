@extends('layouts.website')

@section('title', 'آفاق | خدمة استئذان الطالب')

@section('content')

      <section class="hero">
        <div class="wrap hero-grid">
          <div>
            <div class="eyebrow">خدمات أولياء الأمور</div>
            <h1>استئذان أبنائكم،<br /><em>بخطوات مطمئنة.</em></h1>
            <p>
              قدّم طلب استئذان الطالب، وتابع موافقة المدرسة حتى لحظة الاستلام.
              بكل سهولة، ومن أي مكان.
            </p>
            <div class="hero-buttons">
              <a class="btn" href="{{ url('/request') }}"
                >تقديم طلب استئذان
                <svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path d="M14 3H5v18h14V8zM14 3v6h5M8 13h8M8 17h5" /></svg></a
              ><a class="btn secondary" href="{{ url('/tracking') }}">متابعة طلب سابق</a>
            </div>
            <div class="hero-proof">
              <span
                ><svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path d="m5 12 4 4L19 6" />
                </svg>
                لا حاجة لإنشاء حساب</span
              ><span
                ><svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6zM8 12l3 3 5-6" />
                </svg>
                خصوصية وأمان لبياناتك</span
              >
            </div>
          </div>
          <div class="visual">
            <img
              src="{{ asset('shared/images/school/school.jpg') }}"
              alt="مبنى تعليمي حديث في الرياض، صورة توضيحية"
              width="550"
              height="400"
            />
            <div class="float-card">
              <span class="circle"
                ><svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path
                    d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6zM8 12l3 3 5-6"
                  /></svg
              ></span>
              <div>
                <b>سلامتهم أولويتنا</b
                ><small>من بداية الطلب، حتى استلام الطالب</small>
              </div>
            </div>
            <div class="image-tag">رعاية متكاملة. تواصل يطمئن.</div>
          </div>
        </div>
      </section>
      <div class="wrap service-strip">
        <span
          ><svg
            class="icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
          >
            <path d="M14 3H5v18h14V8zM14 3v6h5M8 13h8M8 17h5" />
          </svg>
          طلب إلكتروني بخطوات بسيطة</span
        ><span
          ><svg
            class="icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
          >
            <path d="M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
          </svg>
          متابعة حالة الطلب لحظة بلحظة</span
        ><span
          ><svg
            class="icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
          >
            <path d="M21 11a9 9 0 0 1-9 9H3l2-5a9 9 0 1 1 16-4M8 10h8M8 14h5" />
          </svg>
          إشعارات عبر واتساب</span
        ><span
          ><svg
            class="icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
          >
            <path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6zM8 12l3 3 5-6" />
          </svg>
          استلام آمن ومنظّم</span
        >
      </div>
      <section class="section wrap" id="how">
        <div class="section-head">
          <p class="section-label">رحلة واضحة من البداية</p>
          <h2>كيف تعمل الخدمة؟</h2>
          <p class="muted">أربع خطوات بسيطة، واطمئنان في كل خطوة.</p>
        </div>
        <div class="steps">
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
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path d="M14 3H5v18h14V8zM14 3v6h5M8 13h8M8 17h5" /></svg
              ><small>1</small>
            </div>
            <h3>أدخل بيانات الطلب</h3>
            <p>بيانات الطالب وولي الأمر وسبب الاستئذان.</p>
          </div>
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
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path d="m3 11 18-8-8 18-2-8zM11 13 21 3" /></svg
              ><small>2</small>
            </div>
            <h3>أرسل الطلب للإدارة</h3>
            <p>راجع المعلومات وأرسل طلبك بسهولة.</p>
          </div>
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
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path
                  d="M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0"
                /></svg
              ><small>3</small>
            </div>
            <h3>تابع حالة الطلب</h3>
            <p>اطّلع على القرار وإشعار واتساب.</p>
          </div>
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
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path d="M10 4H4v16h6M10 12h11m-4-4 4 4-4 4" /></svg
              ><small>4</small>
            </div>
            <h3>استلم الطالب</h3>
            <p>أظهر رمز الاستلام بعد الموافقة.</p>
          </div>
        </div>
      </section>
      <section class="wrap section section-service" id="about">
        <div class="service-panel">
          <div class="service-copy">
            <p class="section-label">أقرب إليكم، أينما كنتم</p>
            <h2>وقتكم ثمين،<br />وسلامة أبنائكم أمانة.</h2>
            <p>
              خدمة استئذان تربطكم بالمدرسة مباشرة. أرسل البيانات المطلوبة، ودع
              فريق المدرسة يراجع طلبك ويوافيك بالقرار.
            </p>
            <a class="btn" href="{{ url('/request') }}">ابدأ طلب الاستئذان</a>
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
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path
                  d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0M4 21v-3a8 8 0 0 1 16 0v3"
                />
              </svg>
              <div>
                <b>بدون حساب أو كلمة مرور</b>
                <p>كل ما تحتاجه هو بياناتك وبيانات الطالب.</p>
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
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path
                  d="M21 11a9 9 0 0 1-9 9H3l2-5a9 9 0 1 1 16-4M8 10h8M8 14h5"
                />
              </svg>
              <div>
                <b>نبقيك على اطلاع</b>
                <p>يصلك إشعار واتساب عند تحديث حالة الطلب.</p>
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
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path d="M6 10h12v11H6zM8 10V7a4 4 0 0 1 8 0v3" />
              </svg>
              <div>
                <b>بياناتكم محل عنايتنا</b>
                <p>الهوية والتوقيع لتأكيد صفة المستلم وحماية الطالب.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="notice">
          <svg
            class="icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
          >
            <path d="M12 11v6M12 7v1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
          </svg>
          <div>
            <h3>قبل تقديم الطلب</h3>
            <ul>
              <li>تأكد من صحة بيانات الطالب.</li>
              <li>أحضر الهوية الأصلية عند الاستلام.</li>
              <li>انتظر موافقة المدرسة قبل الحضور.</li>
            </ul>
          </div>
        </div>
      </section>
      <section id="contact" class="section contact">
        <div class="wrap contact-grid">
          <div>
            <p class="section-label">يسعدنا تواصلكم</p>
            <h2>نحن هنا لمساعدتكم</h2>
            <p class="muted contact-description">
              فريق المدرسة بجانبك في كل خطوة.
            </p>
          </div>
          <div class="contact-items">
            <div class="contact-item">
              <svg
                class="icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path
                  d="M5 3h4l2 5-3 2a15 15 0 0 0 6 6l2-3 5 2v4c0 2-3 2-5 1C9 18 6 15 3 8c-1-2 0-5 2-5"
                />
              </svg>
              <div>
                <small>هاتف المدرسة</small><b dir="ltr">011 234 5678</b>
              </div>
            </div>
            <div class="contact-item">
              <svg
                class="icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path
                  d="M21 11a9 9 0 0 1-9 9H3l2-5a9 9 0 1 1 16-4M8 10h8M8 14h5"
                />
              </svg>
              <div>
                <small>واتساب المدرسة</small><b dir="ltr">050 123 4567</b>
              </div>
            </div>
            <div class="contact-item">
              <svg
                class="icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path
                  d="M19 10c0 6-7 11-7 11S5 16 5 10a7 7 0 1 1 14 0M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0"
                />
              </svg>
              <div>
                <small>العنوان</small
                ><b dir="rtl">حي الياسمين، الرياض، المملكة العربية السعودية</b>
              </div>
            </div>
            <div class="contact-item">
              <svg
                class="icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <path d="M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
              </svg>
              <div>
                <small>ساعات العمل</small
                ><b dir="rtl">الأحد – الخميس، ٧:٠٠ ص – ٢:٠٠ م</b>
              </div>
            </div>
          </div>
        </div>
        <p class="wrap demo-note contact-demo-note">
          مدرسة وبيانات تواصل افتراضية للعرض التجريبي. إشعارات واتساب محاكاة
          فقط.
          <a
            href="https://bsbgroup.com/blog/misk-schools-receives-first-students"
            target="_blank"
            rel="noopener"
            >الصورة التوضيحية: مدارس مسك · Chris Goldstraw</a
          >
        </p>
      </section>

@endsection
