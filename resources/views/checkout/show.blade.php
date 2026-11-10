@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-xl">
            <nav aria-label="Breadcrumb" class="text-sm text-[color:var(--color-charcoal-600)] mb-2">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a> /
                <span aria-current="page">Checkout</span>
            </nav>
            <h1>Checkout</h1>

            <div class="card mt-4">
                <p class="m-0 font-semibold">{{ $book->title }} — Edition {{ $edition->edition_name }}</p>
                <p class="m-0 mt-1 text-sm text-[color:var(--color-charcoal-600)]">
                    Unit price: {{ $edition->priceInrFormatted() }} (INR). Price shown is server-computed.
                </p>
            </div>

            @if ($errors->any())
                <div class="card mt-4" role="alert" style="border-left:4px solid #b32020;">
                    <p class="m-0 font-semibold">Please fix the following:</p>
                    <ul class="mt-2 mb-0 pl-5">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('checkout.submit', $book->slug) }}" class="card mt-5 grid gap-3">
                @csrf

                <fieldset class="grid gap-3">
                    <legend class="font-display font-semibold text-[color:var(--color-forest-700)]">Buyer details</legend>
                    <label class="flex flex-col text-sm">
                        <span class="font-semibold">Full name</span>
                        <input required type="text" name="buyer_name" value="{{ old('buyer_name') }}" maxlength="120"
                               class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                    </label>
                    <label class="flex flex-col text-sm">
                        <span class="font-semibold">Email</span>
                        <input required type="email" name="buyer_email" value="{{ old('buyer_email') }}"
                               class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                    </label>
                    <label class="flex flex-col text-sm">
                        <span class="font-semibold">Mobile</span>
                        <input required type="tel" name="buyer_mobile" value="{{ old('buyer_mobile') }}" maxlength="32"
                               class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                    </label>
                    <label class="flex flex-col text-sm">
                        <span class="font-semibold">Quantity</span>
                        <input required type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="20"
                               class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white w-24">
                    </label>
                </fieldset>

                <fieldset class="grid gap-3">
                    <legend class="font-display font-semibold text-[color:var(--color-forest-700)]">Shipping address</legend>
                    <label class="flex flex-col text-sm">
                        <span class="font-semibold">Address line 1</span>
                        <input required type="text" name="ship_line1" value="{{ old('ship_line1') }}" maxlength="200"
                               class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                    </label>
                    <label class="flex flex-col text-sm">
                        <span class="font-semibold">Address line 2 (optional)</span>
                        <input type="text" name="ship_line2" value="{{ old('ship_line2') }}" maxlength="200"
                               class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex flex-col text-sm">
                            <span class="font-semibold">City</span>
                            <input required type="text" name="ship_city" value="{{ old('ship_city') }}" maxlength="80"
                                   class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                        </label>
                        <label class="flex flex-col text-sm">
                            <span class="font-semibold">State</span>
                            <input required type="text" name="ship_state" value="{{ old('ship_state') }}" maxlength="80"
                                   class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white">
                        </label>
                    </div>
                    <label class="flex flex-col text-sm">
                        <span class="font-semibold">Pincode</span>
                        <input required type="text" name="ship_pincode" value="{{ old('ship_pincode') }}" pattern="\d{6}" maxlength="10"
                               class="mt-1 border border-[color:var(--color-ivory-100)] rounded p-2 bg-white w-32">
                    </label>
                </fieldset>

                <div class="mt-4 card" style="background:#fff;">
                    <p class="m-0 font-semibold text-[color:var(--color-forest-700)]">Order summary</p>
                    <dl class="mt-2 grid grid-cols-2 gap-1 text-sm">
                        <dt>Unit price</dt><dd class="text-right">{{ $edition->priceInrFormatted() }}</dd>
                        <dt>Quantity</dt><dd class="text-right">{{ $price->quantity }}</dd>
                        <dt>Shipping</dt><dd class="text-right">₹{{ number_format(intdiv($price->shippingPaise, 100)) }}</dd>
                        <dt class="font-semibold">Total (server-priced)</dt>
                        <dd class="text-right font-semibold">{{ $price->totalInrLabel() }}</dd>
                    </dl>
                    <p class="text-xs text-[color:var(--color-charcoal-600)] m-0 mt-2">
                        No coupon code is required or accepted.
                    </p>
                </div>

                <p class="mt-4"><button type="submit" class="btn-primary">Continue to payment</button></p>
            </form>
        </div>
    </section>
@endsection
