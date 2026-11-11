<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\BuyerPasswordReset;
use App\Models\Entitlement;
use App\Models\StudyMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class StudyController extends Controller
{
    public function loginForm()
    {
        return view('study.login', [
            'title' => 'Study login — Territorial Army exam guide',
            'description' => 'Buyer login to access study materials.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
        ]);
    }

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'order_ref' => 'required|string|max:64',
            'password' => 'required|string|max:72',
        ]);

        $buyer = Buyer::query()->where('order_ref', $data['order_ref'])->first();
        if (!$buyer || !Hash::check($data['password'], $buyer->password_hash)) {
            return back()->withErrors(['order_ref' => 'Order reference or password is incorrect.'])
                ->withInput(['order_ref' => $data['order_ref']]);
        }

        Auth::guard('buyer')->login($buyer);
        $buyer->update(['last_login_at' => now()]);
        return redirect()->route('study.index');
    }

    public function index()
    {
        $buyer = Auth::guard('buyer')->user();
        abort_unless($buyer, 403);

        $editionIds = Entitlement::query()
            ->where('buyer_id', $buyer->id)
            ->active()
            ->pluck('book_edition_id');

        $materials = StudyMaterial::query()->published()
            ->whereIn('book_edition_id', $editionIds)
            ->with('pdf')
            ->get();

        return view('study.index', [
            'title' => 'Study materials — Territorial Army exam guide',
            'description' => 'Your study material library.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
            'buyer' => $buyer,
            'materials' => $materials,
        ]);
    }

    /**
     * Phase 4 entitlement-gated download. Signed, 60s TTL.
     * Private files stay on the 'private' disk; this endpoint streams them
     * after re-checking entitlement on EVERY request.
     */
    public function download(Request $request, int $material)
    {
        if (!$request->hasValidSignature()) abort(403, 'signed link expired');

        $buyer = Auth::guard('buyer')->user();
        abort_unless($buyer, 403);

        $mat = StudyMaterial::query()->published()->with('pdf')->findOrFail($material);
        if (!$mat->pdf || !$mat->book_edition_id) abort(404);

        $hasEntitlement = Entitlement::query()
            ->where('buyer_id', $buyer->id)
            ->where('book_edition_id', $mat->book_edition_id)
            ->active()
            ->exists();

        abort_unless($hasEntitlement, 403, 'no entitlement for this study material');

        if (!Storage::disk('private')->exists($mat->pdf->path)) {
            abort(404, 'file missing');
        }

        return Storage::disk('private')->download($mat->pdf->path, $mat->title.'.pdf');
    }

    public function signedUrlFor(int $materialId): string
    {
        return URL::temporarySignedRoute('study.download', now()->addSeconds(60), ['material' => $materialId]);
    }

    public function logout()
    {
        Auth::guard('buyer')->logout();
        return redirect()->route('home');
    }

    // --- Password reset / setup ---

    public function forgotForm()
    {
        return view('study.forgot', [
            'title' => 'Request a new setup link',
            'description' => 'Request a fresh password-setup link for your buyer account.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
        ]);
    }

    public function forgotSubmit(Request $request)
    {
        $data = $request->validate([
            'order_ref' => 'required|string|max:64',
            'email'     => 'required|email:rfc|max:191',
        ]);

        $buyer = Buyer::query()
            ->where('order_ref', $data['order_ref'])
            ->whereRaw('LOWER(email) = ?', [strtolower(trim($data['email']))])
            ->first();

        // Always render the same confirmation view regardless of whether the
        // buyer existed, so this endpoint can't be used to enumerate order
        // references or emails. If the match was real, we send a fresh link.
        if ($buyer) {
            // Expire any previous unused tokens for this buyer.
            BuyerPasswordReset::query()
                ->where('buyer_id', $buyer->id)
                ->whereNull('used_at')
                ->update(['expires_at' => now()->subMinute()]);

            $raw = \Illuminate\Support\Str::random(48);
            BuyerPasswordReset::create([
                'buyer_id' => $buyer->id,
                'token_hash' => hash('sha256', $raw),
                'expires_at' => now()->addHours(48),
            ]);

            try {
                \Illuminate\Support\Facades\Mail::to($buyer->email)
                    ->queue(new \App\Mail\OrderReceiptMail($buyer->order_ref
                        ? \App\Models\Order::firstWhere('order_ref', $buyer->order_ref)
                            ?? new \App\Models\Order(['order_ref' => $buyer->order_ref, 'total_paise' => 0, 'paid_at' => $buyer->last_login_at ?? now()])
                        : new \App\Models\Order(['order_ref' => $buyer->order_ref ?? '', 'total_paise' => 0, 'paid_at' => now()]),
                        url('/study/reset/'.$raw)));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return view('study.reset-outcome', [
            'title' => 'Setup link sent',
            'description' => 'If your details match a buyer account, a password-setup link is on its way.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
            'heading' => 'Check your inbox',
            'blurb' => 'If your order reference and email match an account on record, we have just sent a fresh password-setup link to that email. The link expires in 48 hours and works once.',
        ]);
    }

    public function resetForm(string $token)
    {
        return view('study.reset', [
            'title' => 'Set your password',
            'description' => 'Set a new password for your buyer account.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
            'token' => $token,
        ]);
    }

    public function resetSubmit(Request $request, string $token)
    {
        $data = $request->validate([
            'password' => 'required|string|min:10|max:72|confirmed',
        ]);

        $reset = BuyerPasswordReset::query()->where('token_hash', hash('sha256', $token))->first();
        if (!$reset || !$reset->isValid()) {
            return view('study.reset-outcome', [
                'title' => 'Reset link expired',
                'description' => 'This reset link is no longer valid.',
                'canonical' => url()->current(),
                'robots' => 'noindex',
                'heading' => 'Reset link expired',
                'blurb' => 'This reset link is no longer valid. Please request a new one from the study login page.',
            ]);
        }

        $buyer = $reset->buyer_id ? Buyer::find($reset->buyer_id) : null;
        if (!$buyer) abort(404);

        $buyer->update(['password_hash' => Hash::make($data['password'])]);
        $reset->update(['used_at' => now()]);

        return view('study.reset-outcome', [
            'title' => 'Password set',
            'description' => 'Your password has been set.',
            'canonical' => url()->current(),
            'robots' => 'noindex',
            'heading' => 'Password set',
            'blurb' => 'You can now sign in with your order reference as the login id and the password you just set.',
        ]);
    }
}
