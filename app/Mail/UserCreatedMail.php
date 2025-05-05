<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $employeeId;
    public $name;
    public $email;
    public $phone;
    public $defaultPassword;

    public function __construct($user, $defaultPassword)
    {
        $this->user = $user;
        $this->employeeId = $user->employee_id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->defaultPassword = $defaultPassword;
    }

    public function build()
    {
        return $this->subject('Welcome to SKM & Company - Your Account Details')
            ->view('emails.user_created');
    }
}
