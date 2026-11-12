@extends('layouts.public', ['nav' => 'bookshop'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / Bookshop</div>
            <h1>Bookshop</h1>
            <p>Preparation resources for the Territorial Army Commission examination.</p>
        </div>
    </div>

    <section class="wrap panel">
        <div class="product">
            <div class="showcase">
                <div class="kicker"><i></i>FROM THE BOOKSHOP</div>
                <div class="bookart" aria-label="Illustrative TAC examination book cover" role="img">
                    <div class="bookcover">
                        <small>TERRITORIAL ARMY</small>
                        <b>TAC</b>
                        <em>EXAMINATION<br>BOOK</em>
                        <span>PREPARATION SERIES</span>
                    </div>
                </div>
                <small>Illustrative cover; real artwork before launch.</small>
            </div>
            <article>
                <div class="kicker"><i></i>EDITION {{ $book['edition'] ?? '' }}</div>
                <h2>{{ $book['title'] }}</h2>
                <p>by {{ $book['author'] ?? 'Publisher' }}</p>
                <p class="price">&#8377;{{ number_format($book['price_inr'], 0) }} <span>INR, shipping extra where applicable</span></p>

                <h3 style="font:700 22px var(--display);margin:22px 0 6px">What you receive</h3>
                <ul>
                    @foreach ($book['included'] as $item)<li>{{ $item }}</li>@endforeach
                </ul>

                <h3 style="font:700 22px var(--display);margin:22px 0 6px">Delivery &amp; study access</h3>
                <p>{{ $book['delivery_blurb'] }}</p>

                <p style="margin-top:22px">
                    <a href="{{ route('checkout.show', $book['slug']) }}" class="btn">
                        Buy the book
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h15m-6-6 6 6-6 6"/></svg>
                    </a>
                </p>
                <p class="fine">Single-column mobile checkout. Payment runs through CCAvenue. No coupon code required or accepted.</p>
            </article>
        </div>

        @if (!empty($book['sample_reviews']))
            <section style="margin-top:48px">
                <div class="section-head">
                    <div><div class="kicker"><i></i>MODERATED</div><h2>Verified reviews</h2></div>
                    <a class="textlink" href="{{ route('reviews.form', $book['slug']) }}">
                        Write a review
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h15m-6-6 6 6-6 6"/></svg>
                    </a>
                </div>
                <div class="resource-grid" style="margin-top:20px">
                    @foreach ($book['sample_reviews'] as $r)
                        <div class="quick" style="cursor:default;align-items:flex-start">
                            <strong>{{ $r['name'] }}</strong>
                            <span>Rating: {{ $r['rating'] }} / 5 · moderated</span>
                            <p style="font-size:15px;color:var(--mute);margin:8px 0 0">{{ $r['body'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </section>
@endsection
