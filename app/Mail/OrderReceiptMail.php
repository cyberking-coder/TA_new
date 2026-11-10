<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $setupUrl) {}

    public function build(): self
    {
        return $this->subject('Order '.$this->order->order_ref.' confirmed')
            ->html(view('mail.order-receipt', [
                'order' => $this->order,
                'setupUrl' => $this->setupUrl,
            ])->render());
    }
}
