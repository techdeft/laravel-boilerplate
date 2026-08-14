<?php

use Illuminate\Support\Facades\Route;


Route::livewire('cart.html', 'customer.cart')->name('customer.cart');

Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {

    Route::livewire('overview.html', 'customer.dashboard')->name('dashboard');
    Route::livewire('wishlist.html', 'customer.wishlist')->name('wishlist');
    // Route::livewire('cart.html', 'customer.cart')->name('cart');
    Route::livewire('profile.html', 'customer.profile')->name('profile');
    Route::livewire('security.html', 'customer.security')->name('security');
    Route::livewire('addresses.html', 'customer.addresses')->name('addresses');
    Route::livewire('checkout.html', 'customer.checkout')->name('checkout');
    Route::livewire('orders.html', 'customer.orders')->name('orders');
    Route::livewire('orders/{orderNumber}.html', 'customer.order-details')->name('order-details');
    Route::livewire('order-success/{orderNumber}', 'customer.order-success')->name('order-success');

    // Add more customer routes here as needed
});
