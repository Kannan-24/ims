<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class CustomResetPassword extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        $url = $this->resetUrl($notifiable);
        // Generate 7-digit OTP
        $otp = (string) random_int(1000000, 9999999);
        $otpKey = 'password_reset_otp:'.sha1($notifiable->getEmailForPasswordReset() ?? $notifiable->email);
        $otpTtlMinutes = 15;
        Cache::put($otpKey, $otp, now()->addMinutes($otpTtlMinutes));
        $otpExpiresAt = now()->addMinutes($otpTtlMinutes)->toDateTimeString();

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->view('emails.password.reset', [
                'resetUrl' => $url,
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
                'otp' => $otp,
                'otp_expires_at' => $otpExpiresAt,
                'otp_ttl' => $otpTtlMinutes,
            ]);
    }
}
