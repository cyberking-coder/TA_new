<?php

namespace App\Console\Commands;

use App\Services\Migration\Importers\NoticeImporter;
use App\Services\Migration\Importers\OrderImporter;
use App\Services\Migration\Importers\ResultRegistrationImporter;
use App\Services\Migration\Importers\SubscriberImporter;
use Illuminate\Console\Command;

class ImportLegacyCommand extends Command
{
    protected $signature = 'import:legacy
        {--source=database/fixtures/legacy : Source directory (CSV files inside)}
        {--only=all : Modules (all|notices|officer|soldier|results|orders, comma separated)}
        {--dry-run : Read everything, report counts, roll back every DB change}';

    protected $description = 'Idempotent Phase 5 legacy import. --dry-run to preview without writes.';

    public function handle(): int
    {
        $source = rtrim(base_path($this->option('source')), '/');
        $dry = (bool) $this->option('dry-run');
        $modules = array_filter(array_map('trim', explode(',', $this->option('only'))));
        if ($modules === [] || in_array('all', $modules, true)) {
            $modules = ['notices', 'officer', 'soldier', 'results', 'orders'];
        }

        $this->info(($dry ? '[DRY RUN] ' : '').'Importing from '.$source.' — modules: '.implode(', ', $modules));

        $summaries = [];

        foreach ($modules as $m) {
            try {
                $summaries[$m] = match ($m) {
                    'notices' => app(NoticeImporter::class)->run("$source/notices.csv", $dry),
                    'officer' => app(SubscriberImporter::class)->run('officer', "$source/officer_subscribers.csv", $dry),
                    'soldier' => app(SubscriberImporter::class)->run('soldier', "$source/soldier_subscribers.csv", $dry),
                    'results' => app(ResultRegistrationImporter::class)->run("$source/result_registrations.csv", $dry),
                    'orders'  => app(OrderImporter::class)->run("$source/orders.csv", $dry),
                    default   => null,
                };
            } catch (\Throwable $e) {
                $this->error("$m failed: ".$e->getMessage());
                return self::FAILURE;
            }
        }

        $this->newLine();
        $this->line('Module          | Created | Updated | Skipped | Date-unknown | Per-status');
        $this->line('----------------+---------+---------+---------+--------------+--------------------------');
        foreach ($summaries as $s) {
            if (!$s) continue;
            $status = json_encode($s->perStatusCount);
            $this->line(sprintf('%-15s | %7d | %7d | %7d | %12d | %s',
                $s->module, $s->createdCount, $s->updatedCount, $s->skippedCount, $s->dateUnknownCount, $status));
            if ($s->exceptions) {
                foreach ($s->exceptions as $ex) $this->warn("  - exception: $ex");
            }
        }

        if (in_array('orders', $modules, true)) {
            $paidPaise = OrderImporter::paidTotalPaise();
            $this->newLine();
            $this->info(($dry ? '[DRY RUN] ' : '').'Paid total: ₹'.number_format(intdiv($paidPaise, 100)).' ('.$paidPaise.' paise)');
        }

        return self::SUCCESS;
    }
}
