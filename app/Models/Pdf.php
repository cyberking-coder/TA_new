<?php

namespace App\Models;

use App\Models\Concerns\HasNewBadge;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pdf extends Model
{
    use HasNewBadge;

    protected $fillable = ['legacy_id', 'title', 'disk', 'path', 'size_bytes', 'mime', 'download_label', 'uploaded_by', 'new_until'];

    protected $casts = ['new_until' => 'datetime'];

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
