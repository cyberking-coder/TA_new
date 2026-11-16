<?php

namespace App\Services\Sms;

interface SmsProvider
{
    /**
     * Send a transactional SMS.
     *
     * @return array{ok: bool, provider: string, response?: string, error?: string}
     */
    public function send(string $mobile, string $message): array;
}
