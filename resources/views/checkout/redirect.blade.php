@extends('layouts.public', ['nav' => 'bookshop'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('books.show', $order->items->first()?->bookEdition?->book?->slug ?? 'tac-examination-book') }}">Bookshop</a> / Secure payment</div>
            <h1>Redirecting to secure payment</h1>
            <p>Your order <strong>{{ $order->order_ref }}</strong> is pending. We're handing you to CCAvenue now.</p>

            <ol class="checkout-steps" aria-label="Checkout progress">
                <li class="is-done"><span class="step-num">1</span><span class="step-label">Book selected</span></li>
                <li class="is-done"><span class="step-num">2</span><span class="step-label">Your details</span></li>
                <li class="is-current" aria-current="step"><span class="step-num">3</span><span class="step-label">Secure payment</span></li>
                <li><span class="step-num">4</span><span class="step-label">Access &amp; delivery</span></li>
            </ol>
        </div>
    </div>

    <section class="wrap panel">
        <div class="reading" style="text-align:center">
            <div class="redirect-spinner" aria-hidden="true"></div>
            <p>If you aren't redirected automatically in a moment, press the button below.</p>
            <form method="post" action="{{ $url }}" id="pay-form" style="display:inline-block">
                @csrf
                @foreach ($fields as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <button type="submit" class="btn">Continue to payment</button>
            </form>
            <script>setTimeout(() => document.getElementById('pay-form').submit(), 150);</script>
            <noscript><p class="fine">JavaScript is disabled — press the button to proceed.</p></noscript>
        </div>
    </section>
@endsection
