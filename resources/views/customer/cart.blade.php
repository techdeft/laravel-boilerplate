<?php

use Livewire\Volt\Component;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest.app')] class extends Component {
    public function incrementQuantity(CartService $cartService, $itemId)
    {
        $item = CartItem::find($itemId);
        if ($item) {
            $cartService->updateQuantity($itemId, $item->quantity + 1);
        }
    }

    public function decrementQuantity(CartService $cartService, $itemId)
    {
        $item = CartItem::find($itemId);
        if ($item && $item->quantity > 1) {
            $cartService->updateQuantity($itemId, $item->quantity - 1);
        }
    }

    public function removeItem(CartService $cartService, $itemId)
    {
        $cartService->removeItem($itemId);
    }

    public function clearCart(CartService $cartService)
    {
        $cartService->clear();
    }

    public function getCartProperty(CartService $cartService)
    {
        return $cartService->getCart();
    }

    public function with(CartService $cartService)
    {
        $cart = $this->cart;
        return [
            'cartItems' => $cart ? $cart->items()->with('product')->get() : collect(),
            'total' => $cart ? $cart->total : 0,
        ];
    }
}; ?>

<div class="bg-[#f1f1f2] min-h-screen pb-12">
    <div class="max-w-[1184px] mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4 items-start">
            <!-- Main Cart Area -->
            <div class="flex-1 w-full space-y-4">
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <h1 class="text-[20px] font-medium text-gray-900 leading-tight">Cart
                            ({{ $cartItems->sum('quantity') }})</h1>
                        @if($cartItems->isNotEmpty())
                            <button wire:click="clearCart"
                                class="text-[12px] font-bold text-red-500 hover:text-red-700 uppercase tracking-wide transition-colors flex items-center gap-1.5 px-3 py-1.5 rounded hover:bg-red-50">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                                Clear Cart
                            </button>
                        @endif
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($cartItems as $item)
                            <div class="p-4 flex flex-col sm:flex-row gap-4 group relative">
                                <!-- Product Image -->
                                <div
                                    class="w-full sm:w-[120px] h-[120px] aspect-square rounded bg-white overflow-hidden shrink-0 border border-gray-50 flex items-center justify-center">
                                    <img src="{{ $item->product->image_path ? Storage::url($item->product->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($item->product->name) . '&background=f3f4f6&color=9ca3af' }}"
                                        alt="{{ $item->product->name }}"
                                        class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-500">
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-2">
                                        <div class="flex-1">
                                            <h3
                                                class="text-[16px] font-normal text-gray-900 line-clamp-2 leading-snug mb-1">
                                                {{ $item->product->name }}
                                            </h3>
                                            <p class="text-[12px] text-gray-500 mb-2">Seller: <span
                                                    class="text-[#2b1770]">{{ $item->product->brand->name ?? 'MedMall' }}</span>
                                            </p>

                                            <div class="flex items-center gap-2 mt-2">
                                                <button wire:click="removeItem({{ $item->id }})"
                                                    class="text-[12px] font-bold text-primary-500 hover:text-[#e07e1b] uppercase tracking-wide flex items-center gap-1.5 transition-colors">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                    Remove
                                                </button>
                                            </div>
                                        </div>

                                        <div class="text-left md:text-right shrink-0">
                                            <div class="flex flex-row md:flex-col items-baseline md:items-end gap-2">
                                                <span class="text-[20px] font-bold text-gray-900 leading-none">₦
                                                    {{ number_format($item->price, 0) }}</span>
                                                @if($item->product->compare_at_price > $item->price && $item->product->compare_at_price > 0)
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[12px] text-gray-400 line-through">₦
                                                            {{ number_format($item->product->compare_at_price, 0) }}</span>
                                                        <span
                                                            class="text-[10px] text-primary-500 bg-primary-500/10 px-1 py-0.5 rounded-sm font-bold">-{{ round((($item->product->compare_at_price - $item->price) / $item->product->compare_at_price) * 100) }}%</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-primary-500 mt-1">MEdmall Express</p>
                                        </div>
                                    </div>

                                    <!-- Quantity Selector -->
                                    <div class="mt-4 flex items-center justify-between md:justify-start gap-4">
                                        <div class="flex items-center border border-gray-100 rounded bg-white">
                                            <button wire:click="decrementQuantity({{ $itemId = $item->id }})"
                                                class="size-8 flex items-center justify-center text-primary-500 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                                                @disabled($item->quantity <= 1)>
                                                <i class="fa-solid fa-minus text-xs"></i>
                                            </button>
                                            <span
                                                class="w-10 text-center text-[14px] font-bold text-gray-900 border-x border-gray-100">{{ $item->quantity }}</span>
                                            <button wire:click="incrementQuantity({{ $itemId }})"
                                                class="size-8 flex items-center justify-center text-primary-500 hover:bg-gray-50 transition-colors">
                                                <i class="fa-solid fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 flex flex-col items-center justify-center text-center">
                                <div class="size-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                    <i class="fa-solid fa-cart-shopping text-3xl text-gray-200"></i>
                                </div>
                                <h3 class="text-[18px] font-bold text-gray-900 mb-2">Your cart is empty!</h3>
                                <p class="text-[14px] text-gray-500 mb-8 max-w-xs mx-auto">Already have an account? Log in
                                    to see the items you previously added.</p>
                                <a href="{{ route('home') }}"
                                    class="px-8 py-3 bg-[#2b1770] text-white rounded font-bold text-[14px] uppercase tracking-wide hover:bg-[#3f238f] transition-all shadow-sm">
                                    Start Shopping
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recently Viewed Placeholder Section -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-[16px] font-bold text-gray-900 uppercase">Recently Viewed</h2>
                        <a href="#" class="text-[13px] font-bold text-[#2b1770] hover:underline uppercase">See All</a>
                    </div>
                    <div class="p-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                        <!-- We can loop real products here if recently viewed is tracked -->
                        @foreach(App\Models\Product::where('is_active', true)->take(5)->get() as $p)
                            <div
                                class="group/p relative bg-white border border-transparent hover:border-gray-100 p-2 transition-all">
                                <div class="aspect-square bg-white mb-2 overflow-hidden flex items-center justify-center">
                                    <img src="{{ $p->image_path ? Storage::url($p->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($p->name) . '&background=f3f4f6&color=9ca3af' }}"
                                        class="max-w-full max-h-full object-contain">
                                </div>
                                <h4 class="text-[12px] text-gray-500 line-clamp-1 mb-1">{{ $p->name }}</h4>
                                <p class="text-[14px] font-bold text-gray-900">₦ {{ number_format($p->price, 0) }}</p>
                                @if($p->compare_at_price > $p->price && $p->compare_at_price > 0)
                                    <p class="text-[11px] text-gray-400 line-through">₦
                                        {{ number_format($p->compare_at_price, 0) }}
                                    </p>
                                @endif
                                <div
                                    class="absolute top-2 right-2 bg-[#2b1770] text-white text-[10px] font-bold px-1 rounded-sm opacity-0 group-hover/p:opacity-100">
                                    @if($p->compare_at_price > 0)
                                        -{{ round((($p->compare_at_price - $p->price) / $p->compare_at_price) * 100) }}%
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recommendations Placeholder Section -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h2 class="text-[16px] font-bold text-gray-900 uppercase">Customers who viewed this also viewed
                        </h2>
                    </div>
                    <div class="p-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach(App\Models\Product::where('is_active', true)->inRandomOrder()->take(5)->get() as $p)
                            <div
                                class="group/p relative bg-white border border-transparent hover:border-gray-100 p-2 transition-all">
                                <div class="aspect-square bg-white mb-2 overflow-hidden flex items-center justify-center">
                                    <img src="{{ $p->image_path ? Storage::url($p->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($p->name) . '&background=f3f4f6&color=9ca3af' }}"
                                        class="max-w-full max-h-full object-contain">
                                </div>
                                <h4 class="text-[12px] text-gray-500 line-clamp-1 mb-1">{{ $p->name }}</h4>
                                <p class="text-[14px] font-bold text-gray-900">₦ {{ number_format($p->price, 0) }}</p>
                                @if($p->compare_at_price > $p->price && $p->compare_at_price > 0)
                                    <p class="text-[11px] text-gray-400 line-through">₦
                                        {{ number_format($p->compare_at_price, 0) }}
                                    </p>
                                @endif
                                <div
                                    class="absolute top-2 right-2 bg-[#2b1770] text-white text-[10px] font-bold px-1 rounded-sm opacity-0 group-hover/p:opacity-100">
                                    @if($p->compare_at_price > 0)
                                        -{{ round((($p->compare_at_price - $p->price) / $p->compare_at_price) * 100) }}%
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary Area -->
            <div class="w-full lg:w-[280px] space-y-4 lg:sticky lg:top-4">
                <div class="bg-white rounded shadow-sm p-4">
                    <h2
                        class="text-[14px] font-bold text-gray-900 uppercase tracking-tight border-b border-gray-100 pb-3 mb-4">
                        Cart Summary</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-[14px]">
                            <span class="text-gray-900 font-medium uppercase tracking-tight">Subtotal</span>
                            <span class="text-[18px] font-bold text-gray-900">₦ {{ number_format($total, 0) }}</span>
                        </div>
                        <p class="text-[12px] text-gray-400">Excluding delivery fees</p>
                        <div class="mt-4">
                            <a href="{{ route('customer.checkout') }}"
                               @guest href="{{ route('login') }}" @endguest
                               wire:navigate
                               class="block w-full text-center bg-[#2b1770] hover:bg-[#3f238f] text-white py-3.5 rounded font-bold text-[14px] transition-all shadow-md uppercase
                               @if($cartItems->isEmpty()) opacity-50 cursor-not-allowed @endif"
                               @if($cartItems->isEmpty()) disabled @endif>
                                Checkout (₦ {{ number_format($total, 0) }})
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded shadow-sm p-4 space-y-4">
                    <h2 class="text-[12px] font-bold text-gray-900 uppercase border-b border-gray-100 pb-2">Returns are
                        easy</h2>
                    <p class="text-[11px] text-gray-500">Free return within 15 days for Official Stores and 7 days for
                        other eligible items.</p>
                </div>
            </div>
        </div>
    </div>
</div>