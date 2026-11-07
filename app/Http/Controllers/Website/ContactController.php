<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\Website\ContactStoreRequest;
use App\Services\Website\ContactMessageService;

class ContactController extends Controller
{
    protected $contactMessageService;
    public function __construct(ContactMessageService $contactMessageService)
    {
        $this->contactMessageService = $contactMessageService;
    }


    public function index(): View
    {
        $school = $this->contactMessageService->index();

        return view('website.contact', compact('school'));
    }

    public function store(ContactStoreRequest $request): RedirectResponse
    {
        $this->contactMessageService->store($request->validated());

        return redirect()
            ->route('website.contact')
            ->with(
                'success',
                'تم استلام رسالتك وحفظها بنجاح. ستراجعها المدرسة.'
            );
    }
}