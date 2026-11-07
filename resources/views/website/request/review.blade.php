
<section
    class="request-step"
    data-step="5"
    aria-labelledby="step-5-title"
>
    <div class="card-head">
        <p>الخطوة 5 من 5</p>

        <h2 id="step-5-title">مراجعة الطلب</h2>

        <p>
            تأكد من صحة جميع البيانات قبل إرسالها للمدرسة.
        </p>
    </div>

    <div class="card-body">

        <!-- بيانات الطالب -->
        <div class="review-section">
            <h3>
                بيانات الطالب

                <button
                    type="button"
                    class="text-btn"
                    data-edit="1"
                >
                    تعديل
                </button>
            </h3>

            <div class="summary-grid">

                <div>
                    <small>اسم الطالب</small>
                    <b data-bind="studentName">—</b>
                </div>

                <div>
                    <small>رقم الهوية / الإقامة</small>
                    <b data-bind="studentId">—</b>
                </div>

                <div>
                    <small>الصف والفصل</small>
                    <b data-bind="studentClass">—</b>
                </div>

                <div>
                    <small>الرقم الطلابي</small>
                    <b data-bind="studentNo">—</b>
                </div>

            </div>
        </div>

        <!-- بيانات ولي الأمر -->
        <div class="review-section">
            <h3>
                بيانات ولي الأمر

                <button
                    type="button"
                    class="text-btn"
                    data-edit="2"
                >
                    تعديل
                </button>
            </h3>

            <div class="summary-grid">

                <div>
                    <small>الاسم بالكامل</small>
                    <b data-bind="guardianName">—</b>
                </div>

                <div>
                    <small>رقم الهوية / الإقامة</small>
                    <b data-bind="guardianId">—</b>
                </div>

                <div>
                    <small>رقم الجوال</small>
                    <b data-bind="phone">—</b>
                </div>

                <div>
                    <small>صلة القرابة</small>
                    <b data-bind="relation">—</b>
                </div>

            </div>
        </div>

        <!-- تفاصيل الاستئذان -->
        <div class="review-section">
            <h3>
                تفاصيل الاستئذان

                <button
                    type="button"
                    class="text-btn"
                    data-edit="3"
                >
                    تعديل
                </button>
            </h3>

            <div class="summary-grid">

                <div>
                    <small>سبب الاستئذان</small>
                    <b data-bind="permissionReason">—</b>
                </div>

                <div>
                    <small>ملاحظات إضافية</small>
                    <b data-bind="notes">لا توجد ملاحظات</b>
                </div>

            </div>
        </div>

        <!-- الهوية والتوقيع -->
        <div class="review-section">
            <h3>
                الهوية والتوقيع

                <button
                    type="button"
                    class="text-btn"
                    data-edit="4"
                >
                    تعديل التوقيع
                </button>
            </h3>

            <div class="review-media">

                <div>
                    <small>
                        صورة الهوية

                        <button
                            type="button"
                            class="text-btn"
                            data-edit="2"
                        >
                            تعديل
                        </button>
                    </small>

                    <br>

                    <img
                        id="review-identity"
                        alt="معاينة صورة الهوية المرفقة"
                        hidden
                    >

                    <p id="review-identity-empty">
                        لم تُرفق صورة هوية بعد.
                    </p>
                </div>

                <div>
                    <small>توقيع ولي الأمر</small>

                    <br>

                    <img
                        id="review-signature"
                        alt="معاينة توقيع ولي الأمر"
                        hidden
                    >

                    <p id="review-signature-empty">
                        لم يُضف توقيع بعد.
                    </p>
                </div>

            </div>

            <div class="hint">
                يُرجى التأكد من صحة البيانات، وأن مقدم الطلب
                مخوّل بطلب الاستئذان. ستتحقق المدرسة من
                هوية المستلم قبل تسليم الطالب.
            </div>

        </div>

    </div>

    <!-- أزرار الخطوة الأخيرة -->
    <div class="card-actions">

        <button
            type="button"
            class="btn secondary"
            data-edit="1"
        >
            تعديل البيانات
        </button>

        <button
            type="button"
            class="btn"
            data-action="confirm-request"
            id="open-submit-confirmation"
        >
            إرسال طلب الاستئذان
        </button>

    </div>
</section>
