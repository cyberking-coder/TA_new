@extends('layouts.public', ['nav' => 'bookshop'])

@php
    $ok = $attempt && $attempt->status === 'succeeded' && optional($attempt->order)->payment_status === 'paid';
    $pending = $attempt && in_array($attempt->status, ['pending', 'unknown'], true);
@endphp

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Payment status</div>
            <h1>{{ $ok ? 'Payment confirmed' : ($pending ? 'Confirming payment…' : 'Payment did not complete') }}</h1>
            @if ($ok)
                <p>Order <strong>{{ $attempt->order->order_ref }}</strong> is paid. We've emailed your receipt and password-setup link.</p>
            @elseif ($pending)
                <p>We're waiting for the gateway to confirm. This usually takes under a minute.</p>
            @else
                <p>No charge was captured. You can try again or pick a different payment method.</p>
            @endif

            <ol class="checkout-steps" aria-label="Checkout progress">
                <li class="is-done"><span class="step-num">1</span><span class="step-label">Book selected</span></li>
                <li class="is-done"><span class="step-num">2</span><span class="step-label">Your details</span></li>
                <li class="is-done"><span class="step-num">3</span><span class="step-label">Secure payment</span></li>
                <li class="{{ $ok ? 'is-current' : '' }}" @if($ok) aria-current="step" @endif>
                    <span class="step-num">4</span><span class="step-label">Access &amp; delivery</span>
                </li>
            </ol>
        </div>
    </div>

    <section class="wrap panel">
        <article class="reading" style="text-align:center">
            @if (!$attempt)
                <p>No record found for order reference <code>{{ $ref }}</code>.</p>
            @elseif ($ok)
                <div class="return-status return-ok" aria-hidden="true">✓</div>
                <h2>Thank you</h2>
                <p>Your order <strong>{{ $attempt->order->order_ref }}</strong> is confirmed.</p>
                <p>Next step — open the password-setup link in the email we just sent, then sign in to the study library.</p>
                <p style="margin-top:22px">
                    <a href="{{ route('study.login') }}" class="btn">Go to Study login</a>
                </p>
            @elseif ($pending)
                <div class="return-status return-pending" aria-hidden="true">
                    <span></span><span></span><span></span>
                </div>
                <h2>Confirming with the gateway</h2>
                <p>A browser return alone does not grant access — we wait for the server-to-server confirmation before marking the order paid.</p>
                <p class="fine">Do not resubmit; refreshing this page is safe.</p>
            @else
                <div class="return-status return-fail" aria-hidden="true">!</div>
                <h2>Payment not completed</h2>
                <p>Status: <strong>{{ $attempt->status }}</strong>. No charge was captured.</p>
                <p>
                    <a href="{{ route('books.show', 'tac-examination-book') }}" class="btn">Try again</a>
                </p>
            @endif
        </article>
    </section>
@endsection
