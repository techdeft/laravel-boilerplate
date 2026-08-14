<?php

use Livewire\Volt\Component;
use App\Models\HomeSlider;
use App\Models\Media;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app.app')] class extends Component {
    public $title;
    public $subtitle;
    public $image_path;
    public $link_url;
    public $order = 0;
    public $is_active = true;

    public $editingSlider = null;
    public $showMediaLibrary = false;

    public function selectImage($path)
    {
        $this->image_path = $path;
        $this->showMediaLibrary = false;
    }

    public function save()
    {
        $this->validate([
            'image_path' => 'required',
            'order' => 'integer',
        ]);

        if ($this->editingSlider) {
            HomeSlider::find($this->editingSlider)->update([
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'image_path' => $this->image_path,
                'link_url' => $this->link_url,
                'order' => $this->order,
                'is_active' => $this->is_active,
            ]);
        } else {
            HomeSlider::create([
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'image_path' => $this->image_path,
                'link_url' => $this->link_url,
                'order' => $this->order,
                'is_active' => $this->is_active,
            ]);
        }

        $this->reset(['title', 'subtitle', 'image_path', 'link_url', 'order', 'is_active', 'editingSlider']);
        $this->dispatch('slider-saved');
    }

    public function edit($id)
    {
        $slider = HomeSlider::findOrFail($id);
        $this->editingSlider = $id;
        $this->title = $slider->title;
        $this->subtitle = $slider->subtitle;
        $this->image_path = $slider->image_path;
        $this->link_url = $slider->link_url;
        $this->order = $slider->order;
        $this->is_active = $slider->is_active;
    }

    public function toggleActive($id)
    {
        $slider = HomeSlider::findOrFail($id);
        $slider->update(['is_active' => !$slider->is_active]);
    }

    public function delete($id)
    {
        HomeSlider::findOrFail($id)->delete();
    }

    public function with()
    {
        return [
            'sliders' => HomeSlider::orderBy('order')->get(),
            'mediaItems' => Media::latest()->take(20)->get(),
        ];
    }
}; ?>

<div class="p-6 space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl ">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Home Slider Management</h1>
            <p class="text-gray-500">Add or edit the main banner sliders for your homepage.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Section --}}
        <div class="bg-white p-6 rounded-2xl h-fit">
            <h3 class="text-lg font-bold mb-4">{{ $editingSlider ? 'Edit Slider' : 'Add New Slider' }}</h3>
            <form wire:submit.prevent="save" class="space-y-5">
                {{-- Title --}}
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Slider Title</label>
                    <div class="relative group">
                        <input type="text" wire:model="title" placeholder="e.g. 50% Off First Purchase" 
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('title') border-red-500 ring-red-500/10 @enderror">
                        <div class="absolute inset-0 rounded-xl pointer-events-none border border-transparent group-focus-within:border-blue-500/20 transition-all"></div>
                    </div>
                    @error('title') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>

                {{-- Subtitle --}}
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Subtitle / Description</label>
                    <textarea wire:model="subtitle" placeholder="Describe the promotion or message..." rows="3"
                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('subtitle') border-red-500 ring-red-500/10 @enderror"></textarea>
                    @error('subtitle') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>

                {{-- Link --}}
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Target URL / Link</label>
                    <div class="relative flex items-center group">
                        <span class="absolute left-4 text-gray-400 text-xs font-mono">/</span>
                        <input type="text" wire:model="link_url" placeholder="shop/category-name" 
                            class="w-full pl-7 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('link_url') border-red-500 ring-red-500/10 @enderror">
                    </div>
                    @error('link_url') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-5">
                    {{-- Order --}}
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Order</label>
                        <input type="number" wire:model="order" 
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('order') border-red-500 ring-red-500/10 @enderror">
                        @error('order') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Toggle --}}
                    <div class="flex flex-col justify-center space-y-2">
                        <span class="text-sm font-semibold text-gray-700">Status</span>
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 transition-all"></div>
                            <span class="ms-3 text-xs font-medium text-gray-500 group-hover:text-gray-900 transition-colors">Visible on Home</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Slider Image</label>
                    @if ($image_path)
                        <div class="relative mb-3 group">
                            <img src="{{ Storage::url($image_path) }}"
                                class="w-full h-40 object-cover rounded-xl border border-gray-200">
                            <button type="button" @click="$wire.set('image_path', null)"
                                class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    <button type="button" wire:click="$toggle('showMediaLibrary')"
                        class="w-full py-3 border-2 border-dashed border-gray-200 rounded-xl text-sm font-medium text-gray-500 hover:border-blue-500 hover:text-blue-500 transition-all">
                        {{ $image_path ? 'Change Image' : 'Select Image from Media' }}
                    </button>
                </div>

                <div class="pt-4 flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-blue-900 text-white py-2.5 rounded-xl font-bold hover:bg-blue-800 shadow-sm transition-all">
                        {{ $editingSlider ? 'Update Slider' : 'Create Slider' }}
                    </button>
                    @if($editingSlider)
                        <button type="button"
                            wire:click="$set('editingSlider', null); $wire.reset(['title', 'subtitle', 'image_path', 'link_url', 'order', 'is_active'])"
                            class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all">
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>

        {{-- List Section --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold">Active Sliders</h3>
            </div>
            <div class="p-0">
                <table class="w-full text-left">
                    <thead
                        class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Slider</th>
                            <th class="px-6 py-3">Info</th>
                            <th class="px-6 py-3">Order</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sliders as $slider)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <img src="{{ Storage::url($slider->image_path) }}"
                                        class="w-20 h-12 object-cover rounded-lg border border-gray-200">
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $slider->title ?? 'Untitled' }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($slider->subtitle, 40) }}</p>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm">{{ $slider->order }}</td>
                                <td class="px-6 py-4">
                                    <button wire:click="toggleActive({{ $slider->id }})" @class([
                                        'px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider',
                                        'bg-green-100 text-green-700' => $slider->is_active,
                                        'bg-gray-100 text-gray-500' => !$slider->is_active,
                                    ])>
                                        {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button wire:click="edit({{ $slider->id }})"
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $slider->id }})"
                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        onclick="confirm('Delete this slider?') || event.stopImmediatePropagation()">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">No sliders created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Simple Media Selector Modal --}}
    @if($showMediaLibrary)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[80vh] overflow-hidden flex flex-col shadow-2xl">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold">Select Image</h3>
                    <button wire:click="$set('showMediaLibrary', false)"
                        class="p-2 hover:bg-gray-100 rounded-xl transition-all">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                    @foreach($mediaItems as $media)
                        <div wire:click="selectImage('{{ $media->file_path }}')"
                            class="relative aspect-square rounded-xl overflow-hidden border-2 border-transparent hover:border-blue-500 cursor-pointer transition-all group">
                            <img src="{{ Storage::url($media->file_path) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>