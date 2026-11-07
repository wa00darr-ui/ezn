<section
          class="state-panel"
          data-page-state="loading"
          role="status"
          hidden
        >
          <span class="spinner"></span>
          <h2>جارٍ تحميل البيانات…</h2>
          <div class="skeleton"></div>
          <div class="skeleton"></div>
          <div class="skeleton"></div>
        </section>
<section class="state-panel" data-page-state="empty" hidden>
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
          <h2>لا توجد بيانات حتى الآن</h2>
          <p>ستظهر البيانات هنا عند بدء استخدام الخدمة.</p>
          <button type="button" class="btn secondary" data-demo-state="ready">
            العودة للعرض
          </button>
        </section>
<section
          class="state-panel"
          data-page-state="error"
          role="alert"
          hidden
        >
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
          <h2>تعذّر تحميل البيانات</h2>
          <p>حدث خطأ في الاتصال التجريبي. يمكنك المحاولة مرة أخرى.</p>
          <button type="button" class="btn" data-demo-state="ready">
            إعادة المحاولة
          </button>
        </section>
