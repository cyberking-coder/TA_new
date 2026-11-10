<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAttempt extends Model
{
    protected $fillable = [
        'order_id', 'provider', 'merchant_order_ref', 'amount_paise', 'currency',
        'status', 'gateway_event_ref', 'raw_response_redacted_json', 'received_at',
    ];

    protected $casts = [
        'raw_response_redacted_json' => 'array',
        'received_at' => 'datetime',
    ];

    public const STATUS_PENDING   = 'pending';
    public const STATUS_SUCCEEDED = 'succeeded';
    public const STATUS_FAILED    = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_UNKNOWN   = 'unknown';

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
