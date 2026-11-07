<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $school = SchoolSetting::query()->find(1);

        return view('website.contact', compact('school'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],

            'phone' => [
                'required',
                'regex:/^05[0-9]{8}$/',
            ],

            'subject' => ['required', 'string', 'max:120'],

            'message' => ['required', 'string', 'max:2000'],
        ], [
            'name.required' => 'يرجى إدخال الاسم بالكامل.',
            'phone.required' => 'يرجى إدخال رقم الجوال.',
            'phone.regex' => 'يجب أن يبدأ الجوال بـ 05 ويتكون من 10 أرقام.',
            'subject.required' => 'يرجى إدخال موضوع الرسالة.',
            'message.required' => 'يرجى كتابة نص الرسالة.',
        ]);

        ContactMessage::create([
            ...$data,
            'status' => 'new',
        ]);

        return redirect()
            ->route('website.contact')
            ->with(
                'success',
                'تم استلام رسالتك وحفظها بنجاح. ستراجعها المدرسة.'
            );
    }
}