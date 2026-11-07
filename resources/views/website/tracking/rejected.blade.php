<article class="form-card" data-request-state="rejected">
            <div class="status-banner rejected">
              <div class="status-icon">×</div>
              <h2>تم رفض طلب الاستئذان</h2>
              <p>يرجى التواصل مع إدارة المدرسة لمزيد من المعلومات.</p>
            </div>
            <div class="card-body">
              <div class="summary-grid">
                <div>
                  <small>رقم الطلب</small
                  ><b data-bind="requestNumber">AF-2026-0482</b>
                </div>
                <div>
                  <small>اسم الطالب</small
                  ><b data-bind="studentName">عبدالله محمد العتيبي</b>
                </div>
                <div>
                  <small>تاريخ ووقت تقديم الطلب</small
                  ><time data-bind="submittedAt">٠٤ / ١٠ / ٢٠٢٦، ١٠:٣٠ ص</time>
                </div>
                <div>
                  <small>سبب الاستئذان</small
                  ><b data-bind="permissionReason">موعد طبي</b>
                </div>
                <div>
                  <small>وقت القرار</small
                  ><time data-bind="decisionAt">٠٤ / ١٠ / ٢٠٢٦، ١٠:٤٠ ص</time>
                </div>
              </div>
              <div class="timeline">
                <div class="done"><span>✓</span>تم إرسال الطلب</div>
                <div class="done"><span>✓</span>تمت المراجعة</div>
                <div class="done"><span>✓</span>تم رفض الطلب</div>
                <div><span>4</span>لم يتم الاستلام</div>
              </div>
              <x-website.alert>
                <b>سبب الرفض</b><br />يتزامن وقت الاستئذان مع اختبار مقرر
                للطالب. يرجى التواصل مع إدارة المدرسة لترتيب موعد مناسب.
              </x-website.alert>
              <div class="whatsapp">
                <svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path
                    d="M21 11a9 9 0 0 1-9 9H3l2-5a9 9 0 1 1 16-4M8 10h8M8 14h5"
                  />
                </svg>
                <div>
                  ✓ إشعار قرار الطلب عبر واتساب<small
                    >محاكاة إرسال إلى
                    <span data-bind="phone">0500000000</span> · لا تُرسل رسائل
                    فعلية</small
                  >
                </div>
              </div>
            </div>
          </article>
