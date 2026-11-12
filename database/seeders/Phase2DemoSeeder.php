<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\Exam;
use App\Models\HeroMedia;
use App\Models\NewslineItem;
use App\Models\Notice;
use App\Models\Pdf;
use App\Models\Review;
use App\Models\StudyMaterial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Phase 2 demo seeder. All content is clearly labelled synthetic.
 * Never run against a production database.
 */
class Phase2DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.test'],
            [
                'name' => 'Phase 2 Admin',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ],
        );

        $editor = User::updateOrCreate(
            ['email' => 'editor@example.test'],
            [
                'name' => 'Phase 2 Editor',
                'password' => Hash::make('password'),
                'role' => User::ROLE_EDITOR,
                'email_verified_at' => now(),
            ],
        );

        $ops = User::updateOrCreate(
            ['email' => 'ops@example.test'],
            [
                'name' => 'Phase 2 Operations',
                'password' => Hash::make('password'),
                'role' => User::ROLE_OPS,
                'email_verified_at' => now(),
            ],
        );

        $tac = Exam::updateOrCreate(['slug' => 'tac'], ['name' => 'Territorial Army Commission (TAC)']);
        $off = Exam::updateOrCreate(['slug' => 'officer'], ['name' => 'Officer entry']);
        $sol = Exam::updateOrCreate(['slug' => 'soldier'], ['name' => 'Soldier entry']);

        $publicPdf = Pdf::updateOrCreate(
            ['title' => '[Synthetic] TAC 2026 notification source document'],
            [
                'disk' => Pdf::DISK_PUBLIC,
                'path' => 'public-pdfs/synthetic-notice.pdf',
                'size_bytes' => 420_000,
                'mime' => 'application/pdf',
                'download_label' => 'Open source document',
                'uploaded_by' => $admin->id,
            ],
        );

        $privatePdf = Pdf::updateOrCreate(
            ['title' => '[Synthetic] Buyer-only study notes'],
            [
                'disk' => Pdf::DISK_PRIVATE,
                'path' => 'study/synthetic-study.pdf',
                'size_bytes' => 1_200_000,
                'mime' => 'application/pdf',
                'download_label' => 'Open study PDF',
                'uploaded_by' => $admin->id,
            ],
        );

        Notice::updateOrCreate(
            ['slug' => 'tac-2026-notification'],
            [
                'title' => '[Synthetic] TAC 2026 written notification',
                'summary' => 'Placeholder notification used to validate the publication template and the public cache invalidation.',
                'body' => 'Placeholder body. In production this field would carry the editorial summary and any issuing-authority quotes, with the official source document linked above.',
                'exam_id' => $tac->id,
                'published_on' => '2026-10-28',
                'type' => 'notification',
                'source_label' => 'Placeholder issuing office',
                'source_url' => 'https://example.gov.in/notifications/2026-10-28',
                'pdf_id' => $publicPdf->id,
                'status' => Notice::STATUS_PUBLISHED,
                'published_at' => now()->subDays(2),
                'author_id' => $editor->id,
            ],
        );

        Notice::updateOrCreate(
            ['slug' => 'tac-2026-written-result'],
            [
                'title' => '[Synthetic] TAC 2026 written result',
                'summary' => 'Placeholder result entry to validate the listing and the filtered-by-type view.',
                'body' => 'Placeholder body for a result entry.',
                'exam_id' => $tac->id,
                'published_on' => '2026-07-03',
                'type' => 'result',
                'source_label' => 'Placeholder issuing office',
                'source_url' => 'https://example.gov.in/results/tac-2026',
                'status' => Notice::STATUS_PUBLISHED,
                'published_at' => now()->subMonths(4),
                'author_id' => $editor->id,
            ],
        );

        Notice::updateOrCreate(
            ['slug' => 'draft-only-visible-in-admin'],
            [
                'title' => '[Synthetic] Draft notice — admin only',
                'summary' => 'This draft should never appear in the public listing or detail route.',
                'body' => 'Draft body kept behind the Filament admin.',
                'exam_id' => $tac->id,
                'published_on' => now()->toDateString(),
                'type' => 'notification',
                'status' => Notice::STATUS_DRAFT,
                'author_id' => $editor->id,
            ],
        );

        HeroMedia::updateOrCreate(
            ['headline' => 'Territorial Army exam guide'],
            [
                'subhead' => 'Latest notices, preparation resources and book information.',
                'action_label' => 'View latest notices',
                'action_url' => '/notices-and-downloads',
                'is_featured' => true,
            ],
        );

        NewslineItem::updateOrCreate(
            ['title' => '[Synthetic] TAC 2026 notification published'],
            [
                'destination_url' => '/notices/tac-2026-notification',
                'published_on' => now()->subDays(2),
                'display_order' => 1,
                'status' => NewslineItem::STATUS_PUBLISHED,
            ],
        );

        $book = Book::updateOrCreate(
            ['slug' => 'tac-examination-book'],
            [
                'title' => 'Territorial Army Commission Examination Book',
                'author' => 'Placeholder publisher',
                'description' => 'Comprehensive preparation guide for the Territorial Army Commission examination. Includes previous papers, practice sets and keyed answers.',
                'status' => Book::STATUS_PUBLISHED,
                'published_at' => now()->subMonth(),
            ],
        );

        BookEdition::updateOrCreate(
            ['book_id' => $book->id, 'edition_name' => '2026'],
            [
                'price_paise' => 199_800, // ₹1,998.00
                'stock_state' => 'in_stock',
                'delivery_blurb' => 'Books ship within three working days. Study-material access is granted when the gateway confirms payment.',
                'is_default' => true,
            ],
        );

        Review::updateOrCreate(
            ['book_id' => $book->id, 'author_name' => '[Synthetic] Reviewer A'],
            [
                'rating' => 5,
                'body' => 'Placeholder approved review. Covers content quality and delivery.',
                'moderation_state' => Review::STATE_APPROVED,
                'moderated_by' => $editor->id,
                'moderated_at' => now()->subWeek(),
            ],
        );

        Review::updateOrCreate(
            ['book_id' => $book->id, 'author_name' => '[Synthetic] Reviewer B — pending'],
            [
                'rating' => 4,
                'body' => 'Placeholder pending review. Should NOT appear on the public book page.',
                'moderation_state' => Review::STATE_PENDING,
            ],
        );

        $edition = $book->defaultEdition();
        if ($edition) {
            StudyMaterial::updateOrCreate(
                ['slug' => 'tac-2026-study-pack'],
                [
                    'title' => '[Synthetic] TAC 2026 study pack',
                    'description' => 'Buyer-only notes attached to the 2026 edition.',
                    'pdf_id' => $privatePdf->id,
                    'exam_id' => $tac->id,
                    'book_edition_id' => $edition->id,
                    'status' => StudyMaterial::STATUS_PUBLISHED,
                    'published_at' => now()->subWeek(),
                ],
            );
        }

        $this->command->info("Seeded admin={$admin->email} / editor={$editor->email} / ops={$ops->email} (password 'password')");
    }
}
