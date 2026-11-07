
@extends('layouts.dashboard', [
    'activePage' => 'employees',
    'pageTitle' => 'الموظفون والصلاحيات'
])

@section('title', 'الموظفون والصلاحيات')
@section('page', 'employees')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ config('school.name', 'إدارة المدرسة') }}
        </p>
        <h1>الموظفون والصلاحيات</h1>
    </div>

    <div class="page-actions">
        <button type="button" class="btn" id="add-employee">
            + إضافة موظف
        </button>
    </div>
</div>

<div data-page-content>

    @if(session('success'))
        <div class="success-box" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-box" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- التبويبات -->
    <div
        class="tabs"
        role="tablist"
        aria-label="الموظفون والصلاحيات"
    >
        <button
            type="button"
            role="tab"
            id="employees-tab-button"
            aria-controls="employees-tab"
            aria-selected="true"
            data-emp-tab="employees"
        >
            الموظفون
        </button>

        <button
            type="button"
            role="tab"
            id="permissions-tab-button"
            aria-controls="permissions-tab"
            aria-selected="false"
            data-emp-tab="permissions"
        >
            إدارة الصلاحيات
        </button>
    </div>

    <!-- قائمة الموظفين -->
    <section
        id="employees-tab"
        role="tabpanel"
        aria-labelledby="employees-tab-button"
    >
        <section class="panel">

            <div class="panel-head">
                <h2>فريق المدرسة</h2>
            </div>

            <!-- البحث والفلترة -->
            <form
                class="filters"
                method="GET"
                action="{{ route('dashboard.employees.index') }}"
            >
                <label class="field search-field">
                    <span>بحث</span>
                    <input
                        name="search"
                        type="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="ابحث بالاسم أو البريد أو الجوال"
                    >
                </label>

                <label class="field">
                    <span>الدور</span>
                    <select name="role">
                        <option value="">كل الأدوار</option>

                        @foreach(config('employee_permissions.roles') as $role => $title)
                            <option
                                value="{{ $role }}"
                                @selected(($filters['role'] ?? '') === $role)
                            >
                                {{ $title }}
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

                <button class="btn" type="submit">
                    بحث
                </button>

                <a
                    class="btn secondary"
                    href="{{ route('dashboard.employees.index') }}"
                >
                    مسح الفلاتر
                </a>
            </form>

            <!-- جدول الموظفين -->
            <div
                class="table-wrap"
                tabindex="0"
                role="region"
                aria-label="جدول الموظفين"
            >
                <table>
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>البريد / الجوال</th>
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>آخر تسجيل دخول</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <b>{{ $employee->name }}</b>
                                </td>

                                <td>
                                    <span dir="ltr">
                                        {{ $employee->email }}
                                    </span>

                                    <small dir="ltr">
                                        {{ $employee->phone ?? '—' }}
                                    </small>
                                </td>

                                <td>
                                    {{ config(
                                        'employee_permissions.roles.' . $employee->role,
                                        $employee->role
                                    ) }}
                                </td>

                                <td>
                                    <x-dashboard.status-badge
                                        :status="$employee->is_active ? 'active' : 'inactive'"
                                    >
                                        {{ $employee->is_active ? 'نشط' : 'غير نشط' }}
                                    </x-dashboard.status-badge>
                                </td>

                                <td>
                                    {{ $employee->last_login_at?->format('Y-m-d H:i') ?? 'لم يسجل الدخول' }}
                                </td>

                                <td>
                                    <button
                                        type="button"
                                        class="text-link"
                                        data-edit-employee
                                        data-id="{{ $employee->id }}"
                                        data-name="{{ $employee->name }}"
                                        data-email="{{ $employee->email }}"
                                        data-phone="{{ $employee->phone }}"
                                        data-role="{{ $employee->role }}"
                                        data-active="{{ $employee->is_active ? 1 : 0 }}"
                                    >
                                        تعديل
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <h3>لا توجد نتائج مطابقة</h3>
                                        <p>جرّبي تغيير البحث أو الفلاتر.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-summary">
                عرض {{ $employees->firstItem() ?? 0 }}
                –
                {{ $employees->lastItem() ?? 0 }}
                من {{ $employees->total() }} سجلات
            </div>

            {{ $employees->links() }}

        </section>
    </section>

    <!-- إدارة الصلاحيات -->
    <section
        id="permissions-tab"
        role="tabpanel"
        aria-labelledby="permissions-tab-button"
        hidden
    >
        <section class="panel">

            <div class="panel-head">
                <div>
                    <h2>صلاحيات الأدوار</h2>
                    <p>
                        حدد ما يستطيع كل دور الوصول إليه داخل المدرسة.
                    </p>
                </div>
            </div>

            <form
                action="{{ route('dashboard.employees.permissions') }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div
                    class="table-wrap"
                    tabindex="0"
                    role="region"
                    aria-label="جدول الصلاحيات"
                >
                    <table class="permissions-table">

                        <thead>
                            <tr>
                                <th>الصلاحية</th>
                                <th>مدير النظام</th>
                                <th>مسؤولة الموافقات</th>
                                <th>موظف البوابة</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach(config('employee_permissions.permissions') as $key => $name)
                                <tr>
                                    <th scope="row">
                                        {{ $name }}
                                    </th>

                                    <td>
                                        <input
                                            type="checkbox"
                                            checked
                                            disabled
                                            aria-label="{{ $name }} — مدير النظام"
                                        >
                                    </td>

                                    @foreach(['approver', 'gate'] as $role)
                                        <td>
                                            <input
                                                type="checkbox"
                                                name="permissions[{{ $role }}][]"
                                                value="{{ $key }}"
                                                @checked(in_array(
                                                    $key,
                                                    $enabled[$role] ?? [],
                                                    true
                                                ))
                                                aria-label="{{ $name }} — {{ config('employee_permissions.roles.' . $role) }}"
                                            >
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="caption">
                    صلاحيات مدير النظام كاملة وثابتة.
                    ستُحفظ تغييرات الأدوار في قاعدة البيانات.
                </p>

                <button class="btn" type="submit">
                    حفظ الصلاحيات
                </button>

            </form>
        </section>
    </section>

