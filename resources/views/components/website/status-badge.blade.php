
@props([
    'status' => 'pending',
])

@php
    $statuses = [
        'pending' => [
            'label' => 'قيد الانتظار',
            'class' => 'pending',
        ],
        'approved' => [
            'label' => 'تمت الموافقة',
            'class' => 'approved',
        ],
        'rejected' => [
            'label' => 'مرفوض',
            'class' => 'rejected',
        ],
        'completed' => [
            'label' => 'مكتمل',
            'class' => 'completed',
        ],
        'cancelled' => [
            'label' => 'ملغي',
            'class' => 'cancelled',
        ],
    ];

    $currentStatus = $statuses[$status] ?? [
        'label' => 'حالة غير معروفة',
        'class' => 'unknown',
    ];
@endphp

<span
    {{ $attributes->class([
        'badge',
        $currentStatus['class'],
    ]) }}
>
    {{ $slot->isEmpty()
        ? $currentStatus['label']
        : $slot
    }}
</span>
