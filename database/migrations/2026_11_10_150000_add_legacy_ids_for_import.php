<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Mapping keys — Phase 5 importers set these so reruns are idempotent
        // and legacy references survive without exposing legacy primary keys
        // in the public interface.
        $touches = [
            'notices', 'pages', 'pdfs',
            'officer_subscribers', 'soldier_subscribers', 'result_registrations',
            'orders', 'entitlements',
        ];
        foreach ($touches as $table) {
            if (!Schema::hasTable($table)) continue;
            if (Schema::hasColumn($table, 'legacy_id')) continue;
            Schema::table($table, function (Blueprint $t) use ($table) {
                $t->string('legacy_id', 64)->nullable()->after('id')->index();
            });
        }

        // Import-date-unknown flag per PRD:
        // "flag invalid dates as unknown".
        if (Schema::hasTable('notices') && !Schema::hasColumn('notices', 'import_date_unknown')) {
            Schema::table('notices', fn (Blueprint $t) =>
                $t->boolean('import_date_unknown')->default(false)->after('published_on')
            );
        }
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'import_date_unknown')) {
            Schema::table('orders', fn (Blueprint $t) =>
                $t->boolean('import_date_unknown')->default(false)->after('placed_at')
            );
        }
    }

    public function down(): void
    {
        foreach (['notices', 'pages', 'pdfs', 'officer_subscribers', 'soldier_subscribers', 'result_registrations', 'orders', 'entitlements'] as $table) {
            if (Schema::hasColumn($table, 'legacy_id')) {
                Schema::table($table, fn (Blueprint $t) => $t->dropColumn('legacy_id'));
            }
        }
        foreach (['notices', 'orders'] as $t) {
            if (Schema::hasColumn($t, 'import_date_unknown')) {
                Schema::table($t, fn (Blueprint $x) => $x->dropColumn('import_date_unknown'));
            }
        }
    }
};
