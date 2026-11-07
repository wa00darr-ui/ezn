
@php
    $schoolName = config('school.name', 'مدارس آفاق الأهلية');

    $schoolTagline = config(
        'school.tagline',
        'شراكة مع الأسرة، وعناية بكل طالب.'
    );

    $schoolLogoLetter = config('school.logo_letter', 'آ');

    $currentYear = now()->format('Y');

    $arabicYear = strtr(
        $currentYear,
        '0123456789',
        '٠١٢٣٤٥٦٧٨٩'
    );
@endphp

<footer>
    <div class="wrap foot">

        <!-- شعار المدرسة -->
        <a href="{{ url('/') }}" class="brand">
            <span class="mark">
                {{ $schoolLogoLetter }}
            </span>

            <span>
                <b>{{ $schoolName }}</b>
                <small>{{ $schoolTagline }}</small>
            </span>
        </a>

        <!-- روابط الموقع -->
        <div>
            <button
                type="button"
                class="text-btn"
                data-action="privacy"
                aria-label="عرض سياسة الخصوصية"
            >
                سياسة الخصوصية
            </button>

            <a href="{{ url('/contact') }}">
                تواصل معنا
            </a>
        </div>

        <!-- حقوق النشر -->
        <small>
            © {{ $arabicYear }} {{ $schoolName }}.
            جميع الحقوق محفوظة.
        </small>

    </div>
</footer>

