<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultRegistration extends Model
{
    protected $fillable = ['name', 'mobile', 'email', 'exam_id', 'roll_number', 'legacy_zone'];

    public function exam(): BelongsTo { return $this->belongsTo(Exam::class); }
}
