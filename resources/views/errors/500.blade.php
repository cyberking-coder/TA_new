@extends('layouts.public', [
    'title' => 'Something went wrong · Territorial Army Field Guide',
    'description' => 'A server error occurred. Please try again shortly.',
    'robots' => 'noindex,nofollow',
])

@section('content')
    <section class="frame error-page">
        <p class="error-eyebrow">500</p>
        <h1>Something went wrong on our end.</h1>
        <p class="error-lead">
            We hit a server error. The team has been notified. Please try again in a moment,
            or return to the home page.
        </p>
        <p class="error-actions">
            <a class="btn" href="{{ route('home') }}">Back to Home</a>
            <a class="btn btn-ghost" href="{{ url('/pages/contact-us') }}">Contact support</a>
        </p>
    </section>
@endsection
