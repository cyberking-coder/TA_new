<?php

namespace App\Http\Controllers;

use App\Models\PaymentAttempt;
use App\Services\Payments\PaymentProvider;
use App\Services\Payments\ReconciliationService;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    public function __construct(
        private readonly PaymentProvider $provider,
        private readonly ReconciliationService $recon,
    ) {}

    /**
     * Server-to-server notification endpoint. This is the source of truth.
     * Browser return separately in `return()` below — it never grants access.
     */
    public function callback(Request $request)
    {
        $verified = $this->provider->verifyResponse($request->all());
        if (!$verified['ok']) {
            return response('rejected: '.($verified['error'] ?? 'invalid'), 400);
        }
        $r = $this->recon->apply($verified);
        return response($r['status'], $r['applied'] ? 200 : 202);
    }

    /**
     * Browser-return landing page. Shows the outcome but NEVER sets paid
     * state — only the server callback does that. If the attempt is still
     * pending here, the UI says "payment is being confirmed" rather than
     * claiming success on a cookie or query parameter.
     */
    public function return(Request $request)
    {
        $ref = (string) $request->query('ref', '');
        $attempt = PaymentAttempt::query()
            ->where('merchant_order_ref', $ref)
            ->latest('id')
            ->first();

        return view('checkout.return', [
            'title' => 'Payment status — Territorial Army exam guide',
            'description' => 'Payment status for your order.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
            'ref' => $ref,
            'attempt' => $attempt,
        ]);
    }
}
