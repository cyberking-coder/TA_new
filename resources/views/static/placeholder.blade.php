@extends('layouts.public')

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / {{ $heading }}</div>
            <h1>{{ $heading }}</h1>
            <p>{{ $blurb }}</p>
        </div>
    </div>
    <section class="wrap panel">
        <article class="reading">
            <p><span class="kicker" style="display:inline-flex;align-items:center;gap:8px"><i></i>BUILDS IN {{ strtoupper($phaseLabel) }}</span></p>
            <p><a class="btn" href="{{ route('home') }}">Back to home<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h15m-6-6 6 6-6 6"/></svg></a></p>
        </article>
    </section>
@endsection
