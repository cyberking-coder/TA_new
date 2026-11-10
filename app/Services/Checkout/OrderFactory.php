<?php

namespace App\Services\Checkout;

use App\Models\BookEdition;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentAttempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderFactory
{
    /**
     * Create a pending order + its first payment attempt in a single transaction.
     * Returns both. Prefix the order_ref so legacy "TA-" prefix format is preserved
     * when the migration later pulls old orders in.
     */
    public function createPendingOrder(
        BookEdition $edition,
        PriceBreakdown $price,
        array $buyer,
        array $shipping,
    ): array {
        return DB::transaction(function () use ($edition, $price, $buyer, $shipping) {
            $orderRef = 'TA-'.strtoupper(Str::random(8));

            $order = Order::create([
                'order_ref' => $orderRef,
                'buyer_name' => $buyer['name'],
                'buyer_email' => strtolower(trim($buyer['email'])),
                'buyer_mobile' => $buyer['mobile'],
                'shipping_json' => $shipping,
                'payment_status' => Order::PAY_PENDING,
                'dispatch_status' => Order::DISPATCH_UNSHIPPED,
                'subtotal_paise' => $price->subtotalPaise,
                'discount_paise' => $price->discountPaise,
                'shipping_paise' => $price->shippingPaise,
                'total_paise' => $price->totalPaise,
                'currency' => $price->currency,
                'placed_at' => now(),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'book_edition_id' => $edition->id,
                'item_description_snapshot' => $edition->book->title.' — Edition '.$edition->edition_name,
                'unit_price_paise' => $price->unitPricePaise,
                'quantity' => $price->quantity,
                'line_total_paise' => $price->subtotalPaise,
            ]);

            $attempt = PaymentAttempt::create([
                'order_id' => $order->id,
                'provider' => config('payments.provider'),
                'merchant_order_ref' => $orderRef,
                'amount_paise' => $price->totalPaise,
                'currency' => $price->currency,
                'status' => PaymentAttempt::STATUS_PENDING,
            ]);

            return ['order' => $order, 'attempt' => $attempt];
        });
    }
}
