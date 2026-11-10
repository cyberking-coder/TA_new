<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\Buyer;
use App\Models\BuyerPasswordReset;
use App\Models\Entitlement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentAttempt;
use App\Models\Pdf;
use App\Models\StudyMaterial;
use App\Services\Checkout\OrderFactory;
use App\Services\Checkout\PricingService;
use App\Services\Payments\LocalSimulatorProvider;
use App\Services\Payments\PaymentProvider;
use App\Services\Payments\ReconciliationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Phase 4 payment + buyer-access test matrix.
 *
 * All exercises run through the LocalSimulatorProvider so there's no network
 * dependency. The simulator produces REAL signatures with the same algorithm
 * verifyResponse() checks, so we exercise the authentication code path.
 *
 * Merchant-sandbox verification remains PENDING: logged in docs/phase-4-report.md.
 */
class Phase4PaymentTest extends TestCase
{
    use RefreshDatabase;

    private Book $book;
    private BookEdition $edition;
    private LocalSimulatorProvider $sim;

    protected function setUp(): void
    {
        parent::setUp();
        config(['payments.provider' => 'simulator', 'payments.simulator_secret' => 'test-secret']);
        $this->sim = app(PaymentProvider::class);
        $this->book = Book::create([
            'slug' => 'tac-examination-book',
            'title' => 'Territorial Army Commission Examination Book',
            'status' => 'published',
            'published_at' => now(),
        ]);
        $this->edition = BookEdition::create([
            'book_id' => $this->book->id,
            'edition_name' => '2026',
            'price_paise' => 199_800,
            'stock_state' => 'in_stock',
            'is_default' => true,
        ]);
    }

    public function test_prices_quantity_without_tier_bug(): void
    {
        // PRD defect: "Changing quantity from one to two showed ₹3,397 on a ₹1,998 unit price."
        // Correct result: 199_800 paise * 2 = 399_600 paise = ₹3,996.
        $p = app(PricingService::class)->price($this->edition, 2);
        $this->assertSame(199_800, $p->unitPricePaise);
        $this->assertSame(399_600, $p->subtotalPaise);
        $this->assertSame(0,       $p->discountPaise);
        $this->assertSame(399_600, $p->totalPaise);
        $this->assertNotEquals(339_700, $p->totalPaise, 'tier-bug amount must not appear');
    }

    public function test_pending_order_and_payment_attempt_exist_before_redirect(): void
    {
        ['order' => $order, 'attempt' => $attempt] = $this->makeOrder(1);
        $this->assertSame(Order::PAY_PENDING, $order->payment_status);
        $this->assertSame(PaymentAttempt::STATUS_PENDING, $attempt->status);
        $this->assertStringStartsWith('TA-', $order->order_ref, 'legacy prefix preserved on new orders');
        $this->assertSame($order->total_paise, $attempt->amount_paise);
    }

    public function test_successful_callback_marks_paid_and_grants_entitlement(): void
    {
        Mail::fake();
        ['order' => $order, 'attempt' => $attempt] = $this->makeOrder(1);
        $this->applyCallback($attempt, 'success');

        $order->refresh();
        $this->assertSame(Order::PAY_PAID, $order->payment_status);
        $this->assertNotNull($order->paid_at);
        $this->assertSame(1, Entitlement::where('order_id', $order->id)->count());
        Mail::assertQueued(\App\Mail\OrderReceiptMail::class);
    }

    public function test_tampered_amount_does_not_mark_paid(): void
    {
        ['order' => $order, 'attempt' => $attempt] = $this->makeOrder(1);
        // Deliberately lie about the amount.
        $this->applyCallback($attempt, 'success', amountOverride: 1);

        $order->refresh();
        $this->assertNotSame(Order::PAY_PAID, $order->payment_status);
        $attempt->refresh();
        $this->assertSame(PaymentAttempt::STATUS_UNKNOWN, $attempt->status,
            'tampered amount must enter reconciliation, not succeed');
        $this->assertSame(0, Entitlement::where('order_id', $order->id)->count());
    }

    public function test_duplicate_callback_does_not_double_apply(): void
    {
        Mail::fake();
        ['order' => $order, 'attempt' => $attempt] = $this->makeOrder(1);
        $eventRef = 'sim-'.Str::uuid()->toString();

        // Fire the same event twice.
        $this->applyCallback($attempt, 'success', eventRef: $eventRef);
        $r2 = $this->applyCallback($attempt, 'success', eventRef: $eventRef);

        $this->assertSame(1, Entitlement::where('order_id', $order->id)->count(),
            'second identical callback must not grant a second entitlement');
        Mail::assertQueuedCount(1);
    }

    public function test_paid_order_is_protected_from_later_failure_callback(): void
    {
        Mail::fake();
        ['order' => $order, 'attempt' => $attempt] = $this->makeOrder(1);
        $this->applyCallback($attempt, 'success');
        // Later, out-of-order failure callback arrives.
        $this->applyCallback($attempt, 'failure');

        $order->refresh();
        $this->assertSame(Order::PAY_PAID, $order->payment_status,
            'a late failure must not regress a confirmed paid order');
        $this->assertSame(1, Entitlement::where('order_id', $order->id)->count());
    }

    public function test_cancelled_attempt_leaves_order_recoverable(): void
    {
        ['order' => $order, 'attempt' => $attempt] = $this->makeOrder(1);
        $this->applyCallback($attempt, 'cancel');

        $order->refresh();
        $this->assertSame(Order::PAY_FAILED, $order->payment_status);
        // Buyer can place a fresh attempt — modelled by creating a second attempt and succeeding it.
        $attempt2 = PaymentAttempt::create([
            'order_id' => $order->id,
            'provider' => 'simulator',
            'merchant_order_ref' => $order->order_ref,
            'amount_paise' => $order->total_paise,
            'currency' => $order->currency,
        ]);
        $this->applyCallback($attempt2, 'success');
        $order->refresh();
        $this->assertSame(Order::PAY_PAID, $order->payment_status,
            'a new successful attempt after cancel must flip the order to paid');
    }

