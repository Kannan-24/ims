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
                // derive friendly browser string
                $agent = 'Unknown';
                if (preg_match('/Edg\//i', $rawUa, $m)) {
                    preg_match('/Edg\/(\d+(?:\.\d+)*)/i', $rawUa, $v);
                    $agent = 'Edge ' . ($v[1] ?? '');
                } elseif (preg_match('/OPR\//i', $rawUa)) {
                    preg_match('/OPR\/(\d+(?:\.\d+)*)/i', $rawUa, $v);
                    $agent = 'Opera ' . ($v[1] ?? '');
                } elseif (preg_match('/Chrome\//i', $rawUa)) {
                    preg_match('/Chrome\/(\d+(?:\.\d+)*)/i', $rawUa, $v);
                    $agent = 'Chrome ' . ($v[1] ?? '');
                } elseif (preg_match('/Firefox\//i', $rawUa)) {
                    preg_match('/Firefox\/(\d+(?:\.\d+)*)/i', $rawUa, $v);
                    $agent = 'Firefox ' . ($v[1] ?? '');
                } elseif (preg_match('/Safari\//i', $rawUa) && preg_match('/Version\//i', $rawUa)) {
                    preg_match('/Version\/(\d+(?:\.\d+)*)/i', $rawUa, $v);
                    $agent = 'Safari ' . ($v[1] ?? '');
                } else {
                    $agent = substr($rawUa, 0, 200);
                }
                $location = null;
                if (function_exists('geoip')) {
                    try { $g = geoip($ip); $location = trim(implode(', ', array_filter([$g->city, $g->state, $g->country]))); } catch (\Throwable $__e) { $location = null; }
                }
                $user->notify(new \App\Notifications\LoginSuccessNotification(
                    $request->has('google_id') ? 'Social (Google)' : 'Password',
                    $ip,
                    $agent,
                    $time,
                    $location,
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
