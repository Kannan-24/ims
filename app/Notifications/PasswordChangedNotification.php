<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordChangedNotification extends Notification
{
    use Queueable;
    protected string $ip; protected string $agent; protected string $time;
    public function __construct(string $ip, string $agent, string $time)
    { $this->ip=$ip; $this->agent=$agent; $this->time=$time; }
    public function via($notifiable){ return ['mail']; }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your password was changed')
            ->view('emails.security.password_changed', [
                'user' => $notifiable,
                'ip' => $this->ip,
                'agent' => $this->agent,
                'time' => $this->time,
            ]);
    }
}
