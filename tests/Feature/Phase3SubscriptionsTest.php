<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\OfficerSubscriber;
use App\Models\ResultRegistration;
use App\Models\SoldierSubscriber;
use App\Models\Suppression;
use App\Services\Subscriptions\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class Phase3SubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    private Exam $tac;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tac = Exam::create(['name' => 'Territorial Army Commission (TAC)', 'slug' => 'tac']);
    }

    public function test_deduplicates_repeat_subscription_within_same_audience(): void
    {
        Mail::fake();
        $svc = app(SubscriptionService::class);

        $svc->subscribe('officer', 'user@example.test', 'Ada', '555-0001');
        $svc->subscribe('officer', 'USER@example.test', 'Ada updated', '555-0001');

        $this->assertSame(1, OfficerSubscriber::count());
        $this->assertSame('Ada updated', OfficerSubscriber::first()->name);
    }

    public function test_officer_and_soldier_lists_stay_separate(): void
    {
        Mail::fake();
        $svc = app(SubscriptionService::class);

        $svc->subscribe('officer', 'shared@example.test', 'A', null);
        $svc->subscribe('soldier', 'shared@example.test', 'A', null);

        $this->assertSame(1, OfficerSubscriber::count());
        $this->assertSame(1, SoldierSubscriber::count());

        $svc->unsubscribe('officer', 'shared@example.test');
        $this->assertTrue(Suppression::isSuppressed('officer', 'shared@example.test'));
        $this->assertFalse(Suppression::isSuppressed('soldier', 'shared@example.test'));
    }

    public function test_refuses_to_subscribe_a_suppressed_address(): void
    {
        Mail::fake();
        $svc = app(SubscriptionService::class);
        Suppression::create(['audience' => 'officer', 'email' => 'bounced@example.test', 'reason' => 'bounced']);

        $result = $svc->subscribe('officer', 'bounced@example.test', 'X', null);
        $this->assertSame('suppressed', $result['status']);
        $this->assertSame(0, OfficerSubscriber::count());
    }

    public function test_rate_limits_the_verification_resend(): void
    {
        Mail::fake();
        $svc = app(SubscriptionService::class);

        for ($i = 0; $i < SubscriptionService::RESEND_LIMIT; $i++) {
            $r = $svc->subscribe('officer', 'spammy@example.test', 'X', null);
            $this->assertNotSame('rate_limited', $r['status']);
        }
        $r = $svc->subscribe('officer', 'spammy@example.test', 'X', null);
        $this->assertSame('rate_limited', $r['status']);
    }

    public function test_verifies_a_subscriber_with_a_valid_token_and_refuses_replay(): void
    {
        Mail::fake();
        $svc = app(SubscriptionService::class);
        $svc->subscribe('officer', 'verify@example.test', null, null);

        $sub = OfficerSubscriber::firstWhere('email', 'verify@example.test');
        $raw = $svc->issueVerificationToken($sub, 'verify', null);

        $this->assertSame('verified', $svc->verify($raw)['status']);
        $this->assertNotNull($sub->fresh()->verified_at);
        $this->assertSame('already_used', $svc->verify($raw)['status']);
    }

    public function test_result_registration_deduplicates_on_exam_plus_roll(): void
    {
        ResultRegistration::create([
            'name' => 'Old', 'mobile' => '5550000', 'email' => 'old@example.test',
            'exam_id' => $this->tac->id, 'roll_number' => '2026TAC001',
        ]);

        ResultRegistration::updateOrCreate(
            ['exam_id' => $this->tac->id, 'roll_number' => '2026TAC001'],
            ['name' => 'New', 'mobile' => '5551111', 'email' => 'new@example.test'],
        );

        $this->assertSame(1, ResultRegistration::count());
        $this->assertSame('New', ResultRegistration::first()->name);
    }

    public function test_new_registrations_have_no_centre_or_zone_fields(): void
    {
        $r = ResultRegistration::create([
            'name' => 'Ada', 'mobile' => '5550002', 'email' => 'ada@example.test',
            'exam_id' => $this->tac->id, 'roll_number' => '2026TAC002',
        ]);
        $this->assertNull($r->legacy_zone);
        $keys = array_keys($r->getAttributes());
        $this->assertNotContains('centre_id', $keys);
        $this->assertNotContains('centre', $keys);
    }
}
