@extends('layouts.dashboard', ['activePage' => 'reports', 'pageTitle' => 'التقارير'])

@section('title', 'التقارير')
@section('page', 'reports')

@section('content')

        <div class="page-heading">
          <div>
            <p class="eyebrow">مدارس آفاق الأهلية</p>
            <h1>التقارير</h1>
          </div>
          <div class="page-actions">
            <button type="button" class="btn secondary" data-export>
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
                <path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4" />
              </svg>
              تصدير Excel</button
            ><button type="button" class="btn" data-export>
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
                <path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4" />
              </svg>
              تصدير PDF
            </button>
          </div>
        </div>
        <x-dashboard.page-states />

        <div data-page-content>
          <div class="panel report-filter">
            <form data-report-filters>
              <div class="tabs period-tabs" aria-label="فترة التقرير">
                <button
                  type="button"
                  class="selected"
                  data-period="today"
                  data-new="8"
                  data-pending="5"
                  data-total="40"
                  data-approved="24"
                  data-rejected="3"
                  data-checkout="18"
                  data-average="7"
                >
                  اليوم</button
                ><button
                  type="button"
                  data-period="week"
                  data-new="15"
                  data-pending="10"
                  data-total="154"
                  data-approved="118"
                  data-rejected="11"
                  data-checkout="102"
                  data-average="8"
                >
                  هذا الأسبوع</button
                ><button
                  type="button"
                  data-period="month"
                  data-new="10"
                  data-pending="8"
                  data-total="96"
                  data-approved="70"
                  data-rejected="8"
                  data-checkout="58"
                  data-average="7"
                >
                  هذا الشهر</button
                ><button
                  type="button"
                  data-period="custom"
                  data-new="8"
                  data-pending="5"
                  data-total="40"
                  data-approved="24"
                  data-rejected="3"
                  data-checkout="18"
                  data-average="7"
                >
                  فترة مخصصة
                </button>
              </div>
              <div class="filters">
                <label class="field"
                  ><span>من تاريخ</span
                  ><input type="date" name="from" value="2026-10-04" /></label
                ><label class="field"
                  ><span>إلى تاريخ</span
                  ><input type="date" name="to" value="2026-10-04" /></label
                ><label class="field"
                  ><span>الطالب</span
                  ><select name="student">
                    <option value="">كل الطلاب</option>
                    <option
                      value="محمد أحمد القحطاني"
                      data-total="6"
                      data-approved="5"
                      data-rejected="1"
                      data-checkout="4"
                      data-average="7"
                      data-grade="الخامس الابتدائي"
                    >
                      محمد أحمد القحطاني
                    </option>
                    <option
                      value="عبدالله خالد العتيبي"
                      data-total="6"
                      data-approved="5"
                      data-rejected="0"
                      data-checkout="4"
                      data-average="7"
                      data-grade="السادس الابتدائي"
                    >
                      عبدالله خالد العتيبي
                    </option>
                    <option
                      value="سعود محمد الدوسري"
                      data-total="5"
                      data-approved="4"
                      data-rejected="0"
                      data-checkout="3"
                      data-average="7"
                      data-grade="الأول المتوسط"
                    >
                      سعود محمد الدوسري
                    </option>
                    <option
                      value="نورة أحمد الحربي"
                      data-total="5"
                      data-approved="4"
                      data-rejected="1"
                      data-checkout="3"
                      data-average="7"
                      data-grade="الخامس الابتدائي"
                    >
                      نورة أحمد الحربي
                    </option>
                    <option
                      value="خالد فهد الغامدي"
                      data-total="4"
                      data-approved="3"
                      data-rejected="0"
                      data-checkout="2"
                      data-average="7"
                      data-grade="السادس الابتدائي"
                    >
                      خالد فهد الغامدي
                    </option>
                    <option
                      value="ريم صالح الشهري"
                      data-total="4"
                      data-approved="3"
                      data-rejected="0"
                      data-checkout="2"
                      data-average="7"
                      data-grade="الأول المتوسط"
                    >
                      ريم صالح الشهري
                    </option>
                    <option
                      value="عمر عبدالله المطيري"
                      data-total="3"
                      data-approved="2"
                      data-rejected="1"
                      data-checkout="1"
                      data-average="7"
                      data-grade="الخامس الابتدائي"
                    >
                      عمر عبدالله المطيري
                    </option>
                    <option
                      value="سارة محمد الزهراني"
                      data-total="3"
                      data-approved="2"
                      data-rejected="0"
                      data-checkout="1"
                      data-average="7"
                      data-grade="السادس الابتدائي"
                    >
                      سارة محمد الزهراني
                    </option>
                  </select></label
                ><label class="field"
                  ><span>الصف</span
                  ><select name="grade">
                    <option value="">كل الصفوف</option>
                    <option
                      value="الخامس الابتدائي"
                      data-total="16"
                      data-approved="10"
                      data-rejected="1"
                      data-checkout="8"
                      data-average="7"
                    >
                      الخامس الابتدائي
                    </option>
                    <option
                      value="السادس الابتدائي"
                      data-total="14"
                      data-approved="8"
                      data-rejected="1"
                      data-checkout="6"
                      data-average="7"
                    >
                      السادس الابتدائي
                    </option>
                    <option
                      value="الأول المتوسط"
                      data-total="10"
                      data-approved="6"
                      data-rejected="1"
                      data-checkout="4"
                      data-average="7"
                    >
                      الأول المتوسط
                    </option>
                  </select></label
                ><label class="field"
                  ><span>الحالة</span
                  ><select name="status">
                    <option value="">كل الحالات</option>
                    <option value="new">جديد</option>
                    <option value="pending">قيد المراجعة</option>
                    <option value="approved">موافق عليه</option>
                    <option value="rejected">مرفوض</option>
                    <option value="completed">مكتمل</option>
                  </select></label
                ><button class="btn" type="submit">تطبيق الفلاتر</button>
              </div>
            </form>
          </div>
          <p class="notice danger" data-report-range-error role="alert" hidden>
            يرجى اختيار تاريخ نهاية بعد تاريخ البداية.
          </p>
          <p class="notice" data-report-note hidden>
            تم تطبيق نطاق العرض التجريبي. المؤشرات والرسوم أدناه بيانات نموذجية
            ثابتة لتوضيح شكل التقرير.
          </p>
          <div class="kpi-grid">
            <article class="kpi">
              <div class="kpi-top">
                <span>إجمالي الطلبات</span
                ><svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path d="M14 3H5v18h14V8zM14 3v6h5M8 13h8M8 17h5" />
                </svg>
              </div>
              <strong data-report-value="total">٤٠</strong
              ><small>خلال الفترة المختارة</small>
            </article>
            <article class="kpi">
              <div class="kpi-top">
                <span>الموافق عليها</span
                ><svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path d="m5 12 4 4L19 6" />
                </svg>
              </div>
              <strong data-report-value="approved">٢٤</strong
              ><small>تشمل الطلبات المكتملة</small>
            </article>
            <article class="kpi">
              <div class="kpi-top">
                <span>المرفوضة</span
                ><svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path d="M14 3H5v18h14V8zM14 3v6h5M8 13h8M8 17h5" />
                </svg>
              </div>
              <strong data-report-value="rejected">٣</strong
              ><small>طلبات صدر قرار برفضها</small>
            </article>
            <article class="kpi">
              <div class="kpi-top">
                <span>الطلاب الذين خرجوا</span
                ><svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path d="M10 4H4v16h6M10 12h11m-4-4 4 4-4 4" />
                </svg>
              </div>
              <strong data-report-value="checkout">١٨</strong
              ><small>عمليات تسليم مكتملة</small>
            </article>
            <article class="kpi">
              <div class="kpi-top">
                <span>متوسط وقت المعالجة</span
                ><svg
                  class="icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <path d="M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
                </svg>
              </div>
              <strong><span data-report-value="average">٧</span> دقائق</strong
              ><small>أسرع بدقيقتين من أمس</small>
            </article>
          </div>
          <div class="two-col wide-first">
            <section class="panel">
              <div class="panel-head"><h2>الطلبات خلال الفترة</h2></div>
              <p class="caption">
                اتجاهات عامة خلال الأسبوع · عدد الطلبات لكل يوم
              </p>
              <div class="line-chart">
                <svg
                  viewBox="0 0 640 220"
                  role="img"
                  aria-label="عدد الطلبات: الأحد ٢٤، الاثنين ٣٢، الثلاثاء ٢٨، الأربعاء ٤٢، الخميس ٤٠"
                >
                  <path
                    class="chart-grid"
                    d="M40 30H620M40 80H620M40 130H620M40 180H620"
                  />
                  <path
                    class="chart-area"
                    d="M40 145 180 100 320 123 460 45 600 57V180H40Z"
                  />
                  <path
                    class="chart-line"
                    d="m40 145 140-45 140 23 140-78 140 12"
                  />
                  <g class="chart-dots">
                    <circle cx="40" cy="145" r="5" />
                    <circle cx="180" cy="100" r="5" />
                    <circle cx="320" cy="123" r="5" />
                    <circle cx="460" cy="45" r="5" />
                    <circle cx="600" cy="57" r="5" />
                  </g>
                  <g class="chart-labels">
                    <text x="40" y="211">الأحد</text>
                    <text x="180" y="211">الاثنين</text>
                    <text x="320" y="211">الثلاثاء</text>
                    <text x="460" y="211">الأربعاء</text>
                    <text x="600" y="211">الخميس</text>
                  </g>
                </svg>
              </div>
            </section>
            <section class="panel">
              <div class="panel-head">
                <h2>الموافق عليها مقابل المرفوضة</h2>
              </div>
              <div class="distribution">
                <div
                  class="donut comparison"
                  role="img"
                  aria-label="٢٤ طلبًا موافقًا عليه و٣ مرفوضة"
                >
                  <div><b>٨٩٪</b><small>نسبة الموافقة من القرارات</small></div>
                </div>
              </div>
              <ul class="legend horizontal">
                <li><i class="dot green"></i> موافق عليها ٢٤</li>
                <li><i class="dot red"></i> مرفوضة ٣</li>
              </ul>
            </section>
          </div>
          <div class="two-col">
            <section class="panel">
              <div class="panel-head"><h2>أسباب الاستئذان</h2></div>
              <div class="rank-bars">
                <div>
                  <span>موعد طبي <b>١٨</b></span
                  ><i class="w90"></i>
                </div>
                <div>
                  <span>ظرف عائلي طارئ <b>١٠</b></span
                  ><i class="w50"></i>
                </div>
                <div>
                  <span>وعكة صحية <b>٨</b></span
                  ><i class="w40"></i>
                </div>
                <div>
                  <span>أخرى <b>٤</b></span
                  ><i class="w20"></i>
                </div>
              </div>
            </section>
            <section class="panel">
              <div class="panel-head"><h2>الطلبات حسب الصف</h2></div>
              <div
                class="bar-chart"
                role="img"
                aria-label="الخامس ١٦، السادس ١٤، الأول المتوسط ١٠"
              >
                <div><i class="bar h96"></i><b>١٦</b><span>الخامس</span></div>
                <div><i class="bar h80"></i><b>١٤</b><span>السادس</span></div>
                <div>
                  <i class="bar h56"></i><b>١٠</b><span>الأول المتوسط</span>
                </div>
              </div>
            </section>
          </div>
          <section class="panel">
            <div class="panel-head"><h2>الطلاب الأكثر استئذانًا</h2></div>
            <div
              class="table-wrap"
              tabindex="0"
              role="region"
              aria-label="جدول بيانات قابل للتمرير"
            >
              <table>
                <thead>
                  <tr>
                    <th scope="col">الطالب</th>
                    <th scope="col">الصف</th>
                    <th scope="col">عدد الطلبات</th>
                    <th scope="col">الموافق عليها</th>
                    <th scope="col">آخر استئذان</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="{{ url('/dashboard/student-details') }}">محمد أحمد القحطاني</a>
                    </td>
                    <td>الخامس الابتدائي</td>
                    <td>٦</td>
                    <td>٥</td>
                    <td>٤ أكتوبر</td>
                  </tr>
                  <tr>
                    <td>عبدالله خالد العتيبي</td>
                    <td>السادس الابتدائي</td>
                    <td>٥</td>
                    <td>٥</td>
                    <td>٤ أكتوبر</td>
                  </tr>
                  <tr>
                    <td>سعود محمد الدوسري</td>
                    <td>الأول المتوسط</td>
                    <td>٤</td>
                    <td>٣</td>
                    <td>٣ أكتوبر</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <x-dashboard.no-results />
          </section>
        </div>

@endsection
