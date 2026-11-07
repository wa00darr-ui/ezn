<section
    class="request-step"
    data-step="3"
    aria-labelledby="step-3-title"
>
    <div class="card-head">
        <p>الخطوة 3 من 5</p>

        <h2 id="step-3-title">تفاصيل الاستئذان</h2>

        <p>
            أخبرنا بسبب مغادرة الطالب قبل نهاية اليوم الدراسي.
        </p>
    </div>

    <div class="card-body">
        <div class="fields">

            <!-- سبب الاستئذان -->
            <div class="field full">
                <label for="reason">
                    سبب الاستئذان
                    <span class="required">*</span>
                </label>

                <select
                    id="reason"
                    name="reason"
                    required
                >
                    <option value="">اختر سبب الاستئذان</option>

                    @foreach($reasons ?? [] as $reason)
                        <option
                            value="{{ $reason->name }}"
                            @selected(old('reason') === $reason->name)
                        >
                            {{ $reason->name }}
                        </option>
                    @endforeach
                </select>

                <p
                    class="error"
                    data-error-for="reason"
                    data-error-kind="required"
                    @if(!$errors->has('reason')) hidden @endif
                >
                    {{ $errors->first('reason') ?: 'يرجى اختيار سبب الاستئذان' }}
                </p>
            </div>

            <!-- السبب الآخر -->
            <div
                class="field full"
                id="other-wrap"
                hidden
            >
                <label for="otherReason">
                    اذكر سبب الاستئذان
                    <span class="required">*</span>
                </label>

                <input
                    id="otherReason"
                    name="otherReason"
                    type="text"
                    value="{{ old('otherReason') }}"
                    maxlength="100"
                    placeholder="اكتب السبب باختصار"
                    disabled
                >

                <p
                    class="error"
                    data-error-for="otherReason"
                    data-error-kind="required"
                    @if(!$errors->has('otherReason')) hidden @endif
                >
                    {{ $errors->first('otherReason') ?: 'يرجى كتابة سبب الاستئذان' }}
                </p>
            </div>

            <!-- ملاحظات إضافية -->
            <div class="field full">
                <label for="notes">
                    ملاحظات إضافية
                    <small>(اختياري)</small>
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="3"
                    maxlength="500"
                    placeholder="أي تفاصيل تساعد المدرسة في مراجعة الطلب"
                >{{ old('notes') }}</textarea>

                @error('notes')
                    <p class="error" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        <!-- وقت الطلب -->
        <div class="hint">
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
                <path
                    d="M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0"
                />
            </svg>

            التاريخ والوقت الحاليان:

            <time
                id="request-time"
                datetime="{{ now('Asia/Riyadh')->toIso8601String() }}"
            >
                {{ now('Asia/Riyadh')->translatedFormat('d F Y، h:i A') }}
            </time>

            <br>

            يُسجّل وقت إرسال الطلب النهائي تلقائيًا
            بواسطة الخادم بتوقيت الرياض.
        </div>
    </div>

    <!-- أزرار الانتقال بين الخطوات -->
    <div class="card-actions">

        <button
            type="button"
            class="btn secondary"
            data-previous
        >
            السابق
        </button>

        <button
            type="button"
            class="btn"
            data-next
        >
            <span data-next-label>التالي</span>

            <span data-review-label hidden>
                مراجعة الطلب
            </span>
        </button>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const reasonSelect = document.getElementById('reason');
    const otherWrap = document.getElementById('other-wrap');
    const otherInput = document.getElementById('otherReason');

    if (!reasonSelect || !otherWrap || !otherInput) {
        return;
    }

    function updateOtherReason() {
        const isOther = reasonSelect.value === 'أخرى';

        otherWrap.hidden = !isOther;
        otherInput.disabled = !isOther;
        otherInput.required = isOther;

        if (!isOther) {
            otherInput.value = '';
        }
    }

    reasonSelect.addEventListener('change', updateOtherReason);

    updateOtherReason();

});
</script>