@extends('layouts.public', ['nav' => 'bookshop'])

@section('content')
    <div class="pagehead">
        <div class="wrap">
            <div class="crumb">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a> /
                <span aria-current="page">Write a review</span>
            </div>
            <h1>Write a review</h1>
            <p>Your review helps other candidates. Staff moderate every submission before it appears on the book page.</p>
        </div>
    </div>

    <section class="wrap panel">
        @if ($errors->any())
            <div class="checkout-alert" role="alert" style="max-width:640px">
                <strong>Please fix the following:</strong>
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="formbox" method="post" action="{{ route('reviews.submit', $book->slug) }}" style="max-width:640px">
            @csrf

            <div class="kicker"><i></i>MODERATED</div>
            <h2>Share your experience</h2>
            <p class="fine">
                Reviews should describe the book and its content. Approved reviews appear on
                <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a>.
            </p>

            <label>
                <span>Your name</span>
                <input required type="text" name="author_name" value="{{ old('author_name') }}" maxlength="120" autocomplete="name">
            </label>

            <fieldset class="review-rating" style="border:0;padding:0;margin:12px 0">
                <legend style="font:600 14px var(--heading);margin-bottom:6px">Rating</legend>
                <div class="rating-row">
                    @for ($i = 5; $i >= 1; $i--)
                        <label class="rating-chip">
                            <input type="radio" name="rating" value="{{ $i }}" {{ (int) old('rating', 5) === $i ? 'checked' : '' }}>
                            <span>{{ $i }} ★</span>
                        </label>
                    @endfor
                </div>
            </fieldset>

            <label>
                <span>Review (20 characters minimum)</span>
                <textarea required name="body" rows="6" minlength="20" maxlength="2000"
                          style="font:inherit;background:#fffefa;border:1px solid #a6b09e;border-radius:4px;padding:9px;resize:vertical">{{ old('body') }}</textarea>
            </label>

            {{-- Honeypot: real browsers leave this empty. Visible-to-bots, hidden via CSS from humans. --}}
            <label class="hp-field" aria-hidden="true" tabindex="-1" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden">
                <span>Website</span>
                <input type="text" name="website" value="" autocomplete="off" tabindex="-1">
            </label>

            <label>
                <span>Human check</span>
                <input required type="number" name="challenge_answer" min="0" max="99" inputmode="numeric" style="max-width:120px"
                       placeholder="?">
                <span class="fine" style="margin-top:4px">{{ $challenge['question'] }}</span>
            </label>

            @if ($recaptchaSiteKey)
                @if ($recaptchaVersion === 'v3')
                    {{-- reCAPTCHA v3: invisible, injects a hidden g-recaptcha-response on submit. --}}
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}" async defer></script>
                    <script>
                        document.addEventListener('submit', function (e) {
                            if (e.target.matches('form[action*="/review"]')) {
                                e.preventDefault();
                                grecaptcha.ready(function () {
                                    grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'review' }).then(function (token) {
                                        document.getElementById('g-recaptcha-response').value = token;
                                        e.target.submit();
                                    });
                                });
                            }
                        });
                    </script>
                @else
                    {{-- reCAPTCHA v2: "I'm not a robot" checkbox. --}}
                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}" style="margin:14px 0"></div>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                @endif
            @endif

            <button type="submit" class="btn" style="margin-top:14px">
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2v8m0 0 4-4m-4 4-4-4M4 14v4a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4"/>
                </svg>
                Submit review
            </button>
            <p class="fine" style="margin-top:12px">
                By submitting you confirm the review is your own words.
                We do not publish email addresses or phone numbers inside reviews.
            </p>
        </form>
    </section>
@endsection
