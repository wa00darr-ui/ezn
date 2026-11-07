<section
    class="request-step"
    data-step="2"
    aria-labelledby="step-2-title"
>
    <div class="card-head">
        <p>الخطوة 2 من 5</p>

        <h2 id="step-2-title">
            بيانات ولي الأمر
        </h2>

        <p>
            بيانات ولي الأمر أو الشخص المخوّل
            بتقديم طلب الاستئذان واستلام الطالب.
        </p>
    </div>

    <div class="card-body">
        <div class="fields">

            <!-- اسم ولي الأمر -->
            <div class="field full">
                <label for="guardianName">
                    اسم ولي الأمر بالكامل
                    <span class="required">*</span>
                </label>

                <input
                    id="guardianName"
                    name="guardianName"
                    type="text"
                    value="{{ old('guardianName') }}"
                    required
                    maxlength="100"
                    placeholder="مثال: محمد سعد العتيبي"
                    autocomplete="name"
                    aria-describedby="guardianName-error"
                >

                <p
                    id="guardianName-error"
                    class="error"
                    data-error-for="guardianName"
                    data-error-kind="required"
                    @if(! $errors->has('guardianName')) hidden @endif
                >
                    {{ $errors->first('guardianName') ?: 'هذا الحقل مطلوب' }}
                </p>
            </div>

            <!-- رقم الهوية / الإقامة -->
            <div class="field">
                <label for="guardianId">
                    رقم الهوية / الإقامة
                    <span class="required">*</span>
                </label>

                <input
                    id="guardianId"
                    name="guardianId"
                    type="text"
                    value="{{ old('guardianId') }}"
                    required
                    minlength="10"
                    maxlength="10"
                    pattern="[12][0-9]{9}"
                    placeholder="10 أرقام"
                    inputmode="numeric"
                    dir="ltr"
                    autocomplete="off"
                    aria-describedby="guardianId-error"
                >

                <p
                    class="error"
                    data-error-for="guardianId"
                    data-error-kind="required"
                    hidden
                >
                    هذا الحقل مطلوب
                </p>

                <p
                    id="guardianId-error"
                    class="error"
                    data-error-for="guardianId"
                    data-error-kind="identity"
                    @if(! $errors->has('guardianId')) hidden @endif
                >
                    {{ $errors->first('guardianId') ?: 'أدخل رقم هوية أو إقامة من 10 أرقام يبدأ بـ 1 أو 2' }}
                </p>
            </div>

            <!-- رقم الجوال -->
            <div class="field">
                <label for="phone">
                    رقم الجوال
                    <span class="required">*</span>
                </label>

                <input
                    id="phone"
                    name="phone"
                    type="tel"
                    value="{{ old('phone') }}"
                    required
                    minlength="10"
                    maxlength="10"
                    pattern="05[0-9]{8}"
                    placeholder="05xxxxxxxx"
                    inputmode="tel"
                    dir="ltr"
                    autocomplete="tel"
                    aria-describedby="phone-error"
                >

                <p
                    class="error"
                    data-error-for="phone"
                    data-error-kind="required"
                    hidden
                >
                    هذا الحقل مطلوب
                </p>

                <p
                    id="phone-error"
                    class="error"
                    data-error-for="phone"
                    data-error-kind="phone"
                    @if(! $errors->has('phone')) hidden @endif
                >
                    {{ $errors->first('phone') ?: 'أدخل رقم جوال سعودي من 10 أرقام يبدأ بـ 05' }}
                </p>
            </div>

            <!-- صلة القرابة -->
            <div class="field full">
                <label for="relation">
                    صلة القرابة بالطالب
                    <span class="required">*</span>
                </label>

                <select
                    id="relation"
                    name="relation"
                    required
                >
                    <option value="">
                        اختر صلة القرابة بالطالب
                    </option>

                    @foreach([
                        'الأب',
                        'الأم',
                        'الأخ',
                        'الأخت',
                        'ولي شرعي'
                    ] as $relation)
                        <option
                            value="{{ $relation }}"
                            @selected(old('relation') === $relation)
                        >
                            {{ $relation }}
                        </option>
                    @endforeach
                </select>

                <p
                    class="error"
                    data-error-for="relation"
                    data-error-kind="required"
                    @if(! $errors->has('relation')) hidden @endif
                >
                    {{ $errors->first('relation') ?: 'يرجى اختيار صلة القرابة بالطالب' }}
                </p>
            </div>

            <!-- رفع إثبات الهوية -->
            @include('website.request.identity')

        </div>
    </div>

    <!-- أزرار التنقل -->
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
            <span data-next-label>
                التالي
            </span>

            <span data-review-label hidden>
                مراجعة الطلب
            </span>
        </button>

    </div>
</section>