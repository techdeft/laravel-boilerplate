<?php

use Livewire\Volt\Component;
use App\Models\Category;

new class extends Component {
    public function categories()
    {
        return Category::orderBy('name')->get();
    }
};
?>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative overflow-hidden">
    <!-- Section Title -->
    <div class="flex items-center justify-between mb-10">
        <div>
            <h2 class="text-3xl font-black text-gray-900 font-bold tracking-tight mb-2">Categories</h2>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.2em]">Browse by health categories</p>
        </div>
        <!-- <a href="{{ route('shop') }}" class="text-sm font-black font-medium text-blue-900 flex items-center gap-2 group"
            wire:navigate>
            View All Categories
            <svg class="size-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="3">
                <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </a> -->
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
        @foreach($this->categories() as $category)
            <a href="{{ route('shop', ['category' => $category->slug]) }}" wire:navigate
                class="group relative bg-white border border-gray-100 rounded-3xl p-5 h-20 flex items-center justify-center transition-all duration-500 hover:shadow-2xl hover:shadow-blue-900/10 hover:-translate-y-2 cursor-pointer overflow-hidden">
                <!-- Hover Glow -->
                <div
                    class="absolute -right-4 -bottom-4 size-20 bg-blue-50/50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                </div>

                <div class="relative z-10">
                    <!-- Text Container -->
                    <div>
                        <h3
                            class="font-black text-gray-800 text-base font-medium group-hover:text-blue-900 transition-colors leading-tight text-center">
                            {{ $category->name }}
                        </h3>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</section>