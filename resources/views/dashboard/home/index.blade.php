
@extends('layouts.dashboard', [
    'activePage' => 'dashboard',
    'pageTitle' => 'نظرة عامة'
])

@section('title', 'نظرة عامة')
@section('page', 'dashboard')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ config('school.name', 'إدارة المدرسة') }}
        </p>
        <h1>نظرة عامة</h1>
    </div>

    <div class="page-actions">
        @can('checkout-students')
            <a
                class="btn"
                href="{{ route('dashboard.checkout.index') }}"
            >
                تسليم طالب
            </a>
        @endcan
    </div>
</div>

<div data-page-content>

    <!-- الترحيب -->
    <section class="welcome">
        <div>
            <span class="eyebrow">
                يوم دراسي أكثر تنظيمًا
            </span>

            <h2>
                أهلًا، {{ auth()->user()?->name ?? 'موظف المدرسة' }}
            </h2>

            <p>
                يوجد
                <b>{{ $pendingCount }} طلب</b>
                بانتظار المراجعة.
                كل تفاصيل الاستئذان في مكان واحد.
            </p>

            @can('requests.view')
                @if(Route::has('dashboard.requests.index'))
                    <a
                        class="btn light"
                        href="{{ route('dashboard.requests.index') }}"
                    >
                        مراجعة الطلبات ←
                    </a>
                @endif
            @endcan
        </div>

        <div class="welcome-symbol">
            <svg
                class="icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
                aria-hidden="true"
            >
                <path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6zM8 12l3 3 5-6"/>
            </svg>
            <span>سلامتهم أولويتنا</span>
        </div>
    </section>

    <!-- الإحصائيات -->
    <div class="kpi-grid">

        <article class="kpi accent">
            <div class="kpi-top">
                <span>طلبات تنتظر المراجعة</span>
                <span>📋</span>
            </div>
            <strong>{{ $pendingCount }}</strong>
            <small>تحتاج إلى اتخاذ قرار</small>
        </article>

        <article class="kpi">
            <div class="kpi-top">
                <span>طلبات اليوم</span>
                <span>🕒</span>
            </div>
            <strong>{{ $todayTotal }}</strong>
            <small>إجمالي الطلبات المقدمة اليوم</small>
        </article>

        <article class="kpi">
            <div class="kpi-top">
                <span>موافق عليها</span>
                <span>✓</span>
            </div>
            <strong>{{ $approvedToday }}</strong>
            <small>طلبات اليوم التي ما زالت موافقًا عليها</small>
        </article>

        <article class="kpi">
            <div class="kpi-top">
                <span>مرفوضة</span>
                <span>✕</span>
            </div>
            <strong>{{ $rejectedToday }}</strong>
            <small>طلبات مرفوضة مقدمة اليوم</small>
        </article>

        <article class="kpi">
            <div class="kpi-top">
                <span>خرجوا اليوم</span>
                <span>↗</span>
            </div>
            <strong>{{ $checkoutCount }}</strong>
            <small>عمليات التسليم المكتملة اليوم</small>
        </article>

    </div>

    <!-- أحدث طلبات الاستئذان -->
    <section class="panel">
        <div class="panel-head">
            <h2>أحدث طلبات الاستئذان</h2>

            @can('requests.view')
                @if(Route::has('dashboard.requests.index'))
                    <a
                        class="text-link"
                        href="{{ route('dashboard.requests.index') }}"
                    >
                        عرض جميع الطلبات
                    </a>
                @endif
            @endcan
        </div>

        @can('requests.view')
            <div class="table-wrap" tabindex="0">
                <table>
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>الطالب</th>
                            <th>ولي الأمر</th>
                            <th>وقت الطلب</th>
                            <th>السبب</th>
                            <th>الحالة</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($latestRequests as $item)
                            <tr>
                                <td>
                                    <b>#{{ $item->id }}</b>
                                </td>

                                <td>
                                    طالب رقم {{ $item->student_id ?? '—' }}
                                </td>

                                <td>
                                    {{ $item->parent_name }}
                                </td>

                                <td>
                                    {{ $item->created_at?->format('H:i') }}
                                    <small>
                                        {{ $item->created_at?->format('Y-m-d') }}
                                    </small>
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit($item->reason ?? '—', 45) }}
                                </td>

                                <td>
                                    <x-dashboard.status-badge
                                        :status="$item->status"
                                    >
                                        @switch($item->status)
                                            @case('pending')
                                                بانتظار المراجعة
                                                @break
                                            @case('approved')
                                                موافق عليه
                                                @break
                                            @case('completed')
                                                مكتمل
                                                @break
                                            @case('rejected')
                                                مرفوض
                                                @break
                                            @case('cancelled')
                                                ملغى
                                                @break
                                            @default
                                                {{ $item->status }}
                                        @endswitch
                                    </x-dashboard.status-badge>
                                </td>

                                <td>
                                    @if(Route::has('dashboard.requests.show'))
                                        <a
                                            class="row-action"
                                            href="{{ route('dashboard.requests.show', $item->id) }}"
                                        >
                                            عرض ←
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    لا توجد طلبات مسجلة حتى الآن.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <p class="caption">
                ليست لديك صلاحية عرض تفاصيل طلبات الاستئذان.
            </p>
        @endcan
    </section>

    <!-- الرسوم البيانية -->
    <div class="two-col">

        <section class="panel">
            <div class="panel-head">
                <h2>طلبات اليوم حسب الساعة</h2>
            </div>

            <div class="chart-meta">
                <strong>
                    {{ $todayTotal }}
                    <small>طلبًا</small>
                </strong>
            </div>

            <div
                class="bar-chart"
                role="img"
                aria-label="توزيع الطلبات حسب ساعة التقديم"
            >
                @foreach($hourlyChart as $hour)
                    @php
                        $height = $hour['count'] > 0
                            ? max(5, round(
                                $hour['count'] / $maxHourlyCount * 100
                            ))
                            : 0;
                    @endphp

                    <div
                        title="{{ $hour['label'] }}: {{ $hour['count'] }} طلب"
                    >
                        <i
                            class="bar"
                            style="height: {{ $height }}%;"
                        ></i>
                        <span>{{ $hour['label'] }}</span>
                    </div>
                @endforeach
            </div>

            <p class="caption">
                الطلبات المقدمة بين 7 صباحًا و2 ظهرًا.
            </p>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>توزيع حالات طلبات اليوم</h2>
            </div>

            @php
                $angle = 0;
                $segments = [];

                foreach ($statusDistribution as $status) {
                    $portion = $todayTotal > 0
                        ? $status['count'] / $todayTotal * 360
                        : 0;

                    if ($portion > 0) {
                        $next = $angle + $portion;

                        $segments[] = $status['color']
                            . ' ' . $angle . 'deg '
                            . $next . 'deg';

                        $angle = $next;
                    }
                }

                $donutBackground = count($segments)
                    ? 'conic-gradient(' . implode(', ', $segments) . ')'
                    : 'conic-gradient(#e2e8f0 0deg 360deg)';
            @endphp

            <div class="distribution">
                <div
                    class="donut"
                    style="background: {{ $donutBackground }};"
                >
                    <div>
                        <b>{{ $todayTotal }}</b>
                        <small>طلب اليوم</small>
                    </div>
                </div>

                <ul class="legend">
                    @foreach($statusDistribution as $status)
                        <li>
                            <i
                                class="dot"
                                style="background: {{ $status['color'] }};"
                            ></i>
                            {{ $status['label'] }}
                            <b>{{ $status['count'] }}</b>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

    </div>

    <!-- التسليم والنشاط -->
    <div class="two-col">

        <section class="panel">
            <div class="panel-head">
                <h2>آخر عمليات التسليم</h2>

                @can('requests.view')
                    <a
                        class="text-link"
                        href="{{ route('dashboard.history.index') }}"
                    >
                        السجل الكامل
                    </a>
                @endcan
            </div>

            @can('requests.view')
                <ul class="activity-list">
                    @forelse($recentCheckouts as $item)
                        <li>
                            <span class="avatar">
                                {{ mb_substr($item->parent_name ?? 'ط', 0, 1) }}
                            </span>

                            <div>
                                <b>الطلب #{{ $item->id }}</b>

                                <small>
                                    طالب رقم {{ $item->student_id ?? '—' }}
                                    · الموظف:
                                    {{ $item->checkoutEmployee?->name ?? 'غير محدد' }}
                                </small>
                            </div>

                            <time>
                                {{ $item->checked_out_at?->format('Y-m-d H:i') }}
                            </time>
                        </li>
                    @empty
                        <li>لا توجد عمليات تسليم مسجلة.</li>
                    @endforelse
                </ul>
            @else
                <p class="caption">
                    ليست لديك صلاحية عرض عمليات التسليم.
                </p>
            @endcan
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>نشاط فريق المدرسة</h2>

                @can('view-activity-logs')
                    <a
                        class="text-link"
                        href="{{ route('dashboard.activity.index') }}"
                    >
                        كل النشاط
                    </a>
                @endcan
            </div>

            @can('view-activity-logs')
                <ul class="activity-list">
                    @forelse($recentActivities as $activity)
                        <li>
                            <span class="activity-dot"></span>

                            <div>
                                <b>
                                    {{ $activity->employee_name ?? 'موظف' }}
                                    —
                                    {{ $activity->action }}
                                </b>

                                <small>
                                    {{ $activity->record_name ?? $activity->entity ?? '—' }}
                                </small>
                            </div>

                            <time>
                                {{ $activity->created_at?->diffForHumans() }}
                            </time>
                        </li>
                    @empty
                        <li>لا توجد أنشطة مسجلة.</li>
                    @endforelse
                </ul>
            @else
                <p class="caption">
                    ليست لديك صلاحية عرض سجل النشاط.
                </p>
            @endcan
        </section>

    </div>

</div>

@endsection
