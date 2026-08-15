<?php

use Livewire\Volt\Component;
use App\Models\DeliveryZone;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

new #[Layout('layouts.app.app')] class extends Component {
    use WithPagination;

    public $name = '';
    public $city = '';
    public $area = '';
    public $country = 'Nigeria';
    public $delivery_fee = 0;
    public $local_park_fee = 0;
    public $local_park_instructions = '';
    public $special_surcharge = 0;
    public $free_delivery_threshold = null;
    public $is_active = true;
    public $search = '';
    public $editingZone = null;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'delivery_fee' => 'required|numeric|min:0',
            'local_park_fee' => 'required|numeric|min:0',
            'local_park_instructions' => 'nullable|string',
            'special_surcharge' => 'required|numeric|min:0',
            'free_delivery_threshold' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $this->name,
            'city' => $this->city ?: null,
            'area' => $this->area ?: null,
            'country' => $this->country,
            'delivery_fee' => $this->delivery_fee,
            'local_park_fee' => $this->local_park_fee,
            'local_park_instructions' => $this->local_park_instructions,
            'special_surcharge' => $this->special_surcharge ?: 0,
            'free_delivery_threshold' => $this->free_delivery_threshold ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingZone) {
            $this->editingZone->update($data);
            session()->flash('status', 'Delivery zone updated successfully.');
        } else {
            DeliveryZone::create($data);
            session()->flash('status', 'Delivery zone created successfully.');
        }

        $this->resetForm();
    }

    public function edit(DeliveryZone $zone)
    {
        $this->editingZone = $zone;
        $this->name = $zone->name;
        $this->city = $zone->city;
        $this->area = $zone->area;
        $this->country = $zone->country ?? 'Nigeria';
        $this->delivery_fee = $zone->delivery_fee;
        $this->local_park_fee = $zone->local_park_fee;
        $this->local_park_instructions = $zone->local_park_instructions;
        $this->special_surcharge = $zone->special_surcharge ?? 0;
        $this->free_delivery_threshold = $zone->free_delivery_threshold;
        $this->is_active = $zone->is_active;
    }

    public function delete(DeliveryZone $zone)
    {
        $zone->delete();
        session()->flash('status', 'Delivery zone deleted.');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'name', 'city', 'area', 'country', 'delivery_fee', 'local_park_fee',
            'local_park_instructions', 'special_surcharge', 'free_delivery_threshold',
            'is_active', 'editingZone'
        ]);
        $this->country = 'Nigeria';
        $this->is_active = true;
    }

    public function with()
    {
        return [
            'zones' => DeliveryZone::where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('city', 'like', '%' . $this->search . '%')
                      ->orWhere('area', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),
        ];
    }
}; ?>

