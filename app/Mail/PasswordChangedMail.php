<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct() {}

    public function build()
    {
        return $this->subject('Contraseña Modificada')
            ->view('emails.password_changed');
    }
}
