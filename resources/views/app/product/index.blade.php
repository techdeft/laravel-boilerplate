<?php
use Livewire\Volt\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Services\ProductSyncService;

new #[Layout('layouts.app.app')] class extends Component {
    use WithPagination;

    public $search = '';

    public function delete(Product $product)
    {
        $product->delete();
        $this->dispatch('product-deleted');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
    }

    public function syncProducts(ProductSyncService $syncService)
    {
        $syncService->sync();
        $this->dispatch('products-synced');
    }

    public function with()
    {
        return [
            'products' => Product::with(['category', 'brand'])
                ->where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(15),
        ];
    }
}; ?>

<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl ">
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Products Inventory</h1>
            <p class="text-gray-500 text-sm">Manage your store's catalog and stock levels.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="syncProducts" wire:loading.attr="disabled"
                class="inline-flex items-center justify-center px-5 py-2.5 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition-all shadow-sm gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg wire:loading.class="animate-spin" class="size-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span wire:loading.remove>Sync Products</span>
                <span wire:loading>Syncing...</span>
            </button>
            <a href="{{ route('admin.products.create') }}" wire:navigate
                class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-900 text-white rounded-xl font-bold text-sm hover:bg-blue-800 transition-all shadow-sm gap-2">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add New Product
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden ">
        <div
            class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50/30">
            <h3 class="text-lg font-bold text-gray-900">Product List</h3>
            <div class="relative w-full md:w-96">
                <input type="text" wire:model.live="search" placeholder="Search products by name or SKU..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                <svg class="size-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead
                    class="bg-gray-50 text-[10px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Product Detail</th>
                        <th class="px-6 py-4 text-center">Catalog Info</th>
                        <th class="px-6 py-4 text-center">Pricing</th>
                        <th class="px-6 py-4 text-center">Inventory</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="size-12 rounded-xl border border-gray-200 overflow-hidden bg-gray-50 shrink-0">
                                        @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-mono tracking-tighter">
                                            {{ $product->external_id ?? 'Local ID: #' . $product->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span
                                        class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md">{{ $product->category->name ?? $product->category ?? 'General' }}</span>
                                    <span
                                        class="text-[10px] text-gray-400 font-medium">{{ $product->brand->name ?? $product->brand ?? 'No Brand' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center">
                                    <p class="text-sm font-bold text-gray-900">₦{{ number_format($product->price, 2) }}</p>
                                    @if($product->compare_at_price)
                                        <p class="text-[11px] text-gray-400 line-through">
                                            ₦{{ number_format($product->compare_at_price, 2) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span @class([
                                        'px-2 py-0.5 rounded text-[10px] font-bold',
                                        'bg-red-50 text-red-600' => $product->stock <= 5,
                                        'bg-orange-50 text-orange-600' => $product->stock > 5 && $product->stock <= 20,
                                        'bg-green-50 text-green-600' => $product->stock > 20,
                                    ])>
                                        {{ $product->stock }} units
                                    </span>
                                    @if($product->is_synced)
                                        <span class="text-[9px] text-blue-500 font-bold uppercase tracking-tighter">Sync
                                            Active</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <button wire:click="toggleActive({{ $product->id }})" @class([
                                    'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all',
                                    'bg-green-100 text-green-700' => $product->is_active,
                                    'bg-gray-100 text-gray-500' => !$product->is_active,
                                ])>
                                    {{ $product->is_active ? 'Active' : 'Hidden' }}
                                </button>
                            </td>
                            <td class="px-6 py-5 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.products.edit', $product->id) }}" wire:navigate
                                    class="inline-flex p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Edit Product">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button wire:click="delete({{ $product->id }})"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Delete Product"
                                    onclick="confirm('Are you sure you want to delete this product? This action cannot be undone.') || event.stopImmediatePropagation()">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="size-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-300">
                                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">No products found. Start by adding one!</p>
                                    <a href="{{ route('admin.products.create') }}" wire:navigate
                                        class="text-blue-600 text-sm font-bold hover:underline">Create your first product
                                        →</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>