<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest.app')] class extends Component {
    public Product $product;
    public int $quantity = 1;
    public $selectedRegionId = null;
    public int $rating = 5;
    public string $comment = '';

    public function mount(Product $product)
    {
        $this->product = $product->load(['category', 'brand']);
    }

    public function increment()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(CartService $cartService)
    {
        $cartService->addItem($this->product, $this->quantity);
        $this->dispatch('cart-updated');
        session()->flash('success', 'Added to cart successfully!');
    }

    public function toggleWishlist()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        Auth::user()->wishlist()->toggle($this->product->id);
        $this->dispatch('wishlist-updated');
    }

    public function submitReview()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user has a delivered order for this product
        $deliveredOrder = Auth::user()->orders()
            ->whereIn('status', ['delivered', 'completed'])
            ->whereHas('items', fn($q) => $q->where('product_id', $this->product->id))
            ->first();

        if (!$deliveredOrder) {
            session()->flash('review-error', 'You can only review products you have purchased and received.');
            return;
        }

        // Check if already reviewed
        if (\App\Models\Review::where('product_id', $this->product->id)->where('user_id', Auth::id())->exists()) {
            session()->flash('review-error', 'You have already reviewed this product.');
            return;
        }

        \App\Models\Review::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'order_id' => $deliveredOrder->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_published' => true,
        ]);

        $this->reset(['rating', 'comment']);
        session()->flash('review-success', 'Thank you for your review!');
    }

    public function with(CartService $cartService)
    {
        $selectedZone = $this->selectedRegionId ? \App\Models\DeliveryZone::find($this->selectedRegionId) : null;

        $canReview = Auth::check() && Auth::user()->orders()
            ->whereIn('status', ['delivered', 'completed'])
            ->whereHas('items', fn($q) => $q->where('product_id', $this->product->id))
            ->exists() && !\App\Models\Review::where('product_id', $this->product->id)->where('user_id', Auth::id())->exists();

        $reviews = $this->product->reviews()->with('user')->latest()->get();
        $averageRating = $this->product->averageRating();
        $reviewsCount = $reviews->count();

        return [
            'product' => $this->product,
            'quantity' => $this->quantity,
            'cartService' => $cartService,
            'deliveryZones' => \App\Models\DeliveryZone::where('is_active', true)->orderBy('name')->get(),
            'selectedZone' => $selectedZone,
            'isWishlisted' => Auth::check() && Auth::user()->wishlist->contains($this->product->id),
            'relatedProducts' => Product::where('category_id', $this->product->category_id)
                ->where('id', '!=', $this->product->id)
                ->where('is_active', true)
                ->take(10)
                ->get(),
            'canReview' => $canReview,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'reviewsCount' => $reviewsCount,
        ];
    }
}; ?>

<x-slot name="title">{{ $product->name }}</x-slot>

