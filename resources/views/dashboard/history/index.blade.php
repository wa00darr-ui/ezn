
@extends('layouts.dashboard', [
    'activePage' => 'history',
    'pageTitle' => 'سجل الاستئذانات'
])

@section('title', 'سجل الاستئذانات')
@section('page', 'history')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ config('school.name', 'إدارة المدرسة') }}
        </p>

        <h1>سجل الاستئذانات</h1>
    </div>

    <div class="page-actions">
        <a
            class="btn secondary"
            href="{{ route('dashboard.history.export', request()->query()) }}"
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
                <path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/>
            </svg>
            تصدير السجل
        </a>
    </div>
</div>

<div data-page-content>

    <p class="page-description">
        سجل القرارات السابقة وعمليات تسليم الطلاب،
        مع تفاصيل الموظفين والأوقات.
    </p>

    @if($errors->any())
        <div class="error-box" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="panel">

        <div class="panel-head">
            <h2>أرشيف الطلبات</h2>
        </div>

        <!-- نموذج البحث والفلاتر -->
        <form
            class="filters"
            method="GET"
            action="{{ route('dashboard.history.index') }}"
        >

            <!-- البحث -->
            <label class="field search-field">
                <span>بحث</span>

                <input
                    type="search"
                    name="search"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="ابحث برقم الطلب أو ولي الأمر…"
                    aria-label="بحث في الجدول"
                >
            </label>

            <!-- الحالة -->
            <label class="field">
                <span>الحالة</span>

                <select name="status">
                    <option value="">كل الحالات</option>

                    <option
                        value="completed"
                        @selected(($filters['status'] ?? '') === 'completed')
                    >
                        مكتمل
                    </option>

                    <option
                        value="rejected"
                        @selected(($filters['status'] ?? '') === 'rejected')
                    >
                        مرفوض
                    </option>
                </select>
            </label>

            <!-- الطالب -->
            <label class="field">
                <span>الطالب</span>

                <select name="student">
                    <option value="">كل الطلاب</option>

                    @foreach($students as $studentId)
                        <option
                            value="{{ $studentId }}"
                            @selected(
                                (string) ($filters['student'] ?? '') ===
                                (string) $studentId
                            )
                        >
                            طالب رقم {{ $studentId }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- ولي الأمر -->
            <label class="field">
                <span>ولي الأمر</span>

                <select name="guardian">
                    <option value="">كل أولياء الأمور</option>

                    @foreach($guardians as $guardian)
                        <option
                            value="{{ $guardian }}"
                            @selected(
                                ($filters['guardian'] ?? '') === $guardian
                            )
                        >
                            {{ $guardian }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- موظف الموافقة -->
            <label class="field">
                <span>موظف الموافقة</span>

                <select name="approver">
                    <option value="">كل الموظفين</option>

                    @foreach($approvers as $employee)
                        <option
                            value="{{ $employee->id }}"
                            @selected(
                                (string) ($filters['approver'] ?? '') ===
                                (string) $employee->id
                            )
                        >
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- موظف التسليم -->
            <label class="field">
                <span>موظف التسليم</span>

                <select name="checkout">
                    <option value="">كل الموظفين</option>

                    @foreach($checkoutEmployees as $employee)
                        <option
                            value="{{ $employee->id }}"
                            @selected(
                                (string) ($filters['checkout'] ?? '') ===
                                (string) $employee->id
                            )
                        >
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- بداية الفترة -->
            <label class="field">
                <span>من تاريخ</span>

                <input
                    type="date"
                    name="from"
                    value="{{ $filters['from'] ?? '' }}"
                >
            </label>

            <!-- نهاية الفترة -->
            <label class="field">
                <span>إلى تاريخ</span>

                <input
                    type="date"
                    name="to"
                    value="{{ $filters['to'] ?? '' }}"
                >
            </label>

            <!-- الأزرار -->
            <button type="submit" class="btn">
                بحث
            </button>

            <a
                href="{{ route('dashboard.history.index') }}"
                class="btn secondary"
            >
                مسح الفلاتر
            </a>

        </form>

        <!-- جدول سجل الاستئذانات -->
        <div
            class="table-wrap"
            tabindex="0"
            role="region"
            aria-label="جدول سجل الاستئذانات"
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
                        <th scope="col">موظف الموافقة</th>
                        <th scope="col">موظف التسليم / الوقت</th>
                        <th scope="col">إجراء</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($history as $item)

                        <tr>

                            <!-- رقم الطلب -->
                            <td>
                                <b>#{{ $item->id }}</b>
                            </td>

                            <!-- الطالب -->
                            <td>
                                <b>
                                    طالب رقم {{ $item->student_id ?? '—' }}
                                </b>
                            </td>

                            <!-- الرقم الطلابي -->
                            <td dir="ltr">
                                —
                            </td>

                            <!-- ولي الأمر -->
                            <td>
                                {{ $item->parent_name }}
                            </td>

                            <!-- الجوال -->
                            <td dir="ltr">
                                {{ $item->parent_phone }}
                            </td>

                            <!-- السبب -->
                            <td>
                                {{ $item->reason ?? '—' }}
                            </td>

                            <!-- تاريخ تقديم الطلب -->
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

                            <!-- الحالة -->
                            <td>
                                <x-dashboard.status-badge
                                    :status="$item->status"
                                >
                                    {{ $item->status === 'completed'
                                        ? 'مكتمل'
                                        : 'مرفوض' }}
                                </x-dashboard.status-badge>
                            </td>

                            <!-- موظف الموافقة أو الرفض -->
                            <td>
                                {{ $item->reviewer?->name ?? '—' }}
                            </td>

                            <!-- موظف التسليم -->
                            <td>
                                @if($item->status === 'completed')
                                    {{ $item->checkoutEmployee?->name ?? '—' }}

                                    @if($item->checked_out_at)
                                        <small>
                                            {{ $item->checked_out_at->format('Y-m-d H:i') }}
                                        </small>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>

                            <!-- عرض التفاصيل -->
                            <td>
                                <button
                                    type="button"
                                    class="row-action"
                                    data-history-details
                                    data-id="{{ $item->id }}"
                                    data-guardian="{{ $item->parent_name }}"
                                    data-phone="{{ $item->parent_phone }}"
                                    data-reason="{{ $item->reason ?? '—' }}"
                                    data-status="{{ $item->status === 'completed' ? 'مكتمل' : 'مرفوض' }}"
                                    data-approver="{{ $item->reviewer?->name ?? '—' }}"
                                    data-checkout="{{ $item->checkoutEmployee?->name ?? '—' }}"
                                    data-time="{{ $item->checked_out_at?->format('Y-m-d H:i') ?? '—' }}"
                                >
                                    عرض
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
                                        <path d="M19 12H5m7-7-7 7 7 7"/>
                                    </svg>
                                </button>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="11">
                                <div class="empty-state">
                                    <h3>لا توجد نتائج مطابقة</h3>

                                    <p>
                                        لا توجد طلبات مكتملة أو مرفوضة
                                        تطابق الفلاتر الحالية.
                                    </p>

                                    <a
                                        href="{{ route('dashboard.history.index') }}"
                                        class="btn secondary"
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

        <!-- عدد السجلات -->
        <div class="pagination-summary">
            @if($history->total() > 0)
                عرض {{ $history->firstItem() }}
                –
                {{ $history->lastItem() }}
                من {{ $history->total() }} سجلات
            @else
                لا توجد سجلات في الأرشيف.
            @endif
        </div>

        <!-- ترقيم الصفحات -->
        @if($history->hasPages())
            {{ $history->links() }}
        @endif

    </section>

</div>

@endsection

@section('dialogs')

<x-dashboard.modal
    id="history-dialog"
    title="تفاصيل الاستئذان"
>
    <dl class="data-grid">

        <div>
            <dt>رقم الطلب</dt>
            <dd id="history-id">—</dd>
        </div>

        <div>
            <dt>ولي الأمر</dt>
            <dd id="history-guardian">—</dd>
        </div>

        <div>
            <dt>الجوال</dt>
            <dd id="history-phone">—</dd>
        </div>

        <div>
            <dt>السبب</dt>
            <dd id="history-reason">—</dd>
        </div>

        <div>
            <dt>الحالة</dt>
            <dd id="history-status">—</dd>
        </div>

        <div>
            <dt>موظف الموافقة</dt>
            <dd id="history-approver">—</dd>
        </div>

        <div>
            <dt>موظف التسليم</dt>
            <dd id="history-checkout">—</dd>
        </div>

        <div>
            <dt>وقت التسليم</dt>
            <dd id="history-time">—</dd>
        </div>

    </dl>

    <div class="dialog-actions">
        <button
            type="button"
            class="btn secondary"
            data-close
        >
            إغلاق
        </button>
    </div>
</x-dashboard.modal>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const dialog = document.getElementById('history-dialog');

    if (!dialog) {
        return;
    }

    function setValue(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.textContent = value || '—';
        }
    }

    document.querySelectorAll('[data-history-details]')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                setValue('history-id', '#' + button.dataset.id);
                setValue('history-guardian', button.dataset.guardian);
                setValue('history-phone', button.dataset.phone);
                setValue('history-reason', button.dataset.reason);
                setValue('history-status', button.dataset.status);
                setValue('history-approver', button.dataset.approver);
                setValue('history-checkout', button.dataset.checkout);
                setValue('history-time', button.dataset.time);

                if (!dialog.open) {
                    dialog.showModal();
                }
            });
        });

    dialog.querySelectorAll('[data-close]')
        .forEach(function (button) {
            button.addEventListener('click', function () {
                dialog.close();
            });
        });

});
</script>

@endsection

