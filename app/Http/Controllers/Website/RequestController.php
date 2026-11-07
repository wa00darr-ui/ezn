<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\PermissionReason;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function create(): View
    {
        $reasons = PermissionReason::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('website.request.index', compact('reasons'));
    }
}
