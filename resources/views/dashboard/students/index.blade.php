
@extends('layouts.dashboard', [
    'activePage' => 'students',
    'pageTitle' => 'الطلاب'
])

@section('title', 'الطلاب')
@section('page', 'students')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ config('school.name', 'إدارة المدرسة') }}
        </p>
        <h1>الطلاب</h1>
    </div>

    <div class="page-actions">
        <button
            type="button"
            class="btn secondary"
            id="open-student-import"
        >
            استيراد Excel
        </button>

        <button
            type="button"
            class="btn"
            id="add-student"
        >
            + إضافة طالب
        </button>
    </div>
</div>

<div data-page-content>

    @if(session('success'))
        <div class="notice" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="notice" role="alert">
            <strong>يرجى مراجعة البيانات:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="panel">
        <div class="panel-head">
            <h2>
                دليل الطلاب
                <span class="count">{{ $students->total() }}</span>
            </h2>
        </div>

        <form
            class="filters"
            method="GET"
            action="{{ route('dashboard.students.index') }}"
        >
            <label class="field search-field">
                <span>بحث</span>
                <input
                    type="search"
                    name="search"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="اسم الطالب أو رقمه الطلابي أو ولي الأمر"
                >
            </label>

            <label class="field">
                <span>الصف</span>
                <select name="grade">
                    <option value="">كل الصفوف</option>

                    @foreach($grades as $grade)
                        <option
                            value="{{ $grade }}"
                            @selected(($filters['grade'] ?? '') === $grade)
                        >
                            {{ $grade }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="field">
                <span>الفصل</span>
                <select name="classroom">
                    <option value="">كل الفصول</option>

                    @foreach($classrooms as $classroom)
                        <option
                            value="{{ $classroom }}"
                            @selected(($filters['classroom'] ?? '') === $classroom)
                        >
                            {{ $classroom }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="field">
                <span>الحالة</span>
                <select name="status">
                    <option value="">كل الحالات</option>
                    <option
                        value="active"
                        @selected(($filters['status'] ?? '') === 'active')
                    >
                        نشط
                    </option>
                    <option
                        value="inactive"
                        @selected(($filters['status'] ?? '') === 'inactive')
                    >
                        غير نشط
                    </option>
                </select>
            </label>

            <button type="submit" class="btn">
                بحث
            </button>

            <a
                class="btn secondary"
                href="{{ route('dashboard.students.index') }}"
            >
                مسح الفلاتر
            </a>
        </form>

        <div
            class="table-wrap"
            tabindex="0"
            role="region"
            aria-label="دليل الطلاب"
        >
            <table>
                <thead>
                    <tr>
                        <th>الطالب</th>
                        <th>الرقم الطلابي</th>
                        <th>الهوية / الإقامة</th>
                        <th>الصف</th>
                        <th>الفصل</th>
                        <th>ولي الأمر</th>
                        <th>الجوال</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>
                                <a
                                    class="person-link"
                                    href="{{ route('dashboard.students.show', $student) }}"
                                >
                                    <span class="avatar">
                                        {{ mb_substr($student->name, 0, 1) }}
                                    </span>
                                    <b>{{ $student->name }}</b>
                                </a>
                            </td>

                            <td dir="ltr">
                                {{ $student->student_number }}
                            </td>

                            <td dir="ltr">
                                {{ str_repeat('*', max(0, mb_strlen($student->national_id) - 4)) . mb_substr($student->national_id, -4) }}
                            </td>

                            <td>{{ $student->grade }}</td>
                            <td>{{ $student->classroom }}</td>
                            <td>{{ $student->guardian_name }}</td>
                            <td dir="ltr">{{ $student->guardian_phone }}</td>

                            <td>
                                <x-dashboard.status-badge
                                    :status="$student->status"
                                >
                                    {{ $student->status === 'active'
                                        ? 'نشط'
                                        : 'غير نشط' }}
                                </x-dashboard.status-badge>
                            </td>

                            <td>
                                <button
                                    type="button"
                                    class="text-link"
                                    data-edit-student
                                    data-id="{{ $student->id }}"
                                    data-name="{{ $student->name }}"
                                    data-number="{{ $student->student_number }}"
                                    data-national="{{ $student->national_id }}"
                                    data-grade="{{ $student->grade }}"
                                    data-classroom="{{ $student->classroom }}"
                                    data-guardian="{{ $student->guardian_name }}"
                                    data-phone="{{ $student->guardian_phone }}"
                                    data-status="{{ $student->status }}"
                                >
                                    تعديل
                                </button>

                                <a
                                    class="text-link"
                                    href="{{ route('dashboard.students.show', $student) }}"
                                >
                                    الملف
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <h3>لا توجد نتائج</h3>
                                    <p>
                                        لم يتم العثور على طلاب مطابقين
                                        لمعايير البحث.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-summary">
            عرض {{ $students->firstItem() ?? 0 }}
            –
            {{ $students->lastItem() ?? 0 }}
            من {{ $students->total() }} طالب
        </div>

        @if($students->hasPages())
            {{ $students->links() }}
        @endif

    </section>
</div>

@endsection

@section('dialogs')

<x-dashboard.modal id="student-dialog" title="بيانات الطالب">

    <form
        id="student-form"
        method="POST"
        action="{{ route('dashboard.students.store') }}"
    >
        @csrf

        <input
            id="student-form-method"
            type="hidden"
            name="_method"
            value="PUT"
            disabled
        >

        <div class="form-grid">

            <label class="field">
                <span>اسم الطالب *</span>
                <input
                    id="student-name"
                    name="name"
                    type="text"
                    maxlength="255"
                    required
                >
            </label>

            <label class="field">
                <span>الرقم الطلابي *</span>
                <input
                    id="student-number"
                    name="student_number"
                    type="text"
                    maxlength="50"
                    dir="ltr"
                    required
                >
            </label>

            <label class="field">
                <span>رقم الهوية / الإقامة *</span>
                <input
                    id="student-national"
                    name="national_id"
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]{10}"
                    maxlength="10"
                    dir="ltr"
                    required
                >
            </label>

            <label class="field">
                <span>الصف *</span>
                <input
                    id="student-grade"
                    name="grade"
                    list="student-grades"
                    maxlength="100"
                    required
                >
                <datalist id="student-grades">
                    @foreach($grades as $grade)
                        <option value="{{ $grade }}"></option>
                    @endforeach
                </datalist>
            </label>

            <label class="field">
                <span>الفصل *</span>
                <input
                    id="student-classroom"
                    name="classroom"
                    maxlength="30"
                    placeholder="أ"
                    required
                >
            </label>

            <label class="field">
                <span>الحالة</span>
                <select id="student-status" name="status">
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </label>

            <label class="field">
                <span>اسم ولي الأمر *</span>
                <input
                    id="student-guardian"
                    name="guardian_name"
                    maxlength="255"
                    required
                >
            </label>

            <label class="field">
                <span>جوال ولي الأمر *</span>
                <input
                    id="student-phone"
                    name="guardian_phone"
                    type="tel"
                    maxlength="25"
                    dir="ltr"
                    required
                >
            </label>

        </div>

        <div class="dialog-actions">
            <button type="button" class="btn secondary" data-close>
                إلغاء
            </button>
            <button type="submit" class="btn">
                حفظ البيانات
            </button>
        </div>
    </form>
</x-dashboard.modal>

<x-dashboard.modal id="import-dialog" title="استيراد الطلاب من Excel">

    <p>
        ارفعي ملف Excel يحتوي على أعمدة الطلاب بالترتيب المحدد.
        سيتم التحقق من البيانات قبل إدخالها في النظام.
    </p>

    <form
        method="POST"
        action="{{ route('dashboard.students.import') }}"
        enctype="multipart/form-data"
    >
        @csrf

        <label class="field">
            <span>ملف Excel</span>
            <input
                type="file"
                name="file"
                accept=".xlsx,.xls"
                required
            >
        </label>

        <p class="caption">
            الأعمدة المطلوبة بالترتيب:
            name, student_number, national_id, grade,
            classroom, guardian_name, guardian_phone, status
        </p>

        <p class="caption">
            يجب كتابة أرقام الهوية والجوال كقيم نصية
            في Excel للحفاظ على الأصفار في بدايتها.
        </p>

        <div class="dialog-actions">
            <button type="button" class="btn secondary" data-close>
                إلغاء
            </button>

            <button type="submit" class="btn">
                استيراد الطلاب
            </button>
        </div>
    </form>

</x-dashboard.modal>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const studentDialog = document.getElementById('student-dialog');
    const importDialog = document.getElementById('import-dialog');

    const form = document.getElementById('student-form');
    const method = document.getElementById('student-form-method');

    if (!studentDialog || !form || !method) {
        return;
    }

    const fields = {
        name: document.getElementById('student-name'),
        number: document.getElementById('student-number'),
        national: document.getElementById('student-national'),
        grade: document.getElementById('student-grade'),
        classroom: document.getElementById('student-classroom'),
        guardian: document.getElementById('student-guardian'),
        phone: document.getElementById('student-phone'),
        status: document.getElementById('student-status')
    };

    const storeUrl = @json(route('dashboard.students.store'));

    const updateUrl = @json(
        route('dashboard.students.update', ['student' => '__ID__'])
    );

    function openStudent(button = null) {
        form.reset();

        if (button) {
            form.action = updateUrl.replace(
                '__ID__',
                button.dataset.id
            );

            method.disabled = false;

            fields.name.value = button.dataset.name || '';
            fields.number.value = button.dataset.number || '';
            fields.national.value = button.dataset.national || '';
            fields.grade.value = button.dataset.grade || '';
            fields.classroom.value = button.dataset.classroom || '';
            fields.guardian.value = button.dataset.guardian || '';
            fields.phone.value = button.dataset.phone || '';
            fields.status.value = button.dataset.status || 'active';
        } else {
            form.action = storeUrl;
            method.disabled = true;
            fields.status.value = 'active';
        }

        studentDialog.showModal();
    }

    document.getElementById('add-student')
        ?.addEventListener('click', function () {
            openStudent();
        });

    document.querySelectorAll('[data-edit-student]')
        .forEach(function (button) {
            button.addEventListener('click', function () {
                openStudent(button);
            });
        });

    document.getElementById('open-student-import')
        ?.addEventListener('click', function () {
            importDialog?.showModal();
        });

    document.querySelectorAll('[data-close]')
        .forEach(function (button) {
            button.addEventListener('click', function () {
                button.closest('dialog')?.close();
            });
        });
});
</script>

@endsection
