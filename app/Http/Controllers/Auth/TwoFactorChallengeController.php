<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TotpService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TwoFactorChallengeController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        if (!$user || !$user->two_factor_enabled) {
            return redirect()->route('dashboard');
        }
        // Ensure email code exists if email OTP is enabled (even when TOTP also enabled)
        if ($user->email_otp_enabled) {
            if (!$user->pending_otp_code || !$user->pending_otp_expires_at || now()->greaterThan($user->pending_otp_expires_at)) {
                $code = random_int(100000,999999);
                $user->pending_otp_code = Hash::make($code);
                $user->pending_otp_expires_at = now()->addMinutes(10);
                $user->save();
                Mail::raw('Your login email verification code is: '.$code, function($m) use ($user){
                    $m->to($user->email)->subject('Your 2FA email code');
                });
                session()->flash('success','Email verification code sent.');
            }
        }
        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request, TotpService $totp)
    {
        $rules = [];
        $user = Auth::user();
        if ($user->totp_enabled) {
            $rules['totp_code'] = ['required','string'];
        }
        if ($user->email_otp_enabled) {
            $rules['email_code'] = ['required','string'];
        }
        if (empty($rules)) {
            return redirect()->route('dashboard');
        }
        $request->validate($rules);
        $user = Auth::user();
        if (!$user || !$user->two_factor_enabled) {
            return redirect()->route('dashboard');
        }
        // Validate TOTP
        if ($user->totp_enabled) {
            if (!$totp->verifyCode($user->two_factor_secret, $request->input('totp_code'))) {
                return back()->with('error','Invalid authenticator code');
            }
        }
        // Validate Email OTP
        if ($user->email_otp_enabled) {
            if (!$user->pending_otp_code || !$user->pending_otp_expires_at || now()->greaterThan($user->pending_otp_expires_at)) {
                return back()->with('error','Email code expired, resend.');
            }
            if (!Hash::check($request->input('email_code'), $user->pending_otp_code)) {
                return back()->with('error','Invalid email code');
            }
            // Invalidate used email code
            $user->pending_otp_code = null;
            $user->pending_otp_expires_at = null;
            $user->save();
        }
        $request->session()->put('2fa_passed', true);
        return redirect()->intended(route('dashboard'));
    }

    public function resendOtp()
    {
        $user = Auth::user();
        if (!$user || !$user->two_factor_enabled || $user->preferred_2fa_method !== 'otp') {
            return redirect()->route('dashboard');
        }
        $code = random_int(100000,999999);
        $user->pending_otp_code = Hash::make($code);
        $user->pending_otp_expires_at = now()->addMinutes(10);
        $user->save();
        Mail::raw('Your login email verification code is: '.$code, function($m) use ($user){
            $m->to($user->email)->subject('Your 2FA email code');
        });
        return back()->with('success','Email code resent.');
    }
}
