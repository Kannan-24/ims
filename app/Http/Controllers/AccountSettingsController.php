<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountSettingsController extends Controller
{
    /**
     * Display the account settings page.
     */
    public function index()
    {
        return view('profile.account-settings', ['user' => Auth::user()]);
    }

    /**
     * Update profile information.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        Auth::user()->update($request->only('name', 'email'));

        return redirect()->route('account.settings')->with('status', 'profile-updated');
    }

    /**
     * Update the password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('account.settings')->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
        public function destroy(Request $request)
        {
            $request->validate([
                'password' => ['required', 'current_password'],
            ]);

            $user = Auth::user();
            Auth::logout();
            $user->delete();

            return redirect('/')->with('status', 'account-deleted');
        }
}
