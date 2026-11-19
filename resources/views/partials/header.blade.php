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
            <ul class="nav-root">
                <li><a href="{{ route('home') }}"{!! $cur('home') !!}>Home</a></li>

                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Life in Army <span class="caret" aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown" role="menu">
                        <li><a href="{{ url('/pages/what-army-offers-you') }}">What army offers you</a></li>
                        <li><a href="{{ url('/pages/service-benefits') }}">Service benefits</a></li>
                        <li><a href="{{ url('/pages/rules-and-regulations') }}">Rules and regulations</a></li>
                    </ul>
                </li>

                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        TA Units <span class="caret" aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown" role="menu">
                        <li><a href="{{ url('/pages/ta-units') }}">Overview</a></li>
                        <li><a href="{{ url('/pages/ecological-ta') }}">Ecological TA</a></li>
                        <li><a href="{{ url('/pages/departmental-ta') }}">Departmental TA</a></li>
                    </ul>
                </li>

                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Join TA <span class="caret" aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown" role="menu">
                        <li><a href="{{ url('/pages/join-as-jawan') }}">Join as Jawan / JCO</a></li>
                        <li class="has-sub">
                            <button type="button" class="nav-trigger subnav-trigger" aria-expanded="false" aria-haspopup="true">
                                Join as an Officer <span class="caret" aria-hidden="true">▸</span>
                            </button>
                            <ul class="dropdown subdropdown" role="menu">
                                <li><a href="{{ url('/pages/officer-civilian-candidates') }}">For Civilian Candidates</a></li>
                                <li><a href="{{ url('/pages/officer-ex-service') }}">For Ex-Service Officer</a></li>
                                <li><a href="{{ url('/pages/officer-syllabus') }}">Syllabus</a></li>
                                <li class="has-sub">
                                    <button type="button" class="nav-trigger subnav-trigger" aria-expanded="false" aria-haspopup="true">
                                        Service Selection Board <span class="caret" aria-hidden="true">▸</span>
                                    </button>
                                    <ul class="dropdown subdropdown" role="menu">
                                        <li><a href="{{ url('/pages/ssb-selection-center-information') }}">Selection Center Information</a></li>
                                        <li><a href="{{ url('/pages/ssb-interview-instructions') }}">Instruction For SSB Interview</a></li>
                                        <li><a href="{{ url('/pages/ssb-5-days-video') }}">SSB 5 Days Video</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ url('/pages/medical-examination') }}">Medical Examination</a></li>
                                <li><a href="{{ url('/pages/officer-training') }}">Training</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Downloads <span class="caret" aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown" role="menu">
                        <li><a href="{{ route('downloads.index') }}">Important Downloads</a></li>
                    </ul>
                </li>

                <li><a href="{{ url('/pages/ta-policies') }}"{!! $cur('ta-policies') !!}>TA Policies</a></li>

                <li class="has-dropdown">
                    <button type="button" class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Resources <span class="caret" aria-hidden="true">▾</span>
                    </button>
                    <ul class="dropdown" role="menu">
                        <li><a href="{{ route('alerts.officer') }}">SMS Alert</a></li>
                        <li><a href="{{ route('prep.previous') }}">Previous Solved Question Papers</a></li>
                        <li><a href="{{ route('prep.practice') }}">Practice Papers</a></li>
                        <li><a href="{{ route('study.login') }}">Study Material</a></li>
                    </ul>
                </li>

                <li><a href="{{ url('/pages/ta-command-contacts') }}"{!! $cur('contacts') !!}>TA Command Contacts</a></li>
            </ul>
        </nav>

        <div class="tools">
            <a class="login" href="{{ route('study.login') }}">Study login <span aria-hidden="true">→</span></a>
            <button type="button" class="menu" aria-controls="mainnav" aria-expanded="false" aria-label="Open navigation">Menu</button>
        </div>
    </div>
</header>
