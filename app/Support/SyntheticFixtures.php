<?php

namespace App\Support;

/**
 * Phase 1 synthetic fixtures. Replaced by real Eloquent-backed data in Phase 2
 * (content modules) and Phase 4 (orders). Nothing here reflects a real person,
 * publisher or product and no field here exists in production.
 */
class SyntheticFixtures
{
    public static function exams(): array
    {
        return [
            ['slug' => 'tac', 'name' => 'Territorial Army Commission (TAC)'],
            ['slug' => 'officer', 'name' => 'Officer entry'],
            ['slug' => 'soldier', 'name' => 'Soldier entry'],
        ];
    }

    public static function notices(): array
    {
        return [
            [
                'slug' => 'tac-2026-notification',
                'date' => '2026-10-28',
                'exam' => 'tac',
                'exam_name' => 'Territorial Army Commission (TAC)',
                'type' => 'notification',
                'title' => '[Synthetic] TAC 2026 written notification',
                'summary' => 'Placeholder notification used to validate the publication template.',
                'body' => 'This is demonstration content. It has no official status. Real notices will be attributed to the issuing source and link to that source document.',
                'source_label' => 'Placeholder issuing office',
                'source_url' => 'https://example.gov.in/notifications/2026-10-28',
                'source_size' => '420 KB',
            ],
            [
                'slug' => 'asb-2026-admit-card',
                'date' => '2026-09-14',
                'exam' => 'soldier',
                'exam_name' => 'Soldier entry',
                'type' => 'admit-card',
                'title' => '[Synthetic] ASB 2026 admit-card availability',
                'summary' => 'Placeholder admit-card update to validate the result/admit template.',
                'body' => 'Placeholder content. Real admit-card entries will link to the issuing portal and carry a clear external source badge.',
                'source_label' => 'Placeholder issuing office',
                'source_url' => 'https://example.gov.in/asb/2026',
                'source_size' => '180 KB',
            ],
            [
                'slug' => 'tac-2026-written-result',
                'date' => '2026-07-03',
                'exam' => 'tac',
                'exam_name' => 'Territorial Army Commission (TAC)',
                'type' => 'result',
                'title' => '[Synthetic] TAC 2026 written result',
                'summary' => 'Placeholder result entry to validate the listing and filter UI.',
                'body' => 'Placeholder content. Real result entries will link to the source document and the associated alert signup.',
                'source_label' => 'Placeholder issuing office',
                'source_url' => 'https://example.gov.in/results/tac-2026',
                'source_size' => '310 KB',
            ],
            [
                'slug' => 'tac-2025-written-result',
                'date' => '2025-07-19',
                'exam' => 'tac',
                'exam_name' => 'Territorial Army Commission (TAC)',
                'type' => 'result',
                'title' => '[Synthetic] TAC 2025 written result',
                'summary' => 'Prior-year placeholder for the archive view.',
                'body' => 'Placeholder content for an older entry to validate year filtering.',
                'source_label' => 'Placeholder issuing office',
                'source_url' => 'https://example.gov.in/results/tac-2025',
                'source_size' => '295 KB',
            ],
            [
                'slug' => 'officer-screening-2026',
                'date' => '2026-05-06',
                'exam' => 'officer',
                'exam_name' => 'Officer entry',
                'type' => 'notification',
                'title' => '[Synthetic] Officer screening schedule',
                'summary' => 'Placeholder officer-entry notification used to validate the audience badge.',
                'body' => 'Placeholder content. The officer alert list is maintained independently by this publisher.',
                'source_label' => 'Placeholder issuing office',
                'source_url' => 'https://example.gov.in/officer-2026',
                'source_size' => '140 KB',
            ],
        ];
    }

    public static function latestNotices(int $count = 3): array
    {
        $all = self::notices();
        usort($all, fn ($a, $b) => strcmp($b['date'], $a['date']));
        return array_slice($all, 0, $count);
    }

    public static function books(): array
    {
        return [
            [
                'slug' => 'tac-examination-book',
                'title' => 'Territorial Army Commission Examination Book',
                'author' => 'Placeholder publisher',
                'edition' => '2026',
                'price_inr' => 1998,
                'included' => [
                    'Printed copy shipped across India',
                    'Access to buyer-only study PDFs after entitlement',
                    'Previous-paper walkthroughs with keyed answers',
                ],
                'delivery_blurb' => 'Books ship within three working days. Study-material access is granted when payment is confirmed by the gateway — the browser return alone does not grant access.',
                'sample_reviews' => [
                    ['name' => '[Synthetic] Reviewer A', 'rating' => 5, 'body' => 'Placeholder review text used for layout only. Real reviews require moderator approval before showing.'],
                    ['name' => '[Synthetic] Reviewer B', 'rating' => 4, 'body' => 'Placeholder review text. Verified-purchase status will be shown with real reviews in Phase 2.'],
                ],
            ],
        ];
    }

    public static function findBook(string $slug): ?array
    {
        foreach (self::books() as $b) {
            if ($b['slug'] === $slug) return $b;
        }
        return null;
    }

    public static function findNotice(string $slug): ?array
    {
        foreach (self::notices() as $n) {
            if ($n['slug'] === $slug) return $n;
        }
        return null;
    }
}
