<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Two separate tables per PRD: "separate officer/soldier lists".
        // Deduplication happens within each list, independently.
        foreach (['officer_subscribers', 'soldier_subscribers'] as $tableName) {
            Schema::create($tableName, function (Blueprint $t) {
                $t->id();
                $t->string('email')->index();
                $t->string('name')->nullable();
                $t->string('mobile', 32)->nullable();
                $t->timestamp('consent_at')->nullable();
                $t->timestamp('verified_at')->nullable();
                $t->timestamp('unsubscribed_at')->nullable();
                $t->timestamp('suppressed_at')->nullable(); // bounces, complaints, hard suppression
                $t->string('suppression_reason', 64)->nullable();
                $t->timestamps();
                $t->unique('email'); // dedupe within audience
            });
        }

        Schema::create('result_registrations', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('mobile', 32);
            $t->string('email');
            $t->foreignId('exam_id')->constrained('exams')->cascadeOnUpdate();
            $t->string('roll_number', 64);
            // Legacy-only. New registrations collect no zone; import may set it.
            $t->string('legacy_zone', 64)->nullable();
            $t->timestamps();
            // Dedupe: a given exam + roll is unique. A second submission with the
            // same roll under the same exam updates contact details without exposing
            // the previous registration.
            $t->unique(['exam_id', 'roll_number']);
            $t->index('email');
        });

        Schema::create('verification_attempts', function (Blueprint $t) {
            $t->id();
            $t->string('subject_type', 64); // officer_subscribers | soldier_subscribers
            $t->unsignedBigInteger('subject_id');
            $t->string('token_hash', 128);  // store SHA-256; raw token goes only in the email
            $t->string('purpose', 32)->default('verify'); // verify | unsubscribe
            $t->timestamp('expires_at');
            $t->timestamp('used_at')->nullable();
            $t->string('request_ip', 64)->nullable();
            $t->timestamps();
            $t->index(['subject_type', 'subject_id']);
            $t->unique('token_hash');
        });

        Schema::create('suppressions', function (Blueprint $t) {
            $t->id();
            $t->string('audience', 32); // officer | soldier
            $t->string('email')->index();
            $t->string('reason', 64)->default('unsubscribed'); // unsubscribed | bounced | complaint | manual
            $t->timestamp('created_at')->useCurrent();
            $t->unique(['audience', 'email']);
        });

        Schema::create('newsletters', function (Blueprint $t) {
            $t->id();
            $t->string('audience', 32); // officer | soldier
            $t->string('subject');
            $t->longText('body_html');
            $t->longText('body_text')->nullable();
            $t->string('status', 16)->default('draft'); // draft | sending | sent | cancelled
            $t->unsignedInteger('queued_count')->default(0);
            $t->unsignedInteger('sent_count')->default(0);
            $t->unsignedInteger('skipped_count')->default(0); // re-suppressed at send time
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('sent_at')->nullable();
            $t->timestamps();
        });

        Schema::create('export_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('actor_id')->constrained('users')->cascadeOnUpdate();
            $t->string('module', 64); // result_registrations | officer_subscribers | soldier_subscribers
            $t->json('filters_json')->nullable();
            $t->unsignedInteger('row_count');
            $t->timestamp('created_at')->useCurrent();
            $t->index(['module', 'actor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_logs');
        Schema::dropIfExists('newsletters');
        Schema::dropIfExists('suppressions');
        Schema::dropIfExists('verification_attempts');
        Schema::dropIfExists('result_registrations');
        Schema::dropIfExists('soldier_subscribers');
        Schema::dropIfExists('officer_subscribers');
    }
};
