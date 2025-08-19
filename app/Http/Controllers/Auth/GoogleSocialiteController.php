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
