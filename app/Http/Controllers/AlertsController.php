<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Exam;
use App\Models\OfficerSubscriber;
use App\Models\ResultRegistration;
use App\Models\SoldierSubscriber;
use App\Rules\IndianMobile;
use App\Services\Sms\OtpService;
use App\Services\Subscriptions\SubscriptionService;
use App\Support\MobileNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlertsController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $svc,
        private readonly OtpService $otp,
    ) {}

    // ────────────────────────────────────────────────────────────────
    // v2 Mobile-centric subscribe / unsubscribe (SMS Alert spec).
    // ────────────────────────────────────────────────────────────────

    /** Combined GET view for /alerts/gd and /alerts/officer. */
    public function audienceForm(string $audience)
    {
        abort_unless(in_array($audience, ['gd', 'officer'], true), 404);

        $resultEnabled = AppSetting::bool('result_notification_enabled', true);

        return view('alerts.audience', [
            'title'       => ($audience === 'gd' ? 'GD / Soldier' : 'Officer').' SMS alerts — Territorial Army',
            'description' => 'Subscribe to or unsubscribe from Territorial Army SMS alerts for the '.($audience === 'gd' ? 'GD / Soldier / Rally / Bharti' : 'Civilian Officer').' audience.',
            'canonical'   => url()->current(),
            'audience'    => $audience,          // 'gd' | 'officer'
            'audienceLabel' => $audience === 'gd' ? 'GD / Rally / Soldier / Bharti' : 'Civilian Officer Entry',
            'resultNotificationEnabled' => $resultEnabled,
        ]);
    }

    public function subscribeAudience(Request $request)
    {
        $data = $request->validate([
            'audience'   => 'required|in:gd,officer',
            'first_name' => 'required|string|max:80',
            'last_name'  => 'nullable|string|max:80',
            'mobile'     => ['required', new IndianMobile],
            'email'      => 'required|email:rfc|max:191',
        ]);

        $canon = MobileNumber::toCanonical($data['mobile']);
        $Class = $this->modelFor($data['audience']);

        // Per-audience uniqueness: if the same mobile already exists in this
        // audience we update it (also re-subscribes an earlier unsubscribe);
        // the same number is free to exist independently in the other audience.
        $row = $Class::firstOrNew(['mobile_canon' => $canon]);
        $row->fill([
            'first_name'      => $data['first_name'],
            'last_name'       => $data['last_name'] ?? null,
            'name'            => trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? '')),
            'mobile'          => MobileNumber::nationalPart($canon),
            'mobile_canon'    => $canon,
            'email'           => strtolower(trim($data['email'])),
            'consent_at'      => $row->consent_at ?? now(),
            'unsubscribed_at' => null,
        ]);

        // If OTP is ON globally, require a recent verification for this mobile
        // before we count this as "subscribed".
        if (AppSetting::bool('otp_enabled', false)) {
            $audKey = $data['audience'] === 'gd' ? 'gd' : 'officer';
            if (! $this->otp->wasVerifiedRecently($audKey, $canon, 30)) {
                return back()->withInput()->withErrors([
                    'mobile' => 'Please verify your mobile with the OTP sent to it before subscribing.',
                ]);
            }
            $row->verified_at = now();
        } else {
            // When OTP is OFF, mark as verified so the admin send path can
            // still use the "eligible" scope without waiting.
            $row->verified_at = now();
        }

        $row->save();

        return redirect()->route('alerts.'.$data['audience'])
            ->with('flash_ok', 'Subscribed. You will receive SMS alerts for '.($data['audience'] === 'gd' ? 'GD / Rally / Soldier' : 'Officer Entry').' notifications on '.$canon.'.');
    }

    public function unsubscribeAudience(Request $request)
    {
        $data = $request->validate([
            'audience' => 'required|in:gd,officer',
            'mobile'   => ['required', new IndianMobile],
        ]);

        $canon = MobileNumber::toCanonical($data['mobile']);
        $row = $this->modelFor($data['audience'])::query()->where('mobile_canon', $canon)->first();

        if (! $row) {
            return back()->withInput()->withErrors([
                'mobile' => 'That mobile is not registered for '.($data['audience'] === 'gd' ? 'GD / Soldier' : 'Officer').' alerts.',
            ]);
        }

        // Preserve the row, just mark unsubscribed. No cascade delete.
        $row->unsubscribed_at = now();
        $row->save();

        return redirect()->route('alerts.'.$data['audience'])
            ->with('flash_ok', 'You have been unsubscribed from '.($data['audience'] === 'gd' ? 'GD / Rally / Soldier' : 'Officer').' SMS alerts.');
    }

    private function modelFor(string $audience): string
    {
        return $audience === 'gd' ? SoldierSubscriber::class : OfficerSubscriber::class;
    }

    // ────────────────────────────────────────────────────────────────
    // TA Office Written Examination Result Notification Registration.
    // ────────────────────────────────────────────────────────────────

    public function resultForm()
    {
        abort_unless(AppSetting::bool('result_notification_enabled', true), 404);

        return view('alerts.result', [
            'title'       => 'TA Office Examination Result Registration — Territorial Army',
            'description' => 'Register your roll number to receive SMS updates when TA Office Written Examination results are published.',
            'canonical'   => url()->current(),
            'exams'       => Exam::query()->published()->orderBy('name')->get(['id', 'name', 'short_code']),
        ]);
    }

    public function registerResult(Request $request)
    {
        if (! AppSetting::bool('result_notification_enabled', true)) {
            abort(404);
        }

        $data = $request->validate([
            'rr_exam_id' => ['required', 'integer', 'exists:exams,id'],
            'rr_roll'    => ['required', 'string', 'max:64'],
            'rr_name'    => 'required|string|max:120',
            'rr_mobile'  => ['required', new IndianMobile],
            'rr_email'   => 'required|email:rfc|max:191',
        ]);

        $exam = Exam::find($data['rr_exam_id']);
        if (! $exam || ! $exam->isPublished()) {
            return back()->withInput()->withErrors([
                'rr_exam_id' => 'Registration is not open for that examination right now.',
            ]);
        }

        $canon = MobileNumber::toCanonical($data['rr_mobile']);

        // OTP gate when the setting is on.
        if (AppSetting::bool('otp_enabled', false)
            && ! $this->otp->wasVerifiedRecently('result', $canon, 30)) {
            return back()->withInput()->withErrors([
                'rr_mobile' => 'Please verify your mobile with an OTP before submitting the registration.',
            ]);
        }

        DB::transaction(function () use ($data, $canon) {
            ResultRegistration::updateOrCreate(
                ['exam_id' => (int) $data['rr_exam_id'], 'roll_number' => $data['rr_roll']],
                [
                    'name'   => $data['rr_name'],
                    'mobile' => $canon,
                    'email'  => strtolower(trim($data['rr_email'])),
                    'mobile_verified_at' => AppSetting::bool('otp_enabled', false) ? now() : null,
                ],
            );
        });

        return redirect()->route('result.form')
            ->with('flash_ok', 'Registered. We will SMS '.$canon.' when results for this examination are published.');
    }

    // ────────────────────────────────────────────────────────────────
    // OTP endpoints (reused by GD / Officer / result audiences).
    // ────────────────────────────────────────────────────────────────

    public function sendOtp(Request $request)
    {
        if (! AppSetting::bool('otp_enabled', false)) {
            return back()->with('flash_warn', 'OTP is currently disabled by the administrator.');
        }
        $data = $request->validate([
            'audience' => 'required|in:gd,officer,result',
            'mobile'   => ['required', new IndianMobile],
        ]);
        $canon = MobileNumber::toCanonical($data['mobile']);
        $r = $this->otp->send($data['audience'], $canon, $request->ip());
        $redirect = $data['audience'] === 'result' ? route('result.form') : route('alerts.'.$data['audience']);
        return redirect($redirect)->with($r['status'] === 'sent' ? 'flash_ok' : 'flash_warn',
            $r['status'] === 'sent'
                ? 'OTP sent to '.$canon.'. It expires in 10 minutes.'
                : ($r['message'] ?? 'Could not send OTP right now.'));
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'audience' => 'required|in:gd,officer,result',
            'mobile'   => ['required', new IndianMobile],
            'code'     => 'required|digits:6',
        ]);
        $canon = MobileNumber::toCanonical($data['mobile']);
        $r = $this->otp->verify($data['audience'], $canon, $data['code']);
        $redirect = $data['audience'] === 'result' ? route('result.form') : route('alerts.'.$data['audience']);
        $msg = match ($r['status']) {
            'verified'          => ['flash_ok',   'Mobile verified. Complete the form within 30 minutes to finish.'],
            'expired'           => ['flash_warn', 'That OTP expired. Request a new one.'],
            'too_many_attempts' => ['flash_warn', 'Too many wrong attempts on this OTP. Request a new one.'],
            default             => ['flash_warn', 'That OTP is not correct. Try again.'],
        };
        return redirect($redirect)->with($msg[0], $msg[1]);
    }

    // ────────────────────────────────────────────────────────────────
    // Legacy email-token flow — kept for backward compatibility with
    // the admin newsletter tooling (not exposed on the new public UI).
    // ────────────────────────────────────────────────────────────────

    public function form(string $audience)
    {
        // Old URL /alerts/officer previously showed a combined form. Redirect
        // to the v2 audience page so existing links keep working.
        return redirect()->route('alerts.'.($audience === 'soldier' ? 'gd' : 'officer'));
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'audience' => 'required|in:officer,soldier',
            'email'    => 'required|email:rfc|max:191',
            'name'     => 'nullable|string|max:120',
            'mobile'   => 'nullable|string|max:32',
        ]);
        $this->svc->subscribe(
            $data['audience'], $data['email'], $data['name'] ?? null, $data['mobile'] ?? null, $request->ip()
        );
        return back()->with('flash_ok', 'Check your inbox — we sent a verification email that expires in 24 hours.');
    }

    public function verify(string $token)
    {
        $r = $this->svc->verify($token);
        return match ($r['status']) {
            'verified'     => view('alerts.verify', ['title'=>'Subscription verified','description'=>'Your subscription has been verified.','canonical'=>url()->current(),'heading'=>'You\'re verified.','blurb'=>'Thank you. We\'ll send you alerts for this audience from here on.']),
            'already_used' => view('alerts.verify', ['title'=>'Link already used','description'=>'This verification link has already been used.','canonical'=>url()->current(),'heading'=>'Link already used','blurb'=>'This verification link has already been used. No further action is needed.']),
            'expired'      => view('alerts.verify', ['title'=>'Link expired','description'=>'This verification link has expired.','canonical'=>url()->current(),'heading'=>'Link expired','blurb'=>'Verification links expire after 24 hours. Please submit the subscribe form again to receive a new one.']),
            default        => view('alerts.verify', ['title'=>'Invalid link','description'=>'Verification link is invalid.','canonical'=>url()->current(),'heading'=>'Invalid link','blurb'=>'We could not match this verification link. If you believe this is an error, submit the subscribe form again.']),
        };
    }
}
