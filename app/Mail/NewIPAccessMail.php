<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewIPAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ipDetails;

    public function __construct(array $ipDetails)
    {
        $this->ipDetails = $ipDetails;
    }

    public function build()
    {
        return $this->view('emails.new_ip_access')
            ->subject('Nuevo inicio de sesión detectado desde una IP diferente')
            ->with('ipDetails', $this->ipDetails);
    }
}
