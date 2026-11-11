@extends('layouts.public', ['nav' => 'bookshop'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Secure payment</div>
            <div class="kicker"><i></i>LOCAL SIMULATOR · NOT A REAL GATEWAY</div>
            <h1>Payment simulator</h1>
            <p>The simulator signs the response the same way CCAvenue would, then posts it to <code>/payment/callback</code> — so the full reconciliation code path runs end-to-end.</p>

            <ol class="checkout-steps" aria-label="Checkout progress">
                <li class="is-done"><span class="step-num">1</span><span class="step-label">Book selected</span></li>
                <li class="is-done"><span class="step-num">2</span><span class="step-label">Your details</span></li>
                <li class="is-current" aria-current="step"><span class="step-num">3</span><span class="step-label">Secure payment</span></li>
                <li><span class="step-num">4</span><span class="step-label">Access &amp; delivery</span></li>
            </ol>
        </div>
    </div>

    <section class="wrap panel">
        <form method="post" action="{{ route('payment.simulator.decide') }}" class="formbox" style="max-width:640px">
            @csrf
            <input type="hidden" name="merchant_order_ref" value="{{ $merchant_order_ref }}">
            <input type="hidden" name="amount_paise" value="{{ $amount_paise }}">
            <input type="hidden" name="currency" value="{{ $currency }}">

            <div class="sim-meta">
                <div><span>Order ref</span><strong>{{ $merchant_order_ref }}</strong></div>
                <div><span>Amount</span><strong>&#8377;{{ number_format(intdiv($amount_paise, 100)) }} {{ $currency }}</strong></div>
            </div>

            <fieldset class="sim-outcomes">
                <legend class="kicker" style="margin-bottom:10px"><i></i>CHOOSE OUTCOME</legend>

                <label class="sim-opt sim-ok">
                    <input type="radio" name="outcome" value="success" checked>
                    <span class="sim-opt-body">
                        <strong>Success</strong>
                        <span>Order flips to paid · entitlement granted · receipt email queued.</span>
                    </span>
                </label>

                <label class="sim-opt sim-fail">
                    <input type="radio" name="outcome" value="failure">
                    <span class="sim-opt-body">
                        <strong>Failure</strong>
                        <span>Attempt marked failed; order stays recoverable for a retry.</span>
                    </span>
                </label>

                <label class="sim-opt sim-cancel">
                    <input type="radio" name="outcome" value="cancel">
                    <span class="sim-opt-body">
                        <strong>Cancellation</strong>
                        <span>Buyer-initiated cancel; same behaviour as a failure.</span>
                    </span>
                </label>
            </fieldset>

            <label style="margin-top:14px">
                <span>Tamper amount (optional)</span>
                <input type="number" name="tamper_amount" placeholder="Leave blank for honest" inputmode="numeric" style="max-width:260px">
                <span class="fine">Set a different paise value to exercise the amount-mismatch path. Order will not mark paid.</span>
            </label>

            <button type="submit" class="btn" style="margin-top:18px">
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 12h15m-6-6 6 6-6 6"/>
                </svg>
                Send signed callback
            </button>
        </form>
    </section>
@endsection
