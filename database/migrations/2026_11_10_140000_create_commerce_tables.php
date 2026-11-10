<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            // Order reference is a STRING to preserve legacy prefixes (e.g. "TA-00123").
            $t->string('order_ref', 64)->unique();
            $t->string('buyer_name');
            $t->string('buyer_email');
            $t->string('buyer_mobile', 32);
            $t->json('shipping_json'); // address line1/line2/city/state/pincode
            // PRD: distinguish payment status from dispatch status.
            $t->string('payment_status', 16)->default('pending');   // pending|paid|failed|refunded
            $t->string('dispatch_status', 16)->default('unshipped'); // unshipped|shipped|delivered
            // Money in integer PAISE; no floats. Currency fixed INR but stored for completeness.
            $t->unsignedInteger('subtotal_paise');
            $t->unsignedInteger('discount_paise')->default(0);
            $t->unsignedInteger('shipping_paise')->default(0);
            $t->unsignedInteger('total_paise');
            $t->string('currency', 3)->default('INR');
            $t->timestamp('placed_at')->nullable();
            $t->timestamp('paid_at')->nullable();
            $t->foreignId('buyer_id')->nullable()->constrained('buyers')->nullOnDelete();
            $t->timestamps();
            $t->index('payment_status');
            $t->index('buyer_email');
            $t->index('buyer_mobile');
        });

        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('book_edition_id')->constrained()->cascadeOnUpdate();
            // Snapshot of item description at order time — never re-read from catalogue after.
            $t->string('item_description_snapshot');
            $t->unsignedInteger('unit_price_paise');
            $t->unsignedInteger('quantity');
            $t->unsignedInteger('line_total_paise');
            $t->timestamps();
        });

        Schema::create('payment_attempts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->string('provider', 32)->default('ccavenue');
            // merchant_order_ref is what we send to CCAvenue (prefixed with order_ref).
            $t->string('merchant_order_ref', 64)->index();
            $t->unsignedInteger('amount_paise');
            $t->string('currency', 3)->default('INR');
            // pending|succeeded|failed|cancelled|unknown
            $t->string('status', 16)->default('pending');
            // Unique per provider-event so duplicate callbacks cannot double-apply.
            $t->string('gateway_event_ref', 128)->nullable();
            $t->json('raw_response_redacted_json')->nullable();
            $t->timestamp('received_at')->nullable();
            $t->timestamps();
            $t->unique(['provider', 'gateway_event_ref']);
        });

        Schema::create('buyers', function (Blueprint $t) {
            $t->id();
            // Login id = order_ref string. PRD: "seven-digit book order ID" is legacy.
            $t->string('order_ref', 64)->unique();
            $t->string('email');
            $t->string('password_hash');
            $t->timestamp('last_login_at')->nullable();
            $t->timestamps();
            $t->index('email');
        });

        Schema::create('buyer_password_resets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('buyer_id')->constrained()->cascadeOnDelete();
            $t->string('token_hash', 128)->unique();
            $t->timestamp('expires_at');
            $t->timestamp('used_at')->nullable();
            $t->timestamps();
        });

        Schema::create('entitlements', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnUpdate();
            $t->foreignId('buyer_id')->nullable()->constrained('buyers')->nullOnDelete();
            $t->foreignId('book_edition_id')->constrained()->cascadeOnUpdate();
            $t->timestamp('granted_at')->useCurrent();
            $t->timestamp('revoked_at')->nullable();
            $t->timestamps();
            // Prevent double-granting the same edition for the same order.
            $t->unique(['order_id', 'book_edition_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entitlements');
        Schema::dropIfExists('buyer_password_resets');
        Schema::dropIfExists('buyers');
        Schema::dropIfExists('payment_attempts');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
