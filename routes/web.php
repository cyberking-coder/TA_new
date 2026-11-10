<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PdfDownloadController;
use App\Http\Controllers\PlaceholderController;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * Public routes. See docs/route-inventory.md for the committed URL list
 * and docs/legacy-redirects.md (built in Phase 5) for the full legacy map.
 */

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/notices-and-downloads', [NoticeController::class, 'index'])->name('notices.index');
Route::get('/notices/{slug}', [NoticeController::class, 'show'])->name('notices.show');

Route::get('/preparation/previous-papers', fn () => app(PlaceholderController::class)
    ->render('Previous papers', 'Solved previous-paper landing page. Phase 2 lands subject/year groupings and detail pages.', 'Phase 2')
)->name('prep.previous');

Route::get('/preparation/practice-papers', fn () => app(PlaceholderController::class)
    ->render('Practice papers', 'Practice-set landing page kept distinct from solved previous papers. Phase 2 lands the sets.', 'Phase 2')
)->name('prep.practice');

Route::get('/books/{slug}', [BookController::class, 'show'])->name('books.show');

Route::get('/checkout/{slug}', fn (string $slug) => app(PlaceholderController::class)
    ->render('Checkout', 'Single-column mobile checkout for '.$slug.'. Phase 4 lands the server-priced flow, pending-order creation and CCAvenue redirect.', 'Phase 4', 'noindex')
)->name('checkout.show');

Route::get('/alerts/officer', fn () => app(PlaceholderController::class)
    ->render('Officer alerts', 'Officer subscribe / result-registration form. Phase 3 lands verification, suppression and result-registration.', 'Phase 3')
)->name('alerts.officer');

Route::get('/alerts/soldier', fn () => app(PlaceholderController::class)
    ->render('Soldier alerts', 'Soldier subscribe / result-registration form. Phase 3 lands verification, suppression and result-registration.', 'Phase 3')
)->name('alerts.soldier');

Route::get('/study/login', fn () => app(PlaceholderController::class)
    ->render('Study login', 'Buyer login (order ID + password). Phase 4 lands verified authentication and entitlement checks.', 'Phase 4', 'noindex')
)->name('study.login');

Route::get('/policies/{slug}', fn (string $slug) => app(PlaceholderController::class)
    ->render(ucfirst(str_replace('-', ' ', $slug)), 'Policy content lands in Phase 2 once legacy policy copy is migrated.', 'Phase 2')
)->name('policies.show');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Public PDF link. Private PDFs refuse to serve from this route.
Route::get('/pdfs/{id}', [PdfDownloadController::class, 'publicDownload'])
    ->whereNumber('id')
    ->name('pdfs.public');

Route::get('/robots.txt', function () {
    $env = config('app.env');
    $body = $env === 'production'
        ? "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /study\nDisallow: /checkout\nSitemap: ".url('/sitemap.xml')."\n"
        : "User-agent: *\nDisallow: /\n";
    return response($body, 200, ['Content-Type' => 'text/plain']);
});

/*
 * Legacy redirects — P0 migration mapping from the PRD.
 * Payment POSTs must never fall through content redirects: handled explicitly in Phase 4.
 */
Route::redirect('/home', '/', 301);
Route::redirect('/book/5', '/books/tac-examination-book', 301);
Route::redirect('/imp_downloads', '/notices-and-downloads', 301);
Route::redirect('/pib_question_papers', '/preparation/previous-papers', 301);
Route::redirect('/practice_test_papers', '/preparation/practice-papers', 301);
Route::redirect('/login', '/study/login', 301);
Route::get('/buy_now/5', fn () => redirect('/checkout/tac-examination-book', 301));
