@extends('layouts.public', [
    'title' => 'Under maintenance · Territorial Army Field Guide',
    'description' => 'The site is down for scheduled maintenance. Please check back shortly.',
    'robots' => 'noindex,nofollow',
])

@section('content')
    <section class="frame error-page">
        <p class="error-eyebrow">503</p>
        <h1>Down for scheduled maintenance.</h1>
        <p class="error-lead">
            We&rsquo;re briefly offline for an update. Please check back in a few minutes.
        </p>
    </section>
@endsection
