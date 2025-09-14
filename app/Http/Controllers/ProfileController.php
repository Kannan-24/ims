<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    /**
     * Generate QR Code for user profile
     */
    public function generateQRCode(User $user)
    {
        // Generate URL for public profile
        $profileUrl = route('profile.public', $user);
        
        // Generate QR code using GD (not Imagick)
        $qrCode = QrCode::size(300)
            ->format('png')
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->generate($profileUrl);

        return response($qrCode, 200, [
            'Content-Type' => 'image/png',
        ]);
    }

    /**
     * Download QR Code for user profile
     */
    public function downloadQRCode(User $user)
    {
        // Generate URL for public profile
        $profileUrl = route('profile.public', $user);
        
        // Generate QR code using GD (not Imagick)
        $qrCode = QrCode::size(300)
            ->format('png')
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->generate($profileUrl);

        $fileName = 'profile-qr-' . $user->employee_id . '.png';

        return response($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Show public profile page (accessible without authentication)
     */
    public function publicProfile(User $user)
    {
        return view('profile.public', [
            'user' => $user,
        ]);
    }
}
