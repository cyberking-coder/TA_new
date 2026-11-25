@extends('layouts.public', [
    'title'       => 'Contact Us · Territorial Army Field Guide',
    'description' => 'Reach the right Territorial Army Group Headquarters for recruitment, eligibility, examinations, SSB interviews or service-related queries.',
])

@php
    $commands = [
        [
            'name'    => 'Western Command',
            'city'    => 'Chandigarh',
            'address' => 'Building No. 750, Sector 8-B, Chandigarh — 160018',
            'phones'  => ['0172-2547864', '0172-258798'],
            'email'   => null,
        ],
        [
            'name'    => 'Central Command',
            'city'    => 'Lucknow',
            'address' => 'Lucknow — 226002, Uttar Pradesh',
            'phones'  => ['0522-2482278'],
            'email'   => null,
        ],
        [
            'name'    => 'Eastern Command',
            'city'    => 'Kolkata',
            'address' => 'Fort William, Kolkata — 21, West Bengal',
            'phones'  => ['033-22313227'],
            'email'   => null,
        ],
        [
            'name'    => 'Southern Command',
            'city'    => 'Pune',
            'address' => 'Pune — 411001, Maharashtra',
            'phones'  => ['020-26880231', '020-26880123', '7768002503'],
            'email'   => null,
        ],
        [
            'name'    => 'Northern Command',
            'city'    => 'Udhampur',
            'address' => 'Udhampur (J&K) — 182101',
            'phones'  => ['01992-243592'],
            'email'   => null,
        ],
    ];
@endphp

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Contact Us</div>
            <h1>Contact Us</h1>
            <p>Reach the right Territorial Army Group Headquarters for recruitment, eligibility, examinations, SSB interviews or service-related queries.</p>
        </div>
    </div>

    <section class="frame contact-page">
        <div class="contact-head">
            <p class="eyebrow">COMMAND DIRECTORY</p>
            <h2>TA Group Headquarters</h2>
            <p>For recruitment, eligibility, applications, examinations, SSB interviews and service-related queries, contact the concerned command.</p>

            <div class="contact-tabs" role="tablist" aria-label="Topic">
                <button type="button" role="tab" aria-selected="true">Recruitment &amp; eligibility</button>
                <button type="button" role="tab" aria-selected="false">Exams &amp; interviews</button>
                <button type="button" role="tab" aria-selected="false">Joining &amp; service</button>
            </div>
        </div>

        <div class="contact-grid">
            @foreach ($commands as $c)
                <article class="contact-card">
                    <header class="contact-card-head">
                        <strong>{{ $c['name'] }}</strong>
                        <span class="contact-card-arrow" aria-hidden="true">→</span>
                    </header>
                    <p class="contact-card-city">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s-7-6.2-7-12a7 7 0 1 1 14 0c0 5.8-7 12-7 12Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        {{ strtoupper($c['city']) }}
                    </p>
                    <p class="contact-card-address">{{ $c['address'] }}</p>
                    <ul class="contact-card-phones">
                        @foreach ($c['phones'] as $phone)
                            <li>
                                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.4 2.1L8 9.6a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5c.8.3 1.7.5 2.6.6A2 2 0 0 1 22 16.9Z"/></svg>
                                <a href="tel:{{ preg_replace('/[^0-9+]/','', $phone) }}">{{ $phone }}</a>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>

        <aside class="contact-aside">
            <h3>Book &amp; study-material support</h3>
            <p>For queries regarding the Territorial Army Officer Commission (TAC) examination book and study material:</p>
            <ul class="contact-aside-links">
                <li>
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                    <a href="mailto:taexambook@gmail.com">taexambook@gmail.com</a>
                </li>
                <li>
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 20.5 4.6 15A8 8 0 1 1 9 19.4L3 20.5Z"/></svg>
                    <a href="https://wa.me/919017516001" target="_blank" rel="noopener">WhatsApp 9017516001</a>
                </li>
            </ul>
        </aside>
    </section>
@endsection
