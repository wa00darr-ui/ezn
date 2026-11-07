
@extends('layouts.dashboard', [
    'activePage' => 'requests',
    'pageTitle' => 'طلبات الاستئذان'
])

@section('title', 'طلبات الاستئذان')
@section('page', 'requests')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ config('school.name', 'إدارة المدرسة') }}
        </p>

        <h1>طلبات الاستئذان</h1>
    </div>

    <div class="page-actions">
        @can('requests.view')
            @if(Route::has('dashboard.history.index'))
                <a
                    class="btn secondary"
                    href="{{ route('dashboard.history.index') }}"
                >
                    سجل الاستئذانات
                </a>
            @endif
        @endcan
    </div>
</div>

<div data-page-content>

    <p class="page-description">
        راجع الطلبات الواردة واطّلع على بيانات
        الطالب وولي الأمر قبل اتخاذ القرار.
    </p>

    @if(session('success'))
        <div class="success-box" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-box" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="panel">

        <div class="panel-head">
            <h2>
                جميع الطلبات
                <span class="count">
                    {{ $totalRequests }}
                </span>
            </h2>
        </div>

        <!-- البحث والتصفية -->
        <form
            class="filters"
            method="GET"
            action="{{ route('dashboard.requests.index') }}"
        >

            <label class="field search-field">
                <span>بحث</span>

                <input
                    type="search"
                    name="search"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="ابحث برقم الطلب أو ولي الأمر أو الجوال"
                    aria-label="بحث في الطلبات"
                >
            </label>

            <label class="field">
                <span>الحالة</span>

                <select name="status">
                    <option value="">كل الحالات</option>

                    <option
                        value="pending"
                        @selected(($filters['status'] ?? '') === 'pending')
                    >
                        بانتظار المراجعة
                    </option>

                    <option
                        value="approved"
                        @selected(($filters['status'] ?? '') === 'approved')
                    >
                        موافق عليه
                    </option>

                    <option
                        value="rejected"
                        @selected(($filters['status'] ?? '') === 'rejected')
                    >
                        مرفوض
                    </option>

                    <option
                        value="completed"
                        @selected(($filters['status'] ?? '') === 'completed')
                    >
                        مكتمل
                    </option>

                    <option
                        value="cancelled"
                        @selected(($filters['status'] ?? '') === 'cancelled')
                    >
                        ملغى
                    </option>
                </select>
            </label>

            <label class="field">
                <span>الطالب</span>

                <select name="student">
                    <option value="">كل الطلاب</option>

                    @foreach($students as $studentId)
                        <option
                            value="{{ $studentId }}"
                            @selected(
                                (string)($filters['student'] ?? '') ===
                                (string)$studentId
                            )
                        >
                            طالب رقم {{ $studentId }}
                        </option>
                    @endforeach

                </select>
            </label>

            <label class="field">
                <span>من تاريخ</span>

                <input
                    type="date"
                    name="from"
                    value="{{ $filters['from'] ?? '' }}"
                >
            </label>

            <label class="field">
                <span>إلى تاريخ</span>

                <input
                    type="date"
                    name="to"
                    value="{{ $filters['to'] ?? '' }}"
                >
            </label>

            <button class="btn" type="submit">
                بحث
            </button>

            <a
                class="btn secondary"
                href="{{ route('dashboard.requests.index') }}"
            >
                مسح الفلاتر
            </a>

        </form>

        <!-- جدول الطلبات -->
        <div
            class="table-wrap"
            tabindex="0"
            role="region"
            aria-label="جدول طلبات الاستئذان"
        >

            <table>
                <thead>
                    <tr>
                        <th scope="col">رقم الطلب</th>
                        <th scope="col">الطالب</th>
                        <th scope="col">الرقم الطلابي</th>
                        <th scope="col">ولي الأمر</th>
                        <th scope="col">الجوال</th>
                        <th scope="col">السبب</th>
                        <th scope="col">تاريخ التقديم</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">إجراء</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($requests as $item)

                        <tr>

                            <td>
                                <a
                                    class="table-link"
                                    href="{{ route('dashboard.requests.show', $item->id) }}"
                                >
                                    #{{ $item->id }}
                                </a>
                            </td>

                            <td>
                                <b>
                                    طالب رقم
                                    {{ $item->student_id ?? 'غير محدد' }}
                                </b>
                            </td>

                            <td dir="ltr">
                                —
                            </td>

                            <td>
                                {{ $item->parent_name }}
                            </td>

                            <td dir="ltr">
                                {{ $item->parent_phone }}
                            </td>

                            <td>
                                {{ \Illuminate\Support\Str::limit(
                                    $item->reason ?? '—',
                                    60
                                ) }}
                            </td>

                            <td>
                                <time
                                    datetime="{{ $item->created_at?->toIso8601String() }}"
                                >
                                    {{ $item->created_at?->format('Y-m-d') }}

                                    <small>
                                        {{ $item->created_at?->format('H:i') }}
                                    </small>
                                </time>
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
                                            {{ $item->status }}

                                    @endswitch
                                </x-dashboard.status-badge>
                            </td>

                            <td>
                                <a
                                    class="row-action"
                                    href="{{ route('dashboard.requests.show', $item->id) }}"
                                    aria-label="عرض الطلب رقم {{ $item->id }}"
                                >
                                    عرض ←
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <h3>لا توجد طلبات مطابقة</h3>

                                    <p>
                                        لا توجد طلبات مسجلة
                                        تطابق معايير البحث الحالية.
                                    </p>

                                    <a
                                        class="btn secondary"
                                        href="{{ route('dashboard.requests.index') }}"
                                    >
                                        مسح الفلاتر
                                    </a>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- ترقيم الصفحات -->
        <div class="pagination-summary">
            @if($requests->total() > 0)
                عرض {{ $requests->firstItem() }}
                –
                {{ $requests->lastItem() }}
                من {{ $requests->total() }} طلب
            @else
                لا توجد نتائج.
            @endif
        </div>

        @if($requests->hasPages())
            {{ $requests->links() }}
        @endif

    </section>

</div>

@endsection

