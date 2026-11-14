@extends('layouts.public', ['nav' => 'home'])

@section('content')
    <section class="hero hero-fullbleed">
        <picture>
            <source media="(max-width:600px)" srcset="/assets/hero-mobile.webp">
            <img fetchpriority="high" src="/assets/hero.webp" width="1536" height="820" alt="Soldiers on a Himalayan ridge at dawn">
        </picture>
        @if (count($heroSlides ?? []) > 0)
            <div class="hero-slider" aria-roledescription="carousel" aria-label="Homepage hero">
                @foreach ($heroSlides as $i => $s)
                    <img
                        src="{{ $s['url'] }}"
                        alt="{{ $s['alt'] }}"
                        class="hero-slide {{ $i === 0 ? 'is-active' : '' }}"
                        data-duration="{{ (int) ($s['duration'] * 1000) }}"
                        @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif
                        onerror="this.style.display='none'"
                        width="1536" height="820">
                @endforeach
            </div>
        @endif
        <div class="wrap hero-inner">
            <div class="kicker"><i></i>INDEPENDENT EXAM RESOURCE</div>
            <h1 class="editorial-title">Territorial&nbsp;Army<br><em>Exam Guide.</em></h1>
            <p>Find exam notices, results and preparation resources<br class="desktop"> for your Territorial Army journey.</p>
            <div class="actions">
                <a class="btn" href="{{ route('notices.index') }}">
                    View latest notices
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h15m-6-6 6 6-6 6"/></svg>
                </a>
                <a class="herolink" href="{{ route('prep.previous') }}">
                    Explore preparation
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    <div class="update-strip" role="region" aria-label="Latest updates">
        <div class="wrap">
            <span class="update-strip-label">Latest updates</span>
            <ul class="update-strip-items">
                <li><a href="{{ route('notices.index', ['type' => 'notice']) }}">Recruitment notices</a></li>
                <li><a href="{{ route('notices.index', ['type' => 'result']) }}">Exam results</a></li>
                <li><a href="{{ route('downloads.index') }}">Download forms</a></li>
            </ul>
        </div>
    </div>

    @include('partials.newsline-ticker', ['items' => $tickerItems ?? []])

    <section class="wrap quicks" aria-label="Quick access">
        <a class="quick" href="{{ route('notices.index') }}">
            <span class="qicon"><svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h12v16H5zM8 8h6M8 12h6M8 16h4"/><path d="M17 7h3v13H8"/></svg></span>
            <strong>Notices &amp; results</strong>
            <span>Find the latest updates</span>
            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 18 18 6M6 6h12v12"/></svg>
        </a>
        <a class="quick" href="{{ route('downloads.index') }}">
            <span class="qicon"><svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m0 0-4-4m4 4 4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg></span>
            <strong>Important downloads</strong>
            <span>Public PDFs and forms</span>
            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 18 18 6M6 6h12v12"/></svg>
        </a>
        <a class="quick" href="{{ route('alerts.officer') }}">
            <span class="qicon"><svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 17c2-2 2-4 2-7a5 5 0 0 1 10 0c0 3 0 5 2 7zM10 20a2 2 0 0 0 4 0M12 3V2"/></svg></span>
            <strong>Exam alerts</strong>
            <span>Officer and soldier updates</span>
            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 18 18 6M6 6h12v12"/></svg>
        </a>
        <a class="quick" href="{{ route('study.login') }}">
            <span class="qicon"><svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9 12 4l10 5-10 5zM6 12v5c4 3 8 3 12 0v-5M22 10v6"/></svg></span>
            <strong>Study access</strong>
            <span>Open your buyer library</span>
            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 18 18 6M6 6h12v12"/></svg>
        </a>
    </section>

    <section class="wrap lower">
        <div class="board">
            <div class="section-head">
                <div>
                    <div class="kicker"><i></i>EXAM UPDATES</div>
                    <h2>Latest notices</h2>
                </div>
                <a class="textlink" href="{{ route('notices.index') }}">
                    View all notices
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h15m-6-6 6 6-6 6"/></svg>
                </a>
            </div>
            @forelse($latestNotices as $n)
                @php $date = \Carbon\Carbon::parse($n['date']); @endphp
                <a class="notice-row" href="{{ route('notices.show', $n['slug']) }}">
                    <span class="date"><b>{{ $date->format('d') }}</b><small>{{ strtoupper($date->format('M')) }}</small></span>
                    <span class="notice-text">
                        <span class="meta">{{ ucfirst($n['type'] ?? 'Notice') }} <i></i> {{ $date->format('Y') }}</span>
                        <strong>{{ $n['title'] }} <x-new-badge :until="$n['new_until'] ?? null" /></strong>
                    </span>
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 18 18 6M6 6h12v12"/></svg>
                </a>
            @empty
                <p class="count">No notices have been published yet.</p>
            @endforelse
        </div>

        <aside class="featured">
            <div class="kicker"><i></i>FROM THE BOOKSHOP</div>
            <div class="feature-inner">
                <div>
                    <h2>TAC Examination<br>Book</h2>
                    <p>{{ $featuredBook?->defaultEdition()?->edition_name ? 'Edition '.$featuredBook->defaultEdition()->edition_name : 'Eighth edition' }}</p>
                </div>
                <div class="bookart" aria-label="Illustrative TAC examination book cover" role="img">
                    <div class="bookcover">
                        <small>TERRITORIAL ARMY</small>
                        <b>TAC</b>
                        <em>EXAMINATION<br>BOOK</em>
                        <span>PREPARATION SERIES</span>
                    </div>
                </div>
            </div>
            <a class="lightlink" href="{{ route('books.show', 'tac-examination-book') }}">
                Explore the book
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h15m-6-6 6 6-6 6"/></svg>
            </a>
        </aside>
    </section>
@endsection
