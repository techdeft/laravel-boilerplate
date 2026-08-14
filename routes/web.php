<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestPagesController;
use App\Http\Controllers\PhoneVerificationController;


// Guest Routes
Route::get('/', [GuestPagesController::class, 'home'])->name('home');
Route::livewire('/product/{product:slug}.html', 'guest.product-detail')->name('product.show');
Route::livewire('/shop.html', 'guest.shop')->name('shop');
Route::livewire('/track-order.html', 'guest.track-order')->name('track-order');
Route::view('/talk-to-pharmacist.html', 'guest.telehealth')->name('telehealth');
Route::livewire('/book-consultation.html', 'guest.booking.create')->name('booking.create');
Route::view('/about.html', 'guest.about')->name('about');
Route::view('/contact.html', 'guest.contact')->name('contact');
// Payment Callback
Route::get('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'handleGatewayCallback'])->name('payment.callback');







// ── Authenticated App Pages ─────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {


    Route::livewire('/admin/settings', 'app.settings.settings')->name('app.settings');
    Route::livewire('/admin/media', 'app.media')->name('admin.media');
    Route::livewire('/admin/home/slider', 'app.home.slider')->name('admin.home.slider');
    Route::livewire('/admin/categories', 'app.category')->name('admin.categories');
    Route::livewire('/admin/brands', 'app.brand')->name('admin.brands');
    Route::livewire('/admin/delivery-zones', 'app.delivery-zones')->name('admin.delivery-fees');

    // Product Management
    Route::livewire('/admin/products', 'app.product.index')->name('admin.products.index');
    Route::livewire('/admin/products/create', 'app.product.create')->name('admin.products.create');
    Route::livewire('/admin/products/{product}/edit', 'app.product.edit')->name('admin.products.edit');

    // Customer Management
    Route::livewire('/admin/customers', 'app.customer.index')->name('admin.customers.index');
    Route::livewire('/admin/customers/{user:uuid}', 'app.customer.details')->name('admin.customers.details');

    // Order Management
    Route::livewire('/admin/orders', 'app.order.index')->name('admin.orders.index');
    Route::livewire('/admin/orders/{order:order_number}', 'app.order.details')->name('admin.orders.details');

    Route::livewire('/admin/dashboard', 'app.dashboard')->name('dashboard');

    // Booking Management
    Route::livewire('/admin/bookings', 'app.booking.index')->name('admin.bookings.index');
    Route::livewire('/admin/bookings/settings', 'app.booking.settings')->name('admin.bookings.settings');
    Route::livewire('/admin/bookings/{booking}', 'app.booking.details')->name('admin.bookings.details');
});




require __DIR__ . '/settings.php';
require __DIR__ . '/customer.php';
