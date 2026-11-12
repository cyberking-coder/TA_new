<?php

namespace App\Mail;

use App\Models\Newsletter;
use Illuminate\Mail\Mailable;

class NewsletterMail extends Mailable
{
    public function __construct(public Newsletter $newsletter, public bool $isTest = false) {}

    public function build(): self
    {
        $subject = $this->isTest ? '[TEST] '.$this->newsletter->subject : $this->newsletter->subject;
        return $this->subject($subject)->html($this->newsletter->body_html);
    }
}
