
@props([
    'variant' => 'error-box',
    'message' => null,
])

@php
    // أنواع التنبيهات المدعومة
    $allowedVariants = [
        'error-box',
        'success-box',
        'warning-box',
        'info-box',
    ];

    // اختيار نوع تنبيه آمن
    $alertVariant = in_array($variant, $allowedVariants, true)
        ? $variant
        : 'error-box';

    // تحديد الرسالة
    $alertMessage = $message;

    if ($alertMessage === null) {
        $sessionKeys = [
            'error-box' => 'error',
            'success-box' => 'success',
            'warning-box' => 'warning',
            'info-box' => 'info',
        ];

        $sessionKey = $sessionKeys[$alertVariant];

        $alertMessage = session($sessionKey);
    }

    // تحديد نوع التنبيه لقارئ الشاشة
    $alertRole = $alertVariant === 'error-box'
        ? 'alert'
        : 'status';
@endphp

@if($alertMessage || $slot->isNotEmpty())
    <div
        {{ $attributes->class([$alertVariant]) }}
        role="{{ $alertRole }}"
    >
        @if($alertMessage)
            {{ $alertMessage }}
        @else
            {{ $slot }}
        @endif
    </div>
@endif
