<?php

namespace Tests\Feature;

use App\Models\MobileOtp;
use App\Models\OfficerSubscriber;
use App\Services\Sms\LogSmsProvider;
use App\Services\Sms\OtpService;
use App\Services\Sms\SmsProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OtpServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->bind(SmsProvider::class, LogSmsProvider::class);
    }

    public function test_send_stores_hashed_code_and_dispatches(): void
    {
        $r = app(OtpService::class)->send('officer', '9876543210');
        $this->assertSame('sent', $r['status']);
        $this->assertSame(1, MobileOtp::count());
        $otp = MobileOtp::first();
        $this->assertSame('officer', $otp->audience);
        $this->assertSame('9876543210', $otp->mobile);
        $this->assertSame(64, strlen($otp->code_hash)); // sha256 hex
    }

    public function test_send_rate_limited_after_three_per_hour(): void
    {
        $svc = app(OtpService::class);
        for ($i = 0; $i < 3; $i++) {
            $this->assertSame('sent', $svc->send('officer', '9876543210')['status']);
        }
        $this->assertSame('rate_limited', $svc->send('officer', '9876543210')['status']);
    }

    public function test_verify_marks_subscriber_mobile_verified(): void
    {
        OfficerSubscriber::create([
            'email' => 'a@example.test', 'mobile' => '9876543210',
            'consent_at' => now(), 'verified_at' => now(),
        ]);
        $svc = app(OtpService::class);
        $svc->send('officer', '9876543210');
        $otp = MobileOtp::first();
        // Issue a fresh known code by rewriting the hash.
        $otp->update(['code_hash' => hash('sha256', '123456')]);

        $r = $svc->verify('officer', '9876543210', '123456');
        $this->assertSame('verified', $r['status']);
        $this->assertNotNull(OfficerSubscriber::first()->mobile_verified_at);
    }

    public function test_verify_rejects_wrong_code_and_counts_attempts(): void
    {
        $svc = app(OtpService::class);
        $svc->send('officer', '9876543210');
        $this->assertSame('invalid', $svc->verify('officer', '9876543210', '000000')['status']);
        $this->assertSame(1, MobileOtp::first()->attempts);
    }

    public function test_verify_locks_after_five_wrong_attempts(): void
    {
        $svc = app(OtpService::class);
        $svc->send('officer', '9876543210');
        for ($i = 0; $i < 5; $i++) $svc->verify('officer', '9876543210', '000000');
        $this->assertSame('too_many_attempts', $svc->verify('officer', '9876543210', '000000')['status']);
    }

    public function test_new_send_invalidates_previous_code(): void
    {
        $svc = app(OtpService::class);
        $svc->send('officer', '9876543210');
        MobileOtp::first()->update(['code_hash' => hash('sha256', '111111')]);
        $svc->send('officer', '9876543210');
        $this->assertSame('invalid', $svc->verify('officer', '9876543210', '111111')['status']);
    }
}
