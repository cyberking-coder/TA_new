<?php

namespace App\Services\Checkout;

final class PriceBreakdown
{
    public function __construct(
        public readonly int $unitPricePaise,
        public readonly int $quantity,
        public readonly int $subtotalPaise,
        public readonly int $discountPaise,
        public readonly int $shippingPaise,
        public readonly int $totalPaise,
        public readonly string $currency,
    ) {}

    public function totalInrLabel(): string
    {
        return '₹'.number_format(intdiv($this->totalPaise, 100), 0);
    }
}
