<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\PhoneVerificationController;

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Route::livewire('settings/profile', 'app::settings.settings')->name('profile.edit');
// });

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::livewire('settings/password', 'pages::settings.password')->name('user-password.edit');
//     Route::livewire('settings/appearance', 'pages::settings.appearance')->name('app.settings');

//     Route::livewire('settings/two-factor', 'pages::settings.two-factor')
//         ->middleware(
//             when(
//                 Features::canManageTwoFactorAuthentication()
//                 && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
//                 ['password.confirm'],
//                 [],
//             ),
//         )
//         ->name('two-factor.show');
// });


Route::get('/phone/verification', [PhoneVerificationController::class, 'index'])->middleware(['auth', 'verified'])->name('verification.phone.notice');
Route::post('/phone/verify', [PhoneVerificationController::class, 'verify'])->middleware(['auth', 'verified'])->name('verification.phone.verify');
Route::post('/phone/resend', [PhoneVerificationController::class, 'resend'])->middleware(['auth', 'verified'])->name('verification.phone.resend');

