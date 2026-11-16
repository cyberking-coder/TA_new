<?php

namespace App\Services\Sms;

use App\Models\MobileOtp;
use Illuminate\Support\Facades\DB;

/**
 * Issue, send and verify 6-digit mobile OTPs for alert signups.
 *
 * Rules:
 *  - 10-minute TTL per code; 5 wrong attempts invalidates the row.
 *  - Max 3 sends per mobile per audience per hour.
 *  - Previous unused rows for the same (audience, mobile) are expired
 *    when a new one is issued, so there's always at most one live code.
 *  - Codes are stored as SHA-256 hashes; the clear-text code exists only
 *    on the response from send() so the controller can hand it off to
 *    the SMS provider.
 */
class OtpService
{
    public const TTL_MINUTES = 10;
    public const MAX_SEND_PER_HOUR = 3;
    public const MAX_WRONG_ATTEMPTS = 5;

    public function __construct(private readonly SmsProvider $sms) {}

    /**
     * @return array{status: 'sent'|'rate_limited'|'failed', message?: string}
     */
    public function send(string $audience, string $mobile, ?string $ip = null): array
    {
        $mobile = $this->normalise($mobile);

        $sendsLastHour = MobileOtp::query()
            ->where('audience', $audience)
            ->where('mobile', $mobile)
            ->where('created_at', '>', now()->subHour())
            ->count();
        if ($sendsLastHour >= self::MAX_SEND_PER_HOUR) {
            return ['status' => 'rate_limited', 'message' => 'Too many OTP requests. Try again in an hour.'];
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return DB::transaction(function () use ($audience, $mobile, $ip, $code) {
            // Expire any live code for this mobile+audience.
            MobileOtp::query()
                ->where('audience', $audience)
                ->where('mobile', $mobile)
                ->whereNull('verified_at')
                ->where('expires_at', '>', now())
                ->update(['expires_at' => now()->subMinute()]);

            MobileOtp::create([
                'audience' => $audience,
                'mobile' => $mobile,
                'code_hash' => hash('sha256', $code),
                'expires_at' => now()->addMinutes(self::TTL_MINUTES),
                'send_count' => 1,
                'last_sent_at' => now(),
                'request_ip' => $ip,
            ]);

            $message = "Your Territorial Army alerts verification code is $code. It expires in ".self::TTL_MINUTES." minutes. Do not share.";
            $r = $this->sms->send($mobile, $message);

            MobileOtp::query()
                ->where('audience', $audience)
                ->where('mobile', $mobile)
                ->whereNull('verified_at')
                ->latest('id')->limit(1)
                ->update([
                    'provider' => $r['provider'] ?? null,
                    'provider_response' => substr((string) ($r['response'] ?? $r['error'] ?? ''), 0, 240),
                ]);

            if (!$r['ok']) {
                return ['status' => 'failed', 'message' => 'Could not send the SMS right now: '.($r['error'] ?? 'unknown error')];
            }

            return ['status' => 'sent'];
        });
    }

    /**
     * @return array{status: 'verified'|'invalid'|'expired'|'too_many_attempts'}
     */
    public function verify(string $audience, string $mobile, string $code): array
    {
        $mobile = $this->normalise($mobile);
        $code = preg_replace('/\D+/', '', $code);

        $otp = MobileOtp::query()
            ->where('audience', $audience)
            ->where('mobile', $mobile)
            ->whereNull('verified_at')
            ->latest('id')
            ->first();

        if (!$otp) return ['status' => 'invalid'];
        if ($otp->expires_at->isPast()) return ['status' => 'expired'];
        if ($otp->attempts >= self::MAX_WRONG_ATTEMPTS) return ['status' => 'too_many_attempts'];

        if (!hash_equals($otp->code_hash, hash('sha256', $code))) {
            $otp->increment('attempts');
            return ['status' => 'invalid'];
        }

        $otp->update(['verified_at' => now()]);

        // Flip mobile_verified_at on the matching subscriber, if any.
        $model = $audience === 'officer' ? \App\Models\OfficerSubscriber::class : \App\Models\SoldierSubscriber::class;
        $model::query()->where('mobile', $otp->mobile)->update(['mobile_verified_at' => now()]);

        return ['status' => 'verified'];
    }

    /**
     * Did this mobile successfully verify an OTP for the given audience
     * within the last $minutes window? Used by the result-registration
     * controller to confirm the mobile is proven before saving.
     */
    public function wasVerifiedRecently(string $audience, string $mobile, int $minutes = 30): bool
    {
        $mobile = $this->normalise($mobile);
        return MobileOtp::query()
            ->where('audience', $audience)
            ->where('mobile', $mobile)
            ->whereNotNull('verified_at')
            ->where('verified_at', '>=', now()->subMinutes($minutes))
            ->exists();
    }

    public function normalise(string $mobile): string
    {
        $m = preg_replace('/\D+/', '', $mobile);
        if (str_starts_with($m, '91') && strlen($m) === 12) $m = substr($m, 2);
        return $m;
    }
}
