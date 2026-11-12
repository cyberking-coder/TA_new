<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\OfficerSubscriber;
use App\Models\Order;
use App\Models\ResultRegistration;
use App\Models\SoldierSubscriber;
use App\Models\Suppression;
use App\Services\Migration\Importers\NoticeImporter;
use App\Services\Migration\Importers\OrderImporter;
use App\Services\Migration\Importers\ResultRegistrationImporter;
use App\Services\Migration\Importers\SubscriberImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase5MigrationTest extends TestCase
{
    use RefreshDatabase;

    private string $fixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixtures = base_path('database/fixtures/legacy');
        // Need exam records for the fk lookups.
        \App\Models\Exam::create(['name' => 'TAC', 'slug' => 'tac']);
        \App\Models\Exam::create(['name' => 'Officer', 'slug' => 'officer']);
        \App\Models\Exam::create(['name' => 'Soldier', 'slug' => 'soldier']);
    }

    public function test_notice_importer_is_idempotent(): void
    {
        $importer = app(NoticeImporter::class);
        $a = $importer->run($this->fixtures.'/notices.csv', dryRun: false);
        $b = $importer->run($this->fixtures.'/notices.csv', dryRun: false);

        $this->assertSame(4, $a->createdCount);
        $this->assertSame(0, $b->createdCount);
        $this->assertSame(4, $b->updatedCount);
        $this->assertSame(4, Notice::count());
    }

    public function test_notice_importer_flags_unknown_dates(): void
    {
        app(NoticeImporter::class)->run($this->fixtures.'/notices.csv', dryRun: false);
        // L-N-102 has published_on=0000-00-00 in the fixture.
        $n = Notice::where('legacy_id', 'L-N-102')->first();
        $this->assertNotNull($n);
        $this->assertTrue((bool) $n->import_date_unknown);
    }

    public function test_officer_subscriber_importer_deduplicates_within_audience(): void
    {
        app(SubscriberImporter::class)->run('officer', $this->fixtures.'/officer_subscribers.csv', dryRun: false);
        // 5 rows in CSV, 2 share the same email -> 4 subscribers.
        $this->assertSame(4, OfficerSubscriber::count());
    }

    public function test_subscriber_importer_preserves_opt_outs_as_suppressions(): void
    {
        app(SubscriberImporter::class)->run('officer', $this->fixtures.'/officer_subscribers.csv', dryRun: false);
        app(SubscriberImporter::class)->run('soldier', $this->fixtures.'/soldier_subscribers.csv', dryRun: false);
        // One unsubscribed row per audience fixture -> 2 suppressions.
        $this->assertSame(2, Suppression::count());
        $this->assertTrue(Suppression::isSuppressed('officer', 'unsub.legacy@example.test'));
        $this->assertTrue(Suppression::isSuppressed('soldier', 'soldier.unsub@example.test'));
    }

    public function test_result_registration_importer_deduplicates_on_exam_plus_roll(): void
    {
        app(ResultRegistrationImporter::class)->run($this->fixtures.'/result_registrations.csv', dryRun: false);
        // 5 rows; two share (tac, 2024TAC001) -> 4 registrations.
        $this->assertSame(4, ResultRegistration::count());
    }

    public function test_order_importer_preserves_prefixed_legacy_ref_and_paid_totals(): void
    {
        app(OrderImporter::class)->run($this->fixtures.'/orders.csv', dryRun: false);
        $this->assertSame(5, Order::count());
        // All five fixtures keep their LEG- prefix intact.
        foreach (['LEG-0000123', 'LEG-0000124', 'LEG-0000125', 'LEG-0000126', 'LEG-9999999'] as $ref) {
            $this->assertTrue(Order::where('order_ref', $ref)->exists(), "legacy ref $ref lost");
        }
        // 3 paid rows (123 + 124 + 9999999): 199_800 + 399_600 + 599_400 = 1_198_800.
        $this->assertSame(1_198_800, OrderImporter::paidTotalPaise());
    }

    public function test_order_importer_marks_zero_date_rows_as_unknown(): void
    {
        app(OrderImporter::class)->run($this->fixtures.'/orders.csv', dryRun: false);
        $n = Order::where('order_ref', 'LEG-0000126')->first();
        $this->assertTrue((bool) $n->import_date_unknown);
        $this->assertNull($n->placed_at);
    }

    public function test_dry_run_commits_nothing(): void
    {
        app(OrderImporter::class)->run($this->fixtures.'/orders.csv', dryRun: true);
        $this->assertSame(0, Order::count());
    }
}
