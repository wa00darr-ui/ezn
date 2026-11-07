<article class="form-card" data-request-state="approved">
    <div class="status-banner approved">
        <div class="status-icon" aria-hidden="true">✓</div>
        <h2>تمت الموافقة على طلب الاستئذان</h2>
        <p>يرجى مراجعة المدرسة لإتمام إجراءات استلام الطالب وإحضار أصل الهوية المطابقة للطلب.</p>
    </div>
    <div class="card-body">
        @include('website.tracking.summary', ['permissionRequest' => $permissionRequest])

        <div class="timeline">
            <div class="done"><span>✓</span>تم إرسال الطلب</div>
            <div class="done"><span>✓</span>تمت المراجعة</div>
            <div class="done"><span>✓</span>تمت الموافقة</div>
            <div><span>4</span>تم تسليم الطالب</div>
        </div>

        <div class="hint">لم يتم تسجيل تسليم الطالب بعد. تابع هذه الصفحة للاطّلاع على آخر حالة.</div>
    </div>
</article>
