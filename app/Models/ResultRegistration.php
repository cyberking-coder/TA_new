<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultRegistration extends Model
{
    protected $fillable = ['legacy_id', 'name', 'mobile', 'email', 'exam_id', 'roll_number', 'legacy_zone', 'mobile_verified_at'];

    protected $casts = ['mobile_verified_at' => 'datetime'];

    public function exam(): BelongsTo { return $this->belongsTo(Exam::class); }
}
