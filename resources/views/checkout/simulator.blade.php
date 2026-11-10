@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-xl">
            <p class="badge">Local simulator — NOT a real gateway</p>
            <h1>Payment simulator</h1>
            <p class="text-sm text-[color:var(--color-charcoal-600)]">
                Pick an outcome. The simulator posts a signed server-to-server callback
                to /payment/callback so the full reconciliation code path runs.
            </p>

            <form method="post" action="{{ route('payment.simulator.decide') }}" class="card mt-4 grid gap-3">
                @csrf
                <input type="hidden" name="merchant_order_ref" value="{{ $merchant_order_ref }}">
                <input type="hidden" name="amount_paise" value="{{ $amount_paise }}">
                <input type="hidden" name="currency" value="{{ $currency }}">

                <dl class="grid grid-cols-2 gap-1 text-sm">
                    <dt>Order ref</dt><dd class="text-right">{{ $merchant_order_ref }}</dd>
                    <dt>Amount</dt><dd class="text-right">₹{{ number_format(intdiv($amount_paise, 100)) }} {{ $currency }}</dd>
                </dl>

                <fieldset class="grid gap-2">
                    <legend class="font-semibold">Outcome</legend>
                    <label class="text-sm"><input type="radio" name="outcome" value="success" checked> Success</label>
                    <label class="text-sm"><input type="radio" name="outcome" value="failure"> Failure</label>
                    <label class="text-sm"><input type="radio" name="outcome" value="cancel"> Cancellation</label>
                </fieldset>

                <label class="text-sm flex flex-col">
                    <span class="font-semibold">Tamper amount (leave blank for honest)</span>
                    <input type="number" name="tamper_amount" class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white w-48">
                </label>

                <p class="mt-2"><button type="submit" class="btn-primary">Send signed callback</button></p>
            </form>
        </div>
    </section>
@endsection
