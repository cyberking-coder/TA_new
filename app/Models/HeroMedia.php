<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroMedia extends Model
{
    protected $table = 'hero_media';

    protected $fillable = [
        'image_path', 'headline', 'subhead', 'action_label', 'action_url', 'is_featured',
    ];

    protected $casts = ['is_featured' => 'boolean'];

    public static function featured(): ?self
    {
        return self::query()->where('is_featured', true)->latest('updated_at')->first();
    }
}
