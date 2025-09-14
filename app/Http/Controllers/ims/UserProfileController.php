<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Display the specified user profile
     */
    public function show(User $user)
    {
        return view('ims.users.profile', compact('user'));
    }

    /**
     * Start chat with the specified user
     */
    public function startChat(User $user)
    {
        return redirect()->route('chat.with', $user);
    }

    /**
     * Show user's QR code for profile sharing
     */
    public function showQRCode(User $user)
    {
        return view('ims.users.qr-code', compact('user'));
    }
}
