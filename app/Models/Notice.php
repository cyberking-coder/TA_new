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

    protected static function booted(): void
    {
        // Auto-stamp published_at the moment status becomes "published", so
        // admins only have to flip one dropdown. Cleared again if they move
        // the record back to draft/withdrawn.
        static::saving(function (Notice $n) {
            if ($n->status === self::STATUS_PUBLISHED && blank($n->published_at)) {
                $n->published_at = now();
            }
            if ($n->status !== self::STATUS_PUBLISHED) {
                $n->published_at = null;
            }
        });
    }

    public function exam(): BelongsTo { return $this->belongsTo(Exam::class); }
    public function pdf(): BelongsTo { return $this->belongsTo(Pdf::class); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }

    public function scopePublished(Builder $q): Builder
    {
        // status is the single source of truth for "is this public?"; if
        // published_at is set (e.g. future-dated schedule), honour it as a
        // not-before gate.
        return $q->where('status', self::STATUS_PUBLISHED)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }
}
