<?php

use Livewire\Volt\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app.app')] class extends Component {
    public $name = '';
    public $slug = '';
    public $parent_id = null;
    public $search = '';
    public $editingCategory = null;

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug,' . ($this->editingCategory?->id ?? 'NULL'),
            'parent_id' => 'nullable|exists:categories,id|different:editingCategory.id',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id ?: null,
        ];

        if ($this->editingCategory) {
            $this->editingCategory->update($data);
        } else {
            Category::create($data);
        }

        $this->reset(['name', 'slug', 'parent_id', 'editingCategory']);
        $this->dispatch('category-saved');
    }

    public function edit(Category $category)
    {
        $this->editingCategory = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->parent_id = $category->parent_id;
    }

    public function delete(Category $category)
    {
        $category->delete();
        $this->dispatch('category-deleted');
    }

    public function cancel()
    {
        $this->reset(['name', 'slug', 'parent_id', 'editingCategory']);
    }

    public function with()
    {
        return [
            'categories' => Category::with('parent')
                ->where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
            'parentCategories' => Category::whereNull('parent_id')
                ->when($this->editingCategory, fn($q) => $q->where('id', '!=', $this->editingCategory->id))
                ->orderBy('name')
                ->get(),
        ];
    }
}; ?>

<div class="p-6 space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl ">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Category & Subcategory Management</h1>
            <p class="text-gray-500 text-sm">Organize your products into main categories and subcategories.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
            <h3 class="text-lg font-bold mb-4">{{ $editingCategory ? 'Edit Category' : 'Add New Category' }}</h3>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Category Name</label>
                    <input type="text" wire:model.live="name" placeholder="e.g. Pain Relief"
                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Parent Category (Optional)</label>
                    <select wire:model="parent_id"
                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('parent_id') border-red-500 @enderror">
                        <option value="">None (Main Category)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-400">Select a parent category to create a subcategory.</p>
                    @error('parent_id') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Slug (Auto-generated)</label>
                    <input type="text" wire:model="slug" placeholder="pain-relief"
                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('slug') border-red-500 @enderror">
                    @error('slug') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-blue-900 text-white py-2.5 rounded-xl font-bold hover:bg-blue-800 shadow-sm transition-all">
                        {{ $editingCategory ? 'Update Category' : 'Create Category' }}
                    </button>
                    @if($editingCategory)
                        <button type="button" wire:click="cancel"
                            class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all">
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>

        {{-- List Section --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-lg font-bold">All Categories & Subcategories</h3>
                <div class="relative w-full md:w-64">
                    <input type="text" wire:model.live="search" placeholder="Search categories..."
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
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
                        class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Type / Parent</th>
                            <th class="px-6 py-4">Slug</th>
                            <th class="px-6 py-4">Created At</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($category->parent_id)
                                            <span class="text-gray-400 text-xs pl-2">↳</span>
                                        @endif
                                        <span class="text-sm font-bold text-gray-900">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($category->parent)
                                        <span class="px-2.5 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                            <span>Subcategory of</span>
                                            <strong class="font-bold">{{ $category->parent->name }}</strong>
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold">
                                            Main Category
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 bg-gray-100 rounded text-[10px] font-mono text-gray-600">{{ $category->slug }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    {{ $category->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-1">
                                    <button wire:click="edit({{ $category->id }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Edit">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $category->id }})"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete"
                                        onclick="confirm('Are you sure you want to delete this category?') || event.stopImmediatePropagation()">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>