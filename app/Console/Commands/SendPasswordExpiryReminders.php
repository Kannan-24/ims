<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordExpiryReminderMail;

class SendPasswordExpiryReminders extends Command
{
    protected $signature = 'passwords:send-expiry-reminders';
    protected $description = 'Send password expiry reminder emails to users';

    public function handle(): int
    {
        $config = config('password_policy');
        $offsets = collect($config['reminder_offsets'] ?? []);
        if ($offsets->isEmpty()) {
            $this->info('No reminder offsets configured.');
            return self::SUCCESS;
        }
        $today = now();
        $users = User::whereNotNull('password_expires_at')->get();
        $count = 0;
        foreach ($users as $user) {
            if (!$user->password_expires_at) continue;
            $daysLeft = $today->diffInDays($user->password_expires_at, false);
            if ($daysLeft < 0) continue; // already expired
            if (!$offsets->contains($daysLeft)) continue;
            // prevent duplicate reminders same day
            if ($user->password_last_reminder_sent_at && $user->password_last_reminder_sent_at->isToday()) continue;
            Mail::to($user->email)->queue(new PasswordExpiryReminderMail($user, $daysLeft));
            $user->password_last_reminder_sent_at = now();
            $user->save();
            $count++;
        }
        $this->info("Sent {$count} reminder(s).");
        return self::SUCCESS;
    }
}
