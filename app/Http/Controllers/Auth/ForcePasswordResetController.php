<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ForcePasswordResetController extends Controller
{
    public function show()
    {
        return view('auth.force-password-reset');
    }

    public function update(Request $request)
    {
        $rules = [
            'password' => ['required','confirmed','string', function($attr,$value,$fail){
                $config = config('password_policy');
                if(strlen($value) < $config['min_length']) $fail('Password must be at least '.$config['min_length'].' characters.');
                if($config['require_uppercase'] && !preg_match('/[A-Z]/',$value)) $fail('Password must contain an uppercase letter.');
                if($config['require_lowercase'] && !preg_match('/[a-z]/',$value)) $fail('Password must contain a lowercase letter.');
                if($config['require_number'] && !preg_match('/[0-9]/',$value)) $fail('Password must contain a number.');
                if($config['require_symbol'] && !preg_match('/[^A-Za-z0-9]/',$value)) $fail('Password must contain a symbol.');
            }],
        ];
        $request->validate($rules);

    /** @var \App\Models\User $user */
    $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->last_password_changed_at = now();
        $user->password_expires_at = now()->addDays(config('password_policy.expiry_days'));
        $user->must_change_password = false;
        $user->password_last_reminder_sent_at = null;
        $user->save();

        return redirect()->route('dashboard')->with('success','Password updated successfully.');
    }
}
