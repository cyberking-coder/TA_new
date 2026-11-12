@extends('layouts.public', ['nav' => 'bookshop'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('books.show', $book->slug) }}">Bookshop</a> /
                <span aria-current="page">Checkout</span>
            </div>
            <h1>Secure checkout</h1>
            <p>You're one step away from your copy of the examination book.</p>

            <ol class="checkout-steps" aria-label="Checkout progress">
                <li class="is-done">
                    <span class="step-num">1</span>
                    <span class="step-label">Book selected</span>
                </li>
                <li class="is-current" aria-current="step">
                    <span class="step-num">2</span>
                    <span class="step-label">Your details</span>
                </li>
                <li>
                    <span class="step-num">3</span>
                    <span class="step-label">Secure payment</span>
                </li>
                <li>
                    <span class="step-num">4</span>
                    <span class="step-label">Access &amp; delivery</span>
                </li>
            </ol>
        </div>
    </div>

    <section class="wrap panel">
        @if ($errors->any())
            <div class="checkout-alert" role="alert">
                <strong>Please fix the following before continuing:</strong>
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="post" action="{{ route('checkout.submit', $book->slug) }}" class="checkout-grid" novalidate>
            @csrf

            <div class="checkout-main">
                <section class="formbox checkout-card">
                    <div class="kicker"><i></i>STEP 2 · YOUR DETAILS</div>
                    <h2>Contact information</h2>
                    <p class="fine">We use these to send your receipt and confirm delivery. Nothing else.</p>

                    <label>
                        <span>Full name</span>
                        <input required type="text" name="buyer_name" value="{{ old('buyer_name') }}" maxlength="120" autocomplete="name">
                    </label>
                    <div class="checkout-row-2">
                        <label>
                            <span>Email</span>
                            <input required type="email" name="buyer_email" value="{{ old('buyer_email') }}" autocomplete="email" inputmode="email">
                        </label>
                        <label>
                            <span>Mobile</span>
                            <input required type="tel" name="buyer_mobile" value="{{ old('buyer_mobile') }}" maxlength="32" autocomplete="tel" inputmode="tel">
                        </label>
                    </div>
                </section>

                <section class="formbox checkout-card">
                    <div class="kicker"><i></i>SHIPPING ADDRESS</div>
                    <h2>Where should we ship it?</h2>
                    <p class="fine">India delivery, dispatched within three working days of confirmed payment.</p>

                    <label>
                        <span>Address line 1</span>
                        <input required type="text" name="ship_line1" value="{{ old('ship_line1') }}" maxlength="200" autocomplete="address-line1" placeholder="House / flat, street">
                    </label>
                    <label>
                        <span>Address line 2 <em class="opt">(optional)</em></span>
                        <input type="text" name="ship_line2" value="{{ old('ship_line2') }}" maxlength="200" autocomplete="address-line2" placeholder="Area, landmark">
                    </label>
                    <div class="checkout-row-2">
                        <label>
                            <span>City</span>
                            <input required type="text" name="ship_city" value="{{ old('ship_city') }}" maxlength="80" autocomplete="address-level2">
                        </label>
                        <label>
                            <span>State</span>
                            <input required type="text" name="ship_state" value="{{ old('ship_state') }}" maxlength="80" autocomplete="address-level1">
                        </label>
                    </div>
                    <label class="checkout-pincode">
                        <span>Pincode</span>
                        <input required type="text" name="ship_pincode" value="{{ old('ship_pincode') }}" pattern="\d{6}" maxlength="10" inputmode="numeric" autocomplete="postal-code" placeholder="6-digit pincode">
                    </label>
                </section>

                <section class="formbox checkout-card">
                    <div class="kicker"><i></i>ORDER</div>
                    <h2>Quantity</h2>
                    <div class="qty-row">
                        <label class="qty-picker">
                            <span>Copies</span>
                            <input required type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="20" inputmode="numeric">
                        </label>
                        <p class="fine qty-note">Price is computed on the server — buyer input cannot change totals.</p>
                    </div>
                </section>

                <div class="checkout-fineprint">
                    <p class="fine">
                        By continuing you agree to our
                        <a href="{{ url('/pages/terms') }}">Terms</a>,
                        <a href="{{ url('/pages/privacy') }}">Privacy policy</a> and
                        <a href="{{ url('/pages/refund-cancellation') }}">Refund policy</a>.
                    </p>
                </div>
            </div>

            <aside class="checkout-summary" aria-label="Order summary">
                <div class="summary-card">
                    <div class="kicker"><i></i>ORDER SUMMARY</div>

                    <div class="summary-item">
                        <div class="bookart summary-bookart" aria-hidden="true">
                            <div class="bookcover">
                                <small>TERRITORIAL ARMY</small>
                                <b>TAC</b>
                                <em>EXAMINATION<br>BOOK</em>
                                <span>PREPARATION SERIES</span>
                            </div>
                        </div>
                        <div>
                            <strong>{{ $book->title }}</strong>
                            <span>Edition {{ $edition->edition_name }}</span>
                            <span class="summary-item-price">{{ $edition->priceInrFormatted() }}</span>
                        </div>
                    </div>

                    <dl class="summary-totals">
                        <div>
                            <dt>Subtotal</dt>
                            <dd>₹{{ number_format(intdiv($price->subtotalPaise, 100)) }}</dd>
                        </div>
                        <div>
                            <dt>Shipping</dt>
                            <dd>
                                @if ($price->shippingPaise === 0)
                                    <span class="chip-free">FREE</span>
                                @else
                                    ₹{{ number_format(intdiv($price->shippingPaise, 100)) }}
                                @endif
                            </dd>
                        </div>
                        @if ($price->discountPaise > 0)
                            <div>
                                <dt>Discount</dt>
                                <dd>− ₹{{ number_format(intdiv($price->discountPaise, 100)) }}</dd>
                            </div>
                        @endif
                        <div class="summary-grand">
                            <dt>Total</dt>
                            <dd>{{ $price->totalInrLabel() }} <small>INR</small></dd>
                        </div>
                    </dl>

                    <button type="submit" class="btn btn-wide">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="5.5" y="10" width="13" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                        </svg>
                        Pay securely {{ $price->totalInrLabel() }}
                    </button>

                    <p class="summary-pay-note">Redirected to CCAvenue · 256-bit TLS · we never store card data.</p>

                    <ul class="trust-list">
                        <li>
                            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 2 4 6v6c0 5 4 9 8 10 4-1 8-5 8-10V6z"/><path d="m9 12 2 2 4-4"/>
                            </svg>
                            <div><strong>Verified gateway</strong><span>CCAvenue merchant-hosted. Your card never touches our server.</span></div>
                        </li>
                        <li>
                            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 7h18l-2 12H5z"/><path d="M8 7V5a4 4 0 0 1 8 0v2"/>
                            </svg>
                            <div><strong>Ships in 3 working days</strong><span>Across India once payment is confirmed by the gateway.</span></div>
                        </li>
                        <li>
                            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 7h16v12H4zM4 7l8 6 8-6"/>
                            </svg>
                            <div><strong>Study access on receipt</strong><span>Password-setup link is emailed the moment payment clears.</span></div>
                        </li>
                        <li class="trust-nocoupon">No coupon code required or accepted.</li>
                    </ul>
                </div>
            </aside>
        </form>
    </section>
@endsection
