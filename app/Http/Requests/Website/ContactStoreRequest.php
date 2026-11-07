<?php

namespace App\Http\Requests\Website;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],

            'phone' => [
                'required',
                'regex:/^05[0-9]{8}$/',
            ],

            'subject' => ['required', 'string', 'max:120'],

            'message' => ['required', 'string', 'max:2000'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'يرجى إدخال الاسم بالكامل.',
            'phone.required' => 'يرجى إدخال رقم الجوال.',
            'name.string' => 'يجب أن يكون الاسم نص.',
            'name.max' => 'يجب أن يكون الاسم أقل من 100 حرف.',
            'phone.regex' => 'يجب أن يبدأ الجوال بـ 05 ويتكون من 10 أرقام.',
            'phone.max' => 'يجب أن يكون رقم الجوال أقل من 10 أرقام.',
            'subject.string' => 'يجب أن يكون موضوع الرسالة نص.',
            'subject.max' => 'يجب أن يكون موضوع الرسالة أقل من 120 حرف.',
            'subject.required' => 'يرجى إدخال موضوع الرسالة.',
            'message.required' => 'يرجى كتابة نص الرسالة.',
            'message.string' => 'يجب أن يكون نص الرسالة نص.',
            'message.max' => 'يجب أن يكون نص الرسالة أقل من 2000 حرف.',

        ];
    }
}