</div>

@endsection

@section('dialogs')

<x-dashboard.modal
    id="employee-dialog"
    title="بيانات الموظف"
    class="wide-dialog"
>
    <form
        method="POST"
        action="{{ route('dashboard.employees.store') }}"
        id="employee-form"
    >
        @csrf

        <input
            type="hidden"
            name="_method"
            id="employee-method"
            value="PUT"
            disabled
        >

        <div class="form-grid">

            <label class="field">
                <span>اسم الموظف *</span>
                <input
                    name="name"
                    id="emp-name"
                    required
                    maxlength="255"
                >
            </label>

            <label class="field">
                <span>البريد الإلكتروني *</span>
                <input
                    name="email"
                    id="emp-email"
                    type="email"
                    required
                    dir="ltr"
                >
            </label>

            <label class="field">
                <span>رقم الجوال *</span>
                <input
                    name="phone"
                    id="emp-phone"
                    type="tel"
                    required
                    dir="ltr"
                >
            </label>

            <label class="field">
                <span>الدور</span>
                <select name="role" id="emp-role">
                    <option value="approver">
                        وكيلة / مسؤولة الموافقات
                    </option>
                    <option value="gate">
                        موظف البوابة
                    </option>
                </select>
            </label>

            <label class="field">
                <span>الحالة</span>
                <select name="is_active" id="emp-active">
                    <option value="1">نشط</option>
                    <option value="0">غير نشط</option>
                </select>
            </label>

            <label class="field">
                <span>
                    كلمة المرور
                    <span id="password-note">
                        * (8 أحرف على الأقل مع حروف وأرقام)
                    </span>
                </span>
                <input
                    type="password"
                    name="password"
                    id="emp-password"
                    autocomplete="new-password"
                    minlength="8"
                    required
                >
            </label>

            <label class="field">
                <span>تأكيد كلمة المرور</span>
                <input
                    type="password"
                    name="password_confirmation"
                    id="emp-password-confirmation"
                    autocomplete="new-password"
                    minlength="8"
                    required
                >
            </label>

        </div>

        <p class="notice">
            عند تعديل موظف، اتركي كلمة المرور فارغة للاحتفاظ بها.
        </p>

        <div class="dialog-actions">
            <button
                type="button"
                class="btn secondary"
                data-close
            >
                إلغاء
            </button>

            <button class="btn" type="submit">
                حفظ البيانات
            </button>
        </div>

    </form>
