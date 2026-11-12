<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookEdition;
use App\Services\Checkout\OrderFactory;
use App\Services\Checkout\PricingService;
use App\Services\Payments\PaymentProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly PricingService $pricing,
        private readonly OrderFactory $orderFactory,
        private readonly PaymentProvider $provider,
    ) {}

    public function show(string $slug)
    {
        $book = Book::query()->published()->with('editions')->where('slug', $slug)->firstOrFail();
        $edition = $book->defaultEdition();
        abort_unless($edition, 404);

        $qty = 1;
        $price = $this->pricing->price($edition, $qty);

        return view('checkout.show', [
            'title' => 'Checkout — '.$book->title,
            'description' => 'Secure mobile checkout for '.$book->title.'.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
            'book' => $book,
            'edition' => $edition,
            'price' => $price,
        ]);
    }

    public function submit(Request $request, string $slug)
    {
        $book = Book::query()->published()->with('editions')->where('slug', $slug)->firstOrFail();
        $edition = $book->defaultEdition();
        abort_unless($edition, 404);

        $data = $request->validate([
            'buyer_name'   => 'required|string|max:120',
            'buyer_email'  => 'required|email:rfc|max:191',
            'buyer_mobile' => 'required|string|max:32',
            'quantity'     => ['required', 'integer', 'min:1', 'max:20'],
            'ship_line1'   => 'required|string|max:200',
            'ship_line2'   => 'nullable|string|max:200',
            'ship_city'    => 'required|string|max:80',
            'ship_state'   => 'required|string|max:80',
            'ship_pincode' => ['required', 'string', 'max:10', 'regex:/^\d{6}$/'],
        ]);

        // Price computed server-side ONLY — buyer input never sets totals.
        $price = $this->pricing->price($edition, (int) $data['quantity']);

        ['order' => $order, 'attempt' => $attempt] = $this->orderFactory->createPendingOrder(
            $edition, $price,
            buyer: [
                'name' => $data['buyer_name'],
                'email' => $data['buyer_email'],
                'mobile' => $data['buyer_mobile'],
            ],
            shipping: [
                'line1' => $data['ship_line1'],
                'line2' => $data['ship_line2'] ?? null,
                'city' => $data['ship_city'],
                'state' => $data['ship_state'],
                'pincode' => $data['ship_pincode'],
            ],
        );

        $redirect = $this->provider->buildRedirect($order, $attempt);

        return view('checkout.redirect', [
            'title' => 'Redirecting to secure payment…',
            'description' => 'Processing payment.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
            'url' => $redirect['url'],
            'fields' => $redirect['fields'],
            'order' => $order,
        ]);
    }
}
