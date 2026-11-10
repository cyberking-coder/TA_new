<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\PaymentAttempt;

/**
 * CCAvenue merchant-hosted checkout adapter.
 *
 * CONFIG: payments.ccavenue.{merchant_id, access_code, working_key, action_url}.
 *
 * STATUS: scaffold. The AES-128-CBC encrypt/decrypt + merchant-order key logic
 * lands when the integration kit arrives from the owner. Until then the class
 * fails closed — all callers hit the LocalSimulatorProvider in dev/CI.
 *
 * The shape of verifyResponse() is the merchant contract this app enforces
 * independently of the kit:
 *   - decrypt the body with the working key
 *   - compare order_id, amount, currency against our PaymentAttempt record
 *   - dedupe on the provider's tracking_id via payment_attempts.gateway_event_ref
 */
class CCAvenueAdapter implements PaymentProvider
{
    public function __construct(
        private readonly string $merchantId,
        private readonly string $accessCode,
        private readonly string $workingKey,
        private readonly string $actionUrl,
    ) {}

    public function buildRedirect(Order $order, PaymentAttempt $attempt): array
    {
        throw new \RuntimeException(
            'CCAvenue integration kit not yet provisioned. ' .
            'Set PAYMENT_PROVIDER=simulator for local testing. See docs/phase-4-report.md.'
        );
    }

    public function verifyResponse(array $payload): array
    {
        return ['ok' => false, 'error' => 'ccavenue adapter not yet wired'];
    }
}
