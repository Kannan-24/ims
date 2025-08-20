<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MultipleFailedLoginAlert extends Notification
{
    use Queueable;
    protected int $attempts; protected string $ip; protected string $agent; protected string $time;
    public function __construct(int $attempts, string $ip, string $agent, string $time)
    { $this->attempts=$attempts; $this->ip=$ip; $this->agent=$agent; $this->time=$time; }
    public function via($notifiable){ return ['mail']; }
    public function toMail($notifiable)
    { return (new MailMessage)
        ->subject('Security Alert: Multiple Failed Login Attempts')
        ->view('emails.security.failed_attempts', [
            'user'=>$notifiable,
            'attempts'=>$this->attempts,
            'ip'=>$this->ip,
            'agent'=>$this->agent,
            'time'=>$this->time,
        ]); }
}
