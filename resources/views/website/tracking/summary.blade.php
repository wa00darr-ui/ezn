<div class="summary-grid">
    <div>
        <small>رقم الطلب</small>
        <b>{{ $permissionRequest->id }}</b>
    </div>
    <div>
        <small>اسم الطالب</small>
        <b>{{ $permissionRequest->student?->name ?? 'غير متاح' }}</b>
    </div>
    <div>
        <small>تاريخ ووقت تقديم الطلب</small>
        <time>{{ ($permissionRequest->requested_at ?? $permissionRequest->created_at)?->timezone('Asia/Riyadh')->format('Y/m/d H:i') ?? 'غير متاح' }}</time>
    </div>
    @if(in_array($permissionRequest->status, ['approved', 'rejected', 'completed'], true))
        <div>
            <small>{{ $permissionRequest->status === 'rejected' ? 'وقت قرار الرفض' : 'وقت الموافقة' }}</small>
            <time>{{ $permissionRequest->reviewed_at?->timezone('Asia/Riyadh')->format('Y/m/d H:i') ?? 'غير مسجل' }}</time>
        </div>
    @endif
    @if($permissionRequest->status === 'completed')
        <div>
            <small>تاريخ ووقت تسليم الطالب</small>
            <time>{{ $permissionRequest->checked_out_at?->timezone('Asia/Riyadh')->format('Y/m/d H:i') ?? 'غير مسجل' }}</time>
        </div>
    @endif
</div>
