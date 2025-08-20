<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TotpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    private function recalcOverall($user): void
    {
        $changed = false;
        $overall = ($user->totp_enabled ?? false) || ($user->email_otp_enabled ?? false);
        if ($user->two_factor_enabled !== $overall) {
            $user->two_factor_enabled = $overall;
            $changed = true;
        }
        if ($changed) {
            $user->save();
        }
    }
    public function showSettings()
    {
        $user = Auth::user();
        $secret = $user->two_factor_secret;
        $otpauth = null;
        if ($secret) {
            $otpauth = app(TotpService::class)->getOtpAuthUrl(config('app.name'), $user->email, $secret);
        }
        return view('profile.partials.two-factor-settings', compact('user','secret','otpauth'));
    }

    public function start(Request $request, TotpService $totp)
    {
        $request->validate([
            'current_password' => 'required',
            'method' => 'required|in:totp,otp'
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error','Current password incorrect');
        }
        if ($request->method === 'totp') {
            $user->two_factor_secret = $totp->generateSecret();
            $user->preferred_2fa_method = 'totp';
            $user->two_factor_enabled = false; // becomes true after confirmation
            $user->save();
            return back()->with('success','Scan the QR and enter code to confirm TOTP.');
        }
        // email otp
        $code = random_int(100000,999999);
        $user->pending_otp_code = Hash::make($code);
        $user->pending_otp_expires_at = now()->addMinutes(10);
        $user->preferred_2fa_method = 'otp';
        $user->two_factor_enabled = false;
        $user->save();
        Mail::send('emails.security.2fa_otp', [
            'user' => $user,
            'code' => $code,
            'otp_expires_at' => $user->pending_otp_expires_at->toDateTimeString(),
            'otp_ttl' => 10,
            'ip' => $request->ip(),
            'agent' => substr((string)($request->header('User-Agent') ?? 'Unknown Agent'),0,200),
            'purpose' => 'confirm email 2FA'
        ], function($m) use ($user){
            $m->to($user->email)->subject('Your 2FA verification code');
        });
        return back()->with('success','OTP sent to your email. Enter to confirm.');
    }

    public function confirm(Request $request, TotpService $totp)
    {
        $request->validate(['code'=>'required','current_password'=>'required']);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error','Current password incorrect');
        }
    if ($user->preferred_2fa_method === 'totp') {
            if (!$user->two_factor_secret) return back()->with('error','No secret generated.');
            if (!$totp->verifyCode($user->two_factor_secret, $request->code)) {
                return back()->with('error','Invalid code.');
            }
        } else {
            if (!$user->pending_otp_code || !$user->pending_otp_expires_at || now()->greaterThan($user->pending_otp_expires_at)) {
                return back()->with('error','OTP expired. Start again.');
            }
            if (!Hash::check($request->code, $user->pending_otp_code)) {
                return back()->with('error','Invalid code.');
            }
            $user->pending_otp_code = null;
            $user->pending_otp_expires_at = null;
        }
        if ($user->preferred_2fa_method === 'totp') {
            $user->totp_enabled = true;
        } elseif ($user->preferred_2fa_method === 'otp') {
            $user->email_otp_enabled = true;
        }
        // overall 2FA is enabled if any method active
        $user->two_factor_enabled = ($user->totp_enabled || $user->email_otp_enabled);
        $user->two_factor_confirmed_at = now();
        $user->save();
        // notify user that 2FA enabled
        try {
            Mail::send('emails.security.2fa_enabled', [
                'user' => $user,
                'method' => $user->preferred_2fa_method,
                'ip' => $request->ip(),
                'agent' => substr((string)($request->header('User-Agent') ?? 'Unknown Agent'),0,200),
                'time' => now()->toDateTimeString(),
            ], function($m) use ($user){ $m->to($user->email)->subject('Two-factor authentication enabled'); });
        } catch(\Throwable $e) { /* swallow */ }
        return back()->with('success','Two-factor method enabled.');
    }

    public function disable(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'totp_code' => 'nullable|string',
            'email_code' => 'nullable|string'
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error','Current password incorrect');
        }
        // Require codes for each enabled method
        if ($user->totp_enabled) {
            if (!$request->filled('totp_code') || !app(TotpService::class)->verifyCode($user->two_factor_secret, $request->totp_code)) {
                return back()->with('error','Invalid or missing authenticator code.');
            }
        }
        if ($user->email_otp_enabled) {
            if (!$request->filled('email_code') || !$user->pending_otp_code || !$user->pending_otp_expires_at || now()->greaterThan($user->pending_otp_expires_at) || !Hash::check($request->email_code, $user->pending_otp_code)) {
                return back()->with('error','Invalid or missing email code.');
            }
        }
    // Disable all methods collectively
    $user->two_factor_secret = null;
    $user->pending_otp_code = null;
    $user->pending_otp_expires_at = null;
    $user->preferred_2fa_method = null;
    $user->totp_enabled = false;
    $user->email_otp_enabled = false;
    $user->two_factor_enabled = false;
    $user->save();
    // notify user that 2FA disabled
    try {
        Mail::send('emails.security.2fa_disabled', [
            'user' => $user,
            'method' => 'all methods',
            'ip' => $request->ip(),
            'agent' => substr((string)($request->header('User-Agent') ?? 'Unknown Agent'),0,200),
            'time' => now()->toDateTimeString(),
        ], function($m) use ($user){ $m->to($user->email)->subject('Two-factor authentication disabled'); });
    } catch(\Throwable $e) { /* swallow */ }
    return back()->with('success','All two-factor methods disabled.');
    }

    public function disableMethod(Request $request, TotpService $totp)
    {
        $request->validate([
            'method' => 'required|in:totp,otp',
            'current_password' => 'required',
            'code' => 'nullable'
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error','Current password incorrect');
        }
        $method = $request->method;
    if ($method === 'totp' && $user->totp_enabled) {
            // Optional code verification for extra security when disabling TOTP
            if ($request->filled('code')) {
                if (!$totp->verifyCode($user->two_factor_secret, $request->code)) {
                    return back()->with('error','Invalid TOTP code.');
                }
            }
            $user->totp_enabled = false;
            // If no other methods remain keep secret only if user wants maybe reuse; we remove for safety
            $user->two_factor_secret = null;
            try {
                Mail::send('emails.security.2fa_disabled', [
                    'user' => $user,
                    'method' => 'TOTP',
                    'ip' => $request->ip(),
                    'agent' => substr((string)($request->header('User-Agent') ?? 'Unknown Agent'),0,200),
                    'time' => now()->toDateTimeString(),
                ], function($m) use ($user){ $m->to($user->email)->subject('Two-factor method disabled'); });
            } catch(\Throwable $e) { /* swallow */ }
        } elseif ($method === 'otp' && $user->email_otp_enabled) {
            // Require a valid email code when disabling email OTP
            if (!$request->filled('code') || !$user->pending_otp_code || !$user->pending_otp_expires_at || now()->greaterThan($user->pending_otp_expires_at) || !Hash::check($request->code, $user->pending_otp_code)) {
                return back()->with('error','Invalid or missing email code. Use "Send Code" then enter it.');
            }
            $user->email_otp_enabled = false;
            $user->pending_otp_code = null;
            $user->pending_otp_expires_at = null;
            try {
                Mail::send('emails.security.2fa_disabled', [
                    'user' => $user,
                    'method' => 'Email OTP',
                    'ip' => $request->ip(),
                    'agent' => substr((string)($request->header('User-Agent') ?? 'Unknown Agent'),0,200),
                    'time' => now()->toDateTimeString(),
                ], function($m) use ($user){ $m->to($user->email)->subject('Two-factor method disabled'); });
            } catch(\Throwable $e) { /* swallow */ }
        }
        // Recalculate global flag
        $user->two_factor_enabled = ($user->totp_enabled || $user->email_otp_enabled);
        if (!$user->two_factor_enabled) {
            $user->preferred_2fa_method = null;
        } else {
            // keep preferred to remaining method
            if ($user->totp_enabled) {
                $user->preferred_2fa_method = 'totp';
            } elseif ($user->email_otp_enabled) {
                $user->preferred_2fa_method = 'otp';
            }
        }
        $user->save();
        return back()->with('success', ucfirst($method).' disabled.');
    }

    public function sendManagementEmailCode(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->email_otp_enabled) {
            return response()->json(['message'=>'Email OTP not enabled'], 400);
        }
        $code = random_int(100000,999999);
        $user->pending_otp_code = Hash::make($code);
        $user->pending_otp_expires_at = now()->addMinutes(10);
        $user->save();
        Mail::send('emails.security.2fa_otp', [
            'user' => $user,
            'code' => $code,
            'otp_expires_at' => $user->pending_otp_expires_at->toDateTimeString(),
            'otp_ttl' => 10,
            'ip' => request()->ip(),
            'agent' => substr((string)(request()->header('User-Agent') ?? 'Unknown Agent'),0,200),
            'purpose' => 'management',
        ], function($m) use ($user){ $m->to($user->email)->subject('Your 2FA management email code'); });
        if ($request->wantsJson()) {
            return response()->json(['message'=>'Management email code sent']);
        }
        return back()->with('success','Email code sent.');
    }
}
