<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $user = $request->user();
        if ($user) {
            try {
                $ip = $request->ip() ?? 'unknown';
                $rawUa = (string)($request->header('User-Agent') ?? '');
                $time = now()->toDateTimeString();
                // derive friendly device
                if (preg_match('/iPhone|iPad|iPod/i', $rawUa)) {
                    $device = 'iPhone / iPad';
                } elseif (preg_match('/Android/i', $rawUa)) {
                    $device = 'Android device';
                } elseif (preg_match('/Windows NT/i', $rawUa)) {
                    $device = 'Windows PC';
                } elseif (preg_match('/Macintosh|Mac OS X/i', $rawUa)) {
                    $device = 'Mac';
                } else {
                    $device = 'Desktop';
                }
                // core browser name only (no versions)
                if (preg_match('/Edg\//i', $rawUa)) {
                    $agent = 'Edge';
                } elseif (preg_match('/OPR\//i', $rawUa) || preg_match('/Opera/i', $rawUa)) {
                    $agent = 'Opera';
                } elseif (preg_match('/Chrome\//i', $rawUa) && !preg_match('/Edg\//i', $rawUa) && !preg_match('/OPR\//i', $rawUa)) {
                    $agent = 'Chrome';
                } elseif (preg_match('/Firefox\//i', $rawUa)) {
                    $agent = 'Firefox';
                } elseif (preg_match('/Safari\//i', $rawUa) && preg_match('/Version\//i', $rawUa) && !preg_match('/Chrome\//i', $rawUa)) {
                    $agent = 'Safari';
                } else {
                    $agent = 'Other';
                }
                $user->notify(new \App\Notifications\LoginSuccessNotification(
                    $request->has('google_id') ? 'Social (Google)' : 'Password',
                    $ip,
                    $agent,
                    $time,
                    $device
                ));
            } catch (\Throwable $e) { /* swallow notification errors */ }
        }
        if ($user && ($user->must_change_password || ($user->password_expires_at && now()->greaterThan($user->password_expires_at)))) {
            return redirect()->route('password.force.show');
        }
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
