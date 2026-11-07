<noscript
      ><div class="wrap hint">
        يمكنك الاطّلاع على جميع محتويات الصفحات. فعّل JavaScript لتجربة التنقل
        بين خطوات الطلب والإرسال التجريبي.
      </div></noscript
    >
    <div class="topline">
      <div class="wrap">
        <span>بيئة تعليمية آمنة، وتواصل أقرب</span
        ><span>خدمة أولياء الأمور <i></i> نموذج تجريبي</span>
      </div>
    </div>
    <header class="wrap">
      <a href="{{ url('/') }}" class="brand" aria-label="مدارس آفاق — الرئيسية"
        ><span class="mark"
          ><svg
            viewBox="0 0 40 40"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              d="M7 29V13l13-6 13 6v16l-13-6zM20 7v16M12 17l8-4 8 4M12 23l8-4 8 4"
            /></svg></span
        ><span
          ><b>مدارس آفاق الأهلية</b
          ><small>نرتقي بالعلم، ونرعى المستقبل</small></span
        ></a
      >
      <nav id="nav" aria-label="القائمة الرئيسية">
        <a href="{{ url('/') }}">الرئيسية</a><a href="{{ url('/about') }}">عن الخدمة</a
        ><a href="{{ url('/contact') }}">تواصل معنا</a>
      </nav>
      <div class="header-actions">
        <a class="text-btn track-link" href="{{ url('/tracking') }}"> متابعة طلب</a
        ><a class="btn small" href="{{ url('/request') }}">طلب استئذان</a
        ><button
          class="menu"
          aria-label="فتح القائمة"
          aria-expanded="false"
          aria-controls="nav"
        >
          ☰
        </button>
      </div>
    </header>
