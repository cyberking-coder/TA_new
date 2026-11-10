<?php

namespace App\Services\Subscriptions;

use App\Mail\VerifySubscriptionMail;
use App\Models\OfficerSubscriber;
use App\Models\SoldierSubscriber;
use App\Models\Suppression;
use App\Models\VerificationAttempt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * One-stop service for subscribe / verify / unsubscribe across officer + soldier lists.
 *
 * PRD rules enforced here:
 * - deduplicate within each audience (separate tables, unique email)
 * - audience-specific consent (one audience opt-in never implies the other)
 * - expiring, rate-limited, single-use verification tokens
 * - unsubscribe writes a Suppression row that newsletter send re-checks
 * - verified subscribers cannot be silently re-subscribed if suppressed
 */
class SubscriptionService
{
    public const AUDIENCE_OFFICER = 'officer';
    public const AUDIENCE_SOLDIER = 'soldier';

    public const TOKEN_TTL_MINUTES = 60 * 24; // 24h
    public const RESEND_LIMIT = 3; // per email per hour

    /** @return array{status: 'created'|'resent'|'already_verified'|'suppressed'|'rate_limited', subscriber?: Model} */
    public function subscribe(string $audience, string $email, ?string $name, ?string $mobile, ?string $ip = null): array
    {
        $email = $this->normaliseEmail($email);
        $this->assertAudience($audience);

        // Hard stop: email is on this audience's suppression list.
        if (Suppression::isSuppressed($audience, $email)) {
            return ['status' => 'suppressed'];
        }

        // Rate limiter — 3 verification emails per hour per address per audience.
        $rlKey = "subscribe-verify:$audience:".sha1($email);
        if (RateLimiter::tooManyAttempts($rlKey, self::RESEND_LIMIT)) {
            return ['status' => 'rate_limited'];
        }
        RateLimiter::hit($rlKey, 3600);

        $model = $this->modelClass($audience);
        $subscriber = DB::transaction(function () use ($model, $email, $name, $mobile) {
            return $model::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'mobile' => $mobile, 'consent_at' => now()],
            );
        });

        // Keep the contact details fresh on re-subscribe before verification.
        if (!$subscriber->wasRecentlyCreated && !$subscriber->verified_at) {
            $subscriber->update([
                'name' => $name ?? $subscriber->name,
                'mobile' => $mobile ?? $subscriber->mobile,
                'consent_at' => $subscriber->consent_at ?? now(),
            ]);
        }

        if ($subscriber->verified_at) {
            return ['status' => 'already_verified', 'subscriber' => $subscriber];
        }

        $rawToken = $this->issueVerificationToken($subscriber, 'verify', $ip);
        $verifyUrl = url('/alerts/verify/'.$rawToken);
        $unsubUrl  = url('/alerts/unsubscribe-confirm?audience='.$audience.'&email='.urlencode($email));

        Mail::to($email)->queue(new VerifySubscriptionMail($audience, $verifyUrl, $unsubUrl));

        return [
            'status' => $subscriber->wasRecentlyCreated ? 'created' : 'resent',
            'subscriber' => $subscriber,
        ];
    }

    /** @return array{status: 'verified'|'expired'|'invalid'|'already_used', subscriber?: Model} */
    public function verify(string $rawToken): array
    {
        $tokenHash = hash('sha256', $rawToken);
        $attempt = VerificationAttempt::query()->where('token_hash', $tokenHash)->first();

        if (!$attempt) return ['status' => 'invalid'];
        if ($attempt->used_at) return ['status' => 'already_used'];
        if ($attempt->expires_at->isPast()) return ['status' => 'expired'];

        $subscriber = $attempt->subject_type::query()->find($attempt->subject_id);
        if (!$subscriber) return ['status' => 'invalid'];

        DB::transaction(function () use ($attempt, $subscriber) {
            $attempt->update(['used_at' => now()]);
            if (!$subscriber->verified_at) {
                $subscriber->update(['verified_at' => now()]);
            }
        });

        return ['status' => 'verified', 'subscriber' => $subscriber];
    }

    public function unsubscribe(string $audience, string $email, string $reason = 'unsubscribed'): bool
    {
        $email = $this->normaliseEmail($email);
        $this->assertAudience($audience);

        $model = $this->modelClass($audience);
        $subscriber = $model::query()->where('email', $email)->first();

        DB::transaction(function () use ($audience, $email, $subscriber, $reason) {
            Suppression::updateOrCreate(
                ['audience' => $audience, 'email' => $email],
                ['reason' => $reason, 'created_at' => now()],
            );
            if ($subscriber) {
                $subscriber->update(['unsubscribed_at' => now()]);
            }
        });

        return true;
    }

    public function issueVerificationToken(Model $subscriber, string $purpose, ?string $ip): string
    {
        $raw = Str::random(48);
        VerificationAttempt::create([
            'subject_type' => $subscriber::class,
            'subject_id' => $subscriber->id,
            'token_hash' => hash('sha256', $raw),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(self::TOKEN_TTL_MINUTES),
            'request_ip' => $ip,
        ]);
        return $raw;
    }

    public function modelClass(string $audience): string
    {
        return $audience === self::AUDIENCE_OFFICER
            ? OfficerSubscriber::class
            : SoldierSubscriber::class;
    }

    private function normaliseEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    private function assertAudience(string $audience): void
    {
        if (!in_array($audience, [self::AUDIENCE_OFFICER, self::AUDIENCE_SOLDIER], true)) {
            throw new \InvalidArgumentException("Unknown audience: $audience");
        }
    }
}
