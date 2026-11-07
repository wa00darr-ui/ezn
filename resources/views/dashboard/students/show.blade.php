
@extends('layouts.dashboard', [
    'activePage' => 'students',
    'pageTitle' => 'ملف الطالب'
])

@section('title', 'ملف الطالب')
@section('page', 'students')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ config('school.name', 'إدارة المدرسة') }}
        </p>

        <h1>ملف الطالب</h1>
    </div>

    <div class="page-actions">
        <a
            class="btn secondary"
            href="{{ route('dashboard.students.index') }}"
        >
            العودة إلى الطلاب
        </a>
    </div>
</div>

<div data-page-content>

    <section class="panel">
        <div class="student-hero">
            <span class="avatar large">
                {{ mb_substr($student->name, 0, 1) }}
            </span>

            <div>
                <h2>{{ $student->name }}</h2>
                <p>
                    {{ $student->grade }}
                    · الفصل {{ $student->classroom }}
                </p>

                <x-dashboard.status-badge :status="$student->status">
                    {{ $student->status === 'active'
                        ? 'نشط'
                        : 'غير نشط' }}
                </x-dashboard.status-badge>
            </div>
        </div>

        <dl class="data-grid">
            <div>
                <dt>الرقم الطلابي</dt>
                <dd>{{ $student->student_number }}</dd>
            </div>

            <div>
                <dt>الهوية / الإقامة</dt>
                <dd dir="ltr">
                    {{ str_repeat('*', max(0, mb_strlen($student->national_id) - 4)) . mb_substr($student->national_id, -4) }}
                </dd>
            </div>

            <div>
                <dt>الصف</dt>
                <dd>{{ $student->grade }}</dd>
            </div>

            <div>
                <dt>الفصل</dt>
                <dd>{{ $student->classroom }}</dd>
            </div>

            <div>
                <dt>ولي الأمر</dt>
                <dd>{{ $student->guardian_name }}</dd>
            </div>

            <div>
                <dt>جوال ولي الأمر</dt>
                <dd dir="ltr">{{ $student->guardian_phone }}</dd>
            </div>
        </dl>
    </section>

    <section class="panel">
        <div class="panel-head">
            <h2>سجل طلبات الاستئذان للطالب</h2>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>تاريخ الطلب</th>
                        <th>السبب</th>
                        <th>الحالة</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($requests as $item)
                        <tr>
                            <td>#{{ $item->id }}</td>
                            <td>
                                {{ $item->created_at?->format('Y-m-d H:i') }}
                            </td>
                            <td>{{ $item->reason ?? '—' }}</td>
                            <td>
                                <x-dashboard.status-badge
                                    :status="$item->status"
                                >
                                    {{ match ($item->status) {
                                        'pending' => 'بانتظار المراجعة',
                                        'approved' => 'موافق عليه',
                                        'rejected' => 'مرفوض',
                                        'completed' => 'مكتمل',
                                        'cancelled' => 'ملغى',
                                        default => $item->status,
                                    } }}
                                </x-dashboard.status-badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                لا توجد طلبات استئذان مرتبطة بهذا الطالب.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            {{ $requests->links() }}
        @endif
    </section>

</div>

@endsection
