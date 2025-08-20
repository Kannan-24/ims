<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountSettingsController extends Controller
{
    /**
     * Display the account settings page.
     */
    public function index()
    {
        $user = Auth::user();
        $confirmedAt = session('account_settings_confirmed_at');
        $confirmed = $confirmedAt && now()->diffInMinutes($confirmedAt) < 10; // 10 minute window
        $sessions = [];
        if ($confirmed) {
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderByDesc('last_activity')
                ->get()
                ->map(function($row){
                    return [
                        'id' => $row->id,
                        'ip' => $row->ip_address,
                        'user_agent' => $row->user_agent,
                        'last_activity' => \Carbon\Carbon::createFromTimestamp($row->last_activity),
                        'is_current' => $row->id === session()->getId(),
                    ];
                });
        }
        return view('profile.account-settings', compact('user','confirmed','sessions'));
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
            'last_password_changed_at' => now(),
            'password_expires_at' => now()->addDays(config('password_policy.expiry_days')),
        ]);
           
           // Notify user that their password was changed
           try {
               $ip = $request->ip();
               $agent = substr((string)($request->header('User-Agent') ?? 'Unknown Agent'),0,200);
               Auth::user()->notify(new \App\Notifications\PasswordChangedNotification($ip, $agent, now()->toDateTimeString()));
           } catch(\Throwable $e) { /* swallow */ }

        return redirect()->route('account.settings')->with('status', 'password-updated');
    }

    public function confirmAccess(Request $request)
    {
        $request->validate(['current_password' => ['required']]);
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error','Password incorrect');
        }
        session(['account_settings_confirmed_at' => now()]);
        return redirect()->route('account.settings');
    }

    public function destroySession(Request $request, string $sessionId)
    {
        $user = Auth::user();
        if ($sessionId === session()->getId()) {
            return back()->with('error','Cannot delete current session.');
        }
        DB::table('sessions')->where('user_id',$user->id)->where('id',$sessionId)->delete();
        return back()->with('success','Session terminated.');
    }

    public function destroyOtherSessions()
    {
        $user = Auth::user();
        DB::table('sessions')->where('user_id',$user->id)->where('id','!=',session()->getId())->delete();
        return back()->with('success','Other sessions terminated.');
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
