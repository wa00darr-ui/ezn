@props(['summary' => 'عرض ١–٦ من ٨ سجلات', 'page' => '١ / ٢'])
<div class="pagination">
              <span data-page-summary>{{ $summary }}</span>
              <div>
                <button type="button" class="btn secondary" data-page-prev>
                  السابق</button
                ><span data-page-number>١ / ٢</span
                ><button type="button" class="btn secondary" data-page-next>
                  التالي
                </button>
              </div>
            </div>
