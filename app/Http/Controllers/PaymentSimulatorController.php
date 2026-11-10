<?php

namespace App\Http\Controllers;

use App\Services\Payments\LocalSimulatorProvider;
use App\Services\Payments\PaymentProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Local simulator — ONLY available when payments.provider === 'simulator'.
 * Guards against accidental production use.
 */
class PaymentSimulatorController extends Controller
{
    private function assertEnabled(): void
    {
        abort_unless(config('payments.provider') === 'simulator', 404);
    }

    public function form(Request $request)
    {
        $this->assertEnabled();

        return view('checkout.simulator', [
            'title' => 'Payment simulator',
            'description' => 'Local payment simulator — not a real gateway.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
            'merchant_order_ref' => $request->input('merchant_order_ref'),
            'amount_paise' => (int) $request->input('amount_paise'),
            'currency' => $request->input('currency', 'INR'),
        ]);
    }

    public function decide(Request $request, PaymentProvider $provider)
    {
        $this->assertEnabled();
        /** @var LocalSimulatorProvider $provider */
        $data = $request->validate([
            'merchant_order_ref' => 'required|string|max:64',
            'amount_paise' => 'required|integer|min:1',
            'currency' => 'required|string|size:3',
            'outcome' => 'required|in:success,failure,cancel',
            'tamper_amount' => 'nullable|integer',
        ]);

        // Allow the test harness to simulate amount tampering.
        $amount = $data['tamper_amount'] !== null && $data['tamper_amount'] !== ''
            ? (int) $data['tamper_amount']
            : (int) $data['amount_paise'];

        $fields = [
            'merchant_order_ref' => $data['merchant_order_ref'],
            'amount_paise'       => (string) $amount,
            'currency'           => $data['currency'],
            'status'             => $data['outcome'],
            'event_ref'          => 'sim-'.Str::uuid()->toString(),
        ];
        $fields['sig'] = $provider->sign([
            'merchant_order_ref' => $fields['merchant_order_ref'],
            'amount_paise'       => $fields['amount_paise'],
            'currency'           => $fields['currency'],
            'status'             => $fields['status'],
            'event_ref'          => $fields['event_ref'],
        ]);

        // Simulate the server-to-server callback.
        try {
            Http::asForm()->post(url('/payment/callback'), $fields);
        } catch (\Throwable $e) {
            // Local-dev convenience only; ignored.
        }

        return redirect()->route('payment.return', ['ref' => $data['merchant_order_ref']]);
    }
}
