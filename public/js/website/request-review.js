
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('request-form');

    if (!form) return;

    const reviewStep = form.querySelector(
        '.request-step[data-step="5"]'
    );

    if (!reviewStep) return;

    function fieldValue(name) {
        const field = form.elements.namedItem(name);

        if (!field) return '';

        return String(field.value || '').trim();
    }

    function setText(binding, value) {
        document.querySelectorAll(
            `[data-bind="${binding}"]`
        ).forEach(function (element) {
            element.textContent = value || '—';
        });
    }

    function maskId(value) {
        if (!value) return '—';

        return '*'.repeat(Math.max(0, value.length - 4))
            + value.slice(-4);
    }

    function updateReview() {

        setText('studentName', fieldValue('studentName'));
        setText('studentId', maskId(fieldValue('studentId')));

        const grade = fieldValue('grade');
        const classroom = fieldValue('classroom');

        setText(
            'studentClass',
            [grade, classroom].filter(Boolean).join(' · ')
        );

        setText('studentNo', fieldValue('studentNo'));

        setText('guardianName', fieldValue('guardianName'));
        setText('guardianId', maskId(fieldValue('guardianId')));
        setText('phone', fieldValue('phone'));
        setText('relation', fieldValue('relation'));

        const reason = fieldValue('reason');

        const finalReason = reason === 'أخرى'
            ? fieldValue('otherReason')
            : reason;

        setText('permissionReason', finalReason);

        setText(
            'notes',
            fieldValue('notes') || 'لا توجد ملاحظات'
        );
    }

    // معاينة صورة الهوية
    const identityInput = document.getElementById('identity');
    const identityImage = document.getElementById('review-identity');
    const identityEmpty = document.getElementById('review-identity-empty');

    let identityUrl = null;

    function updateIdentityPreview() {

        const file = identityInput?.files?.[0];

        if (identityUrl) {
            URL.revokeObjectURL(identityUrl);
            identityUrl = null;
        }

        if (!file || !identityImage) {
            if (identityImage) {
                identityImage.hidden = true;
                identityImage.removeAttribute('src');
            }

            if (identityEmpty) identityEmpty.hidden = false;

            return;
        }

        if (!file.type.startsWith('image/')) return;

        identityUrl = URL.createObjectURL(file);

        identityImage.src = identityUrl;
        identityImage.hidden = false;

        if (identityEmpty) identityEmpty.hidden = true;
    }

    // معاينة التوقيع
    const signatureImage = document.getElementById('review-signature');
    const signatureEmpty = document.getElementById('review-signature-empty');

    function updateSignaturePreview() {

        const signatureCanvas = document.querySelector(
            'canvas[data-signature]'
        );

        if (!signatureCanvas || !signatureImage) {
            return;
        }

        try {
            const context = signatureCanvas.getContext('2d');

            if (!context) return;

            signatureImage.src = signatureCanvas.toDataURL('image/png');

            signatureImage.hidden = false;

            if (signatureEmpty) signatureEmpty.hidden = true;

        } catch (error) {
            signatureImage.hidden = true;

            if (signatureEmpty) signatureEmpty.hidden = false;
        }
    }

    form.addEventListener('input', updateReview);
    form.addEventListener('change', function () {
        updateReview();
        updateIdentityPreview();
    });

    const observer = new MutationObserver(function () {
        if (!reviewStep.hidden) {
            updateReview();
            updateIdentityPreview();
            updateSignaturePreview();
        }
    });

    observer.observe(reviewStep, {
        attributes: true,
        attributeFilter: ['hidden', 'class', 'style']
    });

    document.querySelectorAll('[data-edit]').forEach(function (button) {

        button.addEventListener('click', function () {

            const step = Number(button.dataset.edit);

            if (
                typeof window.requestFormGoToStep === 'function'
            ) {
                window.requestFormGoToStep(step);
            }
        });
    });

    updateReview();
    updateIdentityPreview();

});
