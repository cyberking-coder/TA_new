<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\PaymentAttempt;
use Illuminate\Support\Facades\URL;

/**
 * Deterministic in-repo gateway for CI and local staging.
 * Produces REAL signatures (HMAC-SHA256 over the canonical field string with
 * the simulator secret) so verifyResponse() exercises the same authentication
 * code path the CCAvenue adapter will. No network, no merchant kit.
 *
 * Simulator secret: config('payments.simulator_secret') — fixed per environment.
 */
class LocalSimulatorProvider implements PaymentProvider
{
    public function __construct(private readonly string $secret) {}

    public function buildRedirect(Order $order, PaymentAttempt $attempt): array
    {
        // Simulator URL is an in-repo route the test clicks through.
        return [
            'url' => URL::route('payment.simulator.form'),
            'fields' => [
                'merchant_order_ref' => $attempt->merchant_order_ref,
                'amount_paise' => (string) $attempt->amount_paise,
                'currency' => $attempt->currency,
            ],
        ];
    }

    public function verifyResponse(array $payload): array
    {
        $required = ['merchant_order_ref', 'amount_paise', 'currency', 'status', 'event_ref', 'sig'];
        foreach ($required as $k) {
            if (!array_key_exists($k, $payload)) {
                return ['ok' => false, 'error' => "missing field: $k"];
            }
        }

        $expected = $this->sign([
            'merchant_order_ref' => $payload['merchant_order_ref'],
            'amount_paise'       => $payload['amount_paise'],
            'currency'           => $payload['currency'],
            'status'             => $payload['status'],
            'event_ref'          => $payload['event_ref'],
        ]);
        if (!hash_equals($expected, (string) $payload['sig'])) {
            return ['ok' => false, 'error' => 'signature mismatch'];
        }

        $status = match ($payload['status']) {
            'success'   => 'succeeded',
            'failure'   => 'failed',
            'cancel'    => 'cancelled',
            default     => 'unknown',
        };

        return [
            'ok' => true,
            'merchant_order_ref' => (string) $payload['merchant_order_ref'],
            'amount_paise' => (int) $payload['amount_paise'],
            'currency' => (string) $payload['currency'],
            'status' => $status,
            'gateway_event_ref' => (string) $payload['event_ref'],
            // Redact nothing on simulator; adapter redacts PAN-adjacent fields.
            'raw_redacted' => $payload,
        ];
    }

    /** Public so tests can forge legitimate callbacks without copying the algorithm. */
    public function sign(array $fields): string
    {
        ksort($fields);
        $canonical = implode('|', array_map(fn ($k, $v) => "$k=$v", array_keys($fields), array_values($fields)));
        return hash_hmac('sha256', $canonical, $this->secret);
    }
}
