<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = ['name', 'slug', 'short_code'];

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }
}
