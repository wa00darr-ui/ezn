import { normalizeDigits } from "./validation.js";

export function initializeContact() {
    const form = document.getElementById("contact-form");
    if (!form) return;

    const button = form.querySelector('button[type="submit"]');
    const phone = form.querySelector('[name="phone"]');
    const label = button?.textContent;
    let submitting = false;

    phone?.addEventListener("input", () => {
        phone.value = normalizeDigits(phone.value);
    });

    form.addEventListener("submit", (event) => {
        if (submitting) {
            event.preventDefault();
            return;
        }

        // إرسال النموذج إلى Laravel؛ تظهر رسالة النجاح بعد الحفظ الفعلي.
        submitting = true;
        form.setAttribute("aria-busy", "true");
        if (button) {
            button.disabled = true;
            button.textContent = "جارٍ إرسال الرسالة…";
        }
    });

    // إعادة تفعيل النموذج عند الرجوع إليه من سجل المتصفح.
    window.addEventListener("pageshow", () => {
        submitting = false;
        form.removeAttribute("aria-busy");
        if (button) {
            button.disabled = false;
            button.textContent = label;
        }
    });
}
