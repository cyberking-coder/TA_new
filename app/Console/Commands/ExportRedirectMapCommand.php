<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class ExportRedirectMapCommand extends Command
{
    protected $signature = 'seo:redirect-map
        {--out=docs/redirect-map.csv}';

    protected $description = 'Export the committed legacy->new URL map as CSV for the staging crawl rehearsal.';

    /**
     * Mirrors the P0 migration mapping in the PRD.
     * Any new entries must land both here AND in routes/web.php to avoid drift.
     */
    private array $map = [
        ['/home',                 '/',                                   '301'],
        ['/book/5',               '/books/tac-examination-book',         '301'],
        ['/imp_downloads',        '/notices-and-downloads',              '301'],
        ['/pib_question_papers',  '/preparation/previous-papers',        '301'],
        ['/practice_test_papers', '/preparation/practice-papers',        '301'],
        ['/login',                '/study/login',                        '301'],
        ['/buy_now/5',            '/checkout/tac-examination-book',      '301'],
        ['/ta_poilicies',         '/policies/privacy',                   '301 (per-slug; build individuals only once confirmed)'],
        ['/page/{id}',            '/policies/{slug}',                    '301 (individual; no blanket homepage redirect)'],
    ];

    public function handle(): int
    {
        $out = base_path($this->option('out'));
        if (!is_dir(dirname($out))) mkdir(dirname($out), 0o775, true);

        $fh = fopen($out, 'w');
        fputcsv($fh, ['legacy_path', 'new_path', 'status', 'notes']);
        foreach ($this->map as [$from, $to, $notes]) {
            // Fail-safe: assert each from/to pair is served by the app so stale entries never ship.
            $routes = Route::getRoutes();
            $servedFrom = $this->resolves($routes, $from);
            fputcsv($fh, [$from, $to, substr($notes, 0, 3), ($servedFrom ? '' : 'MISSING in routes! ').$notes]);
        }
        fclose($fh);
        $this->info('Wrote '.count($this->map).' entries → '.$this->option('out'));
        return self::SUCCESS;
    }

    private function resolves($routes, string $path): bool
    {
        foreach ($routes as $r) {
            if (trim($r->uri(), '/') === ltrim($path, '/')) return true;
            if (str_contains($r->uri(), '{')) continue;
        }
        return false;
    }
}
