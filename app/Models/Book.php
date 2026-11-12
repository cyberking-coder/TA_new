<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = ['slug', 'title', 'author', 'description', 'cover_path', 'status', 'published_at'];

    protected $casts = ['published_at' => 'datetime'];

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_WITHDRAWN = 'withdrawn';

    public function editions(): HasMany { return $this->hasMany(BookEdition::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }

    public function defaultEdition(): ?BookEdition
    {
        return $this->editions()->where('is_default', true)->first()
            ?? $this->editions()->orderByDesc('id')->first();
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED);
    }
}
