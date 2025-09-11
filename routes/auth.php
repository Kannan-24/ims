<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\GoogleSocialiteController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Email lookup for login flow
    Route::post('login/check-email', [AuthenticatedSessionController::class, 'getUserByEmail'])
        ->name('login.check-email');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // Google OAuth Routes
    Route::get('auth/google', [GoogleSocialiteController::class, 'redirectToGoogle'])
        ->name('auth.google');

    Route::get('auth/google/callback', [GoogleSocialiteController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Forced password reset routes
    Route::get('force-password-reset', [\App\Http\Controllers\Auth\ForcePasswordResetController::class, 'show'])
        ->name('password.force.show');
    Route::put('force-password-reset', [\App\Http\Controllers\Auth\ForcePasswordResetController::class, 'update'])
        ->name('password.force.update');

    // Two-Factor routes
    Route::post('2fa/start', [\App\Http\Controllers\Auth\TwoFactorController::class,'start'])->name('2fa.start');
    Route::post('2fa/confirm', [\App\Http\Controllers\Auth\TwoFactorController::class,'confirm'])->name('2fa.confirm');
    Route::post('2fa/disable', [\App\Http\Controllers\Auth\TwoFactorController::class,'disable'])->name('2fa.disable');
    Route::post('2fa/method/disable', [\App\Http\Controllers\Auth\TwoFactorController::class,'disableMethod'])->name('2fa.method.disable');
    Route::post('2fa/management/email-code', [\App\Http\Controllers\Auth\TwoFactorController::class,'sendManagementEmailCode'])->name('2fa.management.email');
    Route::get('2fa/challenge', [\App\Http\Controllers\Auth\TwoFactorChallengeController::class,'show'])->name('2fa.challenge.show');
    Route::post('2fa/challenge', [\App\Http\Controllers\Auth\TwoFactorChallengeController::class,'verify'])->name('2fa.challenge.verify');
    Route::post('2fa/challenge/resend', [\App\Http\Controllers\Auth\TwoFactorChallengeController::class,'resendOtp'])->name('2fa.challenge.resend');
});
