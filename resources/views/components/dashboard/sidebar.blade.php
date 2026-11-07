
<aside class="sidebar" id="sidebar">

    <button
        type="button"
        class="icon-btn sidebar-close"
        data-sidebar-close
        aria-label="إغلاق القائمة الجانبية"
    >
        ×
    </button>

    <!-- شعار النظام -->
    <a
        class="brand {{ $activePage === 'dashboard' ? 'active' : '' }}"
        href="{{ url('/dashboard') }}"
        @if($activePage === 'dashboard') aria-current="page" @endif
    >
        <span class="brand-mark">إ</span>
        <span>
            استأذن
            <small>إدارة المدرسة</small>
        </span>
    </a>

    <!-- معلومات المدرسة -->
    <div class="school-switch">
        <span class="school-icon">
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M3 10 12 3l9 7v11h-6v-7H9v7H3z"/>
            </svg>
        </span>

        <div>
            <b>{{ $schoolName }}</b>
            <small>
                {{ $schoolCity }} · العام {{ $academicYear }}
            </small>
        </div>
    </div>

    <span class="nav-label">مساحة العمل</span>

    <nav aria-label="التنقل الرئيسي">

        <!-- الرئيسية -->
        <a href="{{ url('/dashboard') }}"
           class="{{ $activePage === 'dashboard' ? 'active' : '' }}"
           @if($activePage === 'dashboard') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M3 10 12 3l9 7v11h-6v-7H9v7H3z"/>
            </svg>
            <span>الرئيسية</span>
        </a>

        <!-- طلبات الاستئذان -->
        <a href="{{ url('/dashboard/requests') }}"
           class="{{ $activePage === 'requests' ? 'active' : '' }}"
           @if($activePage === 'requests') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M14 3H5v18h14V8zM14 3v6h5M8 13h8M8 17h5"/>
            </svg>
            <span>طلبات الاستئذان</span>
            <b class="nav-count">
                {{ $pendingRequestsCount }}
            </b>
        </a>

        <!-- تسليم الطلاب -->
        <a href="{{ url('/dashboard/checkout') }}"
           class="{{ $activePage === 'checkout' ? 'active' : '' }}"
           @if($activePage === 'checkout') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M10 4H4v16h6M10 12h11m-4-4 4 4-4 4"/>
            </svg>
            <span>تسليم الطلاب</span>
        </a>

        <!-- سجل الاستئذانات -->
        <a href="{{ url('/dashboard/history') }}"
           class="{{ $activePage === 'history' ? 'active' : '' }}"
           @if($activePage === 'history') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0"/>
            </svg>
            <span>سجل الاستئذانات</span>
        </a>

        <!-- الطلاب -->
        <a href="{{ url('/dashboard/students') }}"
           class="{{ $activePage === 'students' ? 'active' : '' }}"
           @if($activePage === 'students') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0M4 21v-3a8 8 0 0 1 16 0v3"/>
            </svg>
            <span>الطلاب</span>
        </a>

        <!-- الموظفون والصلاحيات -->
        <a href="{{ url('/dashboard/employees') }}"
           class="{{ $activePage === 'employees' ? 'active' : '' }}"
           @if($activePage === 'employees') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6zM8 12l3 3 5-6"/>
            </svg>
            <span>الموظفون والصلاحيات</span>
        </a>

        <!-- التقارير -->
        <a href="{{ url('/dashboard/reports') }}"
           class="{{ $activePage === 'reports' ? 'active' : '' }}"
           @if($activePage === 'reports') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M4 3v18h17M8 17v-5m5 5V7m5 10V4"/>
            </svg>
            <span>التقارير</span>
        </a>

        <!-- سجل النشاط -->
        <a href="{{ url('/dashboard/activity-log') }}"
           class="{{ $activePage === 'activity-log' ? 'active' : '' }}"
           @if($activePage === 'activity-log') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0"/>
            </svg>
            <span>سجل النشاط</span>
        </a>

        <!-- إدارة الموقع -->
        <a href="{{ url('/dashboard/website') }}"
           class="{{ $activePage === 'website' ? 'active' : '' }}"
           @if($activePage === 'website') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0M3 12h18M12 3c5 5 5 13 0 18-5-5-5-13 0-18"/>
            </svg>
            <span>إدارة الموقع</span>
        </a>

        <!-- الإعدادات -->
        <a href="{{ url('/dashboard/settings') }}"
           class="{{ $activePage === 'settings' ? 'active' : '' }}"
           @if($activePage === 'settings') aria-current="page" @endif>
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8M12 2v3m0 14v3M2 12h3m14 0h3M5 5l2 2m10 10 2 2M5 19l2-2M17 7l2-2"/>
            </svg>
            <span>الإعدادات</span>
        </a>

    </nav>

    <!-- بيانات المستخدم وتسجيل الخروج -->
    <div class="sidebar-bottom">

        <span class="avatar">
            {{ $avatarInitials }}
        </span>

        <div>
            <b>{{ $displayName }}</b>
            <small>{{ $displayRole }}</small>
        </div>

        <button
            type="button"
            class="icon-btn"
            data-open="logout-dialog"
            aria-label="تسجيل الخروج"
        >
            <svg class="icon" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <path d="M10 4H4v16h6M10 12h11m-4-4 4 4-4 4"/>
            </svg>
        </button>

    </div>
</aside>
