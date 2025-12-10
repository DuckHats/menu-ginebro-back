<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountSuspensionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reason;

    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject(config('email_subjects.account_suspension'))
            ->view('emails.account-suspension')
            ->with([
                'reason' => $this->reason,
            ]);
    }
}
