@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-xl">
            <h1>Redirecting to secure payment…</h1>
            <p>Your order <strong>{{ $order->order_ref }}</strong> is pending. Continue below.</p>
            <form method="post" action="{{ $url }}" id="pay-form" class="card mt-4">
                @csrf
                @foreach ($fields as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <button type="submit" class="btn-primary">Continue to payment simulator</button>
            </form>
            <script>document.getElementById('pay-form').submit();</script>
            <noscript><p class="mt-2 text-sm">JavaScript is disabled — press the button above to proceed.</p></noscript>
        </div>
    </section>
@endsection
