@php
    $current = $current ?? '';
    $cur = fn ($name) => $current === $name ? ' aria-current="page"' : '';
@endphp
<header>
    <div class="wrap mast">
        <a class="brand" href="{{ route('home') }}" aria-label="Territorial Army Field Guide home">
            @include('partials.crest')
            <span>Territorial Army<small>FIELD GUIDE</small></span>
        </a>
        <nav aria-label="Main navigation" id="mainnav">
            <a href="{{ route('home') }}"{!! $cur('home') !!}>Home</a>
            <a href="{{ route('notices.index') }}"{!! $cur('notices') !!}>Notices</a>
            <a href="{{ route('downloads.index') }}"{!! $cur('downloads') !!}>Downloads</a>
            <a href="{{ route('prep.previous') }}"{!! $cur('previous') !!}>Previous Papers</a>
            <a href="{{ route('prep.practice') }}"{!! $cur('practice') !!}>Practice Papers</a>
            <a href="{{ route('alerts.officer') }}"{!! $cur('alerts') !!}>Alerts</a>
            <a href="{{ route('books.show', 'tac-examination-book') }}"{!! $cur('bookshop') !!}>Bookshop</a>
        </nav>
        <div class="nav-actions">
            <a class="login" href="{{ route('study.login') }}">
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="5.5" y="10" width="13" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/>
                </svg>
                Study login
            </a>
            <button class="menu" aria-label="Open navigation" aria-controls="mainnav" aria-expanded="false">
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 6h18M3 12h18M3 18h18"/>
                </svg>
            </button>
        </div>
    </div>
</header>
