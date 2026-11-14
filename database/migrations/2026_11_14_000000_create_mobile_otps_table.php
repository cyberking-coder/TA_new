<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mobile_otps', function (Blueprint $t) {
            $t->id();
            $t->string('audience', 16); // officer | soldier
            $t->string('mobile', 20)->index();
            $t->string('code_hash', 128); // SHA-256 of normalised code
            $t->timestamp('expires_at');
            $t->timestamp('verified_at')->nullable();
            $t->unsignedTinyInteger('attempts')->default(0); // wrong-code counter
            $t->unsignedTinyInteger('send_count')->default(1);
            $t->timestamp('last_sent_at')->nullable();
            $t->string('request_ip', 64)->nullable();
            $t->string('provider', 32)->nullable();
            $t->string('provider_response', 255)->nullable();
            $t->timestamps();
            $t->index(['audience', 'mobile']);
        });

        // Add a verified-at column to each subscriber table so the public
        // form can tell whether the mobile has been proven.
        foreach (['officer_subscribers', 'soldier_subscribers'] as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'mobile_verified_at')) {
                Schema::table($table, fn (Blueprint $t) =>
                    $t->timestamp('mobile_verified_at')->nullable()->after('verified_at')
                );
            }
        }
    }

    public function down(): void
    {
        foreach (['officer_subscribers', 'soldier_subscribers'] as $table) {
            if (Schema::hasColumn($table, 'mobile_verified_at')) {
                Schema::table($table, fn (Blueprint $t) => $t->dropColumn('mobile_verified_at'));
            }
        }
        Schema::dropIfExists('mobile_otps');
    }
};
