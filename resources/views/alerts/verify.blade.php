@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-2xl">
            <h1>{{ $heading }}</h1>
            <p class="text-lg">{{ $blurb }}</p>
            <p class="mt-6"><a href="{{ route('home') }}" class="btn-ghost">Back to home</a></p>
        </div>
    </section>
@endsection
