export function initializeTracking() {
    const form = document.getElementById("lookup-form");
    if (!form) return;

    const button = form.querySelector('button[type="submit"]');
    const idle = button.querySelector("[data-lookup-idle]");
    const busy = button.querySelector("[data-lookup-busy]");
    let submitting = false;

    form.addEventListener("submit", (event) => {
        if (submitting) {
            event.preventDefault();
            return;
        }

        submitting = true;
        button.disabled = true;
        idle.hidden = true;
        busy.hidden = false;
    });

    // إعادة تفعيل البحث عند الرجوع إلى الصفحة من سجل المتصفح.
    window.addEventListener("pageshow", () => {
        submitting = false;
        button.disabled = false;
        idle.hidden = false;
        busy.hidden = true;
    });
}
