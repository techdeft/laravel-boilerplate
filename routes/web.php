<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestPagesController;



//Guest Routh
Route::get('/', [GuestPagesController::class, 'home'])->name('home');




Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ── Authenticated App Pages ─────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/app/settings', 'app.settings.settings')->name('app.settings');
});




require __DIR__ . '/settings.php';
