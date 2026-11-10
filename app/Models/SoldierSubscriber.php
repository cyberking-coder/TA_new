<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SoldierSubscriber extends Model
{
    public const AUDIENCE = 'soldier';

    protected $fillable = [
        'email', 'name', 'mobile', 'consent_at', 'verified_at', 'unsubscribed_at',
        'suppressed_at', 'suppression_reason',
    ];

    protected $casts = [
        'consent_at' => 'datetime',
        'verified_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'suppressed_at' => 'datetime',
    ];

    public function isEligibleForSend(): bool
    {
        return $this->verified_at
            && !$this->unsubscribed_at
            && !$this->suppressed_at;
    }

    public function scopeEligible(Builder $q): Builder
    {
        return $q->whereNotNull('verified_at')
            ->whereNull('unsubscribed_at')
            ->whereNull('suppressed_at');
    }
}
