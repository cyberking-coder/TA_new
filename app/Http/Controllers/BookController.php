<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use App\Support\SyntheticFixtures;

class BookController extends Controller
{
    public function show(string $slug)
    {
        $book = Book::query()->with('editions', 'reviews')->where('slug', $slug)->first();

        if ($book && $book->status !== Book::STATUS_PUBLISHED) {
            abort(404);
        }

        if (!$book) {
            $fixture = SyntheticFixtures::findBook($slug);
            abort_unless($fixture, 404);
            return view('books.show', [
                'title' => $fixture['title'].' — Edition '.$fixture['edition'].' — Territorial Army exam guide',
                'description' => $fixture['title'].' — Edition '.$fixture['edition'].'. Visible INR price, delivery, study-access terms and moderated reviews.',
                'canonical' => url()->current(),
                'book' => $fixture,
                'jsonLd' => self::productJsonLd($fixture['title'], $fixture['edition'], $fixture['price_inr'], url()->current()),
            ]);
        }

        $edition = $book->defaultEdition();
        $priceInr = $edition ? intdiv($edition->price_paise, 100) : 0;

        // Only approved reviews are visible — pending/rejected stay in the admin queue.
        $reviews = $book->reviews()->approved()->latest()->limit(6)->get()
            ->map(fn (Review $r) => [
                'name' => $r->author_name,
                'rating' => $r->rating,
                'body' => $r->body,
            ])->all();

        $cover = $book->cover_path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($book->cover_path)
            : null;

        $viewBook = [
            'slug' => $book->slug,
            'title' => $book->title,
            'author' => $book->author ?? 'Publisher',
            'description' => $book->description,
            'edition' => $edition?->edition_name ?? '',
            'price_inr' => $priceInr,
            'cover_url' => $cover,
            'stock_state' => $edition?->stock_state ?? 'in_stock',
            'included' => [
                'Printed copy shipped across India',
                'Access to buyer-only study PDFs after entitlement',
                'Previous-paper walkthroughs with keyed answers',
                'Latest syllabus coverage and model answers',
            ],
            'why' => [
                ['title' => 'Written for serving candidates', 'body' => 'Explanations calibrated to the exam, not generic competitive prep.'],
                ['title' => 'Keyed to the official syllabus', 'body' => 'Each section maps to the Territorial Army Commission paper structure.'],
                ['title' => 'Verified by moderators', 'body' => 'Errata tracked across editions and fixed before reprint.'],
            ],
            'delivery_blurb' => $edition?->delivery_blurb ?? 'Delivery and study-access details land on the final purchase confirmation.',
            'specs' => [
                'Edition' => $edition?->edition_name ?? '—',
                'Format' => 'Paperback, print quality mono interior',
                'Language' => 'English',
                'Shipping' => 'All-India dispatch 2–4 working days',
                'Study access' => 'Unlocks buyer-only PDFs after entitlement',
            ],
            'sample_reviews' => $reviews,
        ];

        return view('books.show', [
            'title' => $book->title.' — Edition '.$viewBook['edition'].' — Territorial Army exam guide',
            'description' => $book->title.' — Edition '.$viewBook['edition'].'. Visible INR price, delivery, study-access terms and moderated reviews.',
            'canonical' => url()->current(),
            'book' => $viewBook,
            'jsonLd' => self::productJsonLd($book->title, $viewBook['edition'], $priceInr, url()->current()),
        ]);
    }

    private static function productJsonLd(string $title, string $edition, int $priceInr, string $url): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $title,
            'brand' => ['@type' => 'Brand', 'name' => 'Territorial Army exam guide'],
            'description' => 'Edition '.$edition.' of the '.$title.'.',
            'offers' => [
                '@type' => 'Offer',
                'price' => (string) $priceInr,
                'priceCurrency' => 'INR',
                'availability' => 'https://schema.org/InStock',
                'url' => $url,
            ],
        ];
    }
}
