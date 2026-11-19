@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / About Us</div>
            <h1>About Us</h1>
            <p>An independent online store for Territorial Army officer-entry examination books and study material.</p>
        </div>
    </div>

    <section class="frame info-page">
        <div class="about-hero">
            <div>
                <p class="eyebrow">WHO WE ARE</p>
                <h2>Books and study material for the TAC examination.</h2>
                <p class="about-lede"><strong>territorialarmy.in</strong> is an online store that provides books and online study material for the Territorial Army Online Written Examination. We exist to make preparation for the Territorial Army Officer Entrance Examination accessible, affordable and well-structured.</p>
                <p class="about-lede">Payment is handled through UPI, Debit Card, Credit Card, Cash Card and Net Banking, processed through secure and trusted gateways. We are committed to 100% Purchase Protection and make every effort to fulfil each order and dispatch it through Indian Registered Post.</p>
            </div>
            <aside class="about-stat-stack">
                <div class="about-stat"><b>100%</b><span>Purchase protection</span></div>
                <div class="about-stat"><b>PAN</b><span>India delivery</span></div>
                <div class="about-stat"><b>UPI · Cards</b><span>Secure checkout</span></div>
            </aside>
        </div>

        <section class="info-block about-notice">
            <h2>Not the official application portal</h2>
            <p><strong>territorialarmy.in is not a website to apply online for officer entry for civilian candidates.</strong></p>
            <p>The official portal to apply online for civilian officer entry is <a href="https://www.joinindianarmy.nic.in" target="_blank" rel="noopener">www.joinindianarmy.nic.in</a>. Please use that portal for applications. Our role is limited to providing exam-preparation resources.</p>
        </section>

        <div class="about-pillars">
            <article>
                <span class="info-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l9 4-9 4-9-4 9-4Z"/><path d="M3 11v4c3 2 6 3 9 3s6-1 9-3v-4"/><path d="M12 15v6"/></svg>
                </span>
                <h3>Keyed to the syllabus</h3>
                <p>Reasoning, General Knowledge and English — structured to match the Territorial Army Commission paper.</p>
            </article>

            <article>
                <span class="info-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 8h18M8 12h8M8 16h5"/></svg>
                </span>
                <h3>Printed + buyer-only PDFs</h3>
                <p>A printed book shipped India-wide, plus buyer-only study PDFs unlocked after purchase.</p>
            </article>

            <article>
                <span class="info-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>
                </span>
                <h3>Moderated reviews only</h3>
                <p>Every review is checked before it goes live — no spam, no fake praise.</p>
            </article>
        </div>
    </section>
@endsection
