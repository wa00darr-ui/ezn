<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use App\Models\PermissionReason;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = SchoolSetting::current();

        $reasons = PermissionReason::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('dashboard.settings.index', compact(
            'settings',
            'reasons'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ministerial_number' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],

            'logo' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:2048',
            ],

            'open_time' => ['required', 'date_format:H:i'],
            'close_time' => [
                'required',
                'date_format:H:i',
                'after:open_time',
            ],

            'code_validity_minutes' => [
                'required',
                'integer',
                'between:5,120',
            ],

            'daily_limit' => [
                'required',
                'integer',
                'between:1,20',
            ],

            'require_identity_image' => ['required', 'boolean'],
            'require_signature' => ['required', 'boolean'],
            'allow_requests' => ['required', 'boolean'],
            'notify_staff' => ['required', 'boolean'],
            'notify_decision' => ['required', 'boolean'],
            'notify_checkout' => ['required', 'boolean'],
        ]);

        $settings = SchoolSetting::current();

        $logo = $request->file('logo');

        unset($data['logo']);

        $oldLogo = $settings->logo_path;
        $newLogo = null;

        if ($logo) {
            $newLogo = $logo->store('school-logos', 'public');
            $data['logo_path'] = $newLogo;
        }

        try {
            $settings->update($data);
        } catch (\Throwable $exception) {
            if ($newLogo) {
                Storage::disk('public')->delete($newLogo);
            }

            throw $exception;
        }

        if ($newLogo && $oldLogo) {
            Storage::disk('public')->delete($oldLogo);
        }

        return redirect()
            ->route('dashboard.settings.index')
            ->with('success', 'تم حفظ إعدادات المدرسة بنجاح.');
    }

    public function storeReason(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('permission_reasons', 'name'),
            ],
        ]);

        $nextOrder = (int) PermissionReason::query()
            ->max('sort_order') + 1;

        PermissionReason::create([
            'name' => $data['name'],
            'is_active' => true,
            'sort_order' => $nextOrder,
        ]);

        return back()->with(
            'success',
            'تمت إضافة سبب الاستئذان.'
        );
    }

    public function updateReason(
        Request $request,
        PermissionReason $reason
    ): RedirectResponse {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('permission_reasons', 'name')
                    ->ignore($reason->id),
            ],
        ]);

        $reason->update($data);

        return back()->with(
            'success',
            'تم تعديل سبب الاستئذان.'
        );
    }

    public function toggleReason(
        PermissionReason $reason
    ): RedirectResponse {
        $reason->update([
            'is_active' => !$reason->is_active,
        ]);

        return back()->with(
            'success',
            'تم تحديث حالة السبب.'
        );
    }

    public function moveReason(
        Request $request,
        PermissionReason $reason
    ): RedirectResponse {
        $data = $request->validate([
            'direction' => ['required', Rule::in(['up', 'down'])],
        ]);

        DB::transaction(function () use ($reason, $data) {
            $rows = PermissionReason::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $index = $rows->search(
                fn ($row) => $row->id === $reason->id
            );

            if ($index === false) {
                return;
            }

            $otherIndex = $data['direction'] === 'up'
                ? $index - 1
                : $index + 1;

            if (!isset($rows[$otherIndex])) {
                return;
            }

            $ids = $rows->pluck('id')->all();

            [$ids[$index], $ids[$otherIndex]] = [
                $ids[$otherIndex],
                $ids[$index],
            ];

            foreach ($ids as $position => $id) {
                PermissionReason::whereKey($id)->update([
                    'sort_order' => $position + 1,
                ]);
            }
        });

        return back()->with(
            'success',
            'تم تحديث ترتيب أسباب الاستئذان.'
        );
    }
}


