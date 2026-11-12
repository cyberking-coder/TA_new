<footer>
    <div class="wrap foot">
        <a class="brand" href="{{ route('home') }}" aria-label="Territorial Army Field Guide home">
            @include('partials.crest')
            <span>Territorial Army<small>FIELD GUIDE</small></span>
        </a>
        <div>
            <a href="{{ route('alerts.officer') }}">Exam alerts</a>
            <a href="{{ route('study.login') }}">Study access</a>
            <a href="{{ url('/pages/about-us') }}">About this guide</a>
            <a href="{{ url('/pages/privacy') }}">Privacy</a>
            <a href="{{ url('/pages/terms') }}">Terms</a>
        </div>
        <small>Independent exam information &amp; preparation guide.</small>
    </div>
</footer>
