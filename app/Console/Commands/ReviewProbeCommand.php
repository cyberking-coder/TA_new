<?php

namespace App\Console\Commands;

use App\Http\Controllers\ReviewController;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Smoke-test the review bot defences without touching Google reCAPTCHA.
 * Runs three scenarios against the real ReviewController and prints
 * which defence layer caught each one.
 *
 *   php artisan review:probe
 */
class ReviewProbeCommand extends Command
{
    protected $signature = 'review:probe {slug=tac-examination-book}';
    protected $description = 'Simulate bot + human review submissions and show what the defences do.';

    public function handle(): int
    {
        $slug = $this->argument('slug');
        if (!Book::query()->where('slug', $slug)->exists()) {
            $this->error("Book not found: $slug. Try one from /admin/books.");
            return self::FAILURE;
        }

        $before = Review::count();
        $this->line("Reviews in DB before probe: $before");
        $this->newLine();

        $this->runScenario('bot_filling_honeypot', $slug, [
            'author_name' => 'FakeBot',
            'rating' => 5,
            'body' => 'http://buy-cheap-pills.example free viagra buy here now today only',
            'website' => 'https://evil.example',   // honeypot filled -- dead giveaway
            'challenge_answer' => 12,
        ]);

        $this->runScenario('bot_wrong_math_answer', $slug, [
            'author_name' => 'LazyBot',
            'rating' => 5,
            'body' => 'Great book best seller click here for discount now today',
            'website' => '',                        // honeypot clean
            'challenge_answer' => 99999,            // guessed answer
        ]);

        $this->runScenario('bot_body_too_short', $slug, [
            'author_name' => 'TinyBot',
            'rating' => 1,
            'body' => 'spam',                       // < 20 chars
            'website' => '',
            'challenge_answer' => 12,
        ]);

        $this->runScenario('human_submission', $slug, [
            'author_name' => 'Ritesh Candidate',
            'rating' => 5,
            'body' => 'Really useful preparation book -- clear chapters and the practice sets are tough.',
            'website' => '',
            'challenge_answer' => null, // special: fetched from session
        ]);

        $after = Review::count();
        $this->newLine();
        $this->line("Reviews in DB after probe: $after  (delta: ".($after - $before).')');
        $this->line('Only genuine submissions ever reach the DB. Everything else is cancelled before Review::create().');
        return self::SUCCESS;
    }

    private function runScenario(string $name, string $slug, array $payload): void
    {
        // Fresh session per scenario so the math challenge answer is isolated.
        Session::flush();
        $controller = app(ReviewController::class);
        $controller->form($slug);
        $challenge = Session::get('review_challenge_answer');

        if ($payload['challenge_answer'] === null) {
            // Genuine user path: answer the challenge correctly.
            $payload['challenge_answer'] = $challenge;
        }

        $before = Review::count();
        $req = Request::create('/books/'.$slug.'/review', 'POST', $payload);
        $req->setLaravelSession(Session::driver());

        try {
            $resp = $controller->submit($req, $slug);
            $location = (string) $resp->headers->get('Location');

            // A Review row actually got created only when submission passed EVERY layer.
            $created = Review::count() > $before;

            if ($created) {
                $this->info("  ✓ $name  -> SAVED as pending (admin moderates before public)");
            } else {
                // Redirected back because something rejected it. Pull the error from session.
                $flashed = session('errors');
                $msg = $flashed ? collect($flashed->getBag('default')->all())->first() : 'rejected (reason not flashed)';
                $this->line("  ✗ $name  -> BLOCKED: $msg");
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = collect($e->errors())->flatten()->first();
            $this->line("  ✗ $name  -> BLOCKED: $errors");
        } catch (\Throwable $e) {
            $this->line("  ✗ $name  -> BLOCKED: ".$e->getMessage());
        }
    }
}
