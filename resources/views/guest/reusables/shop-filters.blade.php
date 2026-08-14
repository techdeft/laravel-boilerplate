<!-- Search -->
<div class="space-y-4">
    <h3 class="text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Search</h3>
    <div class="relative group">
        <input type="text" wire:model.live.debounce.500ms="search" placeholder="Type to search..."
            class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-900 placeholder:text-gray-300 focus:ring-4 focus:ring-blue-900/5 focus:border-blue-900 transition-all outline-none">
        <svg class="size-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-900 transition-colors"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>
</div>

<!-- Categories -->
<div class="space-y-4">
    <h3 class="text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Categories</h3>
    <div class="space-y-1 max-h-72 overflow-y-auto pr-2 custom-scrollbar">
        <label
            class="flex items-center group cursor-pointer p-2 rounded-xl transition-colors {{ $selectedCategory === '' ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
            <input type="radio" wire:model.live="selectedCategory" value="" class="hidden">
            <div
                class="size-4 rounded-full border-2 flex items-center justify-center mr-3 transition-all {{ $selectedCategory === '' ? 'border-blue-900 bg-blue-900' : 'border-gray-200 bg-white group-hover:border-blue-900/30' }}">
                @if($selectedCategory === '')
                <div class="size-1.5 bg-white rounded-full"></div> @endif
            </div>
            <span
                class="text-sm font-bold transition-colors {{ $selectedCategory === '' ? 'text-blue-900' : 'text-gray-500 group-hover:text-gray-900' }}">All
                Categories</span>
        </label>
        @foreach($categories as $category)
            <label
                class="flex items-center group cursor-pointer p-2 rounded-xl transition-colors {{ $selectedCategory === $category->slug ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                <input type="radio" wire:model.live="selectedCategory" value="{{ $category->slug }}" class="hidden">
                <div
                    class="size-4 rounded-full border-2 flex items-center justify-center mr-3 transition-all {{ $selectedCategory === $category->slug ? 'border-blue-900 bg-blue-900' : 'border-gray-200 bg-white group-hover:border-blue-900/30' }}">
                    @if($selectedCategory === $category->slug)
                    <div class="size-1.5 bg-white rounded-full"></div> @endif
                </div>
                <span
                    class="text-sm font-bold transition-colors {{ $selectedCategory === $category->slug ? 'text-blue-900' : 'text-gray-500 group-hover:text-gray-900' }}">
                    {{ $category->name }}
                </span>
            </label>
        @endforeach
    </div>
</div>

<!-- Brands -->
<div class="space-y-4">
    <h3 class="text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Brands</h3>
    <div class="space-y-1 max-h-72 overflow-y-auto pr-2 custom-scrollbar">
        <label
            class="flex items-center group cursor-pointer p-2 rounded-xl transition-colors {{ $selectedBrand === '' ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
            <input type="radio" wire:model.live="selectedBrand" value="" class="hidden">
            <div
                class="size-4 rounded-full border-2 flex items-center justify-center mr-3 transition-all {{ $selectedBrand === '' ? 'border-blue-900 bg-blue-900' : 'border-gray-200 bg-white group-hover:border-blue-900/30' }}">
                @if($selectedBrand === '')
                <div class="size-1.5 bg-white rounded-full"></div> @endif
            </div>
            <span
                class="text-sm font-bold transition-colors {{ $selectedBrand === '' ? 'text-blue-900' : 'text-gray-500 group-hover:text-gray-900' }}">All
                Brands</span>
        </label>
        @foreach($brands as $brand)
            <label
                class="flex items-center group cursor-pointer p-2 rounded-xl transition-colors {{ $selectedBrand === $brand->slug ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                <input type="radio" wire:model.live="selectedBrand" value="{{ $brand->slug }}" class="hidden">
                <div
                    class="size-4 rounded-full border-2 flex items-center justify-center mr-3 transition-all {{ $selectedBrand === $brand->slug ? 'border-blue-900 bg-blue-900' : 'border-gray-200 bg-white group-hover:border-blue-900/30' }}">
                    @if($selectedBrand === $brand->slug)
                    <div class="size-1.5 bg-white rounded-full"></div> @endif
                </div>
                <span
                    class="text-sm font-bold transition-colors {{ $selectedBrand === $brand->slug ? 'text-blue-900' : 'text-gray-500 group-hover:text-gray-900' }}">
                    {{ $brand->name }}
                </span>
            </label>
        @endforeach
    </div>
</div>

<!-- Price Range -->
<div class="space-y-4">
    <h3 class="text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Price Range (₦)</h3>
    <div class="flex items-center gap-3">
        <div class="relative flex-1 group">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 font-bold text-xs">MIN</span>
            <input type="number" wire:model.live.debounce.500ms="minPrice" placeholder="0"
                class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-900 placeholder:text-gray-300 focus:ring-4 focus:ring-blue-900/5 focus:border-blue-900 transition-all outline-none">
        </div>
        <div class="relative flex-1 group">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 font-bold text-xs">MAX</span>
            <input type="number" wire:model.live.debounce.500ms="maxPrice" placeholder="10k+"
                class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-900 placeholder:text-gray-300 focus:ring-4 focus:ring-blue-900/5 focus:border-blue-900 transition-all outline-none">
        </div>
    </div>
</div>

<!-- Reset Button -->
<button wire:click="resetFilters"
    class="w-full py-4 mt-6 border-2 border-dashed border-gray-200 rounded-2xl text-sm font-black text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-all flex items-center justify-center gap-2 group">
    <svg class="size-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
    RESET FILTERS
</button>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }

    [x-cloak] {
        display: none !important;
    }
</style>