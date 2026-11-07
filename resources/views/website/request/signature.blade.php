<section
                class="request-step"
                data-step="4"
                aria-labelledby="step-4-title"
              >
                <div class="card-head">
                  <p>الخطوة 4 من 5</p>
                  <h2 id="step-4-title">التحقق والإقرار</h2>
                  <p>أكمل التوقيع والإقرار قبل مراجعة طلبك.</p>
                </div>
                <div class="card-body">
                  <div class="sign-head">
                    <b>توقيع ولي الأمر<span class="required">*</span></b
                    ><button
                      type="button"
                      class="text-btn"
                      data-action="clear-sign"
                    >
                      مسح وإعادة التوقيع
                    </button>
                  </div>
                  <canvas
                    id="signature"
                    class="signature"
                    tabindex="0"
                    role="img"
                    aria-label="مساحة التوقيع باللمس أو الفأرة"
                  ></canvas
                  ><small class="muted"
                    >وقّع بإصبعك أو باستخدام الفأرة داخل المساحة أعلاه.</small
                  >
                  <div class="signature-alternative">
                    <button
                      type="button"
                      class="text-btn"
                      data-action="typed-sign"
                    >
                      استخدام الاسم كتوقيع بديل
                    </button>
                  </div>
                  <p
                    class="error"
                    data-error-for="signature"
                    data-error-kind="required"
                    hidden
                  >
                    أضف توقيعك قبل المتابعة
                  </p>
                  <label class="check-row"
                    ><input
                      id="declaration"
                      name="declaration"
                      type="checkbox"
                      required
                    /><span
                      >أقر بأن جميع البيانات المدخلة صحيحة، وأنني مخول بطلب
                      استئذان الطالب.</span
                    ></label
                  >
                  <p
                    class="error"
                    data-error-for="declaration"
                    data-error-kind="required"
                    hidden
                  >
                    يجب الموافقة على الإقرار للمتابعة
                  </p>
                  <div class="hint">
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
                      <path d="M6 10h12v11H6zM8 10V7a4 4 0 0 1 8 0v3" />
                    </svg>
                    تستخدم صورة الهوية والتوقيع للتحقق من صفة المستلم.
                    <button
                      type="button"
                      class="text-btn"
                      data-action="privacy"
                    >
                      سياسة الخصوصية
                    </button>
                  </div>
                </div>
                <div class="card-actions">
                  <button type="button" class="btn secondary" data-previous>
                    السابق</button
                  ><button type="button" class="btn" data-next>
                    مراجعة الطلب
                  </button>
                </div>
              </section>
