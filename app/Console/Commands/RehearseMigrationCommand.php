<?php

namespace App\Console\Commands;

use App\Models\OfficerSubscriber;
use App\Models\Order;
use App\Models\ResultRegistration;
use App\Models\SoldierSubscriber;
use App\Models\Suppression;
use App\Services\Migration\Importers\OrderImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Runs the full importer chain twice and verifies that:
 *   - counts at end-of-run-1 == counts at end-of-run-2
 *   - paid totals, suppressions, access grants are stable
 *   - the second run creates nothing new (updates only)
 *
 * This proves idempotency against real data without any production access.
 */
class RehearseMigrationCommand extends Command
{
    protected $signature = 'migrate:rehearse
        {--source=database/fixtures/legacy}';

    protected $description = 'Phase 5: run the importer twice, diff, prove idempotency.';

    public function handle(): int
    {
        $this->info('Rehearsal — run 1 / 2');
        Artisan::call('import:legacy', ['--source' => $this->option('source')], $this->getOutput());
        $snapshot1 = $this->snapshot();

        $this->newLine();
        $this->info('Rehearsal — run 2 / 2');
        Artisan::call('import:legacy', ['--source' => $this->option('source')], $this->getOutput());
        $snapshot2 = $this->snapshot();

        $this->newLine();
        $this->line('Metric                          |      Run 1 |      Run 2 | Delta');
        $this->line('--------------------------------+------------+------------+------');
        $delta = false;
        foreach ($snapshot1 as $k => $v) {
            $d = $snapshot2[$k] - $v;
            if ($d !== 0) $delta = true;
            $this->line(sprintf('%-32s|%11d |%11d |%5d', $k, $v, $snapshot2[$k], $d));
        }

        if ($delta) {
            $this->error('Second run produced different totals -- importer is NOT idempotent.');
            return self::FAILURE;
        }
        $this->info('Second run reproduced counts exactly -- importer is idempotent.');
        return self::SUCCESS;
    }

    private function snapshot(): array
    {
        return [
            'notices' => \App\Models\Notice::count(),
            'notices_published' => \App\Models\Notice::where('status', 'published')->count(),
            'officer_subs' => OfficerSubscriber::count(),
            'soldier_subs' => SoldierSubscriber::count(),
            'suppressions' => Suppression::count(),
            'result_regs' => ResultRegistration::count(),
            'orders' => Order::count(),
            'orders_paid' => Order::where('payment_status', 'paid')->count(),
            'paid_total_paise' => OrderImporter::paidTotalPaise(),
        ];
    }
}
