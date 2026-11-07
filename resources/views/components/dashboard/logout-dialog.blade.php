
<x-dashboard.modal id="logout-dialog" title="تسجيل الخروج">
    <p>هل ترغب في إنهاء جلسة العرض والعودة لصفحة الدخول؟</p>

    <div class="dialog-actions">
        <button type="button" class="btn secondary" data-close>
            إلغاء
        </button>

        <form method="POST" action="{{ route('dashboard.logout') }}">
            @csrf
            <button type="submit" class="btn">
                تسجيل الخروج
            </button>
        </form>
    </div>
</x-dashboard.modal>
