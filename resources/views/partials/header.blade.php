@php
    $current = $current ?? '';
    $cur = fn ($name) => $current === $name ? ' aria-current="page"' : '';
@endphp
<header>
    <div class="frame mast">
        <a class="brand" href="{{ route('home') }}" aria-label="Territorial Army Field Guide — home">
            <img src="/assets/mark.svg" alt="" width="36" height="44">
            <span>Territorial Army<small>FIELD GUIDE</small></span>
        </a>

        <nav id="mainnav" aria-label="Main">
            <ul class="nav-root">
                <li><a href="{{ route('home') }}"{!! $cur('home') !!}>Home</a></li>

                {{-- About the TA — merges former "Life in Army" and "TA Units" plus policies. --}}
                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        About the TA <span class="caret" aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown" role="menu">
                        <li class="dropdown-group">Life in the Army</li>
                        <li><a href="{{ url('/pages/what-army-offers-you') }}">What the Army offers you</a></li>
                        <li><a href="{{ url('/pages/service-benefits') }}">Service benefits</a></li>
                        <li><a href="{{ url('/pages/rules-and-regulations') }}">Rules &amp; regulations</a></li>
                        <li class="dropdown-sep" aria-hidden="true"></li>
                        <li class="dropdown-group">TA Units</li>
                        <li><a href="{{ url('/pages/ecological-ta') }}">Ecological TA</a></li>
                        <li><a href="{{ url('/pages/departmental-ta') }}">Departmental TA</a></li>
                        <li class="dropdown-sep" aria-hidden="true"></li>
                        <li><a href="{{ url('/pages/ta-policies') }}"{!! $cur('ta-policies') !!}>TA Policies</a></li>
                    </ul>
                </li>

                {{-- Join the TA — all Jawan + Officer + SSB + medical + training in one flat list. --}}
                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Join the TA <span class="caret" aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown" role="menu">
                        <li><a href="{{ url('/pages/join-as-jawan') }}">Join as Jawan / JCO</a></li>
                        <li class="dropdown-sep" aria-hidden="true"></li>
                        <li class="dropdown-group">Join as an Officer</li>
                        <li><a href="{{ url('/pages/officer-civilian-candidates') }}">Civilian candidates</a></li>
                        <li><a href="{{ url('/pages/officer-ex-service') }}">Ex-service officers</a></li>
                        <li><a href="{{ url('/pages/officer-syllabus') }}">Syllabus &amp; pattern</a></li>
                        <li><a href="{{ url('/pages/medical-examination') }}">Medical examination</a></li>
                        <li><a href="{{ url('/pages/officer-training') }}">Training</a></li>
                        <li class="dropdown-sep" aria-hidden="true"></li>
                        <li class="dropdown-group">SSB Interview</li>
                        <li><a href="{{ url('/pages/ssb-selection-center-information') }}">Selection centres</a></li>
                        <li><a href="{{ url('/pages/ssb-interview-instructions') }}">Interview instructions</a></li>
                        <li><a href="{{ url('/pages/ssb-5-days-video') }}">SSB — 5-day walkthrough</a></li>
                    </ul>
                </li>

                {{-- Prepare — study-focused: papers, practice, study material, downloads. --}}
                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Prepare <span class="caret" aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown" role="menu">
                        <li><a href="{{ route('prep.previous') }}">Previous solved papers</a></li>
                        <li><a href="{{ route('prep.practice') }}">Practice papers</a></li>
                        <li><a href="{{ route('study.login') }}">Study material</a></li>
                        <li class="dropdown-sep" aria-hidden="true"></li>
                        <li><a href="{{ route('downloads.index') }}">Important downloads</a></li>
                        <li><a href="{{ route('notices.index') }}">Notices &amp; notifications</a></li>
                    </ul>
                </li>

                {{-- Alerts & Contact — SMS + results + command contacts. --}}
                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Alerts &amp; Contact <span class="caret" aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown" role="menu">
                        <li><a href="{{ route('alerts.gd') }}">GD / Soldier SMS alerts</a></li>
                        <li><a href="{{ route('alerts.officer') }}">Officer SMS alerts</a></li>
                        <li><a href="{{ route('result.form') }}">Result notification registration</a></li>
                        <li class="dropdown-sep" aria-hidden="true"></li>
                        <li><a href="{{ url('/pages/ta-command-contacts') }}"{!! $cur('contacts') !!}>TA Command Contacts</a></li>
                        <li><a href="{{ url('/pages/contact-us') }}">Contact us</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="tools">
            <a class="login" href="{{ route('study.login') }}">Study login <span aria-hidden="true">→</span></a>
            <button type="button" class="menu" aria-controls="mainnav" aria-expanded="false" aria-label="Open navigation">Menu</button>
        </div>
    </div>
</header>
