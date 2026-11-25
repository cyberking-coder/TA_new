@extends('layouts.public', [
    'title'       => ($page->seo_title ?: $page->title).' | Territorial Army Field Guide',
    'description' => $page->seo_description
        ?: \Illuminate\Support\Str::limit(trim(strip_tags((string) $page->body)), 155),
])

@php
    $raw = (string) $page->body;
    $looksLikeHtml = str_starts_with(trim($raw), '<');
    $html = $looksLikeHtml
        ? $raw
        : (string) \Illuminate\Support\Str::markdown($raw, ['html_input' => 'allow']);

    // Slug-id every h2 / h3 + collect h2 titles for the glance sidebar.
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

    // Editorial decoration: wrap each h2 section in a .doc-section with an
    // auto-generated eyebrow (first word of the heading, uppercase). A
    // horizontal rule is injected between sections by CSS via :not(:first-child).
    $html = preg_replace_callback(
        '#<h2(\b[^>]*)>(.*?)</h2>#is',
        function ($m) {
            $heading = trim(strip_tags($m[2]));
            // Eyebrow = first 1-2 words if the title is long; otherwise use
            // the whole heading uppercased for shorter titles.
            $words = preg_split('/\s+/', $heading);
            $eyebrow = count($words) > 3
                ? strtoupper(implode(' ', array_slice($words, 0, 1)))
                : strtoupper($heading);
            return '<p class="doc-eyebrow" aria-hidden="true">'.htmlspecialchars($eyebrow).'</p>'
                 .'<h2'.$m[1].'>'.$m[2].'</h2>';
        },
        $html
    );

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
            <aside class="page-doc-glance" aria-label="At a glance">
                <h3>At a glance</h3>
                <ul class="glance-list">
                    @foreach ($toc as $i => $item)
                        <li>
                            <span class="glance-num">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <a href="#{{ $item['id'] }}">{{ $item['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
                <p class="fine">Updated {{ $page->updated_at?->format('F Y') }}.</p>
            </aside>
        @endif
    </section>
@endsection
