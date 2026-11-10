<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerPasswordReset extends Model
{
    protected $fillable = ['buyer_id', 'token_hash', 'expires_at', 'used_at'];
    protected $casts = ['expires_at' => 'datetime', 'used_at' => 'datetime'];

    public function isValid(): bool
    {
        return !$this->used_at && $this->expires_at->isFuture();
    }
}
