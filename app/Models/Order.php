<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_ref', 'buyer_name', 'buyer_email', 'buyer_mobile', 'shipping_json',
        'payment_status', 'dispatch_status',
        'subtotal_paise', 'discount_paise', 'shipping_paise', 'total_paise', 'currency',
        'placed_at', 'paid_at', 'buyer_id',
    ];

    protected $casts = [
        'shipping_json' => 'array',
        'placed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public const PAY_PENDING  = 'pending';
    public const PAY_PAID     = 'paid';
    public const PAY_FAILED   = 'failed';
    public const PAY_REFUNDED = 'refunded';

    public const DISPATCH_UNSHIPPED = 'unshipped';
    public const DISPATCH_SHIPPED   = 'shipped';
    public const DISPATCH_DELIVERED = 'delivered';

    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function paymentAttempts(): HasMany { return $this->hasMany(PaymentAttempt::class); }
    public function entitlements(): HasMany { return $this->hasMany(Entitlement::class); }
    public function buyer(): BelongsTo { return $this->belongsTo(Buyer::class); }

    public function totalInrFormatted(): string
    {
        return '₹'.number_format(intdiv($this->total_paise, 100), 0);
    }

    public function scopePaid(Builder $q): Builder
    {
        return $q->where('payment_status', self::PAY_PAID);
    }
}
