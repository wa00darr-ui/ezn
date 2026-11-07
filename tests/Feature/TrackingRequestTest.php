<?php

namespace Tests\Feature;

use App\Models\PermissionRequest;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingRequestTest extends TestCase
{
    use RefreshDatabase;

    private function record(string $number = '00123', string $phone = '0501234567'): PermissionRequest
    {
        $student = Student::create([
            'name' => 'طالب '.$number, 'student_number' => $number, 'national_id' => '1'.$number,
            'grade' => 'الأول', 'classroom' => 'أ', 'guardian_name' => 'ولي الأمر',
            'guardian_phone' => $phone, 'status' => 'active',
        ]);

        return PermissionRequest::create([
            'student_id' => $student->id, 'parent_name' => 'ولي الأمر', 'parent_phone' => $phone,
            'status' => 'pending', 'requested_at' => '2026-11-07 10:00:00',
            'reason' => 'سبب خاص', 'guardian_id' => '1234567890',
            'signature_path' => 'private-signature.png', 'identity_path' => 'private-identity.png',
            'pickup_code_digest' => 'private-digest-'.$number,
        ]);
    }

    public function test_page_has_one_lookup_input_without_demo_results(): void
    {
        $this->record();
        $this->get('/tracking')->assertOk()->assertSee('name="identifier"', false)
            ->assertDontSee('طالب 00123')->assertDontSee('AF-2026-0482')
            ->assertDontSee('محاكاة حالات الطلب');
    }

    public function test_student_number_finds_only_matching_requests_and_keeps_leading_zeros(): void
    {
        $record = $this->record();
        $this->record('00999', '0509999999');
        $this->post('/tracking', ['identifier' => '00123'])->assertOk()
            ->assertSee('طالب 00123')->assertDontSee('طالب 00999')
            ->assertSee('قيد المراجعة')
            ->assertViewHas('requests', fn ($records) => $records->modelKeys() === [$record->id]);
        $this->post('/tracking', ['identifier' => '123'])->assertSee('لم نعثر على طلب مطابق');
    }

    public function test_phone_returns_requests_for_all_matching_students_newest_first(): void
    {
        $first = $this->record();
        $second = $this->record('00456');
        $this->record('00999', '0509999999');
        $this->post('/tracking', ['identifier' => '0501234567'])->assertOk()
            ->assertViewHas('requests', fn ($records) => $records->modelKeys() === [$second->id, $first->id]);
    }

    public function test_arabic_and_persian_digits_are_normalized(): void
    {
        $this->record();
        foreach ([' ٠٠١٢٣ ', '۰۰۱۲۳', '٠٥٠١٢٣٤٥٦٧'] as $identifier) {
            $this->post('/tracking', compact('identifier'))->assertOk()->assertSee('طالب 00123');
        }
    }

    public function test_unknown_identifier_has_clear_empty_state(): void
    {
        $this->record();
        $this->post('/tracking', ['identifier' => '999'])->assertOk()
            ->assertSee('لم نعثر على طلب مطابق')->assertDontSee('طالب 00123');
    }

    public function test_invalid_identifiers_are_rejected(): void
    {
        foreach (['', 'lookup-submit', '1 OR 1=1', ['00123'], str_repeat('1', 51)] as $identifier) {
            $this->from('/tracking')->post('/tracking', compact('identifier'))
                ->assertRedirect('/tracking')->assertSessionHasErrors('identifier');
        }
    }

    public function test_live_status_changes_appear_without_exposing_private_data(): void
    {
        $record = $this->record();
        foreach (['approved' => 'تمت الموافقة', 'rejected' => 'تم رفض الطلب',
            'completed' => 'تم تسليم الطالب', 'cancelled' => 'تم إلغاء الطلب'] as $status => $label) {
            $record->update(['status' => $status]);
            $this->post('/tracking', ['identifier' => '00123'])->assertOk()->assertSee($label)
                ->assertDontSee('سبب خاص')->assertDontSee('private-signature.png')
                ->assertDontSee('private-identity.png')->assertDontSee('private-digest')
                ->assertDontSee('1234567890')->assertDontSee('482915');
        }
    }

    public function test_lookup_is_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 10; $attempt++) {
            $this->post('/tracking', ['identifier' => '999'])->assertOk();
        }
        $this->post('/tracking', ['identifier' => '999'])->assertStatus(429);
    }

    public function test_only_twenty_latest_results_are_displayed(): void
    {
        $first = $this->record();
        for ($index = 0; $index < 20; $index++) {
            PermissionRequest::create([
                'student_id' => $first->student_id, 'parent_name' => 'ولي الأمر',
                'parent_phone' => '0501234567', 'status' => 'pending',
            ]);
        }
        $this->post('/tracking', ['identifier' => '00123'])->assertOk()
            ->assertViewHas('requests', fn ($records) => $records->count() === 20 && ! $records->contains('id', $first->id));
    }

    public function test_each_request_renders_only_its_actual_state_template(): void
    {
        $record = $this->record();
        foreach (['pending', 'approved', 'rejected', 'completed'] as $status) {
            $record->update(['status' => $status]);
            $response = $this->post('/tracking', ['identifier' => '00123'])->assertOk()
                ->assertSee('data-request-state="'.$status.'"', false)
                ->assertDontSee('AF-2026-0482')->assertDontSee('pickup-qr.svg')
                ->assertDontSee('واتساب')->assertDontSee('482915');
            foreach (array_diff(['pending', 'approved', 'rejected', 'completed'], [$status]) as $other) {
                $response->assertDontSee('data-request-state="'.$other.'"', false);
            }
        }
    }

    public function test_rejected_request_displays_escaped_actual_rejection_reason(): void
    {
        $record = $this->record();
        $record->update(['status' => 'rejected', 'rejection_reason' => '<script>alert(1)</script>موعد الاختبار']);
        $this->post('/tracking', ['identifier' => '00123'])->assertOk()
            ->assertSee('موعد الاختبار')->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
        $record->update(['rejection_reason' => null]);
        $this->post('/tracking', ['identifier' => '00123'])->assertOk()
            ->assertSee('لم يتم تسجيل سبب للرفض');
    }

    public function test_completed_request_displays_actual_review_and_checkout_times(): void
    {
        $record = $this->record();
        $record->update([
            'status' => 'completed', 'reviewed_at' => '2026-11-07 10:30:00',
            'checked_out_at' => '2026-11-07 11:15:00',
        ]);
        $this->post('/tracking', ['identifier' => '00123'])->assertOk()
            ->assertSee('2026/11/07 13:30')->assertSee('2026/11/07 14:15')
            ->assertSee('تاريخ ووقت تسليم الطالب');
        $record->update(['reviewed_at' => null, 'checked_out_at' => null]);
        $this->post('/tracking', ['identifier' => '00123'])->assertOk()->assertSee('غير مسجل');
    }

    public function test_multiple_statuses_render_their_own_request_data(): void
    {
        $pending = $this->record();
        $approved = $this->record('00456');
        $approved->update(['status' => 'approved']);
        $this->post('/tracking', ['identifier' => '0501234567'])->assertOk()
            ->assertSeeInOrder(['data-request-state="approved"', 'طالب 00456', 'data-request-state="pending"', 'طالب 00123'], false);
    }
}
