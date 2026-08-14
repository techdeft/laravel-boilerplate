<?php

use Livewire\Volt\Component;
use App\Models\DeliveryZone;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

new #[Layout('layouts.app.app')] class extends Component {
    use WithPagination;

    public $name = '';
    public $city = '';
    public $country = 'Nigeria';
    public $delivery_fee = 0;
    public $local_park_fee = 0;
    public $local_park_instructions = '';
    public $is_active = true;
    public $search = '';
    public $editingZone = null;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:delivery_zones,name,' . ($this->editingZone?->id ?? 'NULL'),
            'city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'delivery_fee' => 'required|numeric|min:0',
            'local_park_fee' => 'required|numeric|min:0',
            'local_park_instructions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($this->editingZone) {
            $this->editingZone->update([
                'name' => $this->name,
                'city' => $this->city,
                'country' => $this->country,
                'delivery_fee' => $this->delivery_fee,
                'local_park_fee' => $this->local_park_fee,
                'local_park_instructions' => $this->local_park_instructions,
                'is_active' => $this->is_active,
            ]);
            session()->flash('status', 'Delivery zone updated successfully.');
        } else {
            DeliveryZone::create([
                'name' => $this->name,
                'city' => $this->city,
                'country' => $this->country,
                'delivery_fee' => $this->delivery_fee,
                'local_park_fee' => $this->local_park_fee,
                'local_park_instructions' => $this->local_park_instructions,
                'is_active' => $this->is_active,
            ]);
            session()->flash('status', 'Delivery zone created successfully.');
        }

        $this->reset(['name', 'city', 'country', 'delivery_fee', 'local_park_fee', 'local_park_instructions', 'is_active', 'editingZone']);
        $this->country = 'Nigeria';
    }

    public function edit(DeliveryZone $zone)
    {
        $this->editingZone = $zone;
        $this->name = $zone->name;
        $this->city = $zone->city;
        $this->country = $zone->country ?? 'Nigeria';
        $this->delivery_fee = $zone->delivery_fee;
        $this->local_park_fee = $zone->local_park_fee;
        $this->local_park_instructions = $zone->local_park_instructions;
        $this->is_active = $zone->is_active;
    }

    public function delete(DeliveryZone $zone)
    {
        $zone->delete();
        session()->flash('status', 'Delivery zone deleted.');
    }

    public function cancel()
    {
        $this->reset(['name', 'city', 'country', 'delivery_fee', 'local_park_fee', 'local_park_instructions', 'is_active', 'editingZone']);
        $this->country = 'Nigeria';
    }

    public function with()
    {
        return [
            'zones' => DeliveryZone::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
        ];
    }
}; ?>

<div class="p-6 space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl ">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Delivery Fee Management</h1>
            <p class="text-gray-500 text-sm">Manage regions and their corresponding delivery fees.</p>
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
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Region/State Name</label>
                        <input type="text" wire:model="name" placeholder="e.g. Lagos"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">City (Optional)</label>
                        <input type="text" wire:model="city" placeholder="e.g. Ikeja"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('city') border-red-500 @enderror">
                        @error('city') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Country</label>
                    <input type="text" wire:model="country" placeholder="e.g. Nigeria"
                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('country') border-red-500 @enderror">
                    @error('country') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Home Fee (₦)</label>
                        <input type="number" step="0.01" wire:model="delivery_fee" placeholder="0.00"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('delivery_fee') border-red-500 @enderror">
                        @error('delivery_fee') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Park Fee (₦)</label>
                        <input type="number" step="0.01" wire:model="local_park_fee" placeholder="0.00"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('local_park_fee') border-red-500 @enderror">
                        @error('local_park_fee') <p class="text-[11px] text-red-500 font-medium px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Park Instructions</label>
                    <textarea wire:model="local_park_instructions" placeholder="e.g. Pickup at Young Shall Grow Motors"
                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none @error('local_park_instructions') border-red-500 @enderror min-h-[80px]"></textarea>
                    @error('local_park_instructions') <p class="text-[11px] text-red-500 font-medium px-1">
                        {{ $message }}
                    </p> @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_active" id="is_active"
                        class="size-4 text-blue-900 focus:ring-blue-900 border-gray-300 rounded">
                    <label for="is_active" class="text-sm font-semibold text-gray-700">Active</label>
                </div>

                <div class="pt-4 flex gap-2">
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
                    <input type="text" wire:model.live="search" placeholder="Search zones..."
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
                            <th class="px-6 py-4">Region/State</th>
                            <th class="px-6 py-4">Home Fee</th>
                            <th class="px-6 py-4">Park Fee</th>
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
                                        <span
                                            class="text-[10px] text-gray-400 font-bold uppercase">{{ $zone->city ? $zone->city . ', ' : '' }}{{ $zone->country }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-medium text-gray-700">₦{{ number_format($zone->delivery_fee, 0) }}</span>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold">Home</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col text-sm">
                                        <span
                                            class="font-medium text-gray-700">₦{{ number_format($zone->local_park_fee, 0) }}</span>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold">Park</span>
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
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">No delivery zones found.
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