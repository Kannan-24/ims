<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
    $email = $this->input('email');
    $attemptCacheKey = 'login_failed_count:'.sha1($email.'|'.$this->ip());
    $threshold = (int) config('security.failed_login_alert_threshold', 3);
    $alertEvery = (bool) config('security.failed_login_alert_every', false);
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            $count = cache()->increment($attemptCacheKey) ?: 1; // increment returns 1 when created
            cache()->put($attemptCacheKey, $count, now()->addMinutes(30));
            $shouldAlert = false;
            if($count >= $threshold) {
                if($alertEvery) {
                    $shouldAlert = true;
                } else {
                    $shouldAlert = $count === $threshold; // only first time threshold reached
                }
            }
            if($shouldAlert){
                $user = \App\Models\User::where('email',$email)->first();
                if($user){
                    try {
                        Log::info('Dispatching failed login alert email', [
                            'email'=>$email,
                            'count'=>$count,
                            'ip'=>$this->ip(),
                        ]);
                        // derive friendly device and core browser name (no versions)
                        $rawUa = (string)($this->header('User-Agent') ?? '');
                        if (preg_match('/iPhone|iPad|iPod/i', $rawUa)) {
                            $device = 'iPhone / iPad';
                        } elseif (preg_match('/Android/i', $rawUa)) {
                            $device = 'Android device';
                        } elseif (preg_match('/Windows NT/i', $rawUa)) {
                            $device = 'Windows PC';
                        } elseif (preg_match('/Macintosh|Mac OS X/i', $rawUa)) {
                            $device = 'Mac';
                        } else {
                            $device = 'Desktop';
                        }
                        if (preg_match('/Edg\//i', $rawUa)) {
                            $browser = 'Edge';
                        } elseif (preg_match('/OPR\//i', $rawUa) || preg_match('/Opera/i', $rawUa)) {
                            $browser = 'Opera';
                        } elseif (preg_match('/Chrome\//i', $rawUa) && !preg_match('/Edg\//i', $rawUa) && !preg_match('/OPR\//i', $rawUa)) {
                            $browser = 'Chrome';
                        } elseif (preg_match('/Firefox\//i', $rawUa)) {
                            $browser = 'Firefox';
                        } elseif (preg_match('/Safari\//i', $rawUa) && preg_match('/Version\//i', $rawUa) && !preg_match('/Chrome\//i', $rawUa)) {
                            $browser = 'Safari';
                        } else {
                            $browser = 'Other';
                        }
                        $agentLabel = $device.' / '.$browser;
                        $user->notify(new \App\Notifications\MultipleFailedLoginAlert(
                                    $count,
                                    $this->ip() ?? 'unknown',
                                    $agentLabel,
                                    now()->toDateTimeString()
                                ));
                    } catch(\Throwable $e) { /* swallow */ }
                }
            }
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        cache()->forget($attemptCacheKey);
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
