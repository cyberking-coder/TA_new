@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-2xl">
            <h1>{{ $heading }}</h1>
            <p class="text-lg text-[color:var(--color-charcoal-600)]">{{ $blurb }}</p>
            <p class="mt-5 badge">Builds in {{ $phaseLabel }}</p>
            <p class="mt-5">
                <a href="{{ route('home') }}" class="btn-ghost">Back to home</a>
            </p>
        </div>
    </section>
@endsection
