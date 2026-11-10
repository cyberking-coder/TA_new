<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ResultRegistration;
use App\Services\Subscriptions\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AlertsController extends Controller
{
    public function __construct(private readonly SubscriptionService $svc) {}

    public function form(string $audience)
    {
        abort_unless(in_array($audience, ['officer', 'soldier'], true), 404);

        return view('alerts.form', [
            'title' => ucfirst($audience).' alerts — Territorial Army exam guide',
            'description' => 'Subscribe to '.$audience.' exam alerts and register for result updates. Verification email, unsubscribe at any time.',
            'canonical' => url()->current(),
            'audience' => $audience,
            'heading' => ucfirst($audience).' alerts and result registration',
            'intro' => 'Subscribe to '.$audience.' exam alerts, submit a result registration, or manage your subscription below.',
            'exams' => Exam::query()->orderBy('name')->get(['id', 'name'])->toArray(),
        ]);
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'audience' => 'required|in:officer,soldier',
            'email'    => 'required|email:rfc|max:191',
            'name'     => 'nullable|string|max:120',
            'mobile'   => 'nullable|string|max:32',
        ]);

        $result = $this->svc->subscribe(
            $data['audience'], $data['email'], $data['name'] ?? null, $data['mobile'] ?? null, $request->ip()
        );

        $back = redirect()->route('alerts.'.$data['audience']);

        return match ($result['status']) {
            'created', 'resent' => $back->with('flash_ok', 'Check your inbox — we sent a verification email that expires in 24 hours.'),
            'already_verified'  => $back->with('flash_warn', 'This address is already verified for '.$data['audience'].' alerts. If you did not do this, use the unsubscribe form below.'),
            'suppressed'        => $back->with('flash_warn', 'This address is on our suppression list for '.$data['audience'].' alerts and cannot be resubscribed from the public form.'),
            'rate_limited'      => $back->with('flash_warn', 'Too many verification requests for this address. Please try again in an hour.'),
            default             => $back->with('flash_warn', 'Something went wrong. Please try again.'),
        };
    }

    public function verify(string $token)
    {
        $r = $this->svc->verify($token);
        return match ($r['status']) {
            'verified'       => view('alerts.verify', ['title'=>'Subscription verified — Territorial Army exam guide','description'=>'Your subscription has been verified.','canonical'=>url()->current(),'heading'=>'You\'re verified.','blurb'=>'Thank you. We\'ll send you alerts for this audience from here on.']),
            'already_used'   => view('alerts.verify', ['title'=>'Link already used','description'=>'This verification link has already been used.','canonical'=>url()->current(),'heading'=>'Link already used','blurb'=>'This verification link has already been used. No further action is needed.']),
            'expired'        => view('alerts.verify', ['title'=>'Link expired','description'=>'This verification link has expired.','canonical'=>url()->current(),'heading'=>'Link expired','blurb'=>'Verification links expire after 24 hours. Please submit the subscribe form again to receive a new one.']),
            default          => view('alerts.verify', ['title'=>'Invalid link','description'=>'Verification link is invalid.','canonical'=>url()->current(),'heading'=>'Invalid link','blurb'=>'We could not match this verification link. If you believe this is an error, submit the subscribe form again.']),
        };
    }

    public function unsubscribe(Request $request)
    {
        $data = $request->validate([
            'audience' => 'required|in:officer,soldier',
            'email'    => 'required|email:rfc|max:191',
        ]);

        $this->svc->unsubscribe($data['audience'], $data['email']);
        return redirect()->route('alerts.'.$data['audience'])
            ->with('flash_ok', 'You have been unsubscribed from '.$data['audience'].' alerts. Your address is added to our suppression list.');
    }

    public function registerResult(Request $request)
    {
        // Only the five fields: name, mobile, email, exam_id, roll_number.
        // Explicitly NO centre_id, NO zone input (legacy_zone is import-only).
        $data = $request->validate([
            'audience'  => 'required|in:officer,soldier',
            'rr_name'   => 'required|string|max:120',
            'rr_mobile' => 'required|string|max:32',
            'rr_email'  => 'required|email:rfc|max:191',
            'rr_exam_id'=> ['required', 'integer', 'exists:exams,id'],
            'rr_roll'   => ['required', 'string', 'max:64'],
        ]);

        DB::transaction(function () use ($data) {
            // Dedupe on (exam_id, roll_number): a repeat submission with the same
            // roll under the same exam updates contact details without exposing
            // someone else's registration (unique constraint prevents conflicts).
            ResultRegistration::updateOrCreate(
                ['exam_id' => (int) $data['rr_exam_id'], 'roll_number' => $data['rr_roll']],
                [
                    'name'   => $data['rr_name'],
                    'mobile' => $data['rr_mobile'],
                    'email'  => strtolower(trim($data['rr_email'])),
                ],
            );
        });

        return redirect()->route('alerts.'.$data['audience'])
            ->with('flash_ok', 'Result registration saved. You can resubmit with a different exam or roll at any time.');
    }
}
