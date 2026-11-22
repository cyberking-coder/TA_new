<?php

use App\Models\Pdf;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

/**
 * Pdf uploads before the size-saving hook landed stored size_bytes=0 because
 * Livewire's FileUpload sometimes drops the TemporaryUploadedFile reference
 * before afterStateUpdated fires. Re-stat each file on disk so the public
 * download list stops saying "0 KB · updated …".
 */
return new class extends Migration {
    public function up(): void
    {
        Pdf::query()
            ->where(function ($q) { $q->whereNull('size_bytes')->orWhere('size_bytes', 0); })
            ->get()
            ->each(function (Pdf $p) {
                if (! $p->path) return;
                $disk = $p->disk === Pdf::DISK_PRIVATE ? 'private' : 'public';
                try {
                    if (Storage::disk($disk)->exists($p->path)) {
                        $p->size_bytes = (int) Storage::disk($disk)->size($p->path);
                        $p->saveQuietly();
                    }
                } catch (\Throwable $e) {}
            });
    }

    public function down(): void
    {
        // One-way backfill; reverting is not meaningful.
    }
};
