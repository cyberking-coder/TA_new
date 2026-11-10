<?php

use App\Http\Controllers\AlertsController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\PaymentSimulatorController;
use App\Http\Controllers\PdfDownloadController;
use App\Http\Controllers\PlaceholderController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StudyController;
use Illuminate\Support\Facades\Route;

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

// Checkout + payment (Phase 4)
Route::get('/checkout/{slug}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/{slug}', [CheckoutController::class, 'submit'])->name('checkout.submit');
Route::get('/payment/return', [PaymentCallbackController::class, 'return'])->name('payment.return');
// PRD: "Never redirect a payment POST using content-page rules."
Route::post('/payment/callback', [PaymentCallbackController::class, 'callback'])
    ->withoutMiddleware(['web'])
    ->middleware(['throttle:60,1'])
    ->name('payment.callback');

// Local simulator — only when payments.provider === 'simulator'.
Route::get('/payment/simulator', [PaymentSimulatorController::class, 'form'])->name('payment.simulator.form');
Route::post('/payment/simulator', [PaymentSimulatorController::class, 'decide'])->name('payment.simulator.decide');

// Alerts & result registration (Phase 3)
Route::get('/alerts/officer', fn () => app(AlertsController::class)->form('officer'))->name('alerts.officer');
Route::get('/alerts/soldier', fn () => app(AlertsController::class)->form('soldier'))->name('alerts.soldier');
Route::post('/alerts/subscribe', [AlertsController::class, 'subscribe'])->name('alerts.subscribe');
Route::get('/alerts/verify/{token}', [AlertsController::class, 'verify'])->name('alerts.verify');
Route::post('/alerts/unsubscribe', [AlertsController::class, 'unsubscribe'])->name('alerts.unsubscribe');
Route::post('/result/register', [AlertsController::class, 'registerResult'])->name('result.register');

// Study / buyer (Phase 4)
Route::get('/study/login', [StudyController::class, 'loginForm'])->name('study.login');
Route::post('/study/login', [StudyController::class, 'authenticate'])->name('study.authenticate');
Route::post('/study/logout', [StudyController::class, 'logout'])->name('study.logout');
Route::get('/study', [StudyController::class, 'index'])->name('study.index');
Route::get('/study/download/{material}', [StudyController::class, 'download'])
    ->whereNumber('material')
    ->name('study.download');
Route::get('/study/reset/{token}', [StudyController::class, 'resetForm'])->name('study.reset.form');
Route::post('/study/reset/{token}', [StudyController::class, 'resetSubmit'])->name('study.reset.submit');

Route::get('/policies/{slug}', fn (string $slug) => app(PlaceholderController::class)
    ->render(ucfirst(str_replace('-', ' ', $slug)), 'Policy content lands in Phase 2 once legacy policy copy is migrated.', 'Phase 2')
)->name('policies.show');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/pdfs/{id}', [PdfDownloadController::class, 'publicDownload'])->whereNumber('id')->name('pdfs.public');

Route::get('/robots.txt', function () {
    $env = config('app.env');
    $body = $env === 'production'
        ? "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /study\nDisallow: /checkout\nDisallow: /payment\nSitemap: ".url('/sitemap.xml')."\n"
        : "User-agent: *\nDisallow: /\n";
    return response($body, 200, ['Content-Type' => 'text/plain']);
});

// Legacy redirects (P0)
Route::redirect('/home', '/', 301);
Route::redirect('/book/5', '/books/tac-examination-book', 301);
Route::redirect('/imp_downloads', '/notices-and-downloads', 301);
Route::redirect('/pib_question_papers', '/preparation/previous-papers', 301);
Route::redirect('/practice_test_papers', '/preparation/practice-papers', 301);
Route::redirect('/login', '/study/login', 301);
Route::get('/buy_now/5', fn () => redirect('/checkout/tac-examination-book', 301));
