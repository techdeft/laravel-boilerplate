<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestPagesController;
use App\Http\Controllers\PhoneVerificationController;


//Guest Routh
Route::get('/', [GuestPagesController::class, 'home'])->name('home');




Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'phone.verified'])
    ->name('dashboard');

Route::get('/phone/verification', [PhoneVerificationController::class, 'index'])->middleware(['auth', 'verified'])->name('verification.phone.notice');
Route::post('/phone/verify', [PhoneVerificationController::class, 'verify'])->middleware(['auth', 'verified'])->name('verification.phone.verify');
Route::post('/phone/resend', [PhoneVerificationController::class, 'resend'])->middleware(['auth', 'verified'])->name('verification.phone.resend');

// ── Authenticated App Pages ─────────────────────────────────────
Route::middleware(['auth', 'verified', 'phone.verified'])->group(function () {


    Route::livewire('/app/settings', 'app.settings.settings')->name('app.settings');
});




require __DIR__ . '/settings.php';
