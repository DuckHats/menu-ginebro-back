<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BalanceUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $platformUrl;

    public function __construct(User $user, $amount)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->platformUrl = config('services.frontend.url');
    }

    public function build()
    {
        return $this->subject('El teu saldo s\'ha actualitzat - Ginebró')
            ->view('emails.balance_updated');
    }
}