<div class="bg-[#f1f1f2] min-h-screen pb-12">
    <div class="max-w-[1184px] mx-auto px-4 py-4">
        <!-- Breadcrumbs -->
        <nav class="flex mb-4 text-[12px] font-normal text-gray-500 overflow-x-auto whitespace-nowrap no-scrollbar">
            <a href="{{ route('home') }}" class="hover:underline transition-colors">Home</a>
            <span class="mx-1.5 text-gray-400">></span>
            <a href="#" class="hover:underline transition-colors">{{ $product->category->name ?? 'Pharmacy' }}</a>
            <span class="mx-1.5 text-gray-400">></span>
            <span class="text-gray-900 truncate">{{ $product->name }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Left Side: Main Product Block -->
            <div class="flex-1 space-y-4">
                <div class="bg-white rounded shadow-sm flex flex-col md:flex-row p-4 gap-6">
                    <!-- Product Gallery -->
                    <div class="w-full md:w-[380px] shrink-0">
                        <div class="aspect-square relative flex items-center justify-center bg-white">
                            <img src="{{ $product->image_path ? Storage::url($product->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=f3f4f6&color=9ca3af' }}"
                                alt="{{ $product->name }}" class="max-w-full max-h-full object-contain">

                            @if($product->compare_at_price > $product->price && $product->compare_at_price > 0)
                                <div
                                    class="absolute top-0 left-0 bg-[#2b1770] text-white text-[12px] font-bold px-2 py-0.5 rounded-sm">
                                    -{{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}%
                                </div>
                            @endif
                        </div>

                        <!-- Share section (Optional but adds to look) -->
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center gap-4">
                            <span class="text-[14px] font-bold text-gray-900 uppercase">Share this product</span>
                            <div class="flex gap-2">
                                <button
                                    class="size-8 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 text-[#2b1770]">
                                    <i class="fa-brands fa-facebook-f text-xs mt-0.5"></i>
                                </button>
                                <button
                                    class="size-8 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 text-[#2b1770]">
                                    <i class="fa-brands fa-twitter text-xs mt-0.5"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="bg-[#2a4d9c] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-sm">OFFICIAL
                                        STORE</span>
                                </div>
                                <h1 class="text-[20px] font-normal text-gray-900 leading-tight">{{ $product->name }}
                                </h1>
                                <p class="text-[12px] text-gray-600">
                                    Brand: <a href="#"
                                        class="text-[#2a4d9c] hover:underline">{{ $product->brand->name ?? $product->brand ?? 'Generic' }}</a>
                                    |
                                    <a href="#" class="text-[#2a4d9c] hover:underline">Similar products from
                                        {{ $product->brand->name ?? 'Generic' }}</a>
                                </p>
                            </div>
                            <button wire:click="toggleWishlist"
                                class="p-2 hover:bg-gray-50 rounded-full transition-colors {{ $isWishlisted ? 'text-[#2b1770]' : 'text-gray-400' }}">
                                <svg class="size-6" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 pb-4 border-b border-gray-100">
                            <div class="flex items-baseline gap-2">
                                <span class="text-[24px] font-bold text-gray-900 leading-none">₦
                                    {{ number_format($product->price, 0) }}</span>
                                @if($product->compare_at_price > $product->price && $product->compare_at_price > 0)
                                    <span class="text-[14px] text-gray-400 line-through">₦
                                        {{ number_format($product->compare_at_price, 0) }}</span>
                                    <span
                                        class="text-[12px] text-[#2b1770] bg-[#2b1770]/10 px-1 py-0.5 rounded-sm font-bold">-{{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}%</span>
                                @endif
                            </div>
                            <p
                                class="text-[12px] {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600 font-bold' }} mt-1 italic">
                                {{ $product->stock > 0 ? 'In stock' : 'Out of stock' }}
                            </p>
                            <!-- <p class="text-[12px] text-gray-500">+ shipping from ₦ 600 to Lagos</p> -->

                            <!-- Rating -->
                            <div class="mt-2 flex items-center gap-2">
                                <div class="flex text-[#2b1770]">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($averageRating))
                                            <i class="fa-solid fa-star text-xs"></i>
                                        @else
                                            <i class="fa-regular fa-star text-xs text-gray-300"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span
                                    class="text-[12px] text-[#2b1770] hover:underline cursor-pointer">({{ $reviewsCount }}
                                    {{ Str::plural('rating', $reviewsCount) }})</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-6">
                            @if(session()->has('success'))
                                <div class="p-3 bg-green-50 text-green-700 rounded text-sm flex items-center gap-2">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="flex items-center gap-4">
                                <div class="flex items-center border border-gray-200 rounded shrink-0">
                                    <button wire:click="decrement"
                                        class="px-3 py-2 hover:bg-gray-50 transition-colors {{ $quantity <= 1 ? 'opacity-30 cursor-not-allowed' : '' }}">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                    </button>
                                    <span class="w-10 text-center text-sm font-bold">{{ $quantity }}</span>
                                    <button wire:click="increment"
                                        class="px-3 py-2 hover:bg-gray-50 transition-colors {{ $quantity >= $product->stock ? 'opacity-30 cursor-not-allowed' : '' }}">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>

                                @php
                                    $inCart = $cartService->isInCart($this->product);
                                    $isOutOfStock = $product->stock <= 0;
                                @endphp
                                <button wire:click="addToCart" @disabled($isOutOfStock || $inCart)
                                    class="flex-1 {{ $isOutOfStock ? 'bg-red-600 hover:bg-red-700 text-white shadow-md' : ($inCart ? 'bg-gray-100 text-gray-400 cursor-default' : 'bg-[#2b1770] hover:bg-[#3f238f] text-white shadow-sm') }} rounded font-bold text-[14px] py-3.5 transition-all uppercase tracking-wide flex items-center justify-center gap-3">
                                    @if($isOutOfStock)
                                        <i class="fa-solid fa-circle-exclamation text-lg"></i>
                                    @else
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    @endif
                                    {{ $isOutOfStock ? 'Out of Stock' : ($inCart ? 'Already in Cart' : 'Add to Cart') }}
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Product Details Card -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h2 class="text-[16px] font-bold text-gray-900 uppercase">Product Details</h2>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div x-data="{ expanded: false }" class="relative">
                            <div :class="expanded ? '' : 'max-h-[250px] overflow-hidden'"
                                class="prose prose-sm max-w-none text-gray-600 transition-all duration-300">
                                {!! nl2br(e($product->description)) !!}
                            </div>

                            <div x-show="!expanded"
                                class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent pointer-events-none">
                            </div>

                            <button @click="expanded = !expanded"
                                class="mt-4 text-[14px] font-bold text-[#2b1770] hover:underline uppercase flex items-center gap-1">
                                <span x-text="expanded ? 'Read Less' : 'Read More'"></span>
                                <svg class="size-4 transform transition-transform" :class="expanded ? 'rotate-180' : ''"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <h3 class="text-[14px] font-bold text-gray-900">Specifications</h3>
                            <div class="space-y-2">
                                <div class="flex py-2 border-b border-gray-50">
                                    <span class="w-1/3 text-[13px] text-gray-500">Brand</span>
                                    <span
                                        class="flex-1 text-[13px] text-gray-900 font-medium">{{ $product->brand->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex py-2 border-b border-gray-50">
                                    <span class="w-1/3 text-[13px] text-gray-500">SKU</span>
                                    <span
                                        class="flex-1 text-[13px] text-gray-900 font-medium">{{ $product->external_id ?? 'MED' . str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="flex py-2 border-b border-gray-50">
                                    <span class="w-1/3 text-[13px] text-gray-500">Weight (kg)</span>
                                    <span class="flex-1 text-[13px] text-gray-900 font-medium">0.5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-[16px] font-bold text-gray-900 uppercase">Customer Reviews ({{ $reviewsCount }})
                        </h2>
                    </div>

                    <div class="p-4 md:p-6">
                        @if($canReview)
                            <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <h3 class="text-[14px] font-bold text-gray-900 uppercase mb-4">Write a Review</h3>
                                <form wire:submit.prevent="submitReview" class="space-y-4">
                                    @if(session()->has('review-success'))
                                        <div class="p-3 bg-green-50 text-green-700 rounded text-sm">
                                            {{ session('review-success') }}
                                        </div>
                                    @endif
                                    @if(session()->has('review-error'))
                                        <div class="p-3 bg-red-50 text-red-700 rounded text-sm">
                                            {{ session('review-error') }}
                                        </div>
                                    @endif

                                    <div class="space-y-1">
                                        <label class="text-[12px] font-bold text-gray-700 uppercase">Rating</label>
                                        <div class="flex gap-2 text-2xl">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                                    class="{{ $rating >= $i ? 'text-[#2b1770]' : 'text-gray-200' }} hover:scale-110 transition-transform">
                                                    <i class="fa-solid fa-star"></i>
                                                </button>
                                            @endfor
                                        </div>
                                        @error('rating') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-[12px] font-bold text-gray-700 uppercase">Comment
                                            (Optional)</label>
                                        <textarea wire:model="comment" rows="3"
                                            class="w-full px-3 py-2 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770]"
                                            placeholder="Tell us what you think about this product..."></textarea>
                                        @error('comment') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>

                                    <button type="submit"
                                        class="bg-[#2b1770] text-white px-6 py-2 rounded font-bold text-[12px] uppercase tracking-wide hover:bg-[#3f238f] transition-all shadow-sm">
                                        Submit Review
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if($reviews->isNotEmpty())
                            <div class="space-y-6">
                                @foreach($reviews as $review)
                                    <div class="flex gap-4 pb-6 border-b border-gray-50 last:border-0">
                                        <div
                                            class="size-10 rounded-full bg-[#2b1770]/10 flex items-center justify-center text-[#2b1770] font-bold shrink-0">
                                            {{ $review->user->initials() }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <h4 class="text-[14px] font-bold text-gray-900">{{ $review->user->name }}</h4>
                                                <span
                                                    class="text-[12px] text-gray-400">{{ $review->created_at->format('d M, Y') }}</span>
                                            </div>
                                            <div class="flex text-[#2b1770] mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fa-solid fa-star text-[10px] {{ $i <= $review->rating ? '' : 'text-gray-200' }}"></i>
                                                @endfor
                                            </div>
                                            @if($review->comment)
                                                <p class="text-[14px] text-gray-600 italic">"{{ $review->comment }}"</p>
                                            @endif
                                            <div class="mt-2 flex items-center gap-2">
                                                <span
                                                    class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded font-bold uppercase">
                                                    <i class="fa-solid fa-circle-check mr-1"></i> Verified Purchase
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-12 text-center space-y-3">
                                <div
                                    class="size-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto text-gray-200">
                                    <i class="fa-solid fa-comments text-3xl"></i>
                                </div>
                                <h3 class="text-[16px] font-medium text-gray-900">No reviews yet</h3>
                                <p class="text-[14px] text-gray-500 max-w-xs mx-auto">Be the first to review this product
                                    after you've purchased it!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Sidebar: Delivery & Seller Info -->
            <div class="w-full lg:w-[280px] space-y-4">
                <!-- Delivery & Returns -->


                <!-- Delivery Fees by Region Card (Location Picker) -->
                <div class="bg-white rounded shadow-sm p-4 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <h2 class="text-[14px] font-bold text-gray-900">Choose your location</h2>
                        <i class="fa-solid fa-location-dot text-primary-500 text-xs"></i>
                    </div>

                    <div class="space-y-4 pt-1">
                        <div class="space-y-1">
                            <select wire:model.live="selectedRegionId"
                                class="w-full h-11 px-3 bg-white border border-gray-200 rounded text-[14px] text-gray-700 focus:outline-none focus:border-primary-500 hover:border-gray-300 transition-colors appearance-none cursor-pointer">
                                <option value="">Select Region/State</option>
                                @foreach($deliveryZones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            {{-- Simulated second level if needed, for now just follow seeder data --}}
                        </div>

                        @if($selectedZone)
                            <div
                                class="p-3 bg-primary-50 rounded border border-primary-100 animate-in fade-in slide-in-from-top-2 duration-300">
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-[12px] text-primary-700 font-medium lowercase first-letter:uppercase">Delivery
                                        to {{ $selectedZone->name }}</span>
                                    <span class="text-[14px] font-bold text-primary-900">₦
                                        {{ number_format($selectedZone->delivery_fee, 0) }}</span>
                                </div>
                                <p class="text-[10px] text-primary-600/80 mt-1">Ready for delivery in 1-3 business days</p>
                            </div>
                        @else
                            <div class="p-3 bg-gray-50 rounded border border-gray-100 text-center">
                                <p class="text-[11px] text-gray-500">Select a location to see delivery fees</p>
                            </div>
                        @endif
                    </div>

                    <p class="text-[10px] text-gray-400 pt-2 border-t border-gray-50 leading-tight italic">
                        * Exact delivery fee will be calculated at checkout based on your specific address.
                    </p>
                </div>

                <!-- Pharmacist Advert Card -->
                <div
                    class="bg-gradient-to-br from-[#2b1770] to-[#3f238f] rounded shadow-lg p-5 text-white space-y-4 relative overflow-hidden group">
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                        <i class="fa-solid fa-prescription-bottle-medical text-8xl"></i>
                    </div>

                    <div class="relative z-10">
                        <h2 class="text-[16px] font-bold leading-tight mb-2">Need to Talk to a Pharmacist?</h2>
                        <p class="text-[11px] text-purple-100 leading-relaxed mb-4">
                            Our licensed professional pharmacists are available to provide expert advice on your
                            medications and health concerns.
                        </p>

                        <a href="{{route('telehealth')}}"
                            class="block w-full text-center py-2.5 bg-white text-[#2b1770] font-bold rounded text-[12px] hover:bg-purple-50 transition-all shadow-md uppercase tracking-wide">
                            <i class="fa-solid fa-comments-medical mr-1.5"></i>
                            Consult a Pharmacist
                        </a>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <div class="flex -space-x-2">
                            <img class="size-6 rounded-full border-2 border-[#2b1770]"
                                src="https://ui-avatars.com/api/?name=Dr+John&background=random" alt="">
                            <img class="size-6 rounded-full border-2 border-[#2b1770]"
                                src="https://ui-avatars.com/api/?name=Pharm+Jane&background=random" alt="">
                        </div>
                        <span class="text-[10px] text-purple-200 font-medium">Professional help available 24/7</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-8 bg-white rounded shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-[16px] font-bold text-gray-900 uppercase">You May Also Like</h2>
                    <a href="#" class="text-[13px] font-bold text-[#2b1770] hover:underline uppercase">See All</a>
                </div>
                <div class="p-4">
                    <div class="flex gap-x-4 overflow-x-auto pb-4 snap-x snap-mandatory no-scrollbar">
                        @foreach($relatedProducts as $related)
                            <div class="flex-none w-[180px] snap-start">
                                <livewire:guest.reusables.product-card :product="$related" :key="'related-' . $related->id" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #aaa;
    }
</style>