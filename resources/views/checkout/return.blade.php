@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-xl">
            <h1>Payment status</h1>
            @if (!$attempt)
                <p>No record found for order reference <code>{{ $ref }}</code>.</p>
            @elseif ($attempt->status === 'succeeded' && $attempt->order->payment_status === 'paid')
                <p>Thank you — your order <strong>{{ $attempt->order->order_ref }}</strong> is paid.</p>
                <p>A receipt and password-setup link have been sent to your email. You can also sign in at the <a href="{{ route('study.login') }}">study login</a> once your password is set.</p>
            @elseif ($attempt->status === 'pending' || $attempt->status === 'unknown')
                <p>Your payment is being confirmed. We'll email a receipt once the gateway reports back. Do not resubmit — a browser return alone does not grant access.</p>
            @else
                <p>Your payment did not complete. Status: {{ $attempt->status }}. No charge was captured.</p>
            @endif
            <p class="mt-5"><a href="{{ route('home') }}" class="btn-ghost">Back to home</a></p>
        </div>
    </section>
@endsection
