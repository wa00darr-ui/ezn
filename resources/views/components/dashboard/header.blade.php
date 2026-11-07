@props(['title'])
<header class="topbar">
        <div class="topbar-title">
          <button
            class="icon-btn mobile-toggle"
            data-sidebar-toggle
            aria-controls="sidebar"
            aria-expanded="false"
            aria-label="فتح القائمة"
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
              <path d="M4 6h16M4 12h16M4 18h16" />
            </svg></button
          ><span
            >إدارة المدرسة <span class="muted">/</span> <b>{{ $title }}</b></span
          >
        </div>
        <div class="topbar-actions">
          <span class="today">الأحد، ٤ أكتوبر ٢٠٢٦</span>
          <details class="dropdown">
            <summary class="notification-trigger" aria-label="الإشعارات">
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
                <path d="M6 9a6 6 0 0 1 12 0v7l2 2H4l2-2zM10 21h4" /></svg
              ><i></i>
            </summary>
            <div class="dropdown-panel">
              <h3>الإشعارات <span class="badge new">٣ جديدة</span></h3>
              <a href="{{ url('/dashboard/request-details') }}"
                ><b>طلب استئذان جديد</b
                ><small>محمد أحمد القحطاني · منذ دقيقتين</small></a
              ><a href="{{ url('/dashboard/checkout') }}"
                ><b>طالب جاهز للاستلام</b
                ><small>سعود محمد الدوسري · منذ ٥ دقائق</small></a
              ><a href="{{ url('/dashboard/requests') }}"
                >عرض جميع الطلبات
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
                  <path d="M19 12H5m7-7-7 7 7 7" /></svg
              ></a>
            </div>
          </details>
          <details class="dropdown profile">
            <summary>
              <span class="avatar">ع م</span
              ><span>عبدالرحمن المالكي <small>مدير النظام</small></span
              ><span>⌄</span>
            </summary>
            <div class="dropdown-panel">
              <a href="{{ url('/dashboard/settings') }}">إعدادات المدرسة</a
              ><a href="{{ url('/') }}" target="_blank" rel="noopener"
                >عرض موقع أولياء الأمور</a
              ><button type="button" data-open="logout-dialog">
                تسجيل الخروج
              </button>
            </div>
          </details>
        </div>
      </header>
