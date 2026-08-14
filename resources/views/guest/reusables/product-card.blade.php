<?php

use Livewire\Volt\Component;
use App\Services\CartService;

new class extends Component {
    public $product;

    public function mount($product = null)
    {
        $this->product = $product;
    }

    public function addToCart(CartService $cartService)
    {
        if ($this->product instanceof \App\Models\Product) {
            $cartService->addItem($this->product, 1);
            $this->dispatch('cart-updated');
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex-none animate-pulse">
            <div class="bg-white border border-gray-100 rounded-[4px] p-4 h-full flex flex-col items-center">
                <div class="w-full aspect-[231/254] mb-4 rounded-xl bg-gray-100"></div>
                <div class="w-full">
                    <div class="h-3 w-1/3 bg-gray-100 rounded mb-2"></div>
                    <div class="space-y-2">
                        <div class="h-4 w-full bg-gray-100 rounded"></div>
                        <div class="h-4 w-2/3 bg-gray-100 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
};
?>

@php
    $product = $this->product;
    $isModel = $product instanceof \App\Models\Product;
    $name = $isModel ? $product->name : ($product['name'] ?? '');
    $category = $isModel ? ($product->category->name ?? 'Pharmacy') : ($product['category'] ?? '');
    $price = $isModel ? $product->price : ($product['price'] ?? 0);
    $originalPrice = $isModel ? $product->compare_at_price : ($product['original_price'] ?? null);

    $image = $isModel
        ? ($product->image_path ? Storage::url($product->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=f3f4f6&color=9ca3af')
        : asset($product['image'] ?? '');

    $discount = $isModel
        ? ($originalPrice ? round((($originalPrice - $price) / $originalPrice) * 100) . '% OFF' : null)
        : ($product['discount'] ?? null);

    $slug = $isModel ? $product->slug : ($product['slug'] ?? null);
    $productUrl = $slug ? route('product.show', $slug) : 'javascript:void(0)';
@endphp

<div
    class="group/card bg-white border border-gray-100 rounded-[4px] p-4 hover:shadow-2xl hover:border-blue-900/10 transition-all duration-300 relative overflow-hidden h-full flex flex-col items-center w-full">
    <a href="{{ $productUrl }}" wire:navigate>
        <!-- Discount Badge -->
        @if($discount)
            <div class="absolute top-4 left-4 z-20 bg-red-600 text-white text-[10px] font-black px-2.5 py-1 rounded-lg">
                {{ $discount }}
            </div>
        @endif
        <!-- Image -->
        <div
            class="relative w-full aspect-[231/254] mb-4 rounded-xl overflow-hidden bg-gray-50 flex items-center justify-center">
            @if($isModel)
                <img src="{{ $image }}" alt="{{ $name }}"
                    class="w-full h-full object-contain transform group-hover/card:scale-110 transition-transform duration-500">

                @if($product->stock <= 0)
                    <div class="absolute inset-0 bg-gray-900/40 flex items-center justify-center p-4 z-20">
                        <span
                            class="bg-red-600 text-white text-[10px] font-black px-3 py-1.5 rounded uppercase tracking-wider shadow-lg">Out
                            of Stock</span>
                    </div>
                @endif
            @else
                <img src="{{ $image }}" alt="{{ $name }}"
                    class="w-full h-full object-contain transform group-hover/card:scale-110 transition-transform duration-500">
            @endif

            <!-- Quick Actions Overlay -->
            <div
                class="absolute inset-0 bg-blue-900/5 opacity-0 group-hover/card:opacity-100 transition-opacity flex items-center justify-center gap-x-2 z-20">
                @if($isModel && $product->stock > 0)
                    <a href="{{ route('product.show', $product->slug) }}" wire:navigate
                        class="size-10 bg-white text-blue-900 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-900 hover:text-white transition-all transform translate-y-4 group-hover/card:translate-y-0 duration-300">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                @elseif(!$isModel)
                    <button
                        class="size-10 bg-white text-blue-900 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-900 hover:text-white transition-all transform translate-y-4 group-hover/card:translate-y-0 duration-300">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                @endif

                @if($isModel)
                    <livewire:app.product.wishlist-toggle :product="$product" />
                @else
                    <button
                        class="size-10 bg-white text-blue-900 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-900 hover:text-white transition-all transform translate-y-4 group-hover/card:translate-y-0 duration-300 delay-75">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-1 w-full text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                {{ $category }}
            </p>
            @if($slug)
                <a href="{{ $productUrl }}" wire:navigate>
                    <span class="absolute inset-0 z-10" aria-hidden="true"></span>
                    <h3
                        class="font-medium text-sm text-gray-500 group-hover/card:text-blue-900 transition-colors line-clamp-2 leading-snug">
                        {{ Str::limit($name, 24) }}
                    </h3>
                </a>
            @else
                <h3
                    class="font-medium text-gray-900 group-hover/card:text-blue-900 transition-colors line-clamp-2 leading-snug">
                    {{ Str::limit($name, 20) }}
                </h3>
            @endif

            <div class="flex items-center justify-center gap-x-2 pt-1 pb-2">
                <span class="text-lg font-black text-blue-900">₦ {{ number_format($price, 2) }}</span>

            </div>

            @if($isModel)
                @php
                    $cartService = app(\App\Services\CartService::class);
                    $inCart = $cartService->isInCart($product);
                    $isOutOfStock = $product->stock <= 0;
                @endphp
                <!-- <div class="mt-auto w-full pt-2">
                                    <button wire:click="addToCart" @disabled($inCart || $isOutOfStock)
                                        class="w-full py-2 rounded text-[12px] font-bold uppercase transition-all 
                                                                    {{ $isOutOfStock ? 'bg-red-600 text-white shadow-sm' : ($inCart ? 'bg-gray-100 text-gray-400 cursor-default' : 'bg-[#2b1770] text-white hover:bg-[#3f238f]') }}">
                                        {{ $isOutOfStock ? 'Out of Stock' : ($inCart ? 'Already in Cart' : 'Add to Cart') }}
                                    </button>
                                </div> -->
            @endif
        </div>
    </a>
</div>