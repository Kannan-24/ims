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
                $agent = substr((string)($request->header('User-Agent') ?? 'Unknown Agent'), 0, 200);
                $time = now()->toDateTimeString();
                $device = null; // could be parsed from agent if required
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
