
@extends('layouts.dashboard', [
    'activePage' => 'activity-log',
    'pageTitle' => 'سجل النشاط'
])

@section('title', 'سجل النشاط')

@section('page', 'activity-log')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ config('school.name', 'إدارة المدرسة') }}
        </p>
        <h1>سجل النشاط</h1>
    </div>

    <div class="page-actions"></div>
</div>

<div data-page-content>

    <section class="panel">

        <div class="panel-head">
            <h2>سجل النشاط</h2>
        </div>

        <!-- البحث والفلاتر -->
        <form
            class="filters"
            method="GET"
            action="{{ route('dashboard.activity.index') }}"
        >

            <!-- البحث -->
            <label class="field search-field">
                <span>بحث</span>

                <input
                    type="search"
                    name="search"
                    value="{{ old('search', $filters['search'] ?? '') }}"
                    placeholder="ابحث بالاسم أو رقم الطلب…"
                    aria-label="بحث في الجدول"
                >
            </label>

            <!-- الموظف -->
            <label class="field">
                <span>الموظف</span>

                <select name="employee">
                    <option value="">
                        كل الموظفين
                    </option>

                    @foreach($employees as $employee)
                        <option
                            value="{{ $employee }}"
                            @selected(
                                ($filters['employee'] ?? '') === $employee
                            )
                        >
                            {{ $employee }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- نوع السجل -->
            <label class="field">
                <span>النوع</span>

                <select name="entity">
                    <option value="">كل الأنواع</option>

                    @foreach([
                        'طلب',
                        'طالب',
                        'موظف',
                        'صلاحيات',
                        'الموقع'
                    ] as $entity)
                        <option
                            value="{{ $entity }}"
                            @selected(
                                ($filters['entity'] ?? '') === $entity
                            )
                        >
                            {{ $entity }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- تاريخ البداية -->
            <label class="field">
                <span>من تاريخ</span>

                <input
                    type="date"
                    name="from"
                    value="{{ $filters['from'] ?? '' }}"
                >
            </label>

            <!-- تاريخ النهاية -->
            <label class="field">
                <span>إلى تاريخ</span>

                <input
                    type="date"
                    name="to"
                    value="{{ $filters['to'] ?? '' }}"
                >
            </label>

            <!-- زر البحث -->
            <button type="submit" class="btn">
                بحث
            </button>

            <!-- مسح الفلاتر -->
            <a
                href="{{ route('dashboard.activity.index') }}"
                class="btn secondary"
            >
                مسح الفلاتر
            </a>

        </form>

        <!-- أخطاء الفلاتر -->
        @if($errors->any())
            <div class="error-box" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- جدول النشاط -->
        <div
            class="table-wrap"
            tabindex="0"
            role="region"
            aria-label="جدول بيانات قابل للتمرير"
        >

            <table>
                <thead>
                    <tr>
                        <th scope="col">الموظف</th>
                        <th scope="col">الإجراء</th>
                        <th scope="col">الكيان</th>
                        <th scope="col">الطلب / السجل</th>
                        <th scope="col">التاريخ</th>
                        <th scope="col">الوقت</th>
                        <th scope="col">عنوان IP</th>
                        <th scope="col">تفاصيل</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($activities as $activity)

                        <tr>

                            <!-- الموظف -->
                            <td>
                                {{ $activity->employee_name }}
                            </td>

                            <!-- الإجراء -->
                            <td>
                                {{ $activity->action }}
                            </td>

                            <!-- الكيان -->
                            <td>
                                {{ $activity->entity }}
                            </td>

                            <!-- رقم السجل والاسم -->
                            <td>
                                {{ $activity->record_reference ?? '—' }}

                                @if($activity->record_name)
                                    <small>
                                        {{ $activity->record_name }}
                                    </small>
                                @endif
                            </td>

                            <!-- التاريخ -->
                            <td>
                                {{ $activity->created_at->format('Y-m-d') }}
                            </td>

                            <!-- الوقت -->
                            <td dir="ltr">
                                {{ $activity->created_at->format('H:i') }}
                            </td>

                            <!-- عنوان IP -->
                            <td dir="ltr">
                                {{ $activity->ip_address ?? '—' }}
                            </td>

                            <!-- تفاصيل العملية -->
                            <td>
                                <button
                                    type="button"
                                    class="text-link"
                                    data-open-activity
                                    data-employee="{{ $activity->employee_name }}"
                                    data-action-name="{{ $activity->action }}"
                                    data-entity-name="{{ $activity->entity }}"
                                    data-reference="{{ $activity->record_reference ?? '—' }}"
                                    data-record-name="{{ $activity->record_name ?? '—' }}"
                                    data-date="{{ $activity->created_at->format('Y-m-d') }}"
                                    data-time="{{ $activity->created_at->format('H:i') }}"
                                    data-ip="{{ $activity->ip_address ?? '—' }}"
                                    data-details="{{ json_encode($activity->details ?? [], JSON_UNESCAPED_UNICODE) }}"
                                >
                                    التفاصيل
                                </button>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <h3>لا توجد نتائج مطابقة</h3>

                                    <p>
                                        لا توجد سجلات نشاط مطابقة
                                        لمعايير البحث الحالية.
                                    </p>

                                    <a
                                        href="{{ route('dashboard.activity.index') }}"
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

        <!-- ترقيم الصفحات -->
        @if($activities->hasPages())
            <div class="pagination-wrap">
                {{ $activities->links() }}
            </div>
        @endif

        <!-- عدد السجلات -->
        <div class="pagination-summary">
            @if($activities->total() > 0)
                عرض {{ $activities->firstItem() }}
                –
                {{ $activities->lastItem() }}
                من {{ $activities->total() }} سجلات
            @else
                لا توجد سجلات نشاط.
            @endif
        </div>

    </section>

</div>

@endsection

@section('dialogs')

<x-dashboard.modal
    id="activity-dialog"
    title="تفاصيل النشاط"
>
    <dl class="data-grid">

        <div>
            <dt>الموظف</dt>
            <dd id="activity-employee">—</dd>
        </div>

        <div>
            <dt>الإجراء</dt>
            <dd id="activity-action">—</dd>
        </div>

        <div>
            <dt>الكيان</dt>
            <dd id="activity-entity">—</dd>
        </div>

        <div>
            <dt>السجل</dt>
            <dd id="activity-reference">—</dd>
        </div>

        <div>
            <dt>اسم السجل</dt>
            <dd id="activity-record-name">—</dd>
        </div>

        <div>
            <dt>التاريخ</dt>
            <dd id="activity-date">—</dd>
        </div>

        <div>
            <dt>الوقت</dt>
            <dd id="activity-time">—</dd>
        </div>

        <div>
            <dt>عنوان IP</dt>
            <dd id="activity-ip">—</dd>
        </div>

        <div>
            <dt>معلومات إضافية</dt>
            <dd id="activity-details">—</dd>
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

    const dialog = document.getElementById('activity-dialog');

    if (!dialog) {
        return;
    }

    function setText(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.textContent = value || '—';
        }
    }

    document.querySelectorAll('[data-open-activity]').forEach(function (button) {

        button.addEventListener('click', function () {

            setText('activity-employee', button.dataset.employee);
            setText('activity-action', button.dataset.actionName);
            setText('activity-entity', button.dataset.entityName);
            setText('activity-reference', button.dataset.reference);
            setText('activity-record-name', button.dataset.recordName);
            setText('activity-date', button.dataset.date);
            setText('activity-time', button.dataset.time);
            setText('activity-ip', button.dataset.ip);

            let details = {};

            try {
                details = JSON.parse(button.dataset.details || '{}');
            } catch (error) {
                details = {};
            }

            const formattedDetails = Object.entries(details)
                .map(function ([key, value]) {
                    return key + ': ' + (
                        typeof value === 'object'
                            ? JSON.stringify(value)
                            : String(value)
                    );
                })
                .join(' | ');

            setText('activity-details', formattedDetails || 'لا توجد تفاصيل إضافية.');

            if (!dialog.open) {
                dialog.showModal();
            }
        });
    });

    dialog.querySelectorAll('[data-close]').forEach(function (button) {
        button.addEventListener('click', function () {
            dialog.close();
        });
    });

});
</script>

@endsection
