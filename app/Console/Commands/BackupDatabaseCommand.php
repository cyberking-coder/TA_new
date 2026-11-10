<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * One-shot DB backup. Supports sqlite (file copy) and mysql (mysqldump).
 * Writes to --out (default: storage/backups/db-<ts>.sql.gz).
 */
class BackupDatabaseCommand extends Command
{
    protected $signature = 'backup:db {--out=}';
    protected $description = 'Dump the current database connection to a timestamped file (sqlite or mysql).';

    public function handle(): int
    {
        $out = $this->option('out') ?: storage_path('backups/db-'.now()->format('Ymd-His').'.sql.gz');
        @mkdir(dirname($out), 0o775, true);
        $driver = config('database.default');
        $cfg = config("database.connections.$driver");

        if ($driver === 'sqlite') {
            $src = $cfg['database'];
            if (!is_file($src)) { $this->error("sqlite file missing: $src"); return self::FAILURE; }
            $plain = preg_replace('/\.gz$/', '', $out);
            copy($src, $plain);
            (new Process(['gzip', '-f', $plain]))->mustRun();
            $this->info("sqlite → ".$out);
            return self::SUCCESS;
        }

        if ($driver === 'mysql') {
            $cmd = ['mysqldump',
                '-h', $cfg['host'] ?? '127.0.0.1',
                '-P', (string) ($cfg['port'] ?? 3306),
                '-u', $cfg['username'],
                '--password='.$cfg['password'],
                '--single-transaction', '--quick', '--routines', '--events',
                $cfg['database']];
            $plain = preg_replace('/\.gz$/', '', $out);
            $proc = new Process($cmd);
            $proc->setTimeout(1800);
            $fh = fopen($plain, 'w');
            $proc->run(function ($type, $buffer) use ($fh) {
                if ($type === Process::OUT) fwrite($fh, $buffer);
            });
            fclose($fh);
            if (!$proc->isSuccessful()) { $this->error($proc->getErrorOutput()); return self::FAILURE; }
            (new Process(['gzip', '-f', $plain]))->mustRun();
            $this->info("mysql → ".$out);
            return self::SUCCESS;
        }

        $this->error("unsupported driver: $driver");
        return self::FAILURE;
    }
}
