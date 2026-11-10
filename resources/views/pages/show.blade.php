@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-3xl">
            <nav aria-label="Breadcrumb" class="text-sm text-[color:var(--color-charcoal-600)] mb-2">
                <a href="{{ route('home') }}">Home</a> / <span aria-current="page">{{ $page->title }}</span>
            </nav>
            <h1>{{ $page->title }}</h1>
            <div class="mt-5 prose-sm max-w-none text-[17px] leading-[1.6]">
                {!! $page->body !!}
            </div>
        </div>
    </section>
@endsection
