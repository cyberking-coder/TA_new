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
                <li><a href="{{ route('downloads.index') }}"{!! $cur('downloads') !!}>Downloads</a></li>
                <li><a href="{{ url('/pages/ta-command-contacts') }}"{!! $cur('contacts') !!}>TA Command Contacts</a></li>
            </ul>

            <ul class="nav-row nav-row-bottom">
                <li><a href="{{ url('/pages/join-as-jawan') }}"{!! $cur('jawan') !!}>Jawan / JCO Entry</a></li>
                <li class="nav-sep" aria-hidden="true"></li>
                <li><a href="{{ url('/pages/join-as-officer') }}"{!! $cur('officer') !!}>Officer Entry</a></li>
                <li class="nav-sep" aria-hidden="true"></li>
                <li><a href="{{ route('alerts.officer') }}"{!! $cur('alerts') !!}>SMS Alerts</a></li>
                <li class="nav-sep" aria-hidden="true"></li>
                <li><a href="{{ route('prep.previous') }}"{!! $cur('previous') !!}>Solved Papers</a></li>
                <li class="nav-sep" aria-hidden="true"></li>
                <li><a href="{{ route('prep.practice') }}"{!! $cur('practice') !!}>Practice Papers</a></li>
                <li class="nav-sep" aria-hidden="true"></li>
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
