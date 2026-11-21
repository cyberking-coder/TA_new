<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * SMS Alert / Subscription / Examination v2 — schema changes.
 *
 * 1. officer_subscribers + soldier_subscribers: split name into first/last,
 *    add mobile_canon (the +91XXXXXXXXXX canonical form) with a UNIQUE index
 *    scoped to this table only. email_unique is dropped because mobile is
 *    now the identity. Two candidates may share an email across audiences.
 * 2. exams: add status (draft/published/unpublished) + published_at so the
 *    public result-registration dropdown can respect admin publication.
 * 3. app_settings: tiny key/value table so admins can flip OTP and the
 *    Result Notification link ON/OFF without a deploy.
 */
return new class extends Migration {
    public function up(): void
    {
        foreach (['officer_subscribers', 'soldier_subscribers'] as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                $t->string('first_name', 80)->nullable()->after('email');
                $t->string('last_name', 80)->nullable()->after('first_name');
                $t->string('mobile_canon', 16)->nullable()->after('mobile');
            });

            // Backfill: split existing `name` into first/last when present.
            foreach (DB::table($table)->whereNotNull('name')->orWhereNotNull('mobile')->get() as $row) {
                $update = [];
                if ($row->name) {
                    $parts = preg_split('/\s+/', trim((string) $row->name), 2);
                    $update['first_name'] = $parts[0] ?? null;
                    $update['last_name']  = $parts[1] ?? null;
                }
                if ($row->mobile) {
                    $digits = preg_replace('/\D+/', '', (string) $row->mobile);
                    if (strlen($digits) >= 10) {
                        $ten = substr($digits, -10);
                        $update['mobile_canon'] = '+91'.$ten;
                    }
                }
                if ($update) {
                    DB::table($table)->where('id', $row->id)->update($update);
                }
            }

            // Drop old email unique; add mobile_canon unique scoped to this
            // table only (so a mobile can exist independently in BOTH audiences).
            Schema::table($table, function (Blueprint $t) use ($table) {
                try { $t->dropUnique($table.'_email_unique'); } catch (\Throwable $e) {}
                $t->unique('mobile_canon', $table.'_mobile_canon_unique');
            });
        }

        Schema::table('exams', function (Blueprint $t) {
            $t->string('status', 16)->default('draft')->after('short_code');
            $t->timestamp('published_at')->nullable()->after('status');
            $t->index(['status', 'published_at']);
        });

        // Published-by-default for any exam that already has registrations,
        // so the existing public listing doesn't go dark after deploy.
        DB::table('exams')->update(['status' => 'published', 'published_at' => now()]);

        Schema::create('app_settings', function (Blueprint $t) {
            $t->id();
            $t->string('key', 64)->unique();
            $t->text('value')->nullable();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        // Seed defaults: OTP off, Result Notifications on.
        $now = now();
        DB::table('app_settings')->insert([
            ['key' => 'otp_enabled',                 'value' => '0', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'result_notification_enabled', 'value' => '1', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');

        Schema::table('exams', function (Blueprint $t) {
            $t->dropIndex(['status', 'published_at']);
            $t->dropColumn(['status', 'published_at']);
        });

        foreach (['officer_subscribers', 'soldier_subscribers'] as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                try { $t->dropUnique($table.'_mobile_canon_unique'); } catch (\Throwable $e) {}
                $t->dropColumn(['first_name', 'last_name', 'mobile_canon']);
                $t->unique('email');
            });
        }
    }
};
