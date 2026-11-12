<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Newsletter extends Model
{
    protected $fillable = [
        'audience', 'subject', 'body_html', 'body_text',
        'status', 'queued_count', 'sent_count', 'skipped_count',
        'created_by', 'sent_at',
    ];

    protected $casts = ['sent_at' => 'datetime'];

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_SENDING   = 'sending';
    public const STATUS_SENT      = 'sent';
    public const STATUS_CANCELLED = 'cancelled';

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
