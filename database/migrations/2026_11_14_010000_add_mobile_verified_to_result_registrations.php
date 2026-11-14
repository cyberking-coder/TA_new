<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('result_registrations') && !Schema::hasColumn('result_registrations', 'mobile_verified_at')) {
            Schema::table('result_registrations', fn (Blueprint $t) =>
                $t->timestamp('mobile_verified_at')->nullable()->after('mobile')
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('result_registrations', 'mobile_verified_at')) {
            Schema::table('result_registrations', fn (Blueprint $t) => $t->dropColumn('mobile_verified_at'));
        }
    }
};
