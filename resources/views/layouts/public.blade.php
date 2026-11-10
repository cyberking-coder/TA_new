<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Territorial Army exam guide' }}</title>
    <meta name="description" content="{{ $description ?? 'Notices, preparation resources and the Territorial Army Commission examination book.' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    @isset($robots)<meta name="robots" content="{{ $robots }}">@endisset
    @isset($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @endisset
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @if(config('app.env') !== 'production')
        <div class="synthetic-banner" role="status" aria-live="polite">
            Development build · synthetic fixtures only · not for public release
        </div>
    @endif

    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:bg-white focus:text-forest-700 focus:px-3 focus:py-2 focus:z-50">Skip to main content</a>

    @include('partials.header')

    <main id="main">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
