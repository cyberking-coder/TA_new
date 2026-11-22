<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $t) {
            $t->foreignId('pdf_id')->nullable()->after('seo_description')
                ->constrained('pdfs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $t) {
            $t->dropConstrainedForeignId('pdf_id');
        });
    }
};
