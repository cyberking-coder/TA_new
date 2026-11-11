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
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        // Prefer a signed URL on S3/R2 so the browser can actually GET the
        // bytes even when the bucket is private (no public-access toggle on
        // Cloudflare). 24h TTL is fine for a hero image. For a plain local
        // disk, temporaryUrl isn't supported so we fall back to url().
        try {
            if (method_exists($disk, 'temporaryUrl')) {
                try {
                    return $disk->temporaryUrl($this->image_path, now()->addHours(24));
                } catch (\Throwable) { /* fall through to url() */ }
            }
            return $disk->url($this->image_path);
        } catch (\Throwable) {
            return null;
        }
    }
}
