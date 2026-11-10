@extends('layouts.public')

@section('content')
    <section class="section-pad">
        <div class="container-prose grid gap-6 md:grid-cols-5 md:gap-10 items-center">
            <div class="md:col-span-3 order-1 md:order-1">
                <p class="badge">Exam information &middot; books</p>
                <h1 class="mt-3">Territorial Army exam guide</h1>
                <p class="text-lg mt-3 text-[color:var(--color-charcoal-600)]">
                    Latest notices, preparation resources and the Commission examination book — for officers, soldiers and civilian candidates.
                </p>
                <p class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('notices.index') }}" class="btn-primary">View latest notices</a>
                    <a href="{{ route('books.show', 'tac-examination-book') }}" class="btn-ghost">About the book</a>
                </p>
            </div>
            <div class="md:col-span-2 order-2 md:order-2">
                <figure class="m-0">
                    <div class="hero-placeholder" role="img" aria-label="Editorial hero placeholder — replaced with a licensed landscape or training photo before launch.">
                        <figcaption class="text-sm font-normal">Hero placeholder — licensed image pending.</figcaption>
                    </div>
                </figure>
            </div>
        </div>
    </section>

    <section class="section-pad bg-white border-y border-[color:var(--color-ivory-100)]">
        <div class="container-prose">
            <h2 class="sr-only">Choose a task</h2>
            <ul class="grid gap-3 md:grid-cols-4 list-none p-0 m-0">
                <li><a href="{{ route('notices.index') }}" class="tile"><strong>Notices &amp; results</strong><span>Dated recruitment notices and result updates.</span></a></li>
                <li><a href="{{ route('alerts.officer') }}" class="tile"><strong>Officer / soldier alerts</strong><span>Subscribe to exam alerts by audience.</span></a></li>
                <li><a href="{{ route('books.show', 'tac-examination-book') }}" class="tile"><strong>Buy the book</strong><span>Edition, price and delivery in INR.</span></a></li>
                <li><a href="{{ route('study.login') }}" class="tile"><strong>Study login</strong><span>Buyer-only study material access.</span></a></li>
            </ul>
        </div>
    </section>

    <section class="section-pad">
        <div class="container-prose grid gap-8 md:grid-cols-3">
            <div class="md:col-span-2">
                <div class="flex items-baseline justify-between gap-3 mb-2">
                    <h2 class="m-0">Latest notices</h2>
                    <a href="{{ route('notices.index') }}" class="text-sm">View all notices</a>
                </div>
                <ul class="list-none p-0 m-0">
                    @foreach($latestNotices as $notice)
                        <li class="notice-row">
                            <time datetime="{{ $notice['date'] }}">{{ \Carbon\Carbon::parse($notice['date'])->format('d M Y') }}</time>
                            <a href="{{ route('notices.show', $notice['slug']) }}">{{ $notice['title'] }}</a>
                            <p class="m-0 mt-1 text-sm text-[color:var(--color-charcoal-600)]">{{ $notice['summary'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
            <aside>
                <h2 class="m-0 mb-3">The book</h2>
                <div class="card">
                    <div class="grid grid-cols-[96px_1fr] gap-4 items-start">
                        <div class="book-placeholder" role="img" aria-label="Book cover placeholder — real cover supplied before launch.">
                            <span class="book-top">TA</span>
                            <span class="book-title">Commission Examination Book</span>
                            <span class="book-bottom">Edition 2026</span>
                        </div>
                        <div>
                            <p class="font-display font-semibold m-0 text-[color:var(--color-forest-700)]">Territorial Army Commission Examination Book</p>
                            <p class="m-0 mt-1 text-sm text-[color:var(--color-charcoal-600)]">Edition 2026 &middot; ships in India</p>
                            <p class="m-0 mt-2 text-xl font-semibold">&#8377;1,998</p>
                            <p class="mt-3 m-0"><a href="{{ route('books.show', 'tac-examination-book') }}" class="btn-primary">Buy the book</a></p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <section class="section-pad bg-white border-t border-[color:var(--color-ivory-100)]">
        <div class="container-prose">
            <h2 class="m-0">About this site</h2>
            <p class="mt-3 max-w-prose">
                This site publishes exam notices, preparation resources and the Territorial Army Commission examination book.
                Links marked <span class="badge">Official source</span> open the issuing authority's own document in a new tab. Alert signups are independent lists run by this publisher.
            </p>
            <p class="mt-2 max-w-prose text-sm text-[color:var(--color-charcoal-600)]">
                Historical articles are reachable from <a href="{{ route('notices.index') }}">Notices &amp; results</a> and footer policy pages.
            </p>
        </div>
    </section>
@endsection
