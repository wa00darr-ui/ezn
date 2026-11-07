<article class="form-card" data-request-state="rejected">
    <div class="status-banner rejected">
        <div class="status-icon" aria-hidden="true">×</div>
        <h2>تم رفض الطلب</h2>
        <p>يرجى التواصل مع إدارة المدرسة لمزيد من المعلومات.</p>
    </div>
    <div class="card-body">
        @include('website.tracking.summary', ['permissionRequest' => $permissionRequest])

        <div class="timeline">
            <div class="done"><span>✓</span>تم إرسال الطلب</div>
            <div class="done"><span>✓</span>تمت المراجعة</div>
            <div class="done"><span>✓</span>تم رفض الطلب</div>
            <div><span>4</span>لم يتم الاستلام</div>
        </div>

        <x-website.alert>
            <b>سبب الرفض</b>
            <p>{{ $permissionRequest->rejection_reason ?: 'لم يتم تسجيل سبب للرفض. يرجى التواصل مع إدارة المدرسة.' }}</p>
        </x-website.alert>
    </div>
</article>
