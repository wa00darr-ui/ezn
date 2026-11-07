<?php

namespace App\Services\Website;
use App\Models\ContactMessage;
use App\Models\SchoolSetting;

class ContactMessageService
{
    public function index()
    {
        $school = SchoolSetting::query()->find(1);
        return $school;
    }
    
    public function store(array $data)
    {
        $contactMessage = ContactMessage::create([
            ...$data,
            'status' => 'new',
        ]);

        return $contactMessage;
    }
}