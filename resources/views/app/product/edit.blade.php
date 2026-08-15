<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Media;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app.app')] class extends Component {
    public Product $product;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $price = 0;
    public $compare_at_price = null;
    public $stock = 0;
    public $image_path = null;
    public $category_id = null;
    public $brand_id = null;
    public $is_active = true;
    public $is_synced = false;
    public $external_id = null;

    public $showMediaLibrary = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->compare_at_price = $product->compare_at_price;
        $this->stock = $product->stock;
        $this->image_path = $product->image_path;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->is_active = $product->is_active;
        $this->is_synced = $product->is_synced;
        $this->external_id = $product->external_id;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function selectImage($path)
    {
        $this->image_path = $path;
        $this->showMediaLibrary = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $this->product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        $this->product->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
            'stock' => $this->stock,
            'image_path' => $this->image_path,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'is_active' => $this->is_active,
            'is_synced' => $this->is_synced,
            'external_id' => $this->external_id,
        ]);

        session()->flash('success', 'Product updated successfully!');
        return $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function with()
    {
        return [
            'categories' => Category::with('subcategories')->whereNull('parent_id')->orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'mediaItems' => Media::latest()->take(30)->get(),
        ];
    }
}; ?>

<div class="p-6 max-w-5xl mx-auto space-y-6">
    <div class="flex items-center gap-4 bg-white p-6 rounded-2xl ">
        <a href="{{ route('admin.products.index') }}" wire:navigate
            class="p-2 hover:bg-gray-100 rounded-xl transition-all text-gray-400 hover:text-gray-900">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Product: {{ $name }}</h1>
            <p class="text-gray-500 text-sm">Update the details for this item in your inventory.</p>
        </div>
    </div>

    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-2xl border border-gray-100 space-y-6">
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Product Name</label>
                    <input type="text" wire:model.live="name" placeholder="e.g. Paracetamol 500mg (Blister Pack)"
                        class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-base focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400">
                    @error('name') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">URL Slug</label>
                        <div
                            class="flex items-center px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 text-sm">
                            <span class="shrink-0">/products/</span>
                            <input type="text" wire:model="slug"
                                class="bg-transparent border-none p-0 outline-none text-gray-900 font-medium w-full ml-1">
                        </div>
                        @error('slug') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">External ID (Optional)</label>
                        <input type="text" wire:model="external_id" placeholder="SKU-001"
                            class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Description</label>
                    <textarea wire:model="description" rows="6"
                        placeholder="Describe the product, its usage, dosage, or features..."
                        class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all min-h-[160px] resize-none"></textarea>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="size-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pricing & Inventory
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Selling Price (₦)</label>
                        <input type="number" step="0.01" wire:model="price"
                            class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl text-lg font-bold text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        @error('price') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Old Price (₦)</label>
                        <input type="number" step="0.01" wire:model="compare_at_price"
                            class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Stock Units</label>
                        <input type="number" wire:model="stock"
                            class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl text-lg font-bold text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        @error('stock') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Controls --}}
        <div class="space-y-6">
            {{-- Image Card --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Product Featured Image</h3>
                <div class="space-y-4">
                    @if ($image_path)
                        <div
                            class="relative group aspect-square rounded-2xl overflow-hidden border-2 border-blue-100 shadow-inner">
                            <img src="{{ Storage::url($image_path) }}" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-2">
                                <button type="button" wire:click="$set('showMediaLibrary', true)"
                                    class="p-2 bg-white text-blue-600 rounded-xl shadow-lg hover:scale-110 transition-transform">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button" wire:click="$set('image_path', null)"
                                    class="p-2 bg-white text-red-600 rounded-xl shadow-lg hover:scale-110 transition-transform">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @else
                        <button type="button" wire:click="$set('showMediaLibrary', true)"
                            class="w-full aspect-square border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center gap-3 text-gray-400 hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 transition-all group">
                            <div class="p-4 bg-gray-50 rounded-2xl group-hover:bg-white transition-colors shadow-sm">
                                <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Choose Image</span>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Organization Card --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100 space-y-5">
                <h3 class="text-sm font-bold text-gray-900">Organization</h3>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500">Category</label>
                    <select wire:model="category_id"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        <option value="">Uncategorized</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" class="font-bold">{{ $cat->name }}</option>
                            @foreach($cat->subcategories as $sub)
                                <option value="{{ $sub->id }}">&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $sub->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500">Brand</label>
                    <select wire:model="brand_id"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        <option value="">No Brand</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Status Card --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                        <p class="text-sm font-bold text-gray-900">Public Visibility</p>
                        <p class="text-[10px] text-gray-500">Enable or disable product display</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all">
                        </div>
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                        <p class="text-sm font-bold text-gray-900">API Synchronization</p>
                        <p class="text-[10px] text-gray-500">Auto-update from external API</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_synced" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all">
                        </div>
                    </label>
                </div>
            </div>

            {{-- Save Button --}}
            <button type="submit"
                class="w-full py-4 bg-blue-900 text-white rounded-2xl font-bold hover:bg-blue-800 shadow-lg shadow-blue-900/10 transition-all text-sm uppercase tracking-widest">
                Update Product
            </button>
        </div>
    </form>

    {{-- Media Selector Modal --}}
    @if($showMediaLibrary)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-3xl w-full max-w-5xl h-[85vh] flex flex-col shadow-2xl overflow-hidden scale-up">
                <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Select Product Media</h3>
                        <p class="text-sm text-gray-500">Pick an image from your existing library.</p>
                    </div>
                    <button @click="$wire.set('showMediaLibrary', false)"
                        class="p-3 bg-white hover:bg-red-50 hover:text-red-500 rounded-2xl transition-all shadow-sm border border-gray-100">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.6"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 p-8 overflow-y-auto grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-5 bg-gray-50/20">
                    @forelse($mediaItems as $media)
                        <div wire:click="selectImage('{{ $media->file_path }}')"
                            class="group relative aspect-square rounded-2xl overflow-hidden border-2 border-transparent hover:border-blue-500 cursor-pointer transition-all bg-white shadow-sm">
                            <img src="{{ Storage::url($media->file_path) }}" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-blue-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span
                                    class="px-3 py-1 bg-white text-blue-900 text-[10px] font-bold rounded-lg shadow-lg uppercase tracking-wider">Select</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-24 text-center">
                            <p class="text-gray-400 italic">No media found in your library.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .scale-up {
        animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes scaleUp {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>