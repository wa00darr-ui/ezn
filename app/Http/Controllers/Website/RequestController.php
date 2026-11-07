<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\PermissionReason;
use App\Models\PermissionRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

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

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'studentName' => ['required', 'string', 'max:255'],
            'studentId' => ['required', 'regex:/^[12][0-9]{9}$/'],
            'grade' => ['required', 'string', 'max:100'],
            'classroom' => ['required', 'string', 'max:30'],
            'guardianName' => ['required', 'string', 'max:150'],
            'guardianId' => ['required', 'regex:/^[12][0-9]{9}$/'],
            'phone' => ['required', 'regex:/^05[0-9]{8}$/'],
            'relation' => ['required', Rule::in(['الأب', 'الأم', 'الأخ', 'الأخت', 'ولي شرعي'])],
            'reason' => ['required', 'string', 'max:255'],
            'otherReason' => ['required_if:reason,أخرى', 'nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
            'identity' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'signature' => ['bail', 'required', 'string', 'max:2097152', function ($attribute, $value, $fail) {
                $prefix = 'data:image/png;base64,';
                $bytes = str_starts_with($value, $prefix) ? base64_decode(substr($value, strlen($prefix)), true) : false;
                $image = $bytes === false ? false : @getimagesizefromstring($bytes);
                if (! $image || $image[2] !== IMAGETYPE_PNG) {
                    $fail('أضف توقيعًا صحيحًا قبل المتابعة.');
                }
            }],
            'declaration' => ['accepted'],
        ], [
            'required' => 'حقل :attribute مطلوب.',
            'required_if' => 'حقل :attribute مطلوب.',
            'string' => 'يجب أن يكون :attribute نصًا.',
            'max' => 'تجاوز حقل :attribute الحد الأقصى المسموح.',
            'regex' => 'صيغة :attribute غير صحيحة.',
            'in' => 'القيمة المحددة في :attribute غير صحيحة.',
            'image' => 'يرجى إرفاق صورة هوية صحيحة.',
            'mimes' => 'صورة الهوية يجب أن تكون JPG أو PNG أو WebP.',
            'accepted' => 'يجب الموافقة على الإقرار للمتابعة.',
        ], ['studentName' => 'اسم الطالب', 'studentId' => 'هوية الطالب', 'grade' => 'الصف',
            'classroom' => 'الفصل', 'guardianName' => 'اسم ولي الأمر', 'guardianId' => 'هوية ولي الأمر',
            'phone' => 'الجوال', 'relation' => 'صلة القرابة', 'reason' => 'سبب الاستئذان',
            'otherReason' => 'السبب الآخر', 'notes' => 'الملاحظات', 'identity' => 'صورة الهوية',
            'signature' => 'التوقيع', 'declaration' => 'الإقرار']);

        $student = Student::query()->where('national_id', $data['studentId'])->where('status', 'active')->first();
        if (! $student) {
            throw ValidationException::withMessages(['studentId' => 'تعذّر مطابقة بيانات الطالب. تأكد من الاسم ورقم الهوية والصف والفصل.']);
        }

        if (! PermissionReason::query()->where('name', $data['reason'])->where('is_active', true)->exists()) {
            throw ValidationException::withMessages(['reason' => 'يرجى اختيار سبب استئذان متاح.']);
        }

        $disk = Storage::disk('local');
        $identityPath = null;
        $signaturePath = 'permission-requests/signatures/'.Str::uuid().'.png';

        try {
            $identityPath = $data['identity']->store('permission-requests/identities', 'local');
            if (! $identityPath || ! $disk->put($signaturePath, base64_decode(substr($data['signature'], strlen('data:image/png;base64,')), true))) {
                throw new RuntimeException('Unable to store permission request attachments.');
            }

            $permissionRequest = PermissionRequest::query()->create([
                'student_id' => $student->id,
                'parent_name' => $data['guardianName'],
                'parent_phone' => $data['phone'],
                'guardian_id' => $data['guardianId'],
                'guardian_relation' => $data['relation'],
                'reason' => $data['reason'] === 'أخرى' ? ($data['otherReason'] ?? null) : $data['reason'],
                'notes' => $data['notes'] ?? null,
                'identity_path' => $identityPath,
                'signature_path' => $signaturePath,
                'status' => 'pending',
                'requested_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $disk->delete(array_filter([$identityPath, $signaturePath]));
            throw $exception;
        }

        $request->session()->put('submitted_request_number', $permissionRequest->id);

        return redirect()->route('website.request.success');
    }

    public function success(Request $request): View|RedirectResponse
    {
        $requestNumber = $request->session()->get('submitted_request_number');

        if (! $requestNumber) {
            return redirect()->route('website.request.create');
        }

        return view('website.request.success', compact('requestNumber'));
    }
}
