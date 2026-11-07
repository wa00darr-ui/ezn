
@php
    $toastType = null;
    $toastMessage = null;

    if (session()->has('success')) {
        $toastType = 'success';
        $toastMessage = session('success');
    } elseif (session()->has('error')) {
        $toastType = 'error';
        $toastMessage = session('error');
    } elseif (session()->has('warning')) {
        $toastType = 'warning';
        $toastMessage = session('warning');
    } elseif (session()->has('info')) {
        $toastType = 'info';
        $toastMessage = session('info');
    }
@endphp

<div
    class="toast"
    role="status"
    aria-live="polite"
    data-toast-container
>
    <span data-toast="saved" hidden>
        تم حفظ التغييرات بنجاح.
    </span>

    <span data-toast="export" hidden>
        تم تجهيز ملف التصدير بنجاح.
    </span>

    <span data-toast="approved" hidden>
        تمت الموافقة على طلب الاستئذان بنجاح.
    </span>

    <span data-toast="rejected" hidden>
        تم رفض طلب الاستئذان.
    </span>

    <span data-toast="checkout" hidden>
        تم تسليم الطالب بنجاح.
    </span>

    <span data-toast="new" hidden>
        وصل طلب استئذان جديد.
    </span>

    <span data-toast="imported" hidden>
        تم استيراد بيانات الطلاب بنجاح.
    </span>

    <span data-toast="limit" hidden>
        تم الوصول إلى الحد المسموح به.
    </span>

    @if($toastMessage)
        <span
            data-toast="server"
            data-toast-type="{{ $toastType }}"
        >
            {{ $toastMessage }}
        </span>
    @endif
</div>

@if($toastMessage)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toast = document.querySelector(
                '[data-toast-container]'
            );

            const message = toast?.querySelector(
                '[data-toast="server"]'
            );

            if (!toast || !message) {
                return;
            }

            // الحفاظ على طريقة عرض الإشعارات
            toast.classList.add('show');

            setTimeout(function () {
                toast.classList.remove('show');
                message.hidden = true;
            }, 4000);
        });
    </script>
@endif

