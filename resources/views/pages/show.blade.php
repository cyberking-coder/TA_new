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
