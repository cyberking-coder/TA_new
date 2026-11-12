<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function form(string $slug)
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();
        [$a, $b] = $this->freshChallenge();

        return view('reviews.create', [
            'title' => 'Write a review — '.$book->title,
            'description' => 'Share your experience with '.$book->title.'. Reviews appear after staff moderation.',
            'canonical' => url()->current(),
            'book' => $book,
            'challenge' => ['a' => $a, 'b' => $b, 'question' => "What is $a + $b?"],
            'recaptchaSiteKey' => config('services.recaptcha.site_key'),
            'recaptchaVersion' => config('services.recaptcha.version', 'v2'),
        ]);
    }

    public function submit(Request $request, string $slug)
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();

        // Rate limit: 3 submissions per hour per IP per book.
        $rlKey = 'review-submit:'.$book->id.':'.sha1($request->ip());
        if (RateLimiter::tooManyAttempts($rlKey, 3)) {
            return back()->withInput()->withErrors([
                'rate' => 'Too many review submissions from this device. Please try again in an hour.',
            ]);
        }
        RateLimiter::hit($rlKey, 3600);

        $data = $request->validate([
            'author_name' => 'required|string|min:2|max:120',
            'rating'      => 'required|integer|min:1|max:5',
            'body'        => 'required|string|min:20|max:2000',
            'challenge_answer' => 'required|integer',
            // honeypot -- real browsers leave it empty; bots fill every field
            'website'     => 'nullable|string|max:0',
        ], [
            'website.max' => 'Submission flagged as automated.',
            'body.min'    => 'Please write at least 20 characters so the review is useful.',
            'challenge_answer.required' => 'Please answer the human-check question.',
        ]);

        // Human-check — stored on session when the form was rendered.
        $expected = Session::pull('review_challenge_answer');
        if (!$expected || (int) $data['challenge_answer'] !== (int) $expected) {
            return back()->withInput()->withErrors([
                'challenge_answer' => 'Human check failed. Please try the sum again.',
            ]);
        }

        // Optional Google reCAPTCHA — only enforced when configured.
        if (config('services.recaptcha.secret_key')) {
            $version = config('services.recaptcha.version', 'v2');
            $token = $request->input('g-recaptcha-response');
            if (!$token) {
                return back()->withInput()->withErrors([
                    'captcha' => 'reCAPTCHA response missing. Please complete the check and try again.',
                ]);
            }
            try {
                $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ])->json();

                $success = (bool) ($resp['success'] ?? false);

                if ($version === 'v3') {
                    // v3 returns a score 0.0 (bot) -> 1.0 (human). Threshold is configurable.
                    $score = (float) ($resp['score'] ?? 0);
                    $threshold = (float) config('services.recaptcha.min_score', 0.5);
                    $success = $success && $score >= $threshold;
                }

                if (!$success) {
                    return back()->withInput()->withErrors([
                        'captcha' => 'reCAPTCHA verification failed. Please try again.',
                    ]);
                }
            } catch (\Throwable $e) {
                report($e);
                // Fail-open on gateway error; honeypot + math still guard the write.
            }
        }

        Review::create([
            'book_id' => $book->id,
            'author_name' => $data['author_name'],
            'rating' => (int) $data['rating'],
            'body' => $data['body'],
            'moderation_state' => Review::STATE_PENDING,
        ]);

        return redirect()->route('reviews.thanks', $book->slug);
    }

    public function thanks(string $slug)
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();

        return view('reviews.thanks', [
            'title' => 'Thank you for your review',
            'description' => 'Your review is pending staff moderation.',
            'canonical' => url()->current(),
            'book' => $book,
        ]);
    }

    private function freshChallenge(): array
    {
        $a = random_int(2, 9);
        $b = random_int(2, 9);
        Session::put('review_challenge_answer', $a + $b);
        return [$a, $b];
    }
}
