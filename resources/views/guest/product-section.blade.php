<?php

use Livewire\Volt\Component;

new class extends Component {
    public $title;
    public $subtitle;
    public $badgeColor = 'blue'; // blue, red, green, pink
    public $products = [];
    public $bg = 'white'; // white, gray
    public $layout = 'slider'; // slider, grid

    public function mount($title = 'Products', $subtitle = null, $badgeColor = 'blue', $products = [], $bg = 'white', $layout = 'slider')
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->badgeColor = $badgeColor;
        $this->products = $products;
        $this->bg = $bg;
        $this->layout = $layout;
    }

    public function with()
    {
        $firstProduct = collect($this->products)->first();
        return [
            'isModel' => $firstProduct instanceof \App\Models\Product,
        ];
    }
};
?>

<section
    class="w-full py-16 overflow-hidden border-t border-gray-50 {{ $bg === 'gray' ? 'bg-gray-50/50' : 'bg-white' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-x-3">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $title }}</h2>

            </div>
            <a href="{{ route('shop') }}" wire:navigate
                class="text-sm font-bold text-blue-900 hover:text-blue-800 flex items-center gap-x-1 group transition-colors">
                View All
                <svg class="size-4 transform transition-transform group-hover:translate-x-1" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- Scrollable Container / Grid -->
        <div x-data="{ 
            scrollNext() { this.$refs.container.scrollBy({ left: 300, behavior: 'smooth' }) },
            scrollPrev() { this.$refs.container.scrollBy({ left: -300, behavior: 'smooth' }) }
        }" class="relative group">

            @if($layout === 'slider')
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

                <!-- Product Slider (Scrollable) -->
                <div x-ref="container" class="flex gap-x-6 overflow-x-auto pb-8 snap-x snap-mandatory no-scrollbar"
                    style="-ms-overflow-style: none; scrollbar-width: none;">

                    @foreach($products as $product)
                        <div class="flex-none w-[260px] sm:w-[240px] md:w-[calc(20%-1.2rem)] min-w-[220px] snap-start">
                            <livewire:guest.reusables.product-card :product="$product" lazy
                                :key="'pd-'.Str::slug($title).'-'.($isModel ? $product->id : ($product['id'] ?? $loop->index))" />
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Product Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                    @foreach($products as $product)
                        <div class="w-full">
                            <livewire:guest.reusables.product-card :product="$product" lazy
                                :key="'pd-grid-'.Str::slug($title).'-'.($isModel ? $product->id : ($product['id'] ?? $loop->index))" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>