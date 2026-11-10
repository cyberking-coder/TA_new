<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    protected $fillable = [
        'slug', 'title', 'summary', 'body', 'exam_id', 'published_on', 'type',
        'source_label', 'source_url', 'pdf_id', 'status', 'published_at', 'author_id',
    ];

    protected $casts = [
        'published_on' => 'date',
        'published_at' => 'datetime',
    ];

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_WITHDRAWN = 'withdrawn';

    public function exam(): BelongsTo { return $this->belongsTo(Exam::class); }
    public function pdf(): BelongsTo { return $this->belongsTo(Pdf::class); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
