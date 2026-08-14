<?php

use Livewire\Volt\Component;
use App\Models\Product;

new class extends Component {
    public function with()
    {
        $products = Product::where('is_active', true)
            ->whereNotNull('compare_at_price')
            ->whereColumn('compare_at_price', '>', 'price')
            ->latest()
            ->take(10)
            ->get();

        if ($products->isEmpty()) {
            $products = Product::where('is_active', true)->latest()->take(10)->get();
        }

        return [
            'hotProducts' => $products,
        ];
    }
};
?>

<section class="w-full bg-white py-12 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-x-3">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Hot Deals</h2>
                <!-- <div
                    class="flex items-center gap-x-2 bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                    <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                    <span>LIMITED TIME</span>
                </div> -->
            </div>
            <a href="#"
                class="text-sm font-bold text-blue-900 hover:text-blue-800 flex items-center gap-x-1 group transition-colors">
                View All
                <svg class="size-4 transform transition-transform group-hover:translate-x-1" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- Scrollable Container -->
        <div x-data="{ 
            scrollNext() { this.$refs.container.scrollBy({ left: 300, behavior: 'smooth' }) },
            scrollPrev() { this.$refs.container.scrollBy({ left: -300, behavior: 'smooth' }) }
        }" class="relative group">

            <!-- Navigation Buttons -->
            <button @click="scrollPrev()"
                class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 size-10 bg-white shadow-xl border border-gray-100 rounded-full flex items-center justify-center text-gray-400 hover:text-blue-900 hover:scale-110 transition-all opacity-0 group-hover:opacity-100 hidden md:flex">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button @click="scrollNext()"
                class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 size-10 bg-white shadow-xl border border-gray-100 rounded-full flex items-center justify-center text-gray-400 hover:text-blue-900 hover:scale-110 transition-all opacity-0 group-hover:opacity-100 hidden md:flex">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Product Grid (Scrollable) -->
            <div x-ref="container"
                class="flex gap-x-6 overflow-x-auto pb-8 snap-x snap-mandatory scrollbar-hide no-scrollbar"
                style="-ms-overflow-style: none; scrollbar-width: none;">

                @foreach($hotProducts as $product)
                    <div class="flex-none w-[280px] sm:w-[240px] md:w-[calc(20%-1.2rem)] min-w-[220px] snap-start">
                        <livewire:guest.reusables.product-card :product="$product" lazy :key="'hot-'.$product->id" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }
</style>