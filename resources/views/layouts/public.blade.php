<!doctype html>
<html lang="en-IN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Territorial Army Field Guide' }}</title>
    <meta name="description" content="{{ $description ?? 'Independent exam information and preparation guide for the Territorial Army.' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    @isset($robots)<meta name="robots" content="{{ $robots }}">@endisset
    <link rel="icon" type="image/svg+xml" href="/assets/mark.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/mark.svg">
    <link rel="apple-touch-icon" href="/assets/mark.svg">
    <link rel="mask-icon" href="/assets/mark.svg" color="#172e25">

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400&family=Inter:wght@400;500;600;700&display=swap">
    @vite(['resources/css/app.css', 'resources/css/public.css', 'resources/js/public.js'])
    @isset($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @endisset
</head>
<body class="page-shell">
    <a class="skip" href="#main">Skip to content</a>
    @include('partials.header', ['current' => $nav ?? null])
    <main id="main" class="page-main">
        @include('partials.flash')
        @yield('content')
        {{ $slot ?? '' }}
    </main>
    @include('partials.footer')
</body>
</html>
