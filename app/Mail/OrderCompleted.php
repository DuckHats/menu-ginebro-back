<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $platformUrl;

    public function __construct(User $user, Order $order)
    {
        $this->user = $user;
        $this->order = $order;
        $this->platformUrl = config('services.frontend.url');
    }

    public function build()
    {
        return $this->subject('Confirmació de la teva comanda - Ginebró')
            ->view('emails.order_confirmation');
    }
}
