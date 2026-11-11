@extends('layouts.public', ['nav' => 'study'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('study.login') }}">Study access</a> /
                <span aria-current="page">New setup link</span>
            </div>
            <h1>Request a new setup link</h1>
            <p>If you never set a password, or your setup link expired, enter your order details below and we'll send a fresh link.</p>
        </div>
    </div>

    <section class="wrap panel">
        @if ($errors->any())
            <div class="checkout-alert" role="alert" style="max-width:550px">
                <strong>Please fix the following:</strong>
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="formbox" method="post" action="{{ route('study.forgot.submit') }}">
            @csrf
            <div class="kicker"><i></i>SELF-SERVE</div>
            <h2>Email me a new link</h2>
            <p class="fine">
                Enter the <strong>order reference</strong> from your purchase (e.g. <code>TA-A4B9K2XQ</code>)
                and the email address you used at checkout.
            </p>
            <label>
                <span>Order reference</span>
                <input required type="text" name="order_ref" value="{{ old('order_ref') }}" maxlength="64" autocomplete="off">
            </label>
            <label>
                <span>Email</span>
                <input required type="email" name="email" value="{{ old('email') }}" autocomplete="email" inputmode="email">
            </label>
            <button type="submit" class="btn">
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 7h16v12H4zM4 7l8 6 8-6"/>
                </svg>
                Send setup link
            </button>
            <p class="fine" style="margin-top:14px">
                The link arrives at the email on your order record and expires in 48 hours.
                If nothing arrives within a few minutes, check your spam folder, then write to
                <a href="{{ url('/pages/contact-us') }}">contact us</a>.
            </p>
        </form>
    </section>
@endsection
