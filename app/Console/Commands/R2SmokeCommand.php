<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Round-trip test against both R2 disks.
 * Writes a tiny probe file, re-reads it, deletes it. Fails loud on any error
 * so missing credentials or wrong bucket names surface immediately.
 */
class R2SmokeCommand extends Command
{
    protected $signature = 'r2:smoke';
    protected $description = 'Round-trip test the public and private disks (works for R2 or local).';

    public function handle(): int
    {
        $ok = true;
        foreach (['public', 'private'] as $disk) {
            $path = 'r2-smoke/'.now()->format('Ymd-His').'-'.bin2hex(random_bytes(3)).'.txt';
            $payload = "smoke test $disk ".now()->toIso8601String();
            $driver = config("filesystems.disks.$disk.driver");
            $bucket = config("filesystems.disks.$disk.bucket");

            $this->line("--- $disk disk (driver=$driver".($bucket ? ", bucket=$bucket" : '').") ---");
            try {
                Storage::disk($disk)->put($path, $payload);
                $this->line("  ✓ wrote $path");
                $read = Storage::disk($disk)->get($path);
                if ($read === $payload) {
                    $this->line("  ✓ read back identical bytes");
                } else {
                    $this->error("  ✗ read back DIFFERENT bytes");
                    $ok = false;
                }
                Storage::disk($disk)->delete($path);
                $this->line("  ✓ deleted $path");
            } catch (\Throwable $e) {
                $this->error("  ✗ ".$e->getMessage());
                $ok = false;
            }
        }
        $this->newLine();
        return $ok ? self::SUCCESS : self::FAILURE;
    }
}
