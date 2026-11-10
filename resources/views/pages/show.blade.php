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
        <article class="reading">
            {!! $page->body !!}
        </article>
    </section>
@endsection
