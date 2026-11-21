<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    public const STATUS_DRAFT       = 'draft';
    public const STATUS_PUBLISHED   = 'published';
    public const STATUS_UNPUBLISHED = 'unpublished';

    protected $fillable = ['name', 'slug', 'short_code', 'status', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Auto-stamp published_at when flipping to published; clear when
        // moving back to draft / unpublished so the stamp always reflects
        // the latest publish.
        static::saving(function (Exam $e) {
            if ($e->status === self::STATUS_PUBLISHED && blank($e->published_at)) {
                $e->published_at = now();
            }
            if ($e->status !== self::STATUS_PUBLISHED) {
                $e->published_at = null;
            }
        });
    }

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    public function resultRegistrations(): HasMany
    {
        return $this->hasMany(ResultRegistration::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }
}
