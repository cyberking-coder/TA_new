@php
    // Shared layout for long legal pages (disclaimer, privacy, terms).
    // Builds a sticky right-hand table of contents from the given $sections,
    // then renders each section with an anchor.
    //
    // Expected $sections shape:
    //   [ ['id' => 'use', 'title' => 'Terms of use', 'body' => '<html or markdown>'], ... ]
    //
    // $heroTitle, $heroLede, $kicker are also expected.
@endphp

<div class="pagehead">
    <div class="frame">
        <div class="crumb"><a href="{{ route('home') }}">Home</a> / {{ $heroTitle }}</div>
        <h1>{{ $heroTitle }}</h1>
        <p>{{ $heroLede }}</p>
    </div>
</div>

<section class="frame legal-page">
    <aside class="legal-toc" aria-label="On this page">
        <p class="eyebrow">{{ $kicker ?? 'ON THIS PAGE' }}</p>
        <ul>
            @foreach ($sections as $s)
                <li><a href="#{{ $s['id'] }}">{{ $s['title'] }}</a></li>
            @endforeach
        </ul>
        <p class="fine">Last updated {{ $updatedAt ?? now()->format('F Y') }}.</p>
    </aside>

    <article class="legal-body page-body">
        @foreach ($sections as $s)
            <section id="{{ $s['id'] }}" class="legal-section">
                <h2>{{ $s['title'] }}</h2>
                @if (($s['markdown'] ?? true))
                    {!! \Illuminate\Support\Str::markdown((string) $s['body'], ['html_input' => 'allow']) !!}
                @else
                    {!! $s['body'] !!}
                @endif
            </section>
        @endforeach
    </article>
</section>
