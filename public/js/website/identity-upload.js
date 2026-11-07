
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('identity');
    const preview = document.getElementById('identity-preview');
    const image = document.getElementById('identity-image');
    const prompt = document.getElementById('upload-prompt');
    const loading = document.getElementById('upload-loading');
    const remove = document.getElementById('remove-identity');

    if (!input || !preview || !image) return;

    const allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    const maxSize = 5 * 1024 * 1024;

    let imageUrl = null;

    function showError(kind, visible) {
        document.querySelectorAll(
            '[data-error-for="identity"]'
        ).forEach(element => {
            if (element.dataset.errorKind === kind) {
                element.hidden = !visible;
            }
        });
    }

    function clearErrors() {
        document.querySelectorAll(
            '[data-error-for="identity"]'
        ).forEach(element => {
            element.hidden = true;
        });
    }

    function resetImage() {
        if (imageUrl) {
            URL.revokeObjectURL(imageUrl);
            imageUrl = null;
        }

        input.value = '';
        image.removeAttribute('src');
        image.hidden = true;
        preview.hidden = true;

        if (prompt) prompt.hidden = false;
        if (loading) loading.hidden = true;

        clearErrors();
    }

    input.addEventListener('change', () => {
        clearErrors();

        const file = input.files?.[0];

        if (!file) {
            resetImage();
            return;
        }

        if (
            !allowedTypes.includes(file.type) ||
            file.size > maxSize ||
            file.size === 0
        ) {
            resetImage();
            showError('file', true);
            return;
        }

        if (imageUrl) {
            URL.revokeObjectURL(imageUrl);
        }

        imageUrl = URL.createObjectURL(file);

        if (loading) loading.hidden = false;

        image.onload = () => {
            image.hidden = false;
            preview.hidden = false;

            if (prompt) prompt.hidden = true;
            if (loading) loading.hidden = true;
        };

        image.onerror = () => {
            resetImage();
            showError('read', true);
        };

        image.src = imageUrl;
    });

    remove?.addEventListener('click', resetImage);
});
