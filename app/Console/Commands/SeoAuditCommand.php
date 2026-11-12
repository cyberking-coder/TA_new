<?php

namespace App\Console\Commands;

use App\Services\Seo\AuditCsvWriter;
use App\Services\Seo\StagingCrawler;
use Illuminate\Console\Command;

class SeoAuditCommand extends Command
{
    protected $signature = 'seo:audit
        {--base=http://127.0.0.1:8000 : Base URL to crawl}
        {--out=docs/seo-audit.csv : Output CSV path}';

    protected $description = 'Crawl the staging routes and emit a prioritised SEO audit CSV.';

    private array $defaultRoutes = [
        '/',
        '/notices-and-downloads',
        '/notices/tac-2026-notification',
        '/books/tac-examination-book',
        '/alerts/officer',
        '/alerts/soldier',
        '/preparation/previous-papers',
        '/preparation/practice-papers',
        '/policies/privacy',
    ];

    private array $legacyRoutes = [
        '/home', '/book/5', '/imp_downloads', '/pib_question_papers', '/practice_test_papers', '/login', '/buy_now/5',
    ];

    public function handle(): int
    {
        $base = rtrim($this->option('base'), '/');
        $urls = array_map(fn ($r) => $base.$r, [...$this->defaultRoutes, ...$this->legacyRoutes]);

        $this->info("Crawling ".count($urls)." URLs on $base");
        $reports = app(StagingCrawler::class)->crawl($urls);

        $this->line('');
        $this->line(sprintf('%-60s %4s %4s %3s %3s %3s %-4s', 'URL', 'HTTP', 'HOPS', 'H1', 'JL', 'IMG', 'ALT?'));
        $this->line(str_repeat('-', 90));
        foreach ($reports as $r) {
            $this->line(sprintf('%-60s %4d %4d %3d %3d %3d %-4s',
                $this->truncate($r->url, 60), $r->status, $r->hops, $r->h1Count, $r->jsonLdCount, $r->imagesTotal,
                $r->imagesMissingAlt > 0 ? "{$r->imagesMissingAlt}!" : 'ok'));
        }

        $out = base_path($this->option('out'));
        $count = app(AuditCsvWriter::class)->write($out, $reports);
        $this->newLine();
        $this->info("Wrote $count findings → ".$this->option('out'));
        return self::SUCCESS;
    }

    private function truncate(string $s, int $n): string
    {
        return mb_strlen($s) > $n ? mb_substr($s, 0, $n - 3).'...' : $s;
    }
}
