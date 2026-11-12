<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();
        $this->book = Book::create([
            'slug' => 'tac-examination-book',
            'title' => 'TAC Book',
            'status' => Book::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }

    public function test_get_form_renders(): void
    {
        $this->get('/books/tac-examination-book/review')
            ->assertStatus(200)
            ->assertSee('Write a review');
    }

    public function test_submits_as_pending_with_valid_challenge(): void
    {
        $this->get('/books/tac-examination-book/review');
        $challenge = session('review_challenge_answer');
        $this->assertNotNull($challenge);

        $this->post('/books/tac-examination-book/review', [
            'author_name' => 'Sita Candidate',
            'rating' => 5,
            'body' => 'Really useful book for TAC prep. Lots of practice questions.',
            'challenge_answer' => $challenge,
            'website' => '',
        ])->assertRedirect('/books/tac-examination-book/review/thanks');

        $this->assertSame(1, Review::count());
        $this->assertSame(Review::STATE_PENDING, Review::first()->moderation_state);
    }

    public function test_honeypot_blocks_bot_filling_website_field(): void
    {
        $this->get('/books/tac-examination-book/review');
        $challenge = session('review_challenge_answer');

        $this->post('/books/tac-examination-book/review', [
            'author_name' => 'Spam Bot',
            'rating' => 5,
            'body' => 'http://buy-cheap-pills.example free viagra buy here now today only',
            'challenge_answer' => $challenge,
            'website' => 'https://evil.example',
        ])->assertSessionHasErrors('website');

        $this->assertSame(0, Review::count());
    }

    public function test_wrong_challenge_answer_is_refused(): void
    {
        $this->get('/books/tac-examination-book/review');
        $correct = session('review_challenge_answer');

        $this->post('/books/tac-examination-book/review', [
            'author_name' => 'Ada',
            'rating' => 4,
            'body' => 'Good book. Pages are clean and the layout is readable overall.',
            'challenge_answer' => (int) $correct + 1,
            'website' => '',
        ])->assertSessionHasErrors('challenge_answer');

        $this->assertSame(0, Review::count());
    }

    public function test_pending_review_not_shown_on_public_book_page(): void
    {
        Review::create([
            'book_id' => $this->book->id,
            'author_name' => 'PendingPerson',
            'rating' => 5,
            'body' => 'This is a pending review nobody should see yet.',
            'moderation_state' => Review::STATE_PENDING,
        ]);

        $this->get('/books/tac-examination-book')
            ->assertStatus(200)
            ->assertDontSee('PendingPerson');
    }
}
