<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Restores a backup produced by backup:db. Refuses to run without --force
 * on APP_ENV=production so a mis-typed command cannot overwrite the live DB.
 */
class RestoreDatabaseCommand extends Command
{
    protected $signature = 'restore:db {file} {--force}';
    protected $description = 'Restore a .sql.gz backup into the current database connection. --force in production.';

    public function handle(): int
    {
        $file = $this->argument('file');
        if (!is_file($file)) { $this->error("missing: $file"); return self::FAILURE; }

        if (app()->environment('production') && !$this->option('force')) {
            $this->error('Refusing to restore into production without --force.');
            return self::FAILURE;
        }

        $driver = config('database.default');
        $cfg = config("database.connections.$driver");

        if ($driver === 'sqlite') {
            $plain = preg_replace('/\.gz$/', '', $file);
            (new Process(['bash', '-c', "gunzip -c ".escapeshellarg($file)." > ".escapeshellarg($plain)]))->mustRun();
            copy($plain, $cfg['database']);
            @unlink($plain);
            $this->info('sqlite restored from '.$file);
            return self::SUCCESS;
        }

        if ($driver === 'mysql') {
            $cmd = 'gunzip -c '.escapeshellarg($file).' | mysql'
                .' -h '.escapeshellarg($cfg['host'] ?? '127.0.0.1')
                .' -P '.(int) ($cfg['port'] ?? 3306)
                .' -u '.escapeshellarg($cfg['username'])
                .' -p'.escapeshellarg($cfg['password'])
                .' '.escapeshellarg($cfg['database']);
            $proc = Process::fromShellCommandline($cmd);
            $proc->setTimeout(1800);
            $proc->mustRun();
            $this->info('mysql restored from '.$file);
            return self::SUCCESS;
        }

        $this->error("unsupported driver: $driver");
        return self::FAILURE;
    }
}
