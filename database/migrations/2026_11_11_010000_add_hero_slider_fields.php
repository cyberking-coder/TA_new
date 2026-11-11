<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hero_media', function (Blueprint $t) {
            if (!Schema::hasColumn('hero_media', 'duration_seconds')) {
                $t->unsignedSmallInteger('duration_seconds')->default(5)->after('image_path');
            }
            if (!Schema::hasColumn('hero_media', 'display_order')) {
                $t->unsignedInteger('display_order')->default(0)->after('duration_seconds');
            }
            if (!Schema::hasColumn('hero_media', 'is_active')) {
                $t->boolean('is_active')->default(true)->after('display_order');
            }
            if (!Schema::hasColumn('hero_media', 'alt_text')) {
                $t->string('alt_text')->nullable()->after('image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hero_media', function (Blueprint $t) {
            foreach (['duration_seconds', 'display_order', 'is_active', 'alt_text'] as $c) {
                if (Schema::hasColumn('hero_media', $c)) $t->dropColumn($c);
            }
        });
    }
};
