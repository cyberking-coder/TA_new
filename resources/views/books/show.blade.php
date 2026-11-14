@extends('layouts.public', ['nav' => 'bookshop'])

@section('content')
    <div class="pagehead">
        <div class="frame">
            <div class="crumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ url('/') }}">Bookshop</a> / {{ $book['title'] }}</div>
            <h1>{{ $book['title'] }}</h1>
            <p>Edition {{ $book['edition'] ?: '—' }} · by {{ $book['author'] }}</p>
        </div>
    </div>

    <section class="frame product-page">
        <div class="product-grid">
            <aside class="product-gallery">
                @if ($book['cover_url'])
                    <figure class="product-cover">
                        <img src="{{ $book['cover_url'] }}" alt="{{ $book['title'] }} cover" loading="eager">
                    </figure>
                @else
                    <figure class="product-cover product-cover--placeholder" aria-label="Illustrative TAC examination book cover">
                        <span class="book-jacket" aria-hidden="true">
                            <small>Territorial Army</small>
                            <b>TAC</b>
                            <em>Examination<br>Book</em>
                        </span>
                        <figcaption class="fine">Illustrative cover · upload the real jacket in /admin</figcaption>
                    </figure>
                @endif

                <ul class="product-badges" aria-label="Trust badges">
                    <li><span>&#9679;</span> All-India shipping</li>
                    <li><span>&#9679;</span> Secure payment via CCAvenue</li>
                    <li><span>&#9679;</span> Moderated reviews only</li>
                </ul>
            </aside>

            <article class="product-detail">
                <div class="kicker"><i></i>EDITION {{ $book['edition'] ?: '—' }}</div>
                <h2>{{ $book['title'] }}</h2>
                <p class="product-author">by {{ $book['author'] }}</p>

                @if (!empty($book['description']))
                    <p class="product-lede">{{ $book['description'] }}</p>
                @endif

                <div class="product-pricebox">
                    <div>
                        <span class="product-price">&#8377;{{ number_format($book['price_inr'], 0) }}</span>
                        <span class="product-price-note">INR · shipping extra where applicable</span>
                    </div>
                    <div class="product-stock product-stock--{{ $book['stock_state'] }}">
                        @if ($book['stock_state'] === 'in_stock')
                            In stock · ready to dispatch
                        @elseif ($book['stock_state'] === 'low_stock')
                            Low stock · order soon
                        @else
                            Backorder · dispatch within 2 weeks
                        @endif
                    </div>
                </div>

                <div class="product-cta">
                    <a href="{{ route('checkout.show', $book['slug']) }}" class="button">
                        Buy the book <span aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('reviews.form', $book['slug']) }}" class="underlink">Write a review</a>
                </div>
                <p class="fine">Single-column mobile checkout. Payment runs through CCAvenue. No coupon code required or accepted.</p>

                <section class="product-block">
                    <h3>What you receive</h3>
                    <ul class="product-list">
                        @foreach ($book['included'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </section>

                <section class="product-block">
                    <h3>Why candidates choose it</h3>
                    <div class="product-why">
                        @foreach ($book['why'] as $w)
                            <div>
                                <strong>{{ $w['title'] }}</strong>
                                <p>{{ $w['body'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="product-block">
                    <h3>Specifications</h3>
                    <dl class="product-specs">
                        @foreach ($book['specs'] as $k => $v)
                            <div>
                                <dt>{{ $k }}</dt>
                                <dd>{{ $v }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>

                <section class="product-block">
                    <h3>Delivery &amp; study access</h3>
                    <p>{{ $book['delivery_blurb'] }}</p>
                </section>
            </article>
        </div>

        @if (!empty($book['sample_reviews']))
            <section class="product-reviews">
                <div class="section-head">
                    <h2>Verified reviews</h2>
                    <a href="{{ route('reviews.form', $book['slug']) }}">Write a review →</a>
                </div>
                <div class="product-reviews-grid">
                    @foreach ($book['sample_reviews'] as $r)
                        <article class="review-card">
                            <header>
                                <strong>{{ $r['name'] }}</strong>
                                <span class="review-rating" aria-label="{{ $r['rating'] }} out of 5">
                                    @for ($s = 1; $s <= 5; $s++){{ $s <= $r['rating'] ? '★' : '☆' }}@endfor
                                </span>
                            </header>
                            <p>{{ $r['body'] }}</p>
                            <small>Moderated · published after review</small>
                        </article>
                    @endforeach
                </div>
            </section>
        @else
            <section class="product-reviews">
                <div class="section-head">
                    <h2>Verified reviews</h2>
                    <a href="{{ route('reviews.form', $book['slug']) }}">Write the first review →</a>
                </div>
                <p class="fine" style="padding:16px 0">No published reviews yet. Reviews are moderated before they appear.</p>
            </section>
        @endif
    </section>
@endsection
