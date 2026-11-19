@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Refund &amp; Cancellation Policy</div>
            <h1>Refund &amp; Cancellation Policy</h1>
            <p>Full refund if you cancel before dispatch. Partial refund (minus delivery charges) if you return after shipment.</p>
        </div>
    </div>

    <section class="frame info-page">
        <div class="refund-split">
            <article class="refund-card refund-card--ok">
                <header>
                    <span class="refund-badge">BEFORE SHIPMENT</span>
                    <h2>Cancel &amp; get the entire amount back</h2>
                </header>
                <p>Email us with your order number before the book leaves our warehouse and we refund you in full.</p>
                <ul>
                    <li>Full price refund</li>
                    <li>No restocking fee</li>
                    <li>Refund processed within 7 to 21 working days</li>
                </ul>
            </article>

            <article class="refund-card refund-card--returns">
                <header>
                    <span class="refund-badge refund-badge--warn">AFTER SHIPMENT</span>
                    <h2>Refuse delivery or return within 7 days</h2>
                </header>
                <p>Cancellation is not possible once the parcel has left us. Two options remain:</p>
                <ol>
                    <li><strong>Refuse the parcel</strong> from the postman at delivery — it returns to origin and we process your refund.</li>
                    <li><strong>Return unopened</strong> in its original packing within <strong>7 days</strong> by Indian Registered Post. Email us the dispatch details with your order number; we refund after receipt, <strong>minus</strong> our original delivery charges.</li>
                </ol>
                <p class="fine">Refund timing depends on your issuing bank / payment processor — expect 7 to 21 working days.</p>
            </article>
        </div>

        <section class="info-block refund-rules">
            <h2>Order modifications</h2>
            <div class="refund-rules-grid">
                <div>
                    <strong>Reducing quantity</strong>
                    <p>Governed by the cancellation policy above — same before / after shipment rules apply to the removed items.</p>
                </div>
                <div>
                    <strong>Increasing quantity</strong>
                    <p>Not supported on an existing order. Place a new order for the additional requirement.</p>
                </div>
            </div>
        </section>

        <aside class="info-contact-strip">
            <h3>Request a cancellation or refund</h3>
            <ul>
                <li><span>Email</span> <a href="mailto:TAExamBook@gmail.com">TAExamBook@gmail.com</a></li>
                <li><span>WhatsApp</span> <a href="https://wa.me/919017516001" target="_blank" rel="noopener">9017516001</a></li>
            </ul>
            <p class="fine">Mention your order number in every message so we can act on it immediately.</p>
        </aside>
    </section>
@endsection
