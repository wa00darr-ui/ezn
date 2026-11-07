
/*
|--------------------------------------------------------------------------
| ESTAATHEN - Request Form
| نموذج طلب استئذان الطالب
|--------------------------------------------------------------------------
*/

export function initializeRequest() {
    const form = document.getElementById("request-form");

    if (!form) return;

    let currentStep = 1;
    let editing = false;
    let hasSignature = false;
    let drawing = false;
    let sending = false;

    const totalSteps = 5;

    const get = (id) => document.getElementById(id);

    const getValue = (id) => {
        return get(id)?.value?.trim() || "";
    };

    const normalizeDigits = (value) => {
        return String(value)
            .replace(/[٠-٩]/g, (digit) =>
                String(digit.charCodeAt(0) - 1632)
            )
            .replace(/[۰-۹]/g, (digit) =>
                String(digit.charCodeAt(0) - 1776)
            );
    };

    const maskIdentity = (value) => {
        if (!value) return "—";
        return "******" + value.slice(-4);
    };

    /*
    |--------------------------------------------------------------------------
    | إظهار رسائل الأخطاء
    |--------------------------------------------------------------------------
    */

    function showError(id, kind = "required") {
        const input = get(id);

        if (input) {
            input.setAttribute("aria-invalid", "true");
        }

        const messages = document.querySelectorAll(
            `[data-error-for="${id}"]`
        );

        const matching = Array.from(messages).find(
            (message) => message.dataset.errorKind === kind
        );

        const message = matching || messages[0];

        if (message) {
            message.hidden = false;
        }
    }

    function clearError(id) {
        get(id)?.removeAttribute("aria-invalid");

        document.querySelectorAll(
            `[data-error-for="${id}"]`
        ).forEach((message) => {
            message.hidden = true;
        });
    }

    function clearStepErrors(step) {
        const section = form.querySelector(
            `[data-step="${step}"]`
        );

        if (!section) return;

        section.querySelectorAll("[aria-invalid]").forEach(
            (element) => element.removeAttribute("aria-invalid")
        );

        section.querySelectorAll("[data-error-for]").forEach(
            (element) => {
                element.hidden = true;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | التنقل بين خطوات النموذج
    |--------------------------------------------------------------------------
    */

    function showStep(number, shouldFocus = true) {
        currentStep = Math.max(
            1,
            Math.min(totalSteps, number)
        );

        form.querySelectorAll(".request-step[data-step]")
            .forEach((section) => {
                const isActive =
                    Number(section.dataset.step) === currentStep;

                section.classList.toggle("active", isActive);

                // يمنع ظهور حقول الخطوات الأخرى أثناء التنقل
                section.hidden = !isActive;
            });

        document.querySelectorAll(".progress-step")
            .forEach((item, index) => {
                const number = index + 1;

                item.classList.toggle(
                    "active",
                    number === currentStep
                );

                item.classList.toggle(
                    "done",
                    number < currentStep
                );

                if (number === currentStep) {
                    item.setAttribute("aria-current", "step");
                } else {
                    item.removeAttribute("aria-current");
                }

                const stepNumber =
                    item.querySelector("[data-step-number]");

                const stepCheck =
                    item.querySelector("[data-step-check]");

                if (stepNumber) {
                    stepNumber.hidden = number < currentStep;
                }

                if (stepCheck) {
                    stepCheck.hidden = number >= currentStep;
                }
            });

        form.querySelectorAll("[data-next-label]")
            .forEach((item) => {
                item.hidden = editing;
            });

        form.querySelectorAll("[data-review-label]")
            .forEach((item) => {
                item.hidden = !editing;
            });

        const invalidStudentButton =
            form.querySelector('[data-action="invalid-student"]');

        if (invalidStudentButton) {
            invalidStudentButton.hidden = currentStep !== 1;
        }

        if (currentStep === 4) {
            resizeSignature();
        }

        if (currentStep === 5) {
            updateReview();
        }

        if (shouldFocus) {
            const title = get(`step-${currentStep}-title`);

            if (title) {
                title.setAttribute("tabindex", "-1");
                title.focus({ preventScroll: true });
            }

            form.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        }
    }

    window.requestFormGoToStep = (step) => {
        const next = Number(step);

        if (
            Number.isInteger(next) &&
            next >= 1 &&
            next <= totalSteps
        ) {
            editing = next < 5;
            showStep(next);
        }
    };

    /*
    |--------------------------------------------------------------------------
    | سبب الاستئذان
    |--------------------------------------------------------------------------
    */

    function updateReason() {
        const otherWrap = get("other-wrap");
        const otherInput = get("otherReason");

        const isOther = getValue("reason") === "أخرى";

        if (otherWrap) {
            otherWrap.hidden = !isOther;
        }

        if (otherInput) {
            otherInput.disabled = !isOther;
            otherInput.required = isOther;

            if (!isOther) {
                otherInput.value = "";
                clearError("otherReason");
            }
        }
    }

    get("reason")?.addEventListener(
        "change",
        updateReason
    );

    /*
    |--------------------------------------------------------------------------
    | رفع صورة الهوية
    |--------------------------------------------------------------------------
    */

    const identityInput = get("identity");
    const identityPreview = get("identity-preview");
    const identityImage = get("identity-image");
    const uploadPrompt = get("upload-prompt");
    const removeIdentity = get("remove-identity");
    const uploadLoading = get("upload-loading");

    let identityPreviewUrl = null;

    function releaseIdentityPreview() {
        if (identityPreviewUrl) {
            URL.revokeObjectURL(identityPreviewUrl);
            identityPreviewUrl = null;
        }
    }

    function clearIdentity() {
        releaseIdentityPreview();

        if (identityInput) {
            identityInput.value = "";
        }

        if (identityImage) {
            identityImage.removeAttribute("src");
            identityImage.hidden = true;
        }

        if (identityPreview) {
            identityPreview.hidden = true;
        }

        if (uploadPrompt) {
            uploadPrompt.hidden = false;
        }

        if (uploadLoading) {
            uploadLoading.hidden = true;
        }

        const reviewImage = get("review-identity");

        if (reviewImage) {
            reviewImage.removeAttribute("src");
            reviewImage.hidden = true;
        }
    }

    identityInput?.addEventListener("change", () => {
        clearError("identity");
        releaseIdentityPreview();

        const file = identityInput.files?.[0];

        if (!file) {
            clearIdentity();
            return;
        }

        const allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/webp",
        ];

        const maxSize = 5 * 1024 * 1024;

        if (
            !allowedTypes.includes(file.type) ||
            file.size > maxSize
        ) {
            clearIdentity();
            showError("identity", "required");

            alert(
                "يرجى اختيار صورة JPG أو PNG أو WebP بحجم لا يزيد عن 5 ميجابايت."
            );

            return;
        }

        identityPreviewUrl = URL.createObjectURL(file);

        if (identityImage) {
            identityImage.src = identityPreviewUrl;
            identityImage.hidden = false;
        }

        if (identityPreview) {
            identityPreview.hidden = false;
        }

        if (uploadPrompt) {
            uploadPrompt.hidden = true;
        }
    });

    removeIdentity?.addEventListener(
        "click",
        clearIdentity
    );

    /*
    |--------------------------------------------------------------------------
    | التوقيع الإلكتروني
    |--------------------------------------------------------------------------
    */

    const canvas = get("signature");
    const signatureInput = get("signature-data");

    const ctx = canvas?.getContext("2d");

    function setSignatureStyle() {
        if (!ctx) return;

        ctx.strokeStyle = "#173d38";
        ctx.fillStyle = "#173d38";
        ctx.lineWidth = 2.5;
        ctx.lineCap = "round";
        ctx.lineJoin = "round";
    }

    function saveSignature() {
        if (!canvas || !signatureInput) return;

        signatureInput.value = hasSignature
            ? canvas.toDataURL("image/png")
            : "";

        clearError("signature");
    }

    function resizeSignature() {
        if (!canvas || !ctx) return;

        const previous = hasSignature
            ? canvas.toDataURL("image/png")
            : null;

        const width = Math.max(
            1,
            canvas.clientWidth || 600
        );

        const height = Math.max(
            1,
            canvas.clientHeight || 180
        );

        const ratio = window.devicePixelRatio || 1;

        canvas.width = Math.round(width * ratio);
        canvas.height = Math.round(height * ratio);

        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

        setSignatureStyle();

        if (previous) {
            const image = new Image();

            image.onload = () => {
                ctx.drawImage(
                    image,
                    0,
                    0,
                    width,
                    height
                );

                saveSignature();
            };

            image.src = previous;
        }
    }

    function pointerPosition(event) {
        const bounds = canvas.getBoundingClientRect();

        return {
            x: event.clientX - bounds.left,
            y: event.clientY - bounds.top,
        };
    }

    if (canvas && ctx) {
        canvas.style.touchAction = "none";

        canvas.addEventListener(
            "pointerdown",
            (event) => {
                event.preventDefault();

                drawing = true;

                canvas.setPointerCapture?.(
                    event.pointerId
                );

                const point = pointerPosition(event);

                ctx.beginPath();
                ctx.moveTo(point.x, point.y);
                ctx.lineTo(point.x, point.y);
                ctx.stroke();

                hasSignature = true;
                saveSignature();
            }
        );

        canvas.addEventListener(
            "pointermove",
            (event) => {
                if (!drawing) return;

                event.preventDefault();

                const point = pointerPosition(event);

                ctx.lineTo(point.x, point.y);
                ctx.stroke();
            }
        );

        const finishDrawing = () => {
            if (!drawing) return;

            drawing = false;
            ctx.closePath();

            saveSignature();
        };

        canvas.addEventListener(
            "pointerup",
            finishDrawing
        );

        canvas.addEventListener(
            "pointercancel",
            finishDrawing
        );

        window.addEventListener(
            "resize",
            resizeSignature
        );
    }

    function clearSignature() {
        if (!canvas || !ctx) return;

        ctx.clearRect(
            0,
            0,
            canvas.clientWidth,
            canvas.clientHeight
        );

        hasSignature = false;

        if (signatureInput) {
            signatureInput.value = "";
        }

        clearError("signature");
    }

    form.querySelector(
        '[data-action="clear-sign"]'
    )?.addEventListener(
        "click",
        clearSignature
    );

    form.querySelector(
        '[data-action="typed-sign"]'
    )?.addEventListener(
        "click",
        () => {
            const name = getValue("guardianName");

            if (!name) {
                alert(
                    "يرجى إدخال اسم ولي الأمر أولًا."
                );

                return;
            }

            if (!canvas || !ctx) return;

            clearSignature();
            setSignatureStyle();

            ctx.font =
                '24px "IBM Plex Sans Arabic", sans-serif';

            ctx.textAlign = "center";
            ctx.textBaseline = "middle";

            ctx.fillText(
                name,
                canvas.clientWidth / 2,
                canvas.clientHeight / 2,
                Math.max(
                    1,
                    canvas.clientWidth - 30
                )
            );

            hasSignature = true;
            saveSignature();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | التحقق من الحقول
    |--------------------------------------------------------------------------
    */

    function validateStep(step) {
        clearStepErrors(step);

        let valid = true;
        const required = [];

        if (step === 1) {
            required.push(
                "studentName",
                "studentId",
                "grade",
                "classroom"
            );
        }

        if (step === 2) {
            required.push(
                "guardianName",
                "guardianId",
                "phone",
                "relation"
            );
        }

        if (step === 3) {
            required.push("reason");

            if (getValue("reason") === "أخرى") {
                required.push("otherReason");
            }
        }

        required.forEach((id) => {
            if (!getValue(id)) {
                showError(id, "required");
                valid = false;
            }
        });

        if (step === 1 || step === 2) {
            const id = step === 1
                ? "studentId"
                : "guardianId";

            const input = get(id);

            if (input) {
                input.value = normalizeDigits(
                    input.value
                );
            }

            const identity = getValue(id);

            if (
                identity &&
                !/^[12][0-9]{9}$/.test(identity)
            ) {
                showError(id, "identity");
                valid = false;
            }
        }

        if (step === 2) {
            const phone = get("phone");

            if (phone) {
                phone.value = normalizeDigits(
                    phone.value
                );
            }

            if (
                getValue("phone") &&
                !/^05[0-9]{8}$/.test(
                    getValue("phone")
                )
            ) {
                showError("phone", "phone");
                valid = false;
            }

            if (!identityInput?.files?.length) {
                showError("identity", "required");
                valid = false;
            }
        }

        if (step === 4) {
            if (
                !hasSignature ||
                !signatureInput?.value
            ) {
                showError("signature", "required");
                valid = false;
            }

            if (!get("declaration")?.checked) {
                showError(
                    "declaration",
                    "required"
                );

                valid = false;
            }
        }

        if (!valid) {
            const section = form.querySelector(
                `[data-step="${step}"]`
            );

            const firstInvalid =
                section?.querySelector(
                    '[aria-invalid="true"]'
                );

            firstInvalid?.focus();
        }

        return valid;
    }

    /*
    |--------------------------------------------------------------------------
    | عرض المراجعة النهائية
    |--------------------------------------------------------------------------
    */

    function bindReview(name, text) {
        form.querySelectorAll(
            `[data-bind="${name}"]`
        ).forEach((item) => {
            item.textContent = text || "—";
        });
    }

    function updateReview() {
        bindReview(
            "studentName",
            getValue("studentName")
        );

        bindReview(
            "studentId",
            maskIdentity(getValue("studentId"))
        );

        bindReview(
            "studentNo",
            getValue("studentNo")
        );

        bindReview(
            "studentClass",
            [
                getValue("grade"),
                getValue("classroom")
            ].filter(Boolean).join(" · ")
        );

        bindReview(
            "guardianName",
            getValue("guardianName")
        );

        bindReview(
            "guardianId",
            maskIdentity(getValue("guardianId"))
        );

        bindReview(
            "phone",
            getValue("phone")
        );

        bindReview(
            "relation",
            getValue("relation")
        );

        bindReview(
            "permissionReason",
            getValue("reason") === "أخرى"
                ? getValue("otherReason")
                : getValue("reason")
        );

        bindReview(
            "notes",
            getValue("notes")
        );

        const reviewIdentity =
            get("review-identity");

        if (
            reviewIdentity &&
            identityInput?.files?.length
        ) {
            if (!identityPreviewUrl) {
                identityPreviewUrl = URL.createObjectURL(
                    identityInput.files[0]
                );
            }

            reviewIdentity.src = identityPreviewUrl;
            reviewIdentity.hidden = false;
        }

        const reviewSignature =
            get("review-signature");

        if (reviewSignature && hasSignature) {
            reviewSignature.src =
                signatureInput.value;

            reviewSignature.hidden = false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | التالي والسابق
    |--------------------------------------------------------------------------
    */

    function nextStep() {
        if (!validateStep(currentStep)) {
            return;
        }

        const next = editing
            ? 5
            : currentStep + 1;

        editing = false;

        showStep(next);
    }

    form.querySelectorAll("[data-next]")
        .forEach((button) => {
            button.addEventListener(
                "click",
                nextStep
            );
        });

    form.querySelectorAll("[data-previous]")
        .forEach((button) => {
            button.addEventListener(
                "click",
                () => {
                    editing = false;

                    showStep(currentStep - 1);
                }
            );
        });

    form.querySelectorAll("[data-edit]")
        .forEach((button) => {
            button.addEventListener(
                "click",
                () => {
                    editing = true;

                    showStep(
                        Number(button.dataset.edit)
                    );
                }
            );
        });

    form.addEventListener(
        "keydown",
        (event) => {
            if (
                event.key === "Enter" &&
                event.target.tagName === "INPUT" &&
                !["checkbox", "file"].includes(
                    event.target.type
                ) &&
                currentStep < 5
            ) {
                event.preventDefault();

                nextStep();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | التأكيد والإرسال الحقيقي إلى Laravel
    |--------------------------------------------------------------------------
    */

    const dialog = get("submit-dialog");

    const openConfirmation =
        form.querySelector(
            '[data-action="confirm-request"]'
        );

    function validateAllSteps() {
        for (let step = 1; step <= 4; step++) {
            if (!validateStep(step)) {
                editing = true;

                showStep(step);

                return false;
            }
        }

        return true;
    }

    openConfirmation?.addEventListener(
        "click",
        () => {
            if (!validateAllSteps()) {
                return;
            }

            updateReview();

            if (
                dialog &&
                typeof dialog.showModal === "function"
            ) {
                dialog.showModal();
            } else {
                submitToLaravel();
            }
        }
    );

    function submitToLaravel() {
        if (sending) return;

        if (!validateAllSteps()) {
            dialog?.close();
            return;
        }

        if (!form.action) {
            alert(
                "لم يتم تحديد رابط إرسال الطلب."
            );

            return;
        }

        sending = true;
        if (confirmationButton) {
            confirmationButton.disabled = true;
            confirmationButton.querySelector("[data-submit-idle]").hidden = true;
            confirmationButton.querySelector("[data-submit-busy]").hidden = false;
        }

        // يجب أن يكون POST /request مفعّلًا في Laravel.
        // لا نخزّن طلبات وهمية في المتصفح.
        HTMLFormElement.prototype.submit.call(form);
    }

    // زر التأكيد قد يكون بأحد الاسمين في Blade
    const confirmationButton =
        get("confirm-request-submit") ||
        document.querySelector(
            '[data-action="submit-request"]'
        );

    confirmationButton?.addEventListener(
        "click",
        (event) => {
            event.preventDefault();

            submitToLaravel();
        }
    );

    window.addEventListener("pageshow", () => {
        sending = false;
        if (confirmationButton) {
            confirmationButton.disabled = false;
            confirmationButton.querySelector("[data-submit-idle]").hidden = false;
            confirmationButton.querySelector("[data-submit-busy]").hidden = true;
        }
    });

    // منع إرسال النموذج من خطوة غير مكتملة
    form.addEventListener(
        "submit",
        (event) => {
            event.preventDefault();

            if (currentStep < 5) {
                nextStep();
                return;
            }

            if (
                dialog &&
                !dialog.open
            ) {
                if (validateAllSteps()) {
                    dialog.showModal();
                }

                return;
            }

            submitToLaravel();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | معالجة الأخطاء السابقة من Laravel
    |--------------------------------------------------------------------------
    */

    const serverErrors = get("server-errors");

    if (serverErrors) {
        const errorStep = Number(
            serverErrors.dataset.errorStep || 1
        );

        showStep(errorStep, false);
    } else {
        showStep(1, false);
    }

    updateReason();

    const requestTime = get("request-time");

    if (requestTime) {
        requestTime.textContent =
            new Intl.DateTimeFormat("ar-SA", {
                timeZone: "Asia/Riyadh",
                hour: "numeric",
                minute: "2-digit",
            }).format(new Date());
    }
}
