@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose grid gap-8 md:grid-cols-5">
            <div class="md:col-span-2">
                <figure class="m-0 max-w-xs">
                    <div class="book-placeholder" role="img" aria-label="Book cover placeholder — real cover artwork supplied before launch.">
                        <span class="book-top">TA</span>
                        <span class="book-title">{{ $book['title'] }}</span>
                        <span class="book-bottom">Edition {{ $book['edition'] }}</span>
                    </div>
                    <figcaption class="text-xs mt-2 text-[color:var(--color-charcoal-600)]">
                        Placeholder cover — real artwork to be supplied by the client.
                    </figcaption>
                </figure>
            </div>
            <div class="md:col-span-3">
                <nav aria-label="Breadcrumb" class="text-sm text-[color:var(--color-charcoal-600)] mb-2">
                    <a href="{{ route('home') }}">Home</a> / <span aria-current="page">{{ $book['title'] }}</span>
                </nav>
                <h1>{{ $book['title'] }}</h1>
                <p class="text-[color:var(--color-charcoal-600)] mt-1">Edition {{ $book['edition'] }} &middot; by {{ $book['author'] }}</p>

                <p class="text-3xl mt-4 font-semibold text-[color:var(--color-forest-700)]">
                    &#8377;{{ number_format($book['price_inr'], 0) }}
                </p>
                <p class="text-sm text-[color:var(--color-charcoal-600)] mt-0">Prices in INR, shipping extra for cash-on-delivery where available.</p>

                <div class="mt-5">
                    <p class="font-semibold m-0 text-[color:var(--color-forest-700)]">What you receive</p>
                    <ul class="list-disc pl-5 mt-1 m-0 text-base">
                        @foreach($book['included'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-5">
                    <p class="font-semibold m-0 text-[color:var(--color-forest-700)]">Delivery &amp; study access</p>
                    <p class="m-0 mt-1 text-base">{{ $book['delivery_blurb'] }}</p>
                </div>

                <p class="mt-6">
                    <a href="{{ route('checkout.show', $book['slug']) }}" class="btn-primary">Buy the book</a>
                </p>
                <p class="mt-2 text-xs text-[color:var(--color-charcoal-600)]">
                    Checkout opens a single-column mobile-friendly form. Payment runs through CCAvenue. No coupon code is required or accepted.
                </p>
            </div>
        </div>

        <div class="container-prose mt-10">
            <h2>Verified reviews</h2>
            <p class="text-sm text-[color:var(--color-charcoal-600)] max-w-prose">
                Reviews are shown only after a staff moderator has approved them. Synthetic placeholders below validate layout; real reviews land in Phase 2.
            </p>
            <ul class="list-none p-0 m-0 mt-4 grid gap-4 md:grid-cols-2">
                @foreach($book['sample_reviews'] as $r)
                    <li class="card">
                        <p class="m-0 font-semibold text-[color:var(--color-forest-700)]">{{ $r['name'] }}</p>
                        <p class="m-0 text-xs text-[color:var(--color-charcoal-600)]">Rating: {{ $r['rating'] }} / 5 &middot; moderated</p>
                        <p class="mt-2 m-0">{{ $r['body'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endsection
