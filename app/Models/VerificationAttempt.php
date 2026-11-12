<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationAttempt extends Model
{
    protected $fillable = [
        'subject_type', 'subject_id', 'token_hash', 'purpose',
        'expires_at', 'used_at', 'request_ip',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        return !$this->used_at && $this->expires_at->isFuture();
    }
}
