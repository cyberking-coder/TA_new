<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyMaterial extends Model
{
    protected $fillable = [
        'slug', 'title', 'description', 'pdf_id', 'exam_id', 'book_edition_id',
        'status', 'published_at',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_WITHDRAWN = 'withdrawn';

    public function pdf(): BelongsTo { return $this->belongsTo(Pdf::class); }
    public function exam(): BelongsTo { return $this->belongsTo(Exam::class); }
    public function bookEdition(): BelongsTo { return $this->belongsTo(BookEdition::class); }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED);
    }
}
