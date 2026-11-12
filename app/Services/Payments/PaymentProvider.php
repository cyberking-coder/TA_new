<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\PaymentAttempt;

/**
 * Payment provider contract.
 *
 * Phase 4 ships two implementations:
 *   - CCAvenueAdapter: encrypts the merchant redirect request per the live kit
 *     (stub body — the real AES-128-CBC + MD5-key scheme lands with the kit).
 *   - LocalSimulatorProvider: returns deterministic responses so CI can exercise
 *     success, failure, cancel, tampered-amount, and duplicate-callback cases
 *     without touching the merchant sandbox.
 *
 * The site swaps between them via config('payments.provider') — the rest of the
 * app only ever sees this interface.
 */
interface PaymentProvider
{
    /**
     * Build the redirect target the buyer POSTs to, given a pending order
     * and its freshly-created PaymentAttempt.
     *
     * @return array{url: string, fields: array<string,string>}
     *   url    where to POST
     *   fields hidden form fields (encrypted for live, plain for simulator)
     */
    public function buildRedirect(Order $order, PaymentAttempt $attempt): array;

    /**
     * Verify and parse a merchant response (either the browser return POST or
     * the server-to-server callback). Returns a normalised result for the
     * ReconciliationService to apply.
     *
     * @return array{
     *     ok: bool,
     *     error?: string,
     *     merchant_order_ref?: string,
     *     amount_paise?: int,
     *     currency?: string,
     *     status?: string,            // succeeded | failed | cancelled | unknown
     *     gateway_event_ref?: string, // unique per provider event
     *     raw_redacted?: array<string,mixed>,
     * }
     */
    public function verifyResponse(array $payload): array;
}
