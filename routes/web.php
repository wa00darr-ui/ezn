
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

use App\Models\SchoolSetting;

use App\Http\Controllers\Website\RequestController;
use App\Http\Controllers\Website\ContactController;

use App\Http\Controllers\Dashboard\Auth\LoginController;
use App\Http\Controllers\Dashboard\Auth\LogoutController;

use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\ActivityLogController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\CheckoutController;
use App\Http\Controllers\Dashboard\HistoryController;
use App\Http\Controllers\Dashboard\RequestsController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\StudentController;


/*
|--------------------------------------------------------------------------
| موقع أولياء الأمور
|--------------------------------------------------------------------------
*/

// الصفحة الرئيسية
Route::get('/', function () {

    $school = null;

    if (Schema::hasTable('school_settings')) {
        $school = SchoolSetting::query()->find(1);
    }

    return view('website.home', compact('school'));

})->name('website.home');


// صفحة التعريف بالخدمة
Route::get('/about', function () {
    return view('website.about');
})->name('website.about');


// عرض نموذج طلب الاستئذان
Route::get('/request', [
    RequestController::class,
    'create',
])->name('website.request.create');


// عرض صفحة التواصل
Route::get('/contact', [
    ContactController::class,
    'index',
])->name('website.contact');


// حفظ رسائل التواصل
Route::post('/contact', [
    ContactController::class,
    'store',
])
    ->middleware('throttle:3,10')
    ->name('website.contact.store');


/*
|--------------------------------------------------------------------------
| تسجيل الدخول واستعادة كلمة المرور
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard')
    ->middleware('guest')
    ->group(function () {

        // عرض صفحة تسجيل الدخول
        Route::get('/login', [
            LoginController::class,
            'showLogin',
        ])->name('dashboard.login');


        // معالجة تسجيل الدخول
        Route::post('/login', [
            LoginController::class,
            'login',
        ])
            ->middleware('throttle:5,1')
            ->name('dashboard.login.submit');


        // عرض صفحة نسيت كلمة المرور
        Route::get('/forgot-password', [
            LoginController::class,
            'showForgotPassword',
        ])->name('dashboard.password.request');


        // إرسال رابط إعادة تعيين كلمة المرور
        Route::post('/forgot-password', [
            LoginController::class,
            'sendResetLink',
        ])
            ->middleware('throttle:3,1')
            ->name('dashboard.password.email');


        // عرض نموذج إعادة تعيين كلمة المرور
        Route::get('/reset-password/{token}', [
            LoginController::class,
            'showResetPassword',
        ])->name('password.reset');


        // تنفيذ إعادة تعيين كلمة المرور
        Route::post('/reset-password', [
            LoginController::class,
            'resetPassword',
        ])
            ->middleware('throttle:5,1')
            ->name('dashboard.password.update');

    });


/*
|--------------------------------------------------------------------------
| تسجيل الخروج
|--------------------------------------------------------------------------
*/

Route::post('/dashboard/logout', [
    LogoutController::class,
    'logout',
])
    ->middleware(['auth', 'active.user'])
    ->name('dashboard.logout');


