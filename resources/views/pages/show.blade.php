@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / {{ $page->title }}</div>
            <h1>{{ $page->title }}</h1>
            @if ($page->seo_description)
                <p>{{ $page->seo_description }}</p>
            @endif
        </div>
    </div>

    <section class="wrap panel">
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

        <article class="reading page-body">
            @php
                // Pages authored as Markdown get parsed; anything that looks like
                // raw HTML (starts with "<") is passed through unchanged.
                $body = (string) $page->body;
                $looksLikeHtml = str_starts_with(trim($body), '<');
            @endphp
            @if ($looksLikeHtml)
                {!! $body !!}
            @else
                {!! \Illuminate\Support\Str::markdown($body, ['html_input' => 'allow']) !!}
            @endif
        </article>
    </section>
@endsection
