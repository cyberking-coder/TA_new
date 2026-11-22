@extends('layouts.public')

@php
    // Render the body: Markdown unless it already looks like HTML. Then add
    // stable anchor IDs to every h2 / h3 so the sidebar ToC can jump into
    // them.
    $raw = (string) $page->body;
    $looksLikeHtml = str_starts_with(trim($raw), '<');
    $html = $looksLikeHtml
        ? $raw
        : (string) \Illuminate\Support\Str::markdown($raw, ['html_input' => 'allow']);

    // Inject slug IDs on h2 / h3 (preserve any that are already there).
    $html = preg_replace_callback(
        '#<(h[23])(\b[^>]*)>(.*?)</\1>#is',
        function ($m) {
            $attrs = $m[2];
            $inner = trim(strip_tags($m[3]));
            if (preg_match('/\bid\s*=/i', $attrs)) return $m[0];
            $id = \Illuminate\Support\Str::slug($inner) ?: 'section-'.substr(md5($inner), 0, 6);
            return "<{$m[1]}{$attrs} id=\"{$id}\">{$m[3]}</{$m[1]}>";
        },
        $html
    );

    // Pull the h2 list out for the sidebar ToC.
    $toc = [];
    if (preg_match_all('#<h2\b[^>]*\bid=\"([^\"]+)\"[^>]*>(.*?)</h2>#is', $html, $mm, PREG_SET_ORDER)) {
        foreach ($mm as $row) {
            $toc[] = ['id' => $row[1], 'title' => trim(strip_tags($row[2]))];
        }
    }
@endphp

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / {{ $page->title }}</div>
            <h1>{{ $page->title }}</h1>
            @if ($page->seo_description)
                <p>{{ $page->seo_description }}</p>
            @endif
        </div>
    </div>

    <section class="frame page-doc">
        <article class="page-doc-body page-body">
            @if ($page->pdf)
                @php $pdf = $page->pdf; @endphp
                <aside class="page-pdf-card" aria-label="Attached document">
                    <span class="page-pdf-ico" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/></svg>
                    </span>
                    <div class="page-pdf-meta">
                        <strong>{{ $pdf->download_label ?: $pdf->title }}</strong>
                        <small>PDF &middot; {{ $pdf->sizeLabel() }} &middot; updated {{ $pdf->updated_at?->format('d M Y') }}</small>
                    </div>
                    <a class="page-pdf-cta" href="{{ route('pdfs.public', $pdf->id) }}" target="_blank" rel="noopener">
                        Open PDF <span aria-hidden="true">→</span>
                    </a>
                </aside>
            @endif

            {!! $html !!}
        </article>

        @if (count($toc) >= 2)
            <aside class="page-doc-toc" aria-label="On this page">
                <p class="eyebrow">ON THIS PAGE</p>
                <ul>
                    @foreach ($toc as $i)
                        <li><a href="#{{ $i['id'] }}">{{ $i['title'] }}</a></li>
                    @endforeach
                </ul>
                <p class="fine">Updated {{ $page->updated_at?->format('F Y') }}.</p>
            </aside>
        @endif
    </section>
@endsection
