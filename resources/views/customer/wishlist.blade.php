<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Services\CartService;

new #[Layout('layouts.guest.app')] class extends Component {
    public function removeFromWishlist(Product $product)
    {
        Auth::user()->wishlist()->detach($product->id);
        $this->dispatch('wishlist-updated');
    }

    public function addToCart(CartService $cartService, Product $product)
    {
        $cartService->addItem($product);
        $this->dispatch('cart-updated');
        session()->flash('success', 'Product added to cart!');
    }

    public function with()
    {
        return [
            'wishlistItems' => Auth::user()->wishlist()->with('brand')->latest()->get(),
        ];
    }
}; ?>

<x-slot name="title">My Wishlist</x-slot>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-32 md:pb-10">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Customer Sidebar -->
        <aside class="hidden md:block w-full md:w-64 flex-shrink-0">
            @include('customer.sidebar')
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 bg-white rounded-2xl  border border-gray-100 p-8 min-h-[600px]">
            <div class="mb-10">
                <h1 class="text-2xl font-bold text-gray-900">My Wishlist</h1>
                <p class="text-sm text-gray-500">Items you've saved for later. You can move them to your cart when
                    you're ready.</p>
            </div>

            @if(session()->has('success'))
                <div
                    class="mb-6 p-4 bg-green-50 text-green-700 rounded-2xl border border-green-100 flex items-center gap-3">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-bold uppercase tracking-widest">{{ session('success') }}</span>
                </div>
            @endif

            @if($wishlistItems->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($wishlistItems as $product)
                        <div
                            class="group relative bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
                            <div class="aspect-square relative overflow-hidden bg-gray-50">
                                <img src="{{ $product->image_path ? Storage::url($product->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=f3f4f6&color=9ca3af' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                <button wire:click="removeFromWishlist({{ $product->id }})"
                                    class="absolute top-3 right-3 p-2 bg-white/90 backdrop-blur-sm text-red-500 rounded-xl opacity-0 group-hover:opacity-100 transition-all hover:bg-red-50 hover:scale-110 shadow-sm">
                                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div class="p-5">
                                <div class="mb-3">
                                    <h4 class="text-sm font-bold text-gray-900 line-clamp-1 mb-1">{{ $product->name }}</h4>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                        {{ $product->brand->name ?? $product->brand ?? 'No Brand' }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-lg font-black text-blue-900">₦{{ number_format($product->price, 2) }}</span>
                                        @if($product->compare_at_price)
                                            <span
                                                class="text-[10px] text-gray-400 line-through">₦{{ number_format($product->compare_at_price, 2) }}</span>
                                        @endif
                                    </div>

                                    <button wire:click="addToCart({{ $product->id }})"
                                        class="px-4 py-2 bg-blue-900 text-white text-[10px] font-bold rounded-xl hover:bg-blue-800 transition-colors shadow-lg shadow-blue-900/10">
                                        ADD TO CART
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="py-24 flex flex-col items-center justify-center text-center bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                    <div class="size-20 rounded-3xl bg-white shadow-sm flex items-center justify-center mb-6">
                        <svg class="size-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Your wishlist is empty</h3>
                    <p class="text-sm text-gray-500 max-w-xs mb-8">Save products you love to your wishlist to find them
                        easily later and stay updated on price changes.</p>
                    <a href="{{ route('home') }}"
                        class="px-8 py-3 bg-blue-900 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-blue-800 transition-all shadow-xl shadow-blue-900/20">
                        Start Exploring
                    </a>
                </div>
            @endif
        </main>
    </div>
</div>