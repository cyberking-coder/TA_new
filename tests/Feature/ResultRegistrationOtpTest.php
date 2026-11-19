<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ResultRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultRegistrationOtpTest extends TestCase
{
    use RefreshDatabase;

    private Exam $tac;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tac = Exam::create(['name' => 'TAC', 'slug' => 'tac']);
    }

    /**
     * OTP gate was removed per product decision. The registration endpoint
     * now accepts submissions directly without a verified mobile step, and
     * mobile_verified_at is left null so a future OTP reintroduction can
     * still distinguish verified rows.
     */
    public function test_result_registration_saves_without_otp(): void
    {
        $this->post('/result/register', [
            'audience'    => 'officer',
            'rr_name'     => 'Ritesh Candidate',
            'rr_mobile'   => '9876543210',
            'rr_email'    => 'r@example.test',
            'rr_exam_id'  => $this->tac->id,
            'rr_roll'     => '2026TAC001',
        ])->assertRedirect('/alerts/officer');

        $this->assertSame(1, ResultRegistration::count());
        $this->assertNull(ResultRegistration::first()->mobile_verified_at);
    }

    public function test_result_registration_dedupes_on_exam_and_roll(): void
    {
        $this->post('/result/register', [
            'audience'   => 'officer',
            'rr_name'    => 'First Entry',
            'rr_mobile'  => '9876543210',
            'rr_email'   => 'first@example.test',
            'rr_exam_id' => $this->tac->id,
            'rr_roll'    => '2026TAC001',
        ])->assertRedirect('/alerts/officer');

        // Repeat with same (exam_id, roll_number) → updates the row rather
        // than creating a second one.
        $this->post('/result/register', [
            'audience'   => 'officer',
            'rr_name'    => 'Updated Name',
            'rr_mobile'  => '9000000000',
            'rr_email'   => 'updated@example.test',
            'rr_exam_id' => $this->tac->id,
            'rr_roll'    => '2026TAC001',
        ])->assertRedirect('/alerts/officer');

        $this->assertSame(1, ResultRegistration::count());
        $row = ResultRegistration::first();
        $this->assertSame('Updated Name', $row->name);
        $this->assertSame('updated@example.test', $row->email);
    }
}
