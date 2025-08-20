<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'otp' => ['required', 'string'],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        // Verify OTP
        $otpKey = 'password_reset_otp:'.sha1($request->input('email'));
        $cached = cache()->get($otpKey);
        if(!$cached || $cached !== $request->input('otp')){
            return back()->withInput($request->only('email'))->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                    'last_password_changed_at' => now(),
                    'password_expires_at' => now()->addDays(config('password_policy.expiry_days')),
                ])->save();

                event(new PasswordReset($user));
                   
                   // Notify user that their password was changed
                   try {
                       $ip = $request->ip();
                       $agent = substr((string)($request->header('User-Agent') ?? 'Unknown Agent'),0,200);
                       $user->notify(new \App\Notifications\PasswordChangedNotification($ip, $agent, now()->toDateTimeString()));
                   } catch(\Throwable $e) { /* swallow */ }
            }
        );

    // Clear OTP after successful reset attempt
    cache()->forget($otpKey);

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
