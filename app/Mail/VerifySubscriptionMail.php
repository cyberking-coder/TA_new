<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifySubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $audience,
        public string $verifyUrl,
        public string $unsubUrl,
    ) {}

    public function build(): self
    {
        return $this->subject('Confirm your '.$this->audience.' alerts subscription')
            ->html(view('mail.verify-subscription', [
                'audience' => $this->audience,
                'verifyUrl' => $this->verifyUrl,
                'unsubUrl' => $this->unsubUrl,
            ])->render());
    }
}
