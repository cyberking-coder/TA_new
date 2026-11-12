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
    <link rel="preload" as="font" type="font/woff2" crossorigin href="/assets/display.woff2">
    <link rel="stylesheet" href="/assets/field-guide.css">
    @isset($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @endisset
    <script defer src="/assets/field-guide.js"></script>
</head>
<body>
    <a class="skip" href="#main">Skip to content</a>
    @include('partials.header', ['current' => $nav ?? null])
    <main id="main">
        @yield('content')
        {{ $slot ?? '' }}
    </main>
    @include('partials.footer')
</body>
</html>
