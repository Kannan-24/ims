<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoginSuccessNotification extends Notification
{
    use Queueable;

    protected string $method; protected string $ip; protected string $agent; protected string $time; protected ?string $device;

    public function __construct(string $method, string $ip, string $agent, string $time, ?string $device = null)
    {
        $this->method = $method;
        $this->ip = $ip;
        $this->agent = $agent;
        $this->time = $time;
        $this->device = $device;
    }

    public function via($notifiable){ return ['mail']; }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Login to Your Account')
            ->markdown('emails.security.login_success', [
                'user' => $notifiable,
                'method' => $this->method,
                'ip' => $this->ip,
                'agent' => $this->agent,
                'time' => $this->time,
                'location' => null,
                'device' => $this->device,
            ]);
    }
}
