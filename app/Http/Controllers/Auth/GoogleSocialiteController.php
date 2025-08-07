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
    public function redirectToGoogle(Request $request)
    {
        // Store popup parameter in session for callback
        if ($request->has('popup')) {
            session(['google_popup' => true]);
        }
        
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $user = Socialite::driver('google')->user();
            $isPopup = session('google_popup', false) || $request->has('popup');

            // Clear popup session
            session()->forget('google_popup');

            // Check if user exists in our database
            $finduser = User::where('email', $user->email)->first();

            if ($finduser) {
                // User exists, log them in
                Auth::login($finduser);

                // Check if this is a popup request
                if ($isPopup) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Successfully logged in with Google!',
                            'redirect' => route('dashboard')
                        ]);
                    }
                    
                    // Redirect to popup handler view
                    return view('auth.google-popup', [
                        'success' => true,
                        'message' => 'Successfully logged in with Google!',
                        'redirect' => route('dashboard')
                    ]);
                }

                return redirect()->intended(route('dashboard', absolute: false))
                    ->with('response', [
                        'status' => 'success',
                        'message' => 'Successfully logged in with Google!'
                    ]);
            } else {
                // User doesn't exist, return error
                if ($isPopup) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'This email is not registered. Please contact administrator to create an account.'
                        ]);
                    }
                    
                    // Redirect to popup handler view
                    return view('auth.google-popup', [
                        'success' => false,
                        'message' => 'This email is not registered. Please contact administrator to create an account.'
                    ]);
                }

                return redirect()->route('login')
                    ->with('response', [
                        'status' => 'error',
                        'message' => 'This email is not registered. Please contact administrator to create an account.'
                    ]);
            }
        } catch (Exception $e) {
            $isPopup = session('google_popup', false) || $request->has('popup');
            session()->forget('google_popup');

            // Handle any errors during the OAuth process
            if ($isPopup) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong with Google authentication. Please try again.'
                    ]);
                }
                
                // Redirect to popup handler view
                return view('auth.google-popup', [
                    'success' => false,
                    'message' => 'Something went wrong with Google authentication. Please try again.'
                ]);
            }

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
