<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileOtp extends Model
{
    protected $fillable = [
        'audience', 'mobile', 'code_hash', 'expires_at', 'verified_at',
        'attempts', 'send_count', 'last_sent_at', 'request_ip',
        'provider', 'provider_response',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'last_sent_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        return !$this->verified_at && $this->expires_at->isFuture();
    }
}
