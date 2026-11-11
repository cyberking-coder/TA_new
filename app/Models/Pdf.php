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

    public function sizeLabel(): string
    {
        $kb = $this->size_bytes / 1024;
        return $kb < 1024
            ? number_format($kb, 0).' KB'
            : number_format($kb / 1024, 1).' MB';
    }
}
