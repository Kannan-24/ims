<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->two_factor_enabled) {
            if (!$request->session()->get('2fa_passed')) {
                $routeName = optional($request->route())->getName();
                if (!in_array($routeName, ['2fa.challenge.show','2fa.challenge.verify','logout'])) {
                    return redirect()->route('2fa.challenge.show');
                }
            }
        }
        return $next($request);
    }
}
