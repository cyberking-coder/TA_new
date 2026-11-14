@extends('layouts.public', ['nav' => 'home'])

@section('content')
    <section class="hero has-image">
        <img class="landscape" src="/assets/hero.webp" alt="Soldiers on a Himalayan ridge at dawn">
        @if (!empty($heroSlides) && count($heroSlides) > 0)
            <div class="hero-slider" aria-roledescription="carousel" aria-label="Homepage hero">
                @foreach ($heroSlides as $i => $s)
                    <img
                        src="{{ $s['url'] }}"
                        alt="{{ $s['alt'] }}"
                        class="hero-slide {{ $i === 0 ? 'is-active' : '' }}"
                        data-duration="{{ (int) ($s['duration'] * 1000) }}"
                        onerror="this.style.display='none'"
                        width="1536" height="820">
                @endforeach
            </div>
        @endif
        <div class="frame hero-inner">
            <p class="eyebrow">Independent Territorial Army guide</p>
            <h1>A civilian life.<br><em>A call to serve.</em></h1>
            <p class="lede">Your guide to joining the Territorial Army. Explore entry routes, follow recruitment notices and prepare for the examination.</p>
            <div class="hero-actions">
                <a class="button" href="{{ url('/pages/entry-routes') }}">Explore entry routes <span aria-hidden="true">→</span></a>
                <a class="underlink" href="{{ route('notices.index') }}">View notices</a>
            </div>
        </div>
    </section>

    @include('partials.newsline-ticker', ['items' => $tickerItems ?? []])

    <section class="route-band" aria-label="Choose your entry route">
        <div class="frame route-inner">
            <h2>Entry routes</h2>
            <div class="route-options">
                <a href="{{ url('/pages/join-as-officer') }}"><strong>Civilian officer</strong><span aria-hidden="true">→</span></a>
                <a href="{{ url('/pages/ex-service-officer') }}"><strong>Ex-service officer</strong><span aria-hidden="true">→</span></a>
                <a href="{{ url('/pages/join-as-jawan') }}"><strong>Jawan / JCO</strong><span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <div class="frame overview">
        <section>
            <div class="section-head">
                <h2>Notice board</h2>
                <a href="{{ route('notices.index') }}">All notices →</a>
            </div>
            @forelse($latestNotices as $n)
                @php $date = \Carbon\Carbon::parse($n['date']); @endphp
                <a class="notice" data-kind="{{ ucfirst($n['type'] ?? 'Notice') }}" href="{{ route('notices.show', $n['slug']) }}">
                    <time datetime="{{ $date->toDateString() }}">
                        <b>{{ $date->format('d') }}</b>
                        <i>{{ $date->format('M Y') }}</i>
                    </time>
                    <strong>{{ $n['title'] }} <x-new-badge :until="$n['new_until'] ?? null" /></strong>
                    <small>{{ ucfirst($n['type'] ?? 'Notice') }}</small>
                    <span aria-hidden="true">→</span>
                </a>
            @empty
                <p style="padding:16px 0;color:var(--muted)">No notices published yet.</p>
            @endforelse
        </section>

        <aside class="book-feature" id="featured-book" aria-labelledby="book-heading">
            <p class="eyebrow">From the bookshop</p>
            <div class="book-body">
                <div>
                    <h2 id="book-heading">{{ $featuredBook?->title ?? 'TAC Examination Book' }}</h2>
                    <p class="book-edition">{{ $featuredBook?->defaultEdition()?->edition_name ? 'Edition '.$featuredBook->defaultEdition()->edition_name : 'Edition 2026' }}</p>
                </div>
                <span class="book-jacket" aria-hidden="true">
                    <small>Territorial Army</small>
                    <b>TAC</b>
                    <em>Examination<br>Book</em>
                </span>
            </div>
            <a class="book-link" href="{{ route('books.show', 'tac-examination-book') }}">Explore the book <span aria-hidden="true">→</span></a>
        </aside>
    </div>

    <aside class="frame study-panel">
        <h2>Study library</h2>
        <p>Build your preparation around past papers, practice sets and the examination guide.</p>
        <div>
            <a class="resource" href="{{ route('prep.previous') }}">Previous solved papers <span aria-hidden="true">→</span></a>
            <a class="resource" href="{{ route('prep.practice') }}">Practice papers <span aria-hidden="true">→</span></a>
            <a class="resource" href="{{ route('books.show', 'tac-examination-book') }}">TAC examination book <span aria-hidden="true">→</span></a>
        </div>
    </aside>
@endsection
