<?php

namespace App\Models;

use App\Models\Concerns\HasNewBadge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NewslineItem extends Model
{
    use HasNewBadge;

    protected $fillable = ['title', 'destination_url', 'published_on', 'display_order', 'status', 'new_until'];

    protected $casts = ['published_on' => 'date', 'new_until' => 'datetime'];

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_WITHDRAWN = 'withdrawn';

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED);
    }
}
