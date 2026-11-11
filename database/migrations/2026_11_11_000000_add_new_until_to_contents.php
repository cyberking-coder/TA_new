<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['notices', 'pages', 'pdfs', 'newsline_items'] as $table) {
            if (!Schema::hasTable($table) || Schema::hasColumn($table, 'new_until')) continue;
            Schema::table($table, function (Blueprint $t) {
                // When set to a timestamp in the future, the public view shows
                // a gently-pulsing "NEW" badge next to the title.
                $t->timestamp('new_until')->nullable()->after('status')->index();
            });
        }
    }

    public function down(): void
    {
        foreach (['notices', 'pages', 'pdfs', 'newsline_items'] as $table) {
            if (Schema::hasColumn($table, 'new_until')) {
                Schema::table($table, fn (Blueprint $t) => $t->dropColumn('new_until'));
            }
        }
    }
};
