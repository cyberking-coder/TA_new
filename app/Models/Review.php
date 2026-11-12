<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['book_id', 'author_name', 'rating', 'body', 'moderation_state', 'moderated_by', 'moderated_at'];

    protected $casts = ['moderated_at' => 'datetime'];

    public const STATE_PENDING  = 'pending';
    public const STATE_APPROVED = 'approved';
    public const STATE_REJECTED = 'rejected';

    public function book(): BelongsTo { return $this->belongsTo(Book::class); }
    public function moderator(): BelongsTo { return $this->belongsTo(User::class, 'moderated_by'); }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('moderation_state', self::STATE_APPROVED);
    }
}
