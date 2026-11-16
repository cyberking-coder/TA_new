@php
    $current = $current ?? '';
    $cur = fn ($name) => $current === $name ? ' aria-current="page"' : '';
@endphp
<header>
    <div class="frame mast">
        <a class="brand" href="{{ route('home') }}">
            <img src="/assets/mark.svg" alt="" width="36" height="44">
            <span>Territorial Army<small>FIELD GUIDE</small></span>
        </a>

        <nav id="mainnav" aria-label="Main">
            <a href="{{ url('/pages/entry-routes') }}"{!! $cur('entry-routes') !!}>Entry routes</a>
            <a href="{{ url('/pages/life-in-army') }}"{!! $cur('information') !!}>Life &amp; units</a>
            <a href="{{ route('notices.index') }}"{!! $cur('notices') !!}>Notices</a>
            <a href="{{ route('prep.previous') }}"{!! $cur('preparation') !!}>Study library</a>
            <a href="{{ route('alerts.officer') }}"{!! $cur('alerts') !!}>Alerts</a>
            <a href="{{ route('books.show', 'tac-examination-book') }}"{!! $cur('bookshop') !!}>Bookshop</a>
        </nav>

        <div class="tools">
            <a class="login" href="{{ route('study.login') }}">Study login <span aria-hidden="true">→</span></a>
            <button type="button" class="menu" aria-controls="mainnav" aria-expanded="false" aria-label="Open navigation">Menu</button>
        </div>
    </div>
</header>
