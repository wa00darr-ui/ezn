<section
    class="request-step active"
    data-step="1"
    aria-labelledby="step-1-title"
>
    <div class="card-head">
        <p>الخطوة 1 من 5</p>

        <h2 id="step-1-title">بيانات الطالب</h2>

        <p>
            لنبدأ بالتعرّف على الطالب المطلوب استئذانه.
        </p>
    </div>

    <div class="card-body">
        <div class="fields">

            <!-- اسم الطالب -->
            <div class="field full">
                <label for="studentName">
                    اسم الطالب
                    <span class="required">*</span>
                </label>

                <input
                    id="studentName"
                    name="studentName"
                    type="text"
                    value="{{ old('studentName') }}"
                    required
                    maxlength="100"
                    placeholder="مثال: عبدالله محمد العتيبي"
                    autocomplete="off"
                    @if($errors->has('studentName')) aria-invalid="true" @endif
                >

                <p
                    class="error"
                    data-error-for="studentName"
                    data-error-kind="required"
                    @if(!$errors->has('studentName')) hidden @endif
                >
                    {{ $errors->first('studentName') ?: 'هذا الحقل مطلوب' }}
                </p>
            </div>

            <!-- رقم الهوية / الإقامة -->
            <div class="field">
                <label for="studentId">
                    رقم الهوية / الإقامة
                    <span class="required">*</span>
                </label>

                <input
                    id="studentId"
                    name="studentId"
                    type="text"
                    value="{{ old('studentId') }}"
                    required
                    minlength="10"
                    maxlength="10"
                    pattern="[12][0-9]{9}"
                    placeholder="10 أرقام"
                    inputmode="numeric"
                    dir="ltr"
                    autocomplete="off"
                    @if($errors->has('studentId')) aria-invalid="true" @endif
                >

                <p
                    class="error"
                    data-error-for="studentId"
                    data-error-kind="required"
                    hidden
                >
                    هذا الحقل مطلوب
                </p>

                <p
                    class="error"
                    data-error-for="studentId"
                    data-error-kind="identity"
                    @if(!$errors->has('studentId')) hidden @endif
                >
                    {{ $errors->first('studentId') ?: 'أدخل رقم هوية أو إقامة من 10 أرقام يبدأ بـ 1 أو 2' }}
                </p>
            </div>

            <!-- الرقم الطلابي -->
            <div class="field">
                <label for="studentNo">
                    الرقم الطلابي
                    <small>(اختياري)</small>
                </label>

                <input
                    id="studentNo"
                    name="studentNo"
                    type="text"
                    value="{{ old('studentNo') }}"
                    maxlength="50"
                    placeholder="مثال: 20260482"
                    inputmode="numeric"
                    dir="ltr"
                    autocomplete="off"
                >

                @error('studentNo')
                    <p class="error" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- الصف -->
            <div class="field">
                <label for="grade">
                    الصف
                    <span class="required">*</span>
                </label>

                <select
                    id="grade"
                    name="grade"
                    required
                >
                    <option value="">اختر الصف</option>

                    @foreach([
                        'الأول الابتدائي',
                        'الثاني الابتدائي',
                        'الثالث الابتدائي',
                        'الرابع الابتدائي',
                        'الخامس الابتدائي',
                        'السادس الابتدائي',
                        'الأول المتوسط',
                        'الثاني المتوسط',
                        'الثالث المتوسط',
                        'الأول الثانوي',
                        'الثاني الثانوي',
                        'الثالث الثانوي'
                    ] as $grade)
                        <option
                            value="{{ $grade }}"
                            @selected(old('grade') === $grade)
                        >
                            {{ $grade }}
                        </option>
                    @endforeach
                </select>

                <p
                    class="error"
                    data-error-for="grade"
                    data-error-kind="required"
                    @if(!$errors->has('grade')) hidden @endif
                >
                    {{ $errors->first('grade') ?: 'يرجى اختيار الصف' }}
                </p>
            </div>

            <!-- الفصل -->
            <div class="field">
                <label for="classroom">
                    الفصل
                    <span class="required">*</span>
                </label>

                <select
                    id="classroom"
                    name="classroom"
                    required
                >
                    <option value="">اختر الفصل</option>

                    @foreach(['أ', 'ب', 'ج', 'د'] as $classroom)
                        <option
                            value="{{ $classroom }}"
                            @selected(old('classroom') === $classroom)
                        >
                            {{ $classroom }}
                        </option>
                    @endforeach
                </select>

                <p
                    class="error"
                    data-error-for="classroom"
                    data-error-kind="required"
                    @if(!$errors->has('classroom')) hidden @endif
                >
                    {{ $errors->first('classroom') ?: 'يرجى اختيار الفصل' }}
                </p>
            </div>

        </div>

        <!-- رسالة توضيحية -->
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
                    d="M12 11v6M12 7v1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0"
                />
            </svg>

            اكتب بيانات الطالب كما هي مسجلة لدى المدرسة
            لتسهيل مراجعة طلب الاستئذان.
        </div>
    </div>

    <!-- أزرار التنقل -->
    <div class="card-actions">

        <a
            class="text-btn"
            href="{{ url('/') }}"
        >
            العودة للرئيسية
        </a>

        <button
            type="button"
            class="btn"
            data-next
        >
            <span data-next-label>
                التالي
            </span>

            <span data-review-label hidden>
                مراجعة الطلب
            </span>
        </button>

    </div>
</section>