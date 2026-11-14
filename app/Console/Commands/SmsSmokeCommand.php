<?php

namespace App\Console\Commands;

use App\Services\Sms\SmsProvider;
use Illuminate\Console\Command;

/**
 * Round-trip a test SMS through whatever provider is configured.
 * Prints which provider was used and the raw response so you can
 * verify credentials and quota in one command.
 *
 *   php artisan sms:smoke 919XXXXXXXXX
 *   php artisan sms:smoke 919XXXXXXXXX --message="Hello from TA"
 */
class SmsSmokeCommand extends Command
{
    protected $signature = 'sms:smoke {mobile} {--message=Test SMS from Territorial Army Field Guide}';
    protected $description = 'Send a test SMS via the configured provider (Fast2SMS / log).';

    public function handle(SmsProvider $sms): int
    {
        $driver = config('services.sms.provider');
        $this->line("provider: $driver");
        $this->line('to:       '.$this->argument('mobile'));
        $this->line('message:  '.$this->option('message'));
        $this->newLine();

        $r = $sms->send($this->argument('mobile'), $this->option('message'));
        $this->line('result:   '.json_encode($r, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

        if ($driver === 'log') {
            $this->warn('SMS_PROVIDER=log — nothing actually left the server. The message is in storage/logs/laravel.log.');
        }
        return $r['ok'] ? self::SUCCESS : self::FAILURE;
    }
}
