<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;

new class extends Component {
    public $search = '';
    public $selectedCategory = '';
    public $results = [];

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->results = [];
            return;
        }

        $query = Product::query()
            ->where('is_active', true)
            ->where('name', 'like', '%' . $this->search . '%');

        if ($this->selectedCategory && $this->selectedCategory !== 'All Categories') {
            $query->whereHas('category', function ($q) {
                $q->where('name', $this->selectedCategory);
            });
        }

        $this->results = $query->latest()->take(5)->get();
    }

    public function submitSearch()
    {
        $params = [];
        if ($this->search)
            $params['q'] = $this->search;
        if ($this->selectedCategory && $this->selectedCategory !== 'All Categories') {
            $category = Category::where('name', $this->selectedCategory)->first();
            if ($category)
                $params['category'] = $category->slug;
        }

        return redirect()->route('shop', $params);
    }

    public function with()
    {
        return [
            'categories' => Category::orderBy('name')->get(),
        ];
    }
}; ?>

<div class="order-3 lg:order-2 w-full lg:max-w-xl relative" x-data="{ open: false }" @click.away="open = false">
    <form wire:submit.prevent="submitSearch"
        class="relative flex items-center bg-gray-50 border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-blue-900/20 focus-within:border-blue-900 transition-all">
        <div class="relative shrink-0 border-r border-gray-300 bg-gray-100 hidden sm:block">
            <select wire:model.live="selectedCategory"
                class="block py-2.5 px-4 text-sm bg-transparent border-none focus:ring-0 cursor-pointer text-gray-700">
                <option>All Categories</option>
                @foreach($categories as $category)
                    <option>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" @focus="open = true"
            placeholder="Search for products..."
            class="w-full py-2.5 px-4 text-sm bg-transparent border-none focus:ring-0 text-gray-900 placeholder:text-gray-400">

        <button type="submit" class="bg-primary-500 text-white p-3 hover:bg-blue-800 transition-colors">
            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
        </button>
    </form>

    <!-- Suggestions Dropdown -->
    <div x-show="open && $wire.search.length >= 2" x-cloak
        class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 divide-y divide-gray-50 overflow-hidden z-[100]">

        @if(count($results) > 0)
            @foreach($results as $product)
                <a href="{{ route('product.show', $product->slug) }}"
                    class="flex items-center gap-4 p-4 hover:bg-blue-50 transition-colors group" wire:navigate>
                    <div class="size-12 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-50">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                class="size-full object-cover">
                        @else
                            <div class="size-full flex items-center justify-center text-gray-300">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-blue-900 transition-colors">
                            {{ $product->name }}</h4>
                        <p class="text-xs text-gray-500 font-medium">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-blue-900 tracking-tight">₦{{ number_format($product->price, 2) }}</p>
                        @if($product->old_price)
                            <p class="text-[10px] text-gray-400 line-through">₦{{ number_format($product->old_price, 2) }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
            <button wire:click="submitSearch"
                class="w-full py-3 bg-gray-50 text-[11px] font-black text-primary-500 uppercase tracking-widest hover:bg-blue-100 transition-colors">
                View all results for "{{ $search }}"
            </button>
        @else
            <div class="p-8 text-center">
                <div class="size-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                </div>
                <p class="text-sm font-bold text-gray-900">No products found</p>
                <p class="text-xs text-gray-500 mt-1">Try adjusting your keywords or category.</p>
            </div>
        @endif
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>