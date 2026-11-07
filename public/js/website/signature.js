document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signature');
    const hiddenInput = document.getElementById('signature-data');
    const clearButton = document.getElementById('clear-signature');
    const typedButton = document.getElementById('typed-signature');
    const error = document.getElementById('signature-error');

    if (!canvas || !hiddenInput) return;

    const ctx = canvas.getContext('2d');

    if (!ctx) return;

    let drawing = false;
    let signed = false;

    function configureContext() {
        ctx.strokeStyle = '#173d38';
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
    }

    function resizeCanvas() {
        const previous = signed
            ? canvas.toDataURL('image/png')
            : null;

        const width = Math.max(1, canvas.clientWidth || 600);
        const height = Math.max(1, canvas.clientHeight || 180);
        const ratio = window.devicePixelRatio || 1;

        canvas.width = Math.round(width * ratio);
        canvas.height = Math.round(height * ratio);

        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

        configureContext();

        if (previous) {
            const image = new Image();

            image.onload = function () {
                ctx.drawImage(image, 0, 0, width, height);
                saveSignature();
            };

            image.src = previous;
        }
    }

    function position(event) {
        const rect = canvas.getBoundingClientRect();

        return {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top
        };
    }

    function beginDrawing(event) {
        if (event.cancelable) event.preventDefault();

        drawing = true;

        if (canvas.setPointerCapture) {
            canvas.setPointerCapture(event.pointerId);
        }

        const point = position(event);

        ctx.beginPath();
        ctx.moveTo(point.x, point.y);
        ctx.lineTo(point.x, point.y);
        ctx.stroke();

        signed = true;
        saveSignature();
    }

    function draw(event) {
        if (!drawing) return;

        if (event.cancelable) event.preventDefault();

        const point = position(event);

        ctx.lineTo(point.x, point.y);
        ctx.stroke();

        signed = true;
    }

    function endDrawing() {
        if (!drawing) return;

        drawing = false;
        ctx.closePath();

        saveSignature();
    }

    function saveSignature() {
        if (!signed) {
            hiddenInput.value = '';
            return;
        }

        hiddenInput.value = canvas.toDataURL('image/png');

        if (error) error.hidden = true;

        canvas.dispatchEvent(new CustomEvent(
            'signature:updated',
            { bubbles: true }
        ));
    }

    function clearSignature() {
        ctx.clearRect(
            0,
            0,
            canvas.clientWidth,
            canvas.clientHeight
        );

        signed = false;
        hiddenInput.value = '';

        if (error) error.hidden = true;

        canvas.dispatchEvent(new CustomEvent(
            'signature:updated',
            { bubbles: true }
        ));
    }

    function typedSignature() {
        const nameInput = document.getElementById('guardianName');
        const name = nameInput?.value.trim() || '';

        if (!name) {
            alert('يرجى إدخال اسم ولي الأمر أولًا.');
            nameInput?.focus();
            return;
        }

        clearSignature();

        configureContext();

        ctx.fillStyle = '#173d38';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.font = '24px "IBM Plex Sans Arabic", sans-serif';

        ctx.fillText(
            name,
            canvas.clientWidth / 2,
            canvas.clientHeight / 2,
            Math.max(1, canvas.clientWidth - 30)
        );

        signed = true;
        saveSignature();
    }

    canvas.style.touchAction = 'none';

    canvas.addEventListener('pointerdown', beginDrawing);
    canvas.addEventListener('pointermove', draw);
    canvas.addEventListener('pointerup', endDrawing);
    canvas.addEventListener('pointercancel', endDrawing);

    clearButton?.addEventListener('click', clearSignature);
    typedButton?.addEventListener('click', typedSignature);

    window.addEventListener('resize', resizeCanvas);

    resizeCanvas();
});