<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;

/**
 * Fast2SMS transactional SMS provider.
 *
 * Config expected under services.fast2sms:
 *   api_key    (required)
 *   sender_id  (optional; used on "dlt" routes when a DLT sender is approved)
 *   route      (default: "q" -- quick transactional; use "dlt" with
 *               a message_id for DLT-compliant sender IDs)
 *   message_id (required when route = "dlt")
 *
 * Docs: https://docs.fast2sms.com
 */
class Fast2SmsProvider implements SmsProvider
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $route = 'q',
        private readonly ?string $senderId = null,
        private readonly ?string $messageId = null,
    ) {}

    public function send(string $mobile, string $message): array
    {
        if ($this->apiKey === '') {
            return ['ok' => false, 'provider' => 'fast2sms', 'error' => 'missing api_key'];
        }

        $mobile = preg_replace('/\D+/', '', $mobile);
        if (str_starts_with($mobile, '91') && strlen($mobile) === 12) {
            $mobile = substr($mobile, 2);
        }
        if (strlen($mobile) !== 10) {
            return ['ok' => false, 'provider' => 'fast2sms', 'error' => 'invalid mobile format'];
        }

        $payload = [
            'route' => $this->route,
            'numbers' => $mobile,
            'message' => $message,
            'language' => 'english',
            'flash' => 0,
        ];
        if ($this->route === 'dlt') {
            $payload['message'] = $this->messageId ?? '';
            $payload['variables_values'] = $message;
            if ($this->senderId) $payload['sender_id'] = $this->senderId;
        }

        try {
            $resp = Http::withHeaders(['authorization' => $this->apiKey])
                ->timeout(8)
                ->asForm()
                ->post('https://www.fast2sms.com/dev/bulkV2', $payload);

            $body = $resp->json();
            $ok = $resp->successful() && (($body['return'] ?? false) === true);
            return [
                'ok' => $ok,
                'provider' => 'fast2sms',
                'response' => substr(json_encode($body) ?: $resp->body(), 0, 240),
                'error' => $ok ? null : ($body['message'][0] ?? 'send failed'),
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'provider' => 'fast2sms', 'error' => $e->getMessage()];
        }
    }
}
