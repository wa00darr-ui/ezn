<?php

return [
    'roles' => [
        'admin' => 'مدير النظام',
        'approver' => 'وكيلة / مسؤولة الموافقات',
        'gate' => 'موظف البوابة',
    ],

    'permissions' => [
        'requests.view' => 'عرض الطلبات',
        'requests.approve' => 'الموافقة على الطلبات',
        'requests.reject' => 'رفض الطلبات',
        'checkout-students' => 'تأكيد خروج الطالب',
        'students.manage' => 'إدارة الطلاب',
        'manage-employees' => 'إدارة الموظفين',
        'reports.view' => 'عرض التقارير',
        'view-activity-logs' => 'عرض سجل النشاط',
        'website.manage' => 'إدارة الموقع',
    ],
];
