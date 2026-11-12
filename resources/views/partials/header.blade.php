@php
    $current = $current ?? '';
    $cur = fn ($name) => $current === $name ? ' aria-current="page"' : '';
@endphp
<header class="site-header">
    <div class="wrap mast">
        <a class="brand" href="{{ route('home') }}" aria-label="Territorial Army Field Guide home">
            @include('partials.crest')
            <span>Territorial Army<small>FIELD GUIDE</small></span>
        </a>

        <nav class="primary-nav" aria-label="Primary navigation" id="mainnav">
            <ul class="nav-row nav-row-top">
                <li><a href="{{ route('home') }}"{!! $cur('home') !!}>Home</a></li>

                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Life in Army <span aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown">
                        <li><a href="{{ url('/pages/life-in-army') }}">Overview</a></li>
                        <li><a href="{{ url('/pages/what-army-offers-you') }}">What the army offers you</a></li>
                        <li><a href="{{ url('/pages/service-benefits') }}">Service benefits</a></li>
                        <li><a href="{{ url('/pages/rules-and-regulations') }}">Rules and regulations</a></li>
                    </ul>
                </li>

                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        TA Units <span aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown">
                        <li><a href="{{ url('/pages/ta-units') }}">Overview</a></li>
                        <li><a href="{{ url('/pages/ecological-ta') }}">Ecological TA</a></li>
                        <li><a href="{{ url('/pages/departmental-ta') }}">Departmental TA</a></li>
                    </ul>
                </li>

                <li><a href="{{ url('/pages/ta-policies') }}"{!! $cur('ta-policies') !!}>TA Policies</a></li>
                <li><a href="{{ route('downloads.index') }}"{!! $cur('downloads') !!}>Important Downloads</a></li>
                <li><a href="{{ url('/pages/ta-command-contacts') }}"{!! $cur('contacts') !!}>TA Command Contacts</a></li>

                <li class="nav-social">
                    <a href="https://facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13.5 22v-8.5h2.9l.4-3.4h-3.3V8c0-1 .3-1.7 1.7-1.7h1.8V3.3c-.3 0-1.3-.1-2.5-.1-2.4 0-4.1 1.5-4.1 4.2v2.3H7.5v3.4h2.9V22z"/></svg>
                    </a>
                    <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M18.244 2H21l-6.56 7.5L22 22h-6.78l-4.73-6.17L5 22H2.24l7.02-8.02L2 2h6.95l4.27 5.65zm-1.19 18h1.5L7.07 4H5.47z"/></svg>
                    </a>
                </li>
            </ul>

            <ul class="nav-row nav-row-bottom">
                <li><a href="{{ url('/pages/join-as-jawan') }}"{!! $cur('jawan') !!}>Join as a Jawan / JCO</a></li>

                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Join as an Officer <span aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown">
                        <li><a href="{{ url('/pages/join-as-officer') }}">Overview</a></li>
                        <li><a href="{{ route('alerts.officer') }}">Officer alerts &amp; result registration</a></li>
                    </ul>
                </li>

                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        <span class="chip-new">NEW</span>
                        SMS Alert For Further Notification/Results <span aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown">
                        <li><a href="{{ route('alerts.officer') }}">Officer alerts</a></li>
                        <li><a href="{{ route('alerts.soldier') }}">Soldier alerts</a></li>
                    </ul>
                </li>

                <li><a href="{{ route('prep.previous') }}"{!! $cur('previous') !!}>Previous Solved Question Papers</a></li>
                <li><a href="{{ route('prep.practice') }}"{!! $cur('practice') !!}>Practice Papers</a></li>
                <li><a href="{{ route('study.login') }}"{!! $cur('study') !!}>Study Material</a></li>
            </ul>
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
