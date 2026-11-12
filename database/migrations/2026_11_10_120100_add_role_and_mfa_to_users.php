<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            // PRD: administrator | content_editor | operations
            $t->string('role', 32)->default('content_editor')->after('password');
            // MFA is mandatory per PRD for all staff; phase 2 stores the secret, phase 6 enforces challenge
            $t->string('mfa_secret', 128)->nullable()->after('role');
            $t->boolean('mfa_enabled')->default(false)->after('mfa_secret');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['role', 'mfa_secret', 'mfa_enabled']);
        });
    }
};
