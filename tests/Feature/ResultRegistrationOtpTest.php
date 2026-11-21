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
        // Published exam required by the new gating.
        $this->tac = Exam::create([
            'name'   => 'TAC',
            'slug'   => 'tac',
            'status' => Exam::STATUS_PUBLISHED,
        ]);
    }

    /**
     * OTP gate was removed per product decision. The registration endpoint
     * now accepts submissions directly without a verified mobile step.
     */
    public function test_result_registration_saves_without_otp(): void
    {
        $this->post('/result/register', [
            'rr_name'    => 'Ritesh Candidate',
            'rr_mobile'  => '9876543210',
            'rr_email'   => 'r@example.test',
            'rr_exam_id' => $this->tac->id,
            'rr_roll'    => '2026TAC001',
        ])->assertRedirect('/result/register');

        $this->assertSame(1, ResultRegistration::count());
        $this->assertNull(ResultRegistration::first()->mobile_verified_at);
    }

    public function test_result_registration_dedupes_on_exam_and_roll(): void
    {
        $this->post('/result/register', [
            'rr_name'    => 'First Entry',
            'rr_mobile'  => '9876543210',
            'rr_email'   => 'first@example.test',
            'rr_exam_id' => $this->tac->id,
            'rr_roll'    => '2026TAC001',
        ])->assertRedirect('/result/register');

        $this->post('/result/register', [
            'rr_name'    => 'Updated Name',
            'rr_mobile'  => '9000000000',
            'rr_email'   => 'updated@example.test',
            'rr_exam_id' => $this->tac->id,
            'rr_roll'    => '2026TAC001',
        ])->assertRedirect('/result/register');

        $this->assertSame(1, ResultRegistration::count());
        $row = ResultRegistration::first();
        $this->assertSame('Updated Name', $row->name);
        $this->assertSame('updated@example.test', $row->email);
    }
}