    public function test_signature_mismatch_is_rejected(): void
    {
        ['attempt' => $attempt] = $this->makeOrder(1);
        $r = $this->sim->verifyResponse([
            'merchant_order_ref' => $attempt->merchant_order_ref,
            'amount_paise' => (string) $attempt->amount_paise,
            'currency' => 'INR',
            'status' => 'success',
            'event_ref' => 'sim-whatever',
            'sig' => 'definitely-not-the-real-signature',
        ]);
        $this->assertFalse($r['ok']);
        $this->assertSame('signature mismatch', $r['error']);
    }

    public function test_entitlement_gated_download_refuses_without_entitlement(): void
    {
        Mail::fake();
        Storage::fake('private');
        $pdf = Pdf::create(['title' => 'buyer-only', 'disk' => 'private', 'path' => 'study/x.pdf', 'size_bytes' => 10, 'mime' => 'application/pdf']);
        Storage::disk('private')->put('study/x.pdf', 'pretend-pdf-bytes');
        $mat = StudyMaterial::create([
            'slug' => 'tac-pack', 'title' => 'Study pack', 'pdf_id' => $pdf->id,
            'book_edition_id' => $this->edition->id, 'status' => 'published', 'published_at' => now(),
        ]);

        // A buyer with NO entitlement
        $buyer = Buyer::create(['order_ref' => 'TA-NOENT', 'email' => 'x@example.test', 'password_hash' => Hash::make('secret1234')]);
        $this->actingAs($buyer, 'buyer');

        $url = app(\App\Http\Controllers\StudyController::class)->signedUrlFor($mat->id);
        $this->get($url)->assertStatus(403);
    }

    public function test_entitlement_gated_download_succeeds_with_entitlement(): void
    {
        Mail::fake();
        Storage::fake('private');
        $pdf = Pdf::create(['title' => 'buyer-only', 'disk' => 'private', 'path' => 'study/y.pdf', 'size_bytes' => 10, 'mime' => 'application/pdf']);
        Storage::disk('private')->put('study/y.pdf', 'pretend-pdf-bytes');
        $mat = StudyMaterial::create([
            'slug' => 'tac-pack-2', 'title' => 'Study pack 2', 'pdf_id' => $pdf->id,
            'book_edition_id' => $this->edition->id, 'status' => 'published', 'published_at' => now(),
        ]);

        ['order' => $order, 'attempt' => $attempt] = $this->makeOrder(1);
        $this->applyCallback($attempt, 'success');
        $buyer = $order->fresh()->buyer;
        $this->assertNotNull($buyer);

        $this->actingAs($buyer, 'buyer');
        $url = app(\App\Http\Controllers\StudyController::class)->signedUrlFor($mat->id);
        $resp = $this->get($url);
        $resp->assertStatus(200);
    }

    public function test_expired_buyer_reset_link_is_refused(): void
    {
        $buyer = Buyer::create(['order_ref' => 'TA-RESET', 'email' => 'r@example.test', 'password_hash' => Hash::make('tempsecret12')]);
        $raw = Str::random(48);
        BuyerPasswordReset::create([
            'buyer_id' => $buyer->id,
            'token_hash' => hash('sha256', $raw),
            'expires_at' => now()->subMinute(),
        ]);
        $resp = $this->post('/study/reset/'.$raw, [
            'password' => 'newstrongpass1',
            'password_confirmation' => 'newstrongpass1',
        ]);
        $resp->assertSee('Reset link expired');
        // Password hash must NOT have changed.
        $buyer->refresh();
        $this->assertTrue(Hash::check('tempsecret12', $buyer->password_hash));
    }

    public function test_browser_return_alone_does_not_mark_paid(): void
    {
        ['order' => $order, 'attempt' => $attempt] = $this->makeOrder(1);
        $this->get('/payment/return?ref='.$attempt->merchant_order_ref)->assertStatus(200);
        $order->refresh();
        $this->assertSame(Order::PAY_PENDING, $order->payment_status);
    }

    // --- helpers ---

    private function makeOrder(int $qty): array
    {
        $price = app(PricingService::class)->price($this->edition, $qty);
        return app(OrderFactory::class)->createPendingOrder(
            $this->edition, $price,
            buyer: ['name' => 'Ada', 'email' => 'ada@example.test', 'mobile' => '5550001'],
            shipping: ['line1' => 'A', 'city' => 'Pune', 'state' => 'MH', 'pincode' => '411001'],
        );
    }

    private function applyCallback(PaymentAttempt $attempt, string $outcome, ?int $amountOverride = null, ?string $eventRef = null): array
    {
        $amount = (string) ($amountOverride ?? $attempt->amount_paise);
        $fields = [
            'merchant_order_ref' => $attempt->merchant_order_ref,
            'amount_paise' => $amount,
            'currency' => $attempt->currency,
            'status' => $outcome,
            'event_ref' => $eventRef ?? 'sim-'.Str::uuid()->toString(),
        ];
        $fields['sig'] = $this->sim->sign($fields);
        $verified = $this->sim->verifyResponse($fields);
        $this->assertTrue($verified['ok'], 'simulator signed its own payload: '.($verified['error'] ?? ''));
        return app(ReconciliationService::class)->apply($verified);
    }
}
