
<div class="field full">

    <label for="identity">
        صورة الهوية الوطنية أو الإقامة
        <span class="required">*</span>
    </label>

    <div class="upload">

        <!-- منطقة اختيار الصورة -->
        <label id="upload-prompt" for="identity">

            <svg
                class="icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M12 16V3m-5 5 5-5 5 5M4 16v5h16v-5" />
            </svg>

            <p>اضغط لرفع الهوية أو تصويرها</p>

            <small>
                JPG أو PNG أو WebP · حتى 5 ميجابايت
            </small>

        </label>

        <!-- حالة تجهيز الصورة -->
        <div id="upload-loading" role="status" hidden>
            <span class="spinner"></span>
            <p>جارٍ تجهيز صورة الهوية…</p>
        </div>

        <!-- معاينة الصورة -->
        <div id="identity-preview" hidden>

            <img
                class="preview-image"
                id="identity-image"
                alt="معاينة صورة الهوية المختارة"
                hidden
            >

            <div>
                <label for="identity" class="text-btn">
                    استبدال الصورة
                </label>

                <button
                    type="button"
                    class="text-btn"
                    data-action="remove-image"
                    id="remove-identity"
                >
                    حذف
                </button>
            </div>

        </div>

        <!-- الملف الحقيقي الذي سيرسل إلى Laravel -->
        <input
            type="file"
            id="identity"
            name="identity"
            accept="image/jpeg,image/png,image/webp"
            aria-label="رفع أو تصوير الهوية"
            aria-describedby="identity-help identity-error"
            required
        >

    </div>

    <p
        id="identity-error"
        class="error"
        data-error-for="identity"
        data-error-kind="required"
        @if(!$errors->has('identity')) hidden @endif
    >
        {{ $errors->first('identity') ?: 'أرفق صورة الهوية لإكمال الطلب' }}
    </p>

    <p
        class="error"
        data-error-for="identity"
        data-error-kind="uploading"
        hidden
    >
        انتظر حتى اكتمال تجهيز الصورة.
    </p>

    <p
        class="error"
        data-error-for="identity"
        data-error-kind="file"
        hidden
    >
        اختر صورة JPG أو PNG أو WebP بحجم لا يتجاوز 5 ميجابايت.
    </p>

    <p
        class="error"
        data-error-for="identity"
        data-error-kind="read"
        hidden
    >
        تعذّر قراءة الصورة. اختر صورة أخرى.
    </p>

    <small id="identity-help">
        تُستخدم صورة الهوية للتحقق من بيانات ولي الأمر.
        تُعامل باعتبارها بيانات شخصية حساسة ولا ينبغي مشاركتها
        إلا مع الموظفين المخولين.
    </small>

</div>
