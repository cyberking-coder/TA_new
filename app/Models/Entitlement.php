<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entitlement extends Model
{
    protected $fillable = ['order_id', 'buyer_id', 'book_edition_id', 'granted_at', 'revoked_at'];

    protected $casts = ['granted_at' => 'datetime', 'revoked_at' => 'datetime'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function buyer(): BelongsTo { return $this->belongsTo(Buyer::class); }
    public function bookEdition(): BelongsTo { return $this->belongsTo(BookEdition::class); }

    public function scopeActive(Builder $q): Builder { return $q->whereNull('revoked_at'); }
}
