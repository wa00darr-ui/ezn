
@extends('layouts.dashboard', [
    'activePage' => 'settings',
    'pageTitle' => 'الإعدادات'
])

@section('title', 'الإعدادات')
@section('page', 'settings')

@section('content')

<div class="page-heading">
    <div>
        <p class="eyebrow">
            {{ $settings->name }}
        </p>
        <h1>الإعدادات</h1>
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
            <strong>تعذر حفظ البيانات:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- نموذج إعدادات المدرسة -->
    <form
        method="POST"
        action="{{ route('dashboard.settings.update') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="two-col">

            <section class="panel">
                <div class="panel-head">
                    <h2>بيانات المدرسة</h2>
                </div>

                <div class="form-grid">

                    <label class="field full">
                        <span>اسم المدرسة *</span>

                        <input
                            name="name"
                            type="text"
                            value="{{ old('name', $settings->name) }}"
                            maxlength="255"
                            required
                        >
                    </label>

                    <label class="field">
                        <span>الرقم الوزاري</span>

                        <input
                            name="ministerial_number"
                            type="text"
                            value="{{ old('ministerial_number', $settings->ministerial_number) }}"
                        >
                    </label>

                    <label class="field">
                        <span>هاتف المدرسة</span>

                        <input
                            name="phone"
                            type="tel"
                            dir="ltr"
                            value="{{ old('phone', $settings->phone) }}"
                        >
                    </label>

                    <label class="field">
                        <span>البريد الإلكتروني</span>

                        <input
                            name="email"
                            type="email"
                            dir="ltr"
                            value="{{ old('email', $settings->email) }}"
                        >
                    </label>

                    <label class="field">
                        <span>المدينة</span>

                        <input
                            name="city"
                            type="text"
                            value="{{ old('city', $settings->city) }}"
                        >
                    </label>

                    <label class="field full">
                        <span>العنوان</span>

                        <input
                            name="address"
                            type="text"
                            value="{{ old('address', $settings->address) }}"
                        >
                    </label>

                    <label class="field full">
                        <span>شعار المدرسة</span>

                        <input
                            name="logo"
                            type="file"
                            accept="image/png,image/jpeg,image/webp"
                            id="school-logo-input"
                        >

                        <small>
                            PNG أو JPG أو WebP، بحد أقصى 2 ميجابايت.
                        </small>

                        @if($settings->logo_path)
                            <img
                                id="school-logo-preview"
                                class="upload-preview"
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings->logo_path) }}"
                                alt="شعار المدرسة الحالي"
                            >
                        @else
                            <img
                                id="school-logo-preview"
                                class="upload-preview"
                                alt="معاينة شعار المدرسة"
                                hidden
                            >
                        @endif
                    </label>

                </div>
            </section>

            <section class="panel">
                <div class="panel-head">
                    <h2>إعدادات الطلبات</h2>
                </div>

                <div class="form-grid">

                    <label class="field">
                        <span>بداية استقبال الطلبات *</span>

                        <input
                            name="open_time"
                            type="time"
                            dir="ltr"
                            value="{{ old('open_time', substr($settings->open_time, 0, 5)) }}"
                            required
                        >
                    </label>

                    <label class="field">
                        <span>نهاية استقبال الطلبات *</span>

                        <input
                            name="close_time"
                            type="time"
                            dir="ltr"
                            value="{{ old('close_time', substr($settings->close_time, 0, 5)) }}"
                            required
                        >
                    </label>

                    <label class="field">
                        <span>صلاحية رمز الاستلام بالدقائق *</span>

                        <input
                            name="code_validity_minutes"
                            type="number"
                            min="5"
                            max="120"
                            dir="ltr"
                            value="{{ old('code_validity_minutes', $settings->code_validity_minutes) }}"
                            required
                        >
                    </label>

                    <label class="field">
                        <span>الحد اليومي لاستئذانات الطالب *</span>

                        <input
                            name="daily_limit"
                            type="number"
                            min="1"
                            max="20"
                            dir="ltr"
                            value="{{ old('daily_limit', $settings->daily_limit) }}"
                            required
                        >
                    </label>

                </div>

                @foreach([
                    'require_identity_image' => 'طلب صورة هوية ولي الأمر',
                    'require_signature' => 'إلزام التوقيع والإقرار',
                    'allow_requests' => 'السماح بطلبات الاستئذان',
                ] as $key => $label)

                    <label class="switch-line">
                        <span>{{ $label }}</span>

                        <input type="hidden" name="{{ $key }}" value="0">

                        <input
                            type="checkbox"
                            name="{{ $key }}"
                            value="1"
                            role="switch"
                            @checked((bool) old($key, $settings->$key))
                        >
                    </label>

                @endforeach

                <p class="caption">
                    تحفظ هذه القيم في قاعدة البيانات.
                    سيحتاج نموذج تقديم الاستئذان إلى ربطها
                    بمنطق التحقق حتى تُطبّق عليه فعليًا.
                </p>

            </section>
        </div>

        <section class="panel">
            <div class="panel-head">
                <h2>تفضيلات الإشعارات</h2>
            </div>

            @foreach([
                'notify_staff' => 'إشعار الموظفين عند وصول طلب جديد',
                'notify_decision' => 'إشعار ولي الأمر عند الموافقة أو الرفض',
                'notify_checkout' => 'إشعار ولي الأمر عند تسليم الطالب',
            ] as $key => $label)

                <label class="switch-line">
                    <span>{{ $label }}</span>

                    <input type="hidden" name="{{ $key }}" value="0">

                    <input
                        type="checkbox"
                        name="{{ $key }}"
                        value="1"
                        role="switch"
                        @checked((bool) old($key, $settings->$key))
                    >
                </label>

            @endforeach

            <p class="caption">
                يتم حفظ تفضيلات الإشعارات، لكن إرسال الرسائل
                يحتاج إلى ربط مزود إشعارات فعلي.
            </p>
        </section>

        <div class="form-actions">
            <button class="btn" type="submit">
                حفظ الإعدادات
            </button>
        </div>

    </form>

    <!-- أسباب الاستئذان -->
    <section class="panel">

        <div class="panel-head">
            <div>
                <h2>أسباب الاستئذان</h2>

                <p>
                    تحكّمي في الأسباب وترتيبها وحالة ظهورها
                    في نموذج ولي الأمر.
                </p>
            </div>

            <button
                type="button"
                class="btn"
                id="add-reason-button"
            >
                + إضافة سبب
            </button>
        </div>

        <ol class="reason-list">
            @forelse($reasons as $reason)

                <li>
                    <span class="drag-handle">⠿</span>

                    <b>{{ $reason->name }}</b>

                    <x-dashboard.status-badge
                        :status="$reason->is_active ? 'active' : 'inactive'"
                    >
                        {{ $reason->is_active ? 'مفعّل' : 'معطّل' }}
                    </x-dashboard.status-badge>

                    <div class="reason-actions">

                        <form
                            method="POST"
                            action="{{ route('dashboard.settings.reasons.move', $reason) }}"
                        >
                            @csrf
                            <input
                                type="hidden"
                                name="direction"
                                value="up"
                            >

                            <button
                                type="submit"
                                class="icon-btn"
                                aria-label="تحريك السبب لأعلى"
                                @disabled($loop->first)
                            >
                                ↑
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('dashboard.settings.reasons.move', $reason) }}"
                        >
                            @csrf
                            <input
                                type="hidden"
                                name="direction"
                                value="down"
                            >

                            <button
                                type="submit"
                                class="icon-btn"
                                aria-label="تحريك السبب لأسفل"
                                @disabled($loop->last)
                            >
                                ↓
                            </button>
                        </form>

                        <button
                            type="button"
                            class="text-link"
                            data-edit-reason
                            data-id="{{ $reason->id }}"
                            data-name="{{ $reason->name }}"
                        >
                            تعديل
                        </button>

                        <form
                            method="POST"
                            action="{{ route('dashboard.settings.reasons.toggle', $reason) }}"
                        >
                            @csrf

                            <button type="submit" class="text-link">
                                {{ $reason->is_active ? 'تعطيل' : 'تفعيل' }}
                            </button>
                        </form>

                    </div>
                </li>

            @empty

                <li>
                    <p>
                        لا توجد أسباب استئذان مسجلة حتى الآن.
                        اضغطي «إضافة سبب» للبدء.
                    </p>
                </li>

            @endforelse
        </ol>

    </section>

