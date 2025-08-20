<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'last_password_changed_at' => now(),
            'password_expires_at' => now()->addDays(config('password_policy.expiry_days')),
        ]);
           
           try {
               $ip = $request->ip();
               $agent = substr((string)($request->header('User-Agent') ?? 'Unknown Agent'),0,200);
               $request->user()->notify(new \App\Notifications\PasswordChangedNotification($ip, $agent, now()->toDateTimeString()));
           } catch(\Throwable $e) { /* swallow */ }

        return back()->with('status', 'password-updated');
    }
}
