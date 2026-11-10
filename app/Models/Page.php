<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $fillable = [
        'legacy_id', 'slug', 'parent_id', 'menu_label', 'title', 'body',
        'seo_title', 'seo_description', 'status', 'published_at', 'author_id',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_WITHDRAWN = 'withdrawn';

    public function parent(): BelongsTo { return $this->belongsTo(self::class, 'parent_id'); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED)->whereNotNull('published_at');
    }
}
