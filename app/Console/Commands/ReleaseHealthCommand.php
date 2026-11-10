<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\Notice;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * One-shot post-deploy smoke that exercises the critical dependencies
 * without touching real customer data. Exits non-zero on any failure,
 * so systemd / CI can block a bad deploy automatically.
 */
class ReleaseHealthCommand extends Command
{
    protected $signature = 'release:health {--base=}';
    protected $description = 'Phase 6 release-candidate smoke: DB, storage, queue, mail, public routes.';

    public function handle(): int
    {
        $ok = true;
        $this->check('database connection', fn () => DB::connection()->getPdo() !== null);
        $this->check('private disk writable', function () {
            $probe = 'health/'.uniqid('p-', true).'.txt';
            Storage::disk('private')->put($probe, 'ok');
            $r = Storage::disk('private')->exists($probe);
            Storage::disk('private')->delete($probe);
            return $r;
        });
        $this->check('public disk writable', function () {
            $probe = 'health/'.uniqid('p-', true).'.txt';
            Storage::disk('public')->put($probe, 'ok');
            $r = Storage::disk('public')->exists($probe);
            Storage::disk('public')->delete($probe);
            return $r;
        });
        $this->check('mail transport configured', fn () => config('mail.default') !== null);
        $this->check('queue connection configured', fn () => config('queue.default') !== null);
        $this->check('payment provider resolved', fn () => app(\App\Services\Payments\PaymentProvider::class) !== null);

        $base = $this->option('base');
        if ($base) {
            foreach (['/', '/notices-and-downloads', '/books/tac-examination-book', '/sitemap.xml', '/robots.txt'] as $p) {
                $this->check("GET $p is 200", function () use ($base, $p) {
                    return Http::timeout(5)->get(rtrim($base, '/').$p)->ok();
                });
            }
        }

        return $this->ok ? self::SUCCESS : self::FAILURE;
    }

    private bool $ok = true;

    private function check(string $label, \Closure $fn): void
    {
        try {
            $r = $fn();
        } catch (\Throwable $e) {
            $r = false; $this->line("  ✗ $label — ".$e->getMessage());
            $this->ok = false;
            return;
        }
        if ($r) { $this->line("  ✓ $label"); return; }
        $this->ok = false;
        $this->line("  ✗ $label");
    }
}
