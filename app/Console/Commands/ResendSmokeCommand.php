<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Sends a tiny plain-text test email through whatever Mail transport is
 * configured. Primary use: confirm Resend credentials + DNS are correct
 * without clicking through the public verification flow.
 *
 * Usage:
 *   php artisan resend:smoke you@yourdomain.com
 *   php artisan resend:smoke you@yourdomain.com --subject="Launch day checkpoint"
 *
 * Output says which transport was used and the final message id when the
 * transport returns one, so you can cross-check in the Resend dashboard.
 */
class ResendSmokeCommand extends Command
{
    protected $signature = 'resend:smoke
        {to : Recipient address (must be allowed by your Resend domain)}
        {--subject=Resend smoke test : Subject line}
        {--body=This is a test email from the Territorial Army exam guide. If you received this, the Resend transport is wired correctly. : Body text}';

    protected $description = 'Send a smoke-test email via the configured Mail transport (usually Resend).';

    public function handle(): int
    {
        $to = $this->argument('to');
        $subject = $this->option('subject');
        $body = $this->option('body');

        $driver = config('mail.default');
        $from = config('mail.from.address');

        $this->line("transport: $driver");
        $this->line("from:      $from");
        $this->line("to:        $to");
        $this->line("subject:   $subject");
        $this->newLine();

        try {
            $sent = Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });

            $this->info('✓ queued/sent without throwing.');
            if ($driver === 'log') {
                $this->warn('  MAIL_MAILER=log — nothing actually left the server. Captured in storage/logs/laravel.log.');
            } else {
                $this->line('  Check the recipient inbox (and the Resend dashboard if using Resend).');
            }
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('✗ failed: '.$e->getMessage());
            $this->newLine();
            $this->line('Common causes:');
            $this->line('  - RESEND_KEY is missing or wrong');
            $this->line('  - MAIL_FROM_ADDRESS domain is not verified in Resend (SPF/DKIM pending)');
            $this->line('  - Recipient is outside the sandbox allowlist (if your Resend account is still sandboxed)');
            return self::FAILURE;
        }
    }
}
