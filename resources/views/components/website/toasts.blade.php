
@php
    $toastType = null;
    $toastMessage = null;

    // قراءة رسائل Laravel
    foreach (['success', 'error', 'warning', 'info'] as $type) {
        if (session()->has($type)) {
            $toastType = $type;
            $toastMessage = session($type);
            break;
        }
    }

    // عرض أول خطأ من أخطاء التحقق
    if (!$toastMessage && $errors->any()) {
        $toastType = 'error';
        $toastMessage = $errors->first();
    }
@endphp

<div
    id="toast"
    role="status"
    aria-live="polite"
    aria-atomic="true"
    data-server-type="{{ $toastType ?? '' }}"
>
    <!-- تعبئة البيانات -->
    <span data-toast="filled" hidden>
        تمت تعبئة البيانات.
    </span>

    <!-- التوقيع -->
    <span data-toast="signature" hidden>
        تمت إضافة التوقيع.
    </span>

    <!-- إرسال الطلب -->
    <span data-toast="submitted" hidden>
        تم إرسال طلب الاستئذان بنجاح.
    </span>

    <!-- تحديث الحالة -->
    <span data-toast="status" hidden>
        تم تحديث حالة الطلب.
    </span>

    <!-- إعادة المحاولة -->
    <span data-toast="retry" hidden>
        تمت إعادة المحاولة.
    </span>

    <!-- تحديث الطلب -->
    <span data-toast="refresh" hidden>
        تم تحديث معلومات الطلب.
    </span>

    <!-- التواصل -->
    <span data-toast="contact" hidden>
        تم إرسال الرسالة بنجاح.
    </span>

    <!-- خطأ التخزين -->
    <span data-toast="storage" hidden>
        تعذر حفظ البيانات. يرجى المحاولة مرة أخرى.
    </span>

    <!-- رسالة قادمة من Laravel -->
    @if($toastMessage)
        <span data-toast="server">
            {{ $toastMessage }}
        </span>
    @endif
</div>

@if($toastMessage)
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const toast = document.getElementById('toast');

        if (!toast) {
            return;
        }

        const message = toast.querySelector(
            '[data-toast="server"]'
        );

        if (!message) {
            return;
        }

        // إخفاء رسائل JavaScript السابقة
        toast.querySelectorAll('[data-toast]').forEach(function (item) {
            item.hidden = item !== message;
        });

        // إظهار رسالة Laravel
        message.hidden = false;
        toast.classList.add('show');

        // إخفاء الرسالة بعد 4 ثوانٍ
        setTimeout(function () {
            toast.classList.remove('show');
            message.hidden = true;
        }, 4000);

    });
</script>
@endif
