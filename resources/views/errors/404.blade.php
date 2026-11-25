@extends('layouts.public', [
    'title' => 'Page not found · Territorial Army Field Guide',
    'description' => 'The page you were looking for has moved or no longer exists.',
    'robots' => 'noindex,nofollow',
])

@section('content')
    <section class="frame error-page">
        <p class="error-eyebrow">404</p>
        <h1>This page is off the map.</h1>
        <p class="error-lead">
            The page you were looking for has moved, been renamed, or no longer exists.
            Try one of the routes below, or head back to the home page.
        </p>

        <div class="error-grid">
            <a class="error-card" href="{{ route('home') }}">
                <h2>Home</h2>
                <p>Start again from the Field Guide home page.</p>
            </a>
            <a class="error-card" href="{{ route('notices.index') }}">
                <h2>Notices &amp; Downloads</h2>
                <p>Latest notifications, recruitment rallies and official PDFs.</p>
            </a>
            <a class="error-card" href="{{ url('/pages/join-as-jawan') }}">
                <h2>Join the TA</h2>
                <p>Eligibility, exam pattern and application steps.</p>
            </a>
            <a class="error-card" href="{{ url('/pages/contact-us') }}">
                <h2>Contact Us</h2>
                <p>Reach the right TA Group Headquarters.</p>
            </a>
        </div>
    </section>
@endsection
