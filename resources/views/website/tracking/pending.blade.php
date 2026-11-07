<article class="form-card" data-request-state="pending">
    <div class="status-banner pending">
        <div class="status-icon" aria-hidden="true">◷</div>
        <h2>قيد المراجعة</h2>
        <p>تم إرسال طلب الاستئذان إلى إدارة المدرسة، يرجى الانتظار لحين مراجعة الطلب.</p>
    </div>
    <div class="card-body">
        @include('website.tracking.summary', ['permissionRequest' => $permissionRequest])

        <div class="timeline">
            <div class="done"><span>✓</span>تم إرسال الطلب</div>
            <div><span>2</span>قيد المراجعة</div>
            <div><span>3</span>قرار الإدارة</div>
            <div><span>4</span>تم تسليم الطالب</div>
        </div>

        <div class="hint">يرجى انتظار الموافقة قبل الحضور لاستلام الطالب.</div>
    </div>
</article>
