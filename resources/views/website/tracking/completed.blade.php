<article class="form-card" data-request-state="completed">
    <div class="status-banner completed">
        <div class="status-icon" aria-hidden="true">✓</div>
        <h2>تم تسليم الطالب</h2>
        <p>اكتملت إجراءات الاستئذان وتم تسجيل تسليم الطالب.</p>
    </div>
    <div class="card-body">
        @include('website.tracking.summary', ['permissionRequest' => $permissionRequest])

        <div class="timeline">
            <div class="done"><span>✓</span>تم إرسال الطلب</div>
            <div class="done"><span>✓</span>تمت المراجعة</div>
            <div class="done"><span>✓</span>تمت الموافقة</div>
            <div class="done"><span>✓</span>تم تسليم الطالب</div>
        </div>

        <div class="hint">تم إغلاق رحلة الاستئذان بعد التسليم. لا يمكن استخدام رمز الاستلام مرة أخرى.</div>
    </div>
</article>
