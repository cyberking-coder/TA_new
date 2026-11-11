<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 2's original schema made headline/subhead/action_label/action_url
 * NOT NULL. The homepage slider now stores slide images without overlay
 * copy. Make those columns nullable.
 *
 * SQLite doesn't support ALTER COLUMN directly; the standard Laravel
 * approach there is to recreate the table. For MySQL/PostgreSQL the
 * standard change() does the job. We handle both.
 *
 * Belt-and-braces: HeroMedia model also ships empty-string defaults
 * for these columns, so a fresh install still saves even if this
 * migration is skipped on an exotic driver.
 */
return new class extends Migration {
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // Rebuild the table with nullable columns.
            DB::statement('PRAGMA foreign_keys = OFF');
            try {
                Schema::rename('hero_media', 'hero_media_old');
                Schema::create('hero_media', function (Blueprint $t) {
                    $t->id();
                    $t->string('image_path')->nullable();
                    $t->string('alt_text')->nullable();
                    $t->unsignedSmallInteger('duration_seconds')->default(5);
                    $t->unsignedInteger('display_order')->default(0);
                    $t->boolean('is_active')->default(true);
                    $t->string('headline')->nullable();
                    $t->string('subhead', 500)->nullable();
                    $t->string('action_label')->nullable();
                    $t->string('action_url')->nullable();
                    $t->boolean('is_featured')->default(false);
                    $t->timestamps();
                });
                DB::statement('INSERT INTO hero_media (id, image_path, alt_text, duration_seconds, display_order, is_active, headline, subhead, action_label, action_url, is_featured, created_at, updated_at) SELECT id, image_path, alt_text, duration_seconds, display_order, is_active, headline, subhead, action_label, action_url, is_featured, created_at, updated_at FROM hero_media_old');
                Schema::drop('hero_media_old');
            } finally {
                DB::statement('PRAGMA foreign_keys = ON');
            }
            return;
        }

        // MySQL / Postgres / SQL Server: direct ALTER.
        Schema::table('hero_media', function (Blueprint $t) {
            $t->string('headline')->nullable()->change();
            $t->string('subhead', 500)->nullable()->change();
            $t->string('action_label')->nullable()->change();
            $t->string('action_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        // down() would re-tighten NOT NULL with no safe default. Skipped.
    }
};
