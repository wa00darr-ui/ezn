<?php

namespace Tests\Feature;

use App\Models\PermissionReason;
use App\Models\PermissionRequest;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PermissionRequestTest extends TestCase
{
    use RefreshDatabase;

    private function payload(): array
    {
        Storage::fake('local');
        Student::create([
            'name' => 'طالب اختبار', 'national_id' => '1234567890', 'student_number' => '1',
            'grade' => 'الأول', 'classroom' => 'أ', 'guardian_name' => 'ولي الأمر',
            'guardian_phone' => '0501234567', 'status' => 'active',
        ]);
        PermissionReason::create(['name' => 'موعد طبي', 'is_active' => true, 'sort_order' => 1]);
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII=');

        return [
            'studentName' => 'طالب اختبار', 'studentId' => '1234567890', 'grade' => 'الأول',
            'classroom' => 'أ', 'guardianName' => 'ولي الأمر', 'guardianId' => '1234567891',
            'phone' => '0501234567', 'relation' => 'الأب', 'reason' => 'موعد طبي',
            'identity' => UploadedFile::fake()->createWithContent('identity.png', $png),
            'signature' => 'data:image/png;base64,'.base64_encode($png), 'declaration' => 'on',
        ];
    }

    public function test_valid_request_is_saved_pending_with_private_attachments(): void
    {
        $payload = $this->payload();
        $payload['status'] = 'approved';
        $payload['reviewed_by'] = 999;
        $this->post('/request', $payload)->assertRedirect(route('website.request.success'))
            ->assertSessionHasNoErrors();

        $record = PermissionRequest::sole();
        $this->assertSame('pending', $record->status);
        $this->assertNull($record->reviewed_by);
        $this->assertSame('ولي الأمر', $record->parent_name);
        $this->assertSame(Student::sole()->id, $record->student_id);
        Storage::disk('local')->assertExists([$record->identity_path, $record->signature_path]);
        $this->assertSame($record->id, session('submitted_request_number'));
        $this->get('/request/success')->assertOk()
            ->assertSee('تم إرسال طلب استئذان برقم')
            ->assertViewHas('requestNumber', $record->id)
            ->assertSee(route('website.tracking.index'));
        $this->get('/request/success')->assertOk()->assertViewHas('requestNumber', $record->id);
        $this->assertDatabaseCount('permission_requests', 1);
    }

    public function test_missing_fields_return_errors_without_saving(): void
    {
        $this->from('/request')->post('/request', [])->assertRedirect('/request')
            ->assertSessionHasErrors(['studentName', 'guardianName', 'identity', 'signature', 'declaration']);
        $this->assertDatabaseCount('permission_requests', 0);
    }

    public function test_mismatched_student_does_not_save_files_or_request(): void
    {
        $payload = $this->payload();
        $payload['studentName'] = 'اسم غير مطابق';
        $this->from('/request')->post('/request', $payload)->assertSessionHasErrors('studentId');
        $this->assertDatabaseCount('permission_requests', 0);
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $payload = $this->payload();
        $payload['signature'] = 'data:image/png;base64,'.base64_encode('not an image');
        $this->post('/request', $payload)->assertSessionHasErrors('signature');
        $this->assertDatabaseCount('permission_requests', 0);
    }

    public function test_inactive_reason_is_rejected(): void
    {
        $payload = $this->payload();
        PermissionReason::query()->update(['is_active' => false]);
        $this->post('/request', $payload)->assertSessionHasErrors('reason');
        $this->assertDatabaseCount('permission_requests', 0);
    }

    public function test_other_reason_requires_description_and_saves_it(): void
    {
        $payload = $this->payload();
        PermissionReason::create(['name' => 'أخرى', 'is_active' => true, 'sort_order' => 2]);
        $payload['reason'] = 'أخرى';
        $this->post('/request', $payload)->assertSessionHasErrors('otherReason');
        $payload['otherReason'] = 'ظرف عائلي';
        $this->post('/request', $payload)->assertSessionHasNoErrors();
        $this->assertSame('ظرف عائلي', PermissionRequest::sole()->reason);
    }

    public function test_confirmation_requires_a_submitted_request_in_the_current_session(): void
    {
        $this->get('/request/success?requestNumber=999')->assertRedirect(route('website.request.create'));
    }
}
