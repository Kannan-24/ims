<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordExpiryReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $daysLeft;

    public function __construct($user, int $daysLeft)
    {
        $this->user = $user;
        $this->daysLeft = $daysLeft;
    }

    public function build()
    {
        return $this->subject('Password Expiry Reminder')
            ->markdown('emails.password_expiry_reminder_markdown');
    }
}
