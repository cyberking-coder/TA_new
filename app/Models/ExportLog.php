<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExportLog extends Model
{
    public $timestamps = false;
    protected $fillable = ['actor_id', 'module', 'filters_json', 'row_count'];

    protected $casts = [
        'filters_json' => 'array',
        'created_at' => 'datetime',
    ];

    public function actor(): BelongsTo { return $this->belongsTo(User::class, 'actor_id'); }
}
