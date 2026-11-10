<?php

namespace App\Services\Checkout;

use App\Models\BookEdition;

/**
 * Server-side pricing. Buyer input cannot set totals — only the quantity.
 *
 * Phase 4 keeps quantity pricing DISABLED by default per PRD scope-still-open.
 * Tier pricing lands only after the owner ratifies the rounding rule.
 *
 * All math is on integer paise. The PRD-reported defect ("₹3,397 on a ₹1,998
 * unit price with quantity 2") came from float pricing. Here we add paise:
 *   199_800 * 2 = 399_600 paise = ₹3,996.
 * The reproduction + fix is covered by test_prices_quantity_without_tier_bug.
 */
class PricingService
{
    public function price(BookEdition $edition, int $quantity): PriceBreakdown
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('quantity must be at least 1');
        }

        $unit = (int) $edition->price_paise;
        $subtotal = $unit * $quantity;
        $discount = 0; // Phase 4 ships with tier pricing OFF.
        $shipping = (int) config('payments.shipping_paise_flat', 0);
        $total = $subtotal - $discount + $shipping;

        return new PriceBreakdown(
            unitPricePaise: $unit,
            quantity: $quantity,
            subtotalPaise: $subtotal,
            discountPaise: $discount,
            shippingPaise: $shipping,
            totalPaise: $total,
            currency: 'INR',
        );
    }
}
