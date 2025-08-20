<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleSocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            // Check if user exists in our database
            $finduser = User::where('email', $user->email)->first();

            if ($finduser) {
                // User exists, log them in
                Auth::login($finduser);

                // Notify user of login (include ip, agent, time and location when possible)
                try {
                    $ip = request()->ip() ?? 'unknown';
                    $rawUa = (string)(request()->header('User-Agent') ?? '');
                    $time = now()->toDateTimeString();
                    // device
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
                    // browser
                    $agent = 'Unknown';
                    if (preg_match('/Edg\//i', $rawUa)) {
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
                    if (function_exists('geoip') ) {
                        try { $g = geoip($ip); $location = trim(implode(', ', array_filter([$g->city, $g->state, $g->country]))); } catch(\Throwable $__e) { $location = null; }
                    }
                    $finduser->notify(new \App\Notifications\LoginSuccessNotification('Social (Google)', $ip, $agent, $time, $location, $device));
                } catch(\Throwable $e) { /* swallow notification errors */ }

                if ($finduser->must_change_password || ($finduser->password_expires_at && now()->greaterThan($finduser->password_expires_at))) {
                    return redirect()->route('password.force.show');
                }
                return redirect()->intended(route('dashboard', absolute: false))
                    ->with('response', [
                        'status' => 'success',
                        'message' => 'Successfully logged in with Google!'
                    ]);
            } else {
                // User doesn't exist, redirect to login with error
                return redirect()->route('login')
                    ->with('response', [
                        'status' => 'error',
                        'message' => 'This email is not registered. Please contact administrator to create an account.'
                    ]);
            }
        } catch (Exception $e) {
            // Handle any errors during the OAuth process
            return redirect()->route('login')
                ->with('response', [
                    'status' => 'error',
                    'message' => 'Something went wrong with Google authentication. Please try again.'
                ]);
        }
    }

    /**
     * Handle user logout and redirect to login page.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('response', [
                'status' => 'success',
                'message' => 'You have been successfully logged out.'
            ]);
    }
}
