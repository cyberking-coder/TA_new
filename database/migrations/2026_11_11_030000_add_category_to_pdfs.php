<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pdfs', function (Blueprint $t) {
            if (!Schema::hasColumn('pdfs', 'category')) {
                // 'general' | 'previous_papers' | 'practice_papers' | 'study_material'
                $t->string('category', 32)->default('general')->after('disk')->index();
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('pdfs', 'category')) {
            Schema::table('pdfs', fn (Blueprint $t) => $t->dropColumn('category'));
        }
    }
};
