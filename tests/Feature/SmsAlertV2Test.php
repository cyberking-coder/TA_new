<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Exam;
use App\Models\OfficerSubscriber;
use App\Models\ResultRegistration;
use App\Models\SoldierSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmsAlertV2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        AppSetting::set('otp_enabled', false);
        AppSetting::set('result_notification_enabled', true);
    }

    public function test_subscribe_prefixes_91_and_canonicalises(): void
    {
        $this->post('/alerts/subscribe', [
            'audience'   => 'gd',
            'first_name' => 'Rahul',
            'last_name'  => 'Singh',
            'mobile'     => '9876543210',
            'email'      => 'r@example.test',
        ])->assertRedirect('/alerts/gd');

        $row = SoldierSubscriber::firstWhere('mobile_canon', '+919876543210');
        $this->assertNotNull($row);
        $this->assertSame('Rahul',  $row->first_name);
        $this->assertSame('Singh',  $row->last_name);
    }

    public function test_same_mobile_allowed_in_gd_and_officer_independently(): void
    {
        $this->post('/alerts/subscribe', ['audience' => 'gd',      'first_name' => 'Rahul', 'mobile' => '9876543210', 'email' => 'r1@example.test']);
        $this->post('/alerts/subscribe', ['audience' => 'officer', 'first_name' => 'Rahul', 'mobile' => '9876543210', 'email' => 'r2@example.test']);

        $this->assertSame(1, SoldierSubscriber::count());
        $this->assertSame(1, OfficerSubscriber::count());
    }

    public function test_duplicate_mobile_in_same_audience_updates_not_duplicates(): void
    {
        $this->post('/alerts/subscribe', ['audience' => 'gd', 'first_name' => 'Rahul',   'mobile' => '9876543210', 'email' => 'first@example.test']);
        $this->post('/alerts/subscribe', ['audience' => 'gd', 'first_name' => 'Rahul2',  'mobile' => '9876543210', 'email' => 'second@example.test']);

        $this->assertSame(1, SoldierSubscriber::count());
        $this->assertSame('Rahul2', SoldierSubscriber::first()->first_name);
    }

    public function test_unsubscribe_requires_only_mobile_and_preserves_record(): void
    {
        $this->post('/alerts/subscribe', ['audience' => 'gd', 'first_name' => 'Rahul', 'mobile' => '9876543210', 'email' => 'r@example.test']);

        $this->post('/alerts/unsubscribe', [
            'audience' => 'gd',
            'mobile'   => '9876543210',
        ])->assertRedirect('/alerts/gd');

        $row = SoldierSubscriber::first();
        $this->assertNotNull($row->unsubscribed_at);
        $this->assertSame(1, SoldierSubscriber::count());
    }

    public function test_invalid_mobile_rejected(): void
    {
        $this->post('/alerts/subscribe', [
            'audience'   => 'gd',
            'first_name' => 'Rahul',
            'mobile'     => '12345',
            'email'      => 'r@example.test',
        ])->assertSessionHasErrors('mobile');

        $this->assertSame(0, SoldierSubscriber::count());
    }

    public function test_unpublished_exam_cannot_be_registered(): void
    {
        $exam = Exam::create(['name' => 'TAC', 'slug' => 'tac', 'status' => Exam::STATUS_DRAFT]);

        $this->post('/result/register', [
            'rr_exam_id' => $exam->id,
            'rr_roll'    => '001',
            'rr_name'    => 'Ritesh',
            'rr_mobile'  => '9876543210',
            'rr_email'   => 'r@example.test',
        ])->assertSessionHasErrors('rr_exam_id');

        $this->assertSame(0, ResultRegistration::count());
    }

    public function test_published_exam_registration_saves_with_normalised_mobile(): void
    {
        $exam = Exam::create(['name' => 'TAC', 'slug' => 'tac', 'status' => Exam::STATUS_PUBLISHED]);

        $this->post('/result/register', [
            'rr_exam_id' => $exam->id,
            'rr_roll'    => '2026TAC001',
            'rr_name'    => 'Ritesh',
            'rr_mobile'  => '+91 98765 43210',
            'rr_email'   => 'r@example.test',
        ])->assertRedirect('/result/register');

        $this->assertSame(1, ResultRegistration::count());
        $this->assertSame('+919876543210', ResultRegistration::first()->mobile);
    }

    public function test_result_form_returns_404_when_disabled(): void
    {
        AppSetting::set('result_notification_enabled', false);

        $this->get('/result/register')->assertNotFound();

        $this->post('/result/register', [
            'rr_exam_id' => 1,
            'rr_roll'    => '001',
            'rr_name'    => 'Ritesh',
            'rr_mobile'  => '9876543210',
            'rr_email'   => 'r@example.test',
        ])->assertNotFound();
    }
}
