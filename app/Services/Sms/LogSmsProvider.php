<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

/**
 * Local-dev + test provider. Writes the SMS to storage/logs/laravel.log
 * so you can read the OTP without buying credits. Always succeeds.
 */
class LogSmsProvider implements SmsProvider
{
    public function send(string $mobile, string $message): array
    {
        Log::channel('stack')->info('[SMS LOG DRIVER] to='.$mobile.' body='.$message);
        return ['ok' => true, 'provider' => 'log', 'response' => 'logged'];
    }
}
