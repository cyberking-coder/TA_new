<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suppression extends Model
{
    public $timestamps = false;
    protected $fillable = ['audience', 'email', 'reason'];

    protected $casts = ['created_at' => 'datetime'];

    public static function isSuppressed(string $audience, string $email): bool
    {
        return self::query()
            ->where('audience', $audience)
            ->where('email', strtolower(trim($email)))
            ->exists();
    }
}
