<!doctype html>
<html lang="en-IN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Territorial Army Field Guide' }}</title>
    <meta name="description" content="{{ $description ?? 'Independent exam information and preparation guide for the Territorial Army.' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    @isset($robots)<meta name="robots" content="{{ $robots }}">@endisset
    <link rel="icon" href="/assets/mark.svg">

    @php
        $ogTitle = $title ?? 'Territorial Army Field Guide';
        $ogDesc  = $description ?? 'Independent exam information and preparation guide for the Territorial Army.';
        $ogUrl   = $canonical ?? url()->current();
        $ogImage = $ogImage ?? (config('app.url').'/assets/hero.webp');
    @endphp
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Territorial Army Field Guide">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:url" content="{{ $ogUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="theme-color" content="#172e25">
    <link rel="preload" as="font" type="font/woff2" crossorigin href="/assets/display.woff2">
    <link rel="stylesheet" href="/assets/field-guide.css">
    @isset($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @endisset
    <script defer src="/assets/field-guide.js"></script>
</head>
<body class="page-shell">
    <a class="skip" href="#main">Skip to content</a>
    @include('partials.header', ['current' => $nav ?? null])
    <main id="main" class="page-main">
        @yield('content')
        {{ $slot ?? '' }}
    </main>
    @include('partials.footer')
</body>
</html>
