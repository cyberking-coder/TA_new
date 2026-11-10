<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NewslineItem extends Model
{
    protected $fillable = ['title', 'destination_url', 'published_on', 'display_order', 'status'];

    protected $casts = ['published_on' => 'date'];

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_WITHDRAWN = 'withdrawn';

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED);
    }
}
