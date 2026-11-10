<?php

namespace App\Http\Controllers;

use App\Support\SyntheticFixtures;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index', [
            'title' => 'Territorial Army exam guide — notices, preparation and the book',
            'description' => 'Dated recruitment notices, preparation resources, and the Territorial Army Commission examination book. Independent publisher, source-attributed notices, buyer-only study access.',
            'canonical' => url('/'),
            'latestNotices' => SyntheticFixtures::latestNotices(3),
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'url' => url('/'),
                'name' => 'Territorial Army exam guide',
            ],
        ]);
    }
}