<div class="p-6 space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl ">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Delivery Zone & Area Pricing Management</h1>
            <p class="text-gray-500 text-sm">Configure flexible delivery fees by region, city, sub-area, and special surcharges.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="p-4 bg-green-50 text-green-700 text-sm rounded-xl border border-green-100">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
            <h3 class="text-lg font-bold mb-4">{{ $editingZone ? 'Edit Delivery Zone' : 'Add New Delivery Zone' }}</h3>
            <form wire:submit.prevent="save" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Region / State Name</label>
                    <input type="text" wire:model="name" placeholder="e.g. Lagos"
                        class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">City (Optional)</label>
                        <input type="text" wire:model="city" placeholder="e.g. Lekki"
                            class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('city') border-red-500 @enderror">
                        @error('city') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Area (Optional)</label>
                        <input type="text" wire:model="area" placeholder="e.g. Phase 2"
                            class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('area') border-red-500 @enderror">
                        @error('area') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Country</label>
                    <input type="text" wire:model="country" placeholder="e.g. Nigeria"
                        class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('country') border-red-500 @enderror">
                    @error('country') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Home Delivery Fee (₦)</label>
                        <input type="number" step="0.01" wire:model="delivery_fee" placeholder="0.00"
                            class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('delivery_fee') border-red-500 @enderror">
                        @error('delivery_fee') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Park Delivery Fee (₦)</label>
                        <input type="number" step="0.01" wire:model="local_park_fee" placeholder="0.00"
                            class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('local_park_fee') border-red-500 @enderror">
                        @error('local_park_fee') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Area Surcharge (₦)</label>
                        <input type="number" step="0.01" wire:model="special_surcharge" placeholder="0.00"
                            class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('special_surcharge') border-red-500 @enderror">
                        <p class="text-[10px] text-gray-400">Extra fee for remote areas.</p>
                        @error('special_surcharge') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Free Delivery Min (₦)</label>
                        <input type="number" step="0.01" wire:model="free_delivery_threshold" placeholder="Optional e.g. 50000"
                            class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('free_delivery_threshold') border-red-500 @enderror">
                        <p class="text-[10px] text-gray-400">Min total for ₦0 delivery.</p>
                        @error('free_delivery_threshold') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Park Instructions</label>
                    <textarea wire:model="local_park_instructions" placeholder="e.g. Pickup at Young Shall Grow Motors"
                        class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('local_park_instructions') border-red-500 @enderror min-h-[70px]"></textarea>
                    @error('local_park_instructions') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" wire:model="is_active" id="is_active"
                        class="size-4 text-blue-900 focus:ring-blue-900 border-gray-300 rounded">
                    <label for="is_active" class="text-sm font-semibold text-gray-700">Active Zone</label>
                </div>

                <div class="pt-3 flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-blue-900 text-white py-2.5 rounded-xl font-bold hover:bg-blue-800 shadow-sm transition-all">
                        {{ $editingZone ? 'Update Zone' : 'Create Zone' }}
                    </button>
                    @if($editingZone)
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
                <h3 class="text-lg font-bold">All Delivery Zones</h3>
                <div class="relative w-full md:w-64">
                    <input type="text" wire:model.live="search" placeholder="Search region, city, or area..."
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
                            <th class="px-6 py-4">Location</th>
                            <th class="px-6 py-4">Home / Park Fee</th>
                            <th class="px-6 py-4">Surcharge / Free Min</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($zones as $zone)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ $zone->name }}</span>
                                        <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-0.5">
                                            @if($zone->city)
                                                <span class="font-medium text-blue-900 bg-blue-50 px-2 py-0.5 rounded">{{ $zone->city }}</span>
                                            @endif
                                            @if($zone->area)
                                                <span class="font-medium text-purple-900 bg-purple-50 px-2 py-0.5 rounded">Area: {{ $zone->area }}</span>
                                            @endif
                                            <span class="text-[10px] text-gray-400 uppercase font-bold">{{ $zone->country }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col text-sm">
                                        <span class="font-bold text-gray-900">Home: ₦{{ number_format($zone->delivery_fee, 0) }}</span>
                                        <span class="text-xs text-gray-500">Park: ₦{{ number_format($zone->local_park_fee, 0) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col text-xs">
                                        @if($zone->special_surcharge > 0)
                                            <span class="font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded w-fit">+₦{{ number_format($zone->special_surcharge, 0) }} Surcharge</span>
                                        @else
                                            <span class="text-gray-400">No Surcharge</span>
                                        @endif
                                        @if($zone->free_delivery_threshold)
                                            <span class="text-green-700 font-medium mt-1">Free over ₦{{ number_format($zone->free_delivery_threshold, 0) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($zone->is_active)
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-700 rounded text-[10px] font-bold uppercase">Active</span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-[10px] font-bold uppercase">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-1">
                                    <button wire:click="edit({{ $zone->id }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Edit">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $zone->id }})"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete"
                                        onclick="confirm('Are you sure you want to delete this zone?') || event.stopImmediatePropagation()">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">No delivery zones found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($zones->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $zones->links() }}
                </div>
            @endif
        </div>
    </div>
</div>