</div>

@endsection

@section('dialogs')

<x-dashboard.modal id="reason-dialog" title="بيانات سبب الاستئذان">

    <form
        method="POST"
        action="{{ route('dashboard.settings.reasons.store') }}"
        id="reason-form"
    >
        @csrf

        <input
            type="hidden"
            name="_method"
            id="reason-method"
            value="PATCH"
            disabled
        >

        <div class="form-grid">
            <label class="field full">
                <span>اسم السبب *</span>

                <input
                    name="name"
                    type="text"
                    id="reason-name"
                    maxlength="150"
                    required
                >
            </label>
        </div>

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

    const dialog = document.getElementById('reason-dialog');
    const form = document.getElementById('reason-form');
    const method = document.getElementById('reason-method');
    const nameInput = document.getElementById('reason-name');

    const storeUrl = @json(route('dashboard.settings.reasons.store'));

    const updateTemplate = @json(
        route('dashboard.settings.reasons.update', [
            'reason' => '__ID__'
        ])
    );

    if (dialog && form && method && nameInput) {

        document.getElementById('add-reason-button')
            ?.addEventListener('click', function () {

                form.reset();
                form.action = storeUrl;
                method.disabled = true;
                nameInput.value = '';

                dialog.showModal();
            });

        document.querySelectorAll('[data-edit-reason]')
            .forEach(function (button) {

                button.addEventListener('click', function () {
                    form.action = updateTemplate.replace(
                        '__ID__',
                        button.dataset.id
                    );

                    method.disabled = false;
                    method.value = 'PATCH';
                    nameInput.value = button.dataset.name || '';

                    dialog.showModal();
                });
            });

        dialog.querySelectorAll('[data-close]')
            .forEach(function (button) {
                button.addEventListener('click', function () {
                    dialog.close();
                });
            });
    }

    const logoInput = document.getElementById('school-logo-input');
    const logoPreview = document.getElementById('school-logo-preview');

    logoInput?.addEventListener('change', function () {
        const file = logoInput.files?.[0];

        if (!file || !logoPreview) return;

        const objectUrl = URL.createObjectURL(file);

        logoPreview.onload = function () {
            URL.revokeObjectURL(objectUrl);
        };

        logoPreview.src = objectUrl;
        logoPreview.hidden = false;
    });

});
</script>

@endsection