</x-dashboard.modal>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const dialog = document.getElementById('employee-dialog');
    const form = document.getElementById('employee-form');
    const method = document.getElementById('employee-method');

    if (!dialog || !form || !method) {
        return;
    }

    const storeUrl = @json(route('dashboard.employees.store'));
    const updateTemplate = @json(
        route('dashboard.employees.update', ['employee' => '__ID__'])
    );

    const fields = {
        name: document.getElementById('emp-name'),
        email: document.getElementById('emp-email'),
        phone: document.getElementById('emp-phone'),
        role: document.getElementById('emp-role'),
        active: document.getElementById('emp-active')
    };

    const password = document.getElementById('emp-password');
    const confirmation = document.getElementById('emp-password-confirmation');
    const passwordNote = document.getElementById('password-note');

    function openEmployee(editButton = null) {

        form.reset();
        password.value = '';
        confirmation.value = '';

        fields.role.querySelector('option[value="admin"]')?.remove();

        if (editButton) {

            form.action = updateTemplate.replace(
                '__ID__',
                editButton.dataset.id
            );

            method.disabled = false;
            method.value = 'PUT';

            password.required = false;
            confirmation.required = false;

            fields.name.value = editButton.dataset.name;
            fields.email.value = editButton.dataset.email;
            fields.phone.value = editButton.dataset.phone || '';

            const employeeRole = editButton.dataset.role;

            if (employeeRole === 'admin') {
                fields.role.add(
                    new Option('مدير النظام', 'admin')
                );
            }

            fields.role.value = employeeRole;
            fields.active.value = editButton.dataset.active;

            passwordNote.textContent = '(اختياري عند التعديل)';

        } else {

            form.action = storeUrl;
            method.disabled = true;

            password.required = true;
            confirmation.required = true;

            fields.role.value = 'approver';
            fields.active.value = '1';

            passwordNote.textContent =
                '* (8 أحرف على الأقل مع حروف وأرقام)';
        }

        if (!dialog.open) {
            dialog.showModal();
        }
    }

    document.getElementById('add-employee')
        ?.addEventListener('click', function () {
            openEmployee();
        });

    document.querySelectorAll('[data-edit-employee]')
        .forEach(function (button) {
            button.addEventListener('click', function () {
                openEmployee(button);
            });
        });

    dialog.querySelectorAll('[data-close]')
        .forEach(function (button) {
            button.addEventListener('click', function () {
                dialog.close();
            });
        });

    // تبويبات الموظفين والصلاحيات
    const tabs = document.querySelectorAll('[data-emp-tab]');

    function showTab(selected) {

        tabs.forEach(function (button) {
            const active = button.dataset.empTab === selected;

            button.setAttribute('aria-selected', String(active));

            document.getElementById(
                button.dataset.empTab + '-tab'
            ).hidden = !active;
        });
    }

    tabs.forEach(function (button) {
        button.addEventListener('click', function () {
            showTab(button.dataset.empTab);
        });
    });

    showTab(
        @json(($filters['tab'] ?? 'employees') === 'permissions'
            ? 'permissions'
            : 'employees')
    );

});
</script>

@endsection
