<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', '2fa'])
    ->name('dashboard');

// Authenticated User Routes
Route::middleware(['auth','2fa'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.update.photo');

    // Account Settings Routes
    Route::get('/account-settings', [AccountSettingsController::class, 'index'])->name('account.settings');
    Route::post('/account-settings/confirm', [AccountSettingsController::class, 'confirmAccess'])->name('account.settings.confirm');
    Route::delete('/account-settings/sessions/{id}', [AccountSettingsController::class, 'destroySession'])->name('account.settings.sessions.destroy');
    Route::delete('/account-settings/sessions', [AccountSettingsController::class, 'destroyOtherSessions'])->name('account.settings.sessions.destroy.others');
    Route::patch('/account-settings/password', [AccountSettingsController::class, 'updatePassword'])->name('account.update.password');
    Route::delete('/account-settings/delete', [AccountSettingsController::class, 'destroy'])->name('account.destroy');

    // Calendar Routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/events', [CalendarController::class, 'events'])->name('events');
        Route::post('/events', [CalendarController::class, 'store'])->name('events.store');
        Route::get('/events/{event}', [CalendarController::class, 'show'])->name('events.show');
        Route::put('/events/{event}', [CalendarController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [CalendarController::class, 'destroy'])->name('events.destroy');
        Route::patch('/events/{event}/move', [CalendarController::class, 'move'])->name('events.move');
    });
});


require __DIR__ . '/auth.php';
require __DIR__ . '/ims.php';
