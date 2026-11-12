@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose max-w-md">
            <h1>{{ $heading }}</h1>
            <p>{{ $blurb }}</p>
            <p class="mt-5"><a href="{{ route('study.login') }}" class="btn-primary">Go to study login</a></p>
        </div>
    </section>
@endsection
