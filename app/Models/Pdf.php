<?php

namespace App\Models;

use App\Models\Concerns\HasNewBadge;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pdf extends Model
{
    use HasNewBadge;

    protected $fillable = ['legacy_id', 'title', 'disk', 'path', 'size_bytes', 'mime', 'download_label', 'uploaded_by', 'new_until', 'category'];

    protected $casts = ['new_until' => 'datetime'];

    public const CATEGORY_GENERAL         = 'general';
    public const CATEGORY_PREVIOUS_PAPERS = 'previous_papers';
    public const CATEGORY_PRACTICE_PAPERS = 'practice_papers';
    public const CATEGORY_STUDY_MATERIAL  = 'study_material';

    public static function categoryOptions(): array
    {
        return [
            self::CATEGORY_GENERAL         => 'General (notices, forms, documents)',
            self::CATEGORY_PREVIOUS_PAPERS => 'Previous solved question papers',
            self::CATEGORY_PRACTICE_PAPERS => 'Practice papers',
            self::CATEGORY_STUDY_MATERIAL  => 'Study material (buyer-only)',
        ];
    }

    public const DISK_PUBLIC  = 'public';
    public const DISK_PRIVATE = 'private';

    public function isPrivate(): bool
    {
        return $this->disk === self::DISK_PRIVATE;
    }

    protected static function booted(): void
    {
        // Back-fill size_bytes from the file on disk whenever it's missing or 0.
        // The FileUpload component sometimes misses the getSize() callback when
        // Livewire re-hydrates the form, leaving us at 0 and showing "0 KB" on
        // the public page. Settle the truth at save-time instead. We only touch
        // the disk when size_bytes genuinely needs a fill AND this is the first
        // save (or path changed) — otherwise every markAsNew would re-stat.
        static::saving(function (Pdf $p) {
            if ((int) $p->size_bytes > 0) return;
            if (! $p->path) return;
            if ($p->exists && ! $p->isDirty('path')) return;

            $disk = $p->disk === self::DISK_PRIVATE ? 'private' : 'public';
            try {
                if (Storage::disk($disk)->exists($p->path)) {
                    $p->size_bytes = (int) Storage::disk($disk)->size($p->path);
                }
            } catch (\Throwable $e) {
                // Any filesystem or disk issue must not block the save.
            }
        });
    }

    public function sizeLabel(): string
    {
        $bytes = (int) $this->size_bytes;

        // Fallback: if the row was saved before we had the saving-hook, read
        // the file off disk right now.
        if ($bytes <= 0 && $this->path) {
            $disk = $this->disk === self::DISK_PRIVATE ? 'private' : 'public';
            try {
                if (Storage::disk($disk)->exists($this->path)) {
                    $bytes = (int) Storage::disk($disk)->size($this->path);
                }
            } catch (\Throwable $e) {}
        }

        if ($bytes <= 0) return '—';

        $kb = $bytes / 1024;
        return $kb < 1024
            ? number_format($kb, 0).' KB'
            : number_format($kb / 1024, 1).' MB';
    }
}
