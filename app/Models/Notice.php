<?php

namespace App\Models;

use App\Models\Concerns\HasNewBadge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    use HasNewBadge;

    protected $fillable = [
        'legacy_id', 'slug', 'title', 'summary', 'body', 'exam_id', 'published_on', 'type',
        'source_label', 'source_url', 'pdf_id', 'status', 'published_at', 'author_id',
        'import_date_unknown', 'new_until',
    ];

    protected $casts = [
        'published_on' => 'date',
        'published_at' => 'datetime',
        'new_until' => 'datetime',
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
