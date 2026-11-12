@extends('layouts.public', ['nav' => 'bookshop'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a> /
                <span aria-current="page">Review received</span>
            </div>
            <h1>Thank you</h1>
            <p>Your review is in the moderation queue.</p>
        </div>
    </div>

    <section class="wrap panel">
        <article class="reading" style="text-align:center">
            <div class="return-status return-ok" aria-hidden="true">✓</div>
            <h2>Review submitted</h2>
            <p>Our staff will review your submission within a day or two. Approved reviews appear on <a href="{{ route('books.show', $book->slug) }}">the book page</a>. Thank you for taking the time.</p>
            <p style="margin-top:22px">
                <a class="btn" href="{{ route('books.show', $book->slug) }}">Back to the book</a>
            </p>
        </article>
    </section>
@endsection
