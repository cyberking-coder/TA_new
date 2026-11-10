<?php

namespace App\Http\Controllers;

use App\Support\SyntheticFixtures;

class BookController extends Controller
{
    public function show(string $slug)
    {
        $book = SyntheticFixtures::findBook($slug);
        abort_unless($book, 404);

        return view('books.show', [
            'title' => $book['title'].' — Edition '.$book['edition'].' — Territorial Army exam guide',
            'description' => $book['title'].' — Edition '.$book['edition'].'. Visible INR price, delivery details, study-access terms and moderated reviews.',
            'canonical' => url()->current(),
            'book' => $book,
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $book['title'],
                'brand' => ['@type' => 'Brand', 'name' => 'Territorial Army exam guide'],
                'description' => 'Edition '.$book['edition'].' of the Territorial Army Commission examination book.',
                'offers' => [
                    '@type' => 'Offer',
                    'price' => (string) $book['price_inr'],
                    'priceCurrency' => 'INR',
                    'availability' => 'https://schema.org/InStock',
                    'url' => url()->current(),
                ],
            ],
        ]);
    }
}
