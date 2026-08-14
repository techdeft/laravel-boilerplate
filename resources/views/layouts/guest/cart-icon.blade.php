<?php

use Livewire\Volt\Component;
use App\Services\CartService;
use Livewire\Attributes\On;

new class extends Component {
    #[On('cart-updated')]
    public function with(CartService $cartService)
    {
        return [
            'count' => $cartService->getCount(),
        ];
    }
}; ?>

<a href="{{ route('customer.cart') }}" wire:navigate
    class="relative p-2 text-gray-400 hover:text-blue-900 transition-colors group">
    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
    </svg>

    @if($count > 0)
        <span
            class="absolute top-0 right-0 size-5 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white group-hover:scale-110 transition-transform">
            {{ $count }}
        </span>
    @endif
</a>