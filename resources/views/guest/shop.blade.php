<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

new #[Layout('layouts.guest.app')] class extends Component {
    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'category')]
    public $selectedCategory = '';

    #[Url(as: 'brand')]
    public $selectedBrand = '';

    #[Url(as: 'min_price')]
    public $minPrice = '';

    #[Url(as: 'max_price')]
    public $maxPrice = '';

    #[Url(as: 'sort')]
    public $sort = 'latest';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }
    public function updatingSelectedBrand()
    {
        $this->resetPage();
    }
    public function updatingMinPrice()
    {
        $this->resetPage();
    }
    public function updatingMaxPrice()
    {
        $this->resetPage();
    }
    public function updatingSort()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'selectedCategory', 'selectedBrand', 'minPrice', 'maxPrice', 'sort']);
        $this->resetPage();
    }

    public function with()
    {
        $query = Product::query()->where('is_active', true);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->selectedCategory)
                  ->orWhereHas('parent', function ($pq) {
                      $pq->where('slug', $this->selectedCategory);
                  });
            });
        }

        if ($this->selectedBrand) {
            $query->whereHas('brand', function ($q) {
                $q->where('slug', $this->selectedBrand);
            });
        }

        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }

        switch ($this->sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'name_az':
                $query->orderBy('name', 'asc');
                break;
            case 'name_za':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        return [
            'products' => $query->paginate(12),
            'categories' => Category::with('subcategories')->whereNull('parent_id')->orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ];
    }
};
?>

<div class="bg-gray-50 min-h-screen py-10" x-data="{ mobileFiltersOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs & Title -->
        <div class="mb-8">
            <nav class="flex mb-3 text-[12px] text-gray-400 font-bold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-blue-900 transition-colors" wire:navigate>Home</a>
                <span class="mx-2 text-gray-300">/</span>
                <span class="text-blue-900">Shop</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-black font-bold text-gray-900">Explore Products</h1>
        </div>

        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Desktop Sidebar Filters -->
            <aside class="hidden lg:block w-72 shrink-0 space-y-10">
                @include('guest.reusables.shop-filters')
            </aside>

            <!-- Mobile Filter Drawer -->
            <div x-show="mobileFiltersOpen" class="fixed inset-0 z-[100] lg:hidden"
                x-description="Mobile filters dialog" role="dialog" aria-modal="true" x-cloak>

                <!-- Backdrop -->
                <div x-show="mobileFiltersOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="mobileFiltersOpen = false"></div>

                <!-- Drawer Content -->
                <div x-show="mobileFiltersOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in-out duration-300 transform"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="fixed inset-y-0 right-0 z-50 w-full max-w-xs bg-white shadow-2xl flex flex-col">

                    <div class="flex items-center justify-between px-6 py-6 border-b border-gray-100">
                        <h2 class="text-xl font-black text-gray-900">Filters</h2>
                        <button @click="mobileFiltersOpen = false"
                            class="p-2 text-gray-400 hover:text-gray-500 transition-colors">
                            <span class="sr-only">Close menu</span>
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto px-6 py-8 space-y-10 no-scrollbar">
                        @include('guest.reusables.shop-filters')
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <main class="flex-1">
                <!-- Mobile Actions & Desktop Sorting -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
                    <div class="flex items-center justify-between sm:justify-start gap-4">
                        <p class="text-sm text-gray-500 font-medium">
                            Showing <span class="text-gray-900 font-bold">{{ $products->count() }}</span> of <span
                                class="text-gray-900 font-bold">{{ $products->total() }}</span>
                        </p>

                        <!-- Mobile Filter Button -->
                        <button @click="mobileFiltersOpen = true"
                            class="lg:hidden flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-900 hover:bg-gray-50 transition-all shadow-sm">
                            <svg class="size-4 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filters
                        </button>
                    </div>

                    <div
                        class="flex items-center gap-4 bg-white p-1 rounded-2xl border border-gray-100 self-end sm:self-auto">
                        <span
                            class="text-xs font-black text-gray-400 uppercase tracking-widest pl-4 hidden md:inline">Sort
                            by</span>
                        <select wire:model.live="sort"
                            class="bg-transparent border-none text-sm font-bold text-gray-900 focus:ring-0 cursor-pointer py-2 pr-10">
                            <option value="latest">Latest Arrivals</option>
                            <option value="price_low_high">Price: Low to High</option>
                            <option value="price_high_low">Price: High to Low</option>
                            <option value="name_az">Name: A to Z</option>
                            <option value="name_za">Name: Z to A</option>
                        </select>
                    </div>
                </div>

                <!-- Grid -->
                @if($products->isEmpty())
                    <div class="bg-white rounded-3xl p-16 text-center border border-gray-100 shadow-sm">
                        <div
                            class="size-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-8 text-blue-200">
                            <svg class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-3">No matches found</h3>
                        <p class="text-gray-500 max-w-sm mx-auto leading-relaxed">We couldn't find any products matching
                            your selected criteria. Try resetting your filters.</p>
                        <button wire:click="resetFilters"
                            class="mt-8 px-8 py-3 bg-blue-900 text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:scale-105 active:scale-95 transition-all">
                            Clear All Filters
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4 md:gap-8">
                        @foreach($products as $product)
                            <livewire:guest.reusables.product-card :product="$product" :key="'shop-' . $product->id" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-16">
                        {{ $products->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>