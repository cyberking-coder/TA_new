<?php

namespace App\Services\Newsletters;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\OfficerSubscriber;
use App\Models\SoldierSubscriber;
use App\Models\Suppression;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NewsletterSender
{
    public function send(Newsletter $n): array
    {
        $modelClass = $n->audience === 'officer' ? OfficerSubscriber::class : SoldierSubscriber::class;

        // Eligibility at send time, NOT queue time.
        $candidates = $modelClass::query()->eligible()->get();

        $sent = 0; $skipped = 0;
        DB::transaction(function () use ($n, $candidates, &$sent, &$skipped) {
            $n->update(['status' => Newsletter::STATUS_SENDING, 'queued_count' => $candidates->count()]);
            foreach ($candidates as $sub) {
                // PRD: "Gate queued newsletters again at send time."
                if (Suppression::isSuppressed($n->audience, $sub->email) || $sub->unsubscribed_at || $sub->suppressed_at) {
                    $skipped++;
                    continue;
                }
                Mail::to($sub->email)->send(new NewsletterMail($n));
                $sent++;
            }
            $n->update([
                'status' => Newsletter::STATUS_SENT,
                'sent_count' => $sent,
                'skipped_count' => $skipped,
                'sent_at' => now(),
            ]);
        });

        return ['sent' => $sent, 'skipped' => $skipped];
    }

    public function sendTest(Newsletter $n, string $recipient): void
    {
        Mail::to($recipient)->send(new NewsletterMail($n, isTest: true));
    }
}
