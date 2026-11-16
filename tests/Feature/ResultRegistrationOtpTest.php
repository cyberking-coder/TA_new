<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\MobileOtp;
use App\Models\ResultRegistration;
use App\Services\Sms\LogSmsProvider;
use App\Services\Sms\OtpService;
use App\Services\Sms\SmsProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultRegistrationOtpTest extends TestCase
{
    use RefreshDatabase;

    private Exam $tac;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->bind(SmsProvider::class, LogSmsProvider::class);
        $this->tac = Exam::create(['name' => 'TAC', 'slug' => 'tac']);
    }

    public function test_result_registration_refused_without_verified_mobile(): void
    {
        $this->post('/result/register', [
            'audience'    => 'officer',
            'rr_name'     => 'Ritesh Candidate',
            'rr_mobile'   => '9876543210',
            'rr_email'    => 'r@example.test',
            'rr_exam_id'  => $this->tac->id,
            'rr_roll'     => '2026TAC001',
        ])->assertSessionHasErrors('rr_mobile');

        $this->assertSame(0, ResultRegistration::count());
    }

    public function test_result_registration_saved_after_verified_otp(): void
    {
        $svc = app(OtpService::class);
        $svc->send('result', '9876543210');
        // Fix the code so the test knows it.
        MobileOtp::query()->update(['code_hash' => hash('sha256', '123456')]);
        $this->assertSame('verified', $svc->verify('result', '9876543210', '123456')['status']);

        $this->post('/result/register', [
            'audience'    => 'officer',
            'rr_name'     => 'Ritesh Candidate',
            'rr_mobile'   => '9876543210',
            'rr_email'    => 'r@example.test',
            'rr_exam_id'  => $this->tac->id,
            'rr_roll'     => '2026TAC001',
        ])->assertRedirect('/alerts/officer');

        $this->assertSame(1, ResultRegistration::count());
        $this->assertNotNull(ResultRegistration::first()->mobile_verified_at);
    }
}
