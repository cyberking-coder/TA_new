<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroMedia extends Model
{
    protected $table = 'hero_media';

    protected $fillable = [
        'image_path', 'alt_text', 'headline', 'subhead', 'action_label', 'action_url',
        'is_featured', 'duration_seconds', 'display_order', 'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'duration_seconds' => 'integer',
        'display_order' => 'integer',
    ];

    // The legacy Phase 2 schema still has these columns as NOT NULL; keep
    // empty-string defaults so new overlay-less slides save cleanly on any
    // host regardless of whether the relax-columns migration could run.
    protected $attributes = [
        'headline' => '',
        'subhead' => '',
        'action_label' => '',
        'action_url' => '',
    ];

    public static function featured(): ?self
    {
        return self::query()->where('is_featured', true)->latest('updated_at')->first();
    }

    /** Active hero slides, ordered for the homepage slider. */
    public static function activeSlides()
    {
        return self::query()
            ->where('is_active', true)
            ->whereNotNull('image_path')
            ->orderBy('display_order')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function publicUrl(): ?string
    {
        if (!$this->image_path) return null;
        try {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->image_path);
        } catch (\Throwable) {
            return null;
        }
    }
}
