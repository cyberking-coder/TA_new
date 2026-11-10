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

        $viewBook = [
            'slug' => $book->slug,
            'title' => $book->title,
            'author' => $book->author ?? 'Publisher',
            'edition' => $edition?->edition_name ?? '',
            'price_inr' => $priceInr,
            'included' => [
                'Printed copy shipped across India',
                'Access to buyer-only study PDFs after entitlement',
                'Previous-paper walkthroughs with keyed answers',
            ],
            'delivery_blurb' => $edition?->delivery_blurb ?? 'Delivery and study-access details land on the final purchase confirmation.',
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
