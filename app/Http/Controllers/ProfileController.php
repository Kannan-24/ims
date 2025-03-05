<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function show(Request $request)
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Show the edit profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'blood_group' => 'nullable|string|max:10',
            'state' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'doj' => 'nullable|date',
            'designation' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update($request->all());

        return redirect()->route('profile.show')->with('status', 'Profile updated successfully!');
    }

    /**
     * Update only the profile image.
     */

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Ensure old image is deleted if it exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new image
        $path = $request->file('profile_photo')->store('profile_photos', 'public');

        // Debugging: Check if file is uploaded
        if (!$path) {
            return back()->withErrors(['profile_photo' => 'File upload failed!']);
        }

        // Update user profile with new image path
        $user->profile_photo = $path;
        $user->save();

        return redirect()->route('profile.show')->with('status', 'Profile image updated successfully!');
    }
}
