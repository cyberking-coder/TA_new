<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Exams first — notices, study materials and result registrations fk this.
        Schema::create('exams', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('short_code', 32)->nullable();
            $t->timestamps();
        });

        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $t->string('menu_label')->nullable();
            $t->string('title');
            $t->longText('body')->nullable();
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 320)->nullable();
            $t->string('status', 16)->default('draft'); // draft|published|withdrawn
            $t->timestamp('published_at')->nullable();
            $t->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['status', 'published_at']);
        });

        Schema::create('page_redirects', function (Blueprint $t) {
            $t->id();
            $t->string('from_path')->unique();
            $t->string('to_path');
            $t->unsignedTinyInteger('hop_count')->default(1);
            $t->timestamps();
        });

        Schema::create('pdfs', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('disk', 16)->default('public'); // public|private
            $t->string('path');
            $t->unsignedBigInteger('size_bytes')->default(0);
            $t->string('mime', 64)->default('application/pdf');
            $t->string('download_label')->nullable();
            $t->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index('disk');
        });

        Schema::create('notices', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('title');
            $t->string('summary', 500);
            $t->longText('body')->nullable();
            $t->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();
            $t->date('published_on');
            $t->string('type', 24)->default('notification'); // notification|result|admit-card
            $t->string('source_label')->nullable();
            $t->string('source_url', 2048)->nullable();
            $t->foreignId('pdf_id')->nullable()->constrained('pdfs')->nullOnDelete();
            $t->string('status', 16)->default('draft'); // draft|published|withdrawn
            $t->timestamp('published_at')->nullable();
            $t->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['status', 'published_on']);
            $t->index(['exam_id', 'status']);
        });

        Schema::create('hero_media', function (Blueprint $t) {
            $t->id();
            $t->string('image_path')->nullable();
            $t->string('headline');
            $t->string('subhead', 500)->nullable();
            $t->string('action_label')->nullable();
            $t->string('action_url')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->timestamps();
        });

        Schema::create('newsline_items', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('destination_url', 2048);
            $t->date('published_on');
            $t->unsignedInteger('display_order')->default(0);
            $t->string('status', 16)->default('draft');
            $t->timestamps();
            $t->index(['status', 'published_on']);
        });

        Schema::create('books', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('title');
            $t->string('author')->nullable();
            $t->longText('description')->nullable();
            $t->string('cover_path')->nullable();
            $t->string('status', 16)->default('draft');
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });

        Schema::create('book_editions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('book_id')->constrained()->cascadeOnDelete();
            $t->string('edition_name'); // e.g. "2026"
            $t->unsignedInteger('price_paise'); // integer paise, never float
            $t->string('stock_state', 16)->default('in_stock'); // in_stock|out_of_stock|preorder
            $t->longText('delivery_blurb')->nullable();
            $t->boolean('is_default')->default(false);
            $t->timestamps();
            $t->index(['book_id', 'is_default']);
        });

        Schema::create('reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('book_id')->constrained()->cascadeOnDelete();
            $t->string('author_name');
            $t->unsignedTinyInteger('rating'); // 1..5
            $t->longText('body');
            $t->string('moderation_state', 16)->default('pending'); // pending|approved|rejected
            $t->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('moderated_at')->nullable();
            $t->timestamps();
            $t->index(['book_id', 'moderation_state']);
        });

        Schema::create('study_materials', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('title');
            $t->longText('description')->nullable();
            $t->foreignId('pdf_id')->nullable()->constrained('pdfs')->nullOnDelete();
            $t->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();
            $t->foreignId('book_edition_id')->nullable()->constrained('book_editions')->nullOnDelete();
            $t->string('status', 16)->default('draft');
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
            $t->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_materials');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('book_editions');
        Schema::dropIfExists('books');
        Schema::dropIfExists('newsline_items');
        Schema::dropIfExists('hero_media');
        Schema::dropIfExists('notices');
        Schema::dropIfExists('pdfs');
        Schema::dropIfExists('page_redirects');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('exams');
    }
};
