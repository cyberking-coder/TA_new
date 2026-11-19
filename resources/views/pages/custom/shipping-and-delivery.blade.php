@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Shipping &amp; Delivery</div>
            <h1>Shipping &amp; Delivery</h1>
            <p>All-India dispatch via Indian Registered Post, a Government of India undertaking.</p>
        </div>
    </div>

    <section class="frame info-page">
        <div class="info-highlights">
            <article class="info-tile">
                <span class="info-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h13l5 5v5h-3"/><path d="M3 7v10h3"/><circle cx="8.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                </span>
                <h3>All-India delivery</h3>
                <p>Anywhere the Indian Postal Network reaches. International shipments are not supported at present.</p>
            </article>

            <article class="info-tile">
                <span class="info-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                </span>
                <h3>4–8 working days</h3>
                <p>Typical transit time to major destinations. Remote pin-codes may take up to two weeks.</p>
            </article>

            <article class="info-tile">
                <span class="info-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M3 11h18M7 15h4"/></svg>
                </span>
                <h3>No shipping fee</h3>
                <p>Shipping is free. The price you see at checkout is the price you pay — no handling or packing charges added later.</p>
            </article>
        </div>

        <div class="info-split">
            <section class="info-block">
                <p class="eyebrow">WHERE WE DELIVER</p>
                <h2>India only, via India Post</h2>
                <p>We dispatch books through Indian Registered Post, a Government of India undertaking. We deliver to all locations in India where there is reach of the Indian Postal Network. At present, we do <strong>not</strong> make any international shipments.</p>
            </section>

            <section class="info-block">
                <p class="eyebrow">TRANSIT TIME</p>
                <h2>4 to 8 working days, typically</h2>
                <p>Major destinations receive deliveries in 4 to 8 working days. There is <strong>no online tracking</strong> currently available for parcels sent by Indian Registered Post.</p>
                <p>Delivery through India Post can extend to more than two weeks for some remote locations. Please be patient — if there is an inordinate delay, email <a href="mailto:territorialarmyexambooks@gmail.com">territorialarmyexambooks@gmail.com</a> and we will follow up with India Post on your behalf.</p>
            </section>

            <section class="info-block">
                <p class="eyebrow">WEEKEND NOTE</p>
                <h2>Orders after Saturday noon</h2>
                <p>Books ordered on <strong>Saturday after 12 p.m.</strong> will take an extra day to ship due to the weekend.</p>
            </section>
        </div>

        <div class="info-cta">
            <div>
                <h3>Have a delivery question?</h3>
                <p>Reach us at <a href="mailto:territorialarmyexambooks@gmail.com">territorialarmyexambooks@gmail.com</a> or WhatsApp <a href="https://wa.me/919017516001" target="_blank" rel="noopener">9017516001</a>.</p>
            </div>
            <a href="{{ url('/pages/refund-and-cancellation') }}" class="button">Refund &amp; Cancellation Policy <span aria-hidden="true">→</span></a>
        </div>
    </section>
@endsection
