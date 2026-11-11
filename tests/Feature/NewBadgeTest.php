<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\NewslineItem;
use App\Models\Page;
use App\Models\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewBadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_notice_mark_as_new_sets_future_window_and_is_new_reports_true(): void
    {
        $n = Notice::create([
            'slug' => 'n1', 'title' => 'T', 'summary' => 'S',
            'published_on' => now()->toDateString(), 'status' => 'published', 'published_at' => now(),
        ]);
        $this->assertFalse($n->isNew());

        $n->markAsNew();
        $this->assertTrue($n->fresh()->isNew());
        $this->assertTrue($n->fresh()->new_until->isFuture());
        // Default window is 7 days, with a small clock-drift tolerance.
        $this->assertEqualsWithDelta(7 * 24 * 60, $n->fresh()->new_until->diffInMinutes(now(), true), 2);
    }

    public function test_unmark_clears_the_window(): void
    {
        $n = Notice::create([
            'slug' => 'n2', 'title' => 'T', 'summary' => 'S',
            'published_on' => now()->toDateString(), 'status' => 'published', 'published_at' => now(),
            'new_until' => now()->addDays(3),
        ]);
        $this->assertTrue($n->isNew());
        $n->unmarkAsNew();
        $this->assertFalse($n->fresh()->isNew());
        $this->assertNull($n->fresh()->new_until);
    }

    public function test_expired_badge_is_not_new(): void
    {
        $n = Notice::create([
            'slug' => 'n3', 'title' => 'T', 'summary' => 'S',
            'published_on' => now()->toDateString(), 'status' => 'published', 'published_at' => now(),
            'new_until' => now()->subMinute(),
        ]);
        $this->assertFalse($n->isNew());
    }

    public function test_trait_works_on_page_pdf_and_newsline_item(): void
    {
        $p = Page::create(['slug' => 'p', 'title' => 'P', 'status' => 'published', 'published_at' => now()]);
        $pdf = Pdf::create(['title' => 'X', 'disk' => 'public', 'path' => 'x.pdf', 'size_bytes' => 1]);
        $ni = NewslineItem::create(['title' => 'N', 'destination_url' => '/x', 'published_on' => now()->toDateString(), 'status' => 'published']);

        foreach ([$p, $pdf, $ni] as $m) {
            $m->markAsNew();
            $this->assertTrue($m->fresh()->isNew(), $m::class);
            $m->unmarkAsNew();
            $this->assertFalse($m->fresh()->isNew(), $m::class);
        }
    }

    public function test_public_notice_listing_shows_new_badge(): void
    {
        $exam = \App\Models\Exam::create(['name' => 'TAC', 'slug' => 'tac']);
        Notice::create([
            'slug' => 'highlighted', 'title' => 'Fresh Headline', 'summary' => 'x',
            'exam_id' => $exam->id, 'published_on' => now()->toDateString(),
            'status' => 'published', 'published_at' => now(),
            'new_until' => now()->addDays(5),
        ]);
        $resp = $this->get('/notices-and-downloads');
        $resp->assertStatus(200);
        $resp->assertSee('Fresh Headline');
        $resp->assertSee('new-flag', escape: false);
    }
}
