<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingRequestController extends Controller
{
    public function index(): View
    {
        return view('website.tracking.index');
    }

    public function lookup(Request $request): View
    {
        // توحيد الأرقام العربية والإنجليزية مع الحفاظ على الأصفار في البداية.
        if (is_string($request->input('identifier'))) {
            $request->merge([
                'identifier' => strtr(trim($request->input('identifier')), [
                    '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
                    '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
                    '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
                    '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
                ]),
            ]);
        }

        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:50', 'regex:/^[0-9]+$/'],
        ], [
            'identifier.required' => 'أدخل الرقم الدراسي للطالب أو رقم جوال ولي الأمر.',
            'identifier.string' => 'أدخل رقمًا صحيحًا.',
            'identifier.max' => 'يجب ألا يتجاوز الرقم 50 خانة.',
            'identifier.regex' => 'استخدم الأرقام فقط في الرقم الدراسي أو رقم الجوال.',
        ]);

        $identifier = $data['identifier'];

        // البحث برقم الجوال المسجل في الطلب أو الرقم الدراسي المرتبط به.
        $requests = PermissionRequest::query()
            ->select(['id', 'student_id', 'status', 'requested_at', 'created_at', 'reviewed_at', 'checked_out_at', 'rejection_reason'])
            ->with('student:id,name')
            ->where(function ($query) use ($identifier) {
                $query->where('parent_phone', $identifier)
                    ->orWhereHas('student', function ($query) use ($identifier) {
                        $query->where('student_number', $identifier);
                    });
            })
            ->latest('id')
            ->limit(20)
            ->get();

        return view('website.tracking.index', compact('requests', 'identifier'));
    }
}
