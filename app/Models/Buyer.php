<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends Model implements Authenticatable
{
    protected $fillable = ['order_ref', 'email', 'password_hash', 'last_login_at'];

    protected $casts = ['last_login_at' => 'datetime'];

    protected $hidden = ['password_hash'];

    public function entitlements(): HasMany { return $this->hasMany(Entitlement::class); }

    // --- Authenticatable contract ---
    public function getAuthIdentifierName(): string { return 'id'; }
    public function getAuthIdentifier() { return $this->getKey(); }
    public function getAuthPassword(): string { return $this->password_hash; }
    public function getAuthPasswordName(): string { return 'password_hash'; }
    public function getRememberToken(): string { return ''; }
    public function setRememberToken($value): void {}
    public function getRememberTokenName(): string { return ''; }
}
