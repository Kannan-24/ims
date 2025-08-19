<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforcePasswordPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user) {
            $needsChange = $user->must_change_password;
            $expired = $user->password_expires_at && now()->greaterThan($user->password_expires_at);

            if (($needsChange || $expired) && !$this->isExemptRoute($request)) {
                return redirect()->route('password.force.show');
            }
        }
        return $next($request);
    }

    protected function isExemptRoute(Request $request): bool
    {
        $routeName = optional($request->route())->getName();
        $exempt = [
            'password.force.show',
            'password.force.update',
            'logout',
        ];
        return in_array($routeName, $exempt, true) || $request->is('logout');
    }
}