/*
|--------------------------------------------------------------------------
| لوحة التحكم
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware(['auth', 'active.user'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | الصفحة الرئيسية
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            HomeController::class,
            'index',
        ])->name('home');


        /*
        |--------------------------------------------------------------------------
        | سجل النشاط
        |--------------------------------------------------------------------------
        */

        Route::get('/activity-log', [
            ActivityLogController::class,
            'index',
        ])
            ->middleware('can:view-activity-logs')
            ->name('activity.index');


        /*
        |--------------------------------------------------------------------------
        | إدارة الطلاب
        |--------------------------------------------------------------------------
        */

        Route::prefix('students')
            ->name('students.')
            ->middleware('can:students.manage')
            ->group(function () {

                // عرض الطلاب
                Route::get('/', [
                    StudentController::class,
                    'index',
                ])->name('index');


                // إضافة طالب
                Route::post('/', [
                    StudentController::class,
                    'store',
                ])->name('store');


                // استيراد الطلاب
                Route::post('/import', [
                    StudentController::class,
                    'import',
                ])->name('import');


                // عرض تفاصيل طالب
                Route::get('/{student}', [
                    StudentController::class,
                    'show',
                ])
                    ->whereNumber('student')
                    ->name('show');


                // تحديث بيانات طالب
                Route::put('/{student}', [
                    StudentController::class,
                    'update',
                ])
                    ->whereNumber('student')
                    ->name('update');

            });


        /*
        |--------------------------------------------------------------------------
        | إدارة الموظفين والصلاحيات
        |--------------------------------------------------------------------------
        */

        Route::middleware('can:manage-employees')
            ->group(function () {

                // قائمة الموظفين
                Route::get('/employees', [
                    EmployeeController::class,
                    'index',
                ])->name('employees.index');


                // إضافة موظف
                Route::post('/employees', [
                    EmployeeController::class,
                    'store',
                ])->name('employees.store');


                // تحديث صلاحيات الأدوار
                Route::put('/employees/permissions/roles', [
                    EmployeeController::class,
                    'permissions',
                ])->name('employees.permissions');


                // تحديث موظف
                Route::put('/employees/{employee}', [
                    EmployeeController::class,
                    'update',
                ])
                    ->whereNumber('employee')
                    ->name('employees.update');

            });


        /*
        |--------------------------------------------------------------------------
        | طلبات الاستئذان
        |--------------------------------------------------------------------------
        */

        // عرض جميع الطلبات
        Route::get('/requests', [
            RequestsController::class,
            'index',
        ])
            ->middleware('can:requests.view')
            ->name('requests.index');


        // عرض تفاصيل طلب
        Route::get('/requests/{permissionRequest}', [
            RequestsController::class,
            'show',
        ])
            ->whereNumber('permissionRequest')
            ->middleware('can:requests.view')
            ->name('requests.show');


        // الموافقة على طلب
        Route::post('/requests/{permissionRequest}/approve', [
            RequestsController::class,
            'approve',
        ])
            ->whereNumber('permissionRequest')
            ->middleware([
                'can:requests.view',
                'can:requests.approve',
            ])
            ->name('requests.approve');


        // رفض طلب
        Route::post('/requests/{permissionRequest}/reject', [
            RequestsController::class,
            'reject',
        ])
            ->whereNumber('permissionRequest')
            ->middleware([
                'can:requests.view',
                'can:requests.reject',
            ])
            ->name('requests.reject');


        /*
        |--------------------------------------------------------------------------
        | سجل الطلبات
        |--------------------------------------------------------------------------
        */

        Route::get('/history', [
            HistoryController::class,
            'index',
        ])
            ->middleware('can:requests.view')
            ->name('history.index');


        Route::get('/history/export', [
            HistoryController::class,
            'export',
        ])
            ->middleware([
                'can:requests.view',
                'throttle:10,1',
            ])
            ->name('history.export');


        /*
        |--------------------------------------------------------------------------
        | تسليم الطلاب
        |--------------------------------------------------------------------------
        */

        Route::middleware('can:checkout-students')
            ->group(function () {

                // صفحة تسليم الطلاب
                Route::get('/checkout', [
                    CheckoutController::class,
                    'index',
                ])->name('checkout.index');


                // التحقق من رمز الاستلام
                Route::post('/checkout/verify', [
                    CheckoutController::class,
                    'verify',
                ])
                    ->middleware('throttle:5,1')
                    ->name('checkout.verify');


                // تأكيد تسليم الطالب
                Route::post('/checkout/confirm', [
                    CheckoutController::class,
                    'confirm',
                ])->name('checkout.confirm');


                // بدء عملية تسليم جديدة
                Route::post('/checkout/reset', [
                    CheckoutController::class,
                    'reset',
                ])->name('checkout.reset');

            });


        /*
        |--------------------------------------------------------------------------
        | إعدادات المدرسة وأسباب الاستئذان
        |--------------------------------------------------------------------------
        */

        Route::prefix('settings')
            ->name('settings.')
            ->middleware('can:manage-employees')
            ->group(function () {

                // عرض الإعدادات
                Route::get('/', [
                    SettingsController::class,
                    'index',
                ])->name('index');


                // تحديث إعدادات المدرسة
                Route::put('/', [
                    SettingsController::class,
                    'update',
                ])->name('update');


                // إضافة سبب استئذان
                Route::post('/reasons', [
                    SettingsController::class,
                    'storeReason',
                ])->name('reasons.store');


                // تعديل سبب استئذان
                Route::patch('/reasons/{reason}', [
                    SettingsController::class,
                    'updateReason',
                ])
                    ->whereNumber('reason')
                    ->name('reasons.update');


                // تفعيل أو تعطيل سبب استئذان
                Route::post('/reasons/{reason}/toggle', [
                    SettingsController::class,
                    'toggleReason',
                ])
                    ->whereNumber('reason')
                    ->name('reasons.toggle');


                // تغيير ترتيب أسباب الاستئذان
                Route::post('/reasons/{reason}/move', [
                    SettingsController::class,
                    'moveReason',
                ])
                    ->whereNumber('reason')
                    ->name('reasons.move');

            });

    });
