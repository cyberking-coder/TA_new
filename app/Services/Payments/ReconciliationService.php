<?php

namespace App\Services\Payments;

use App\Models\Buyer;
use App\Models\BuyerPasswordReset;
use App\Models\Entitlement;
use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Models\StudyMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Takes a verified provider response and applies it to the order, atomically,
 * idempotently, and only once. Grants entitlement on first success.
 *
 * Rules enforced:
 *  - Amount + currency + order reference MUST match the stored PaymentAttempt.
 *    Tampered amounts enter reconciliation (status 'unknown') and NEVER mark
 *    the order paid.
 *  - gateway_event_ref is unique per provider: duplicate callbacks for the
 *    same event short-circuit and return the already-applied attempt.
 *  - A confirmed paid order cannot be regressed by a later failure callback.
 *  - On first success: create a Buyer, grant one Entitlement per edition,
 *    emit the welcome email with a password-setup token.
 */
class ReconciliationService
{
    /**
     * @return array{applied: bool, status: string, reason?: string, attempt?: PaymentAttempt}
     */
    public function apply(array $verified): array
    {
        $attempt = PaymentAttempt::query()
            ->where('merchant_order_ref', $verified['merchant_order_ref'])
            ->where('provider', config('payments.provider'))
            ->first();

        if (!$attempt) {
            return ['applied' => false, 'status' => 'unknown', 'reason' => 'no matching payment attempt'];
        }

        // Idempotency on gateway_event_ref — re-posts of the same event are silent no-ops.
        if ($verified['gateway_event_ref']
            && PaymentAttempt::query()
                ->where('provider', config('payments.provider'))
                ->where('gateway_event_ref', $verified['gateway_event_ref'])
                ->where('id', '!=', $attempt->id)
                ->exists()
        ) {
            return ['applied' => false, 'status' => 'duplicate', 'reason' => 'event_ref already applied', 'attempt' => $attempt];
        }

        $order = $attempt->order;

        return DB::transaction(function () use ($attempt, $verified, $order) {
            // Server-side amount + currency check. Any mismatch -> unknown + reconciliation queue.
            $amountOk   = (int) $verified['amount_paise'] === (int) $attempt->amount_paise;
            $currencyOk = strtoupper($verified['currency']) === strtoupper($attempt->currency);

            // A confirmed paid order must never be regressed by a later failure
            // — protect paid state before anything else.
            $protect = $order->payment_status === Order::PAY_PAID
                && in_array($verified['status'], ['failed', 'cancelled'], true);

            $applyStatus = $protect ? PaymentAttempt::STATUS_UNKNOWN :
                (($amountOk && $currencyOk) ? $verified['status'] : PaymentAttempt::STATUS_UNKNOWN);

            $attempt->update([
                'status' => $applyStatus,
                'gateway_event_ref' => $verified['gateway_event_ref'],
                'raw_response_redacted_json' => $this->redact($verified['raw_redacted'] ?? []),
                'received_at' => now(),
            ]);

            // First-success path: set the order paid, create the buyer, grant entitlement.
            if (!$protect && $amountOk && $currencyOk && $verified['status'] === PaymentAttempt::STATUS_SUCCEEDED) {
                if ($order->payment_status !== Order::PAY_PAID) {
                    $order->update(['payment_status' => Order::PAY_PAID, 'paid_at' => now()]);
                    $this->provisionBuyerAndEntitlements($order);
                }
                return ['applied' => true, 'status' => 'paid', 'attempt' => $attempt->fresh()];
            }

            // Non-success: mark the order failed only if it wasn't already paid.
            if (!$protect && $order->payment_status !== Order::PAY_PAID
                && in_array($verified['status'], ['failed', 'cancelled'], true)
                && $applyStatus !== PaymentAttempt::STATUS_UNKNOWN
            ) {
                $order->update(['payment_status' => Order::PAY_FAILED]);
            }

            return [
                'applied' => true,
                'status' => $applyStatus === PaymentAttempt::STATUS_UNKNOWN ? 'reconcile' : $applyStatus,
                'reason' => $protect ? 'paid order protected from later failure'
                    : (!$amountOk ? 'amount mismatch' : (!$currencyOk ? 'currency mismatch' : null)),
                'attempt' => $attempt->fresh(),
            ];
        });
    }

    private function provisionBuyerAndEntitlements(Order $order): Buyer
    {
        $buyer = Buyer::firstOrCreate(
            ['order_ref' => $order->order_ref],
            [
                'email' => $order->buyer_email,
                // Ephemeral random hash — real password set via reset link emailed on fulfilment.
                'password_hash' => Hash::make(Str::random(48)),
            ],
        );
        $order->update(['buyer_id' => $buyer->id]);

        foreach ($order->items as $item) {
            Entitlement::updateOrCreate(
                ['order_id' => $order->id, 'book_edition_id' => $item->book_edition_id],
                ['buyer_id' => $buyer->id, 'granted_at' => now()],
            );
        }

        // Issue an expiring one-shot token so the buyer can set their password.
        $raw = Str::random(48);
        BuyerPasswordReset::create([
            'buyer_id' => $buyer->id,
            'token_hash' => hash('sha256', $raw),
            'expires_at' => now()->addHours(48),
        ]);

        // Receipt + setup link — captured via log mailer locally (see .env MAIL_MAILER=log).
        try {
            \Illuminate\Support\Facades\Mail::to($order->buyer_email)
                ->queue(new \App\Mail\OrderReceiptMail($order, url('/study/reset/'.$raw)));
        } catch (\Throwable $e) {
            // Receipt failure MUST NOT block entitlement grant.
            report($e);
        }

        return $buyer;
    }

    private function redact(array $raw): array
    {
        // Redact anything PAN-adjacent. Simulator carries none; adapter would.
        $banned = ['card_number', 'cvv', 'pan', 'expiry', 'card_name'];
        foreach ($banned as $k) {
            if (array_key_exists($k, $raw)) $raw[$k] = '[redacted]';
        }
        return $raw;
    }
}
