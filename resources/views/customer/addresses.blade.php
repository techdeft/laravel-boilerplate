<?php

use Livewire\Volt\Component;
use App\Models\Address;
use App\Models\DeliveryZone;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

new #[Layout('layouts.guest.app')] class extends Component {
    public $addresses = [];
    public $showForm = false;
    public $editingAddressId = null;

    // Form fields
    public $first_name = '';
    public $last_name = '';
    public $phone = '';
    public $additional_phone = '';
    public $address_line = '';
    public $additional_info = '';
    public $region = '';
    public $city = '';
    public $country = 'Nigeria';
    public $is_default = false;

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $this->addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->latest()->get();
    }

    public function toggleForm($id = null)
    {
        $this->resetForm();
        if ($id) {
            $address = Address::find($id);
            if ($address && $address->user_id === Auth::id()) {
                $this->editingAddressId = $id;
                $this->first_name = $address->first_name;
                $this->last_name = $address->last_name;
                $this->phone = $address->phone;
                $this->additional_phone = $address->additional_phone;
                $this->address_line = $address->address_line;
                $this->additional_info = $address->additional_info;
                $this->region = $address->region;
                $this->city = $address->city;
                $this->country = $address->country ?? 'Nigeria';
                $this->is_default = (bool)$address->is_default;
            }
        } else {
            // Pre-fill with user's basic details for new address
            $user = Auth::user();
            $nameParts = explode(' ', $user->name, 2);
            $this->first_name = $nameParts[0] ?? '';
            $this->last_name = $nameParts[1] ?? '';
            $this->phone = $user->phone;
        }
        $this->showForm = true;
    }

    public function cancelForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['first_name', 'last_name', 'phone', 'additional_phone', 'address_line', 'additional_info', 'region', 'city', 'country', 'is_default', 'editingAddressId']);
        $this->country = 'Nigeria'; // Reset to default
    }

    public function saveAddress()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'additional_phone' => 'nullable|string|max:20',
            'address_line' => 'required|string',
            'region' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        if ($this->is_default) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        // If it's the first address, make it default anyway
        if (Auth::user()->addresses()->count() === 0) {
            $this->is_default = true;
        }

        $data = [
            'user_id' => Auth::id(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'additional_phone' => $this->additional_phone,
            'address_line' => $this->address_line,
            'additional_info' => $this->additional_info,
            'region' => $this->region,
            'city' => $this->city,
            'country' => $this->country,
            'is_default' => $this->is_default,
        ];

        if ($this->editingAddressId) {
            $address = Address::find($this->editingAddressId);
            if ($address && $address->user_id === Auth::id()) {
                $address->update($data);
                session()->flash('status', 'Address updated successfully.');
            }
        } else {
            Address::create($data);
            session()->flash('status', 'Address added successfully.');
        }

        $this->showForm = false;
        $this->loadAddresses();
    }

    public function deleteAddress($id)
    {
        $address = Address::find($id);
        if ($address && $address->user_id === Auth::id()) {
            $wasDefault = $address->is_default;
            $address->delete();

            if ($wasDefault) {
                $next = Auth::user()->addresses()->first();
                if ($next) {
                    $next->update(['is_default' => true]);
                }
            }
            session()->flash('status', 'Address deleted successfully.');
            $this->loadAddresses();
        }
    }

    public function setAsDefault($id)
    {
        Auth::user()->addresses()->update(['is_default' => false]);
        Address::where('id', $id)->where('user_id', Auth::id())->update(['is_default' => true]);
        session()->flash('status', 'Default address updated.');
        $this->loadAddresses();
    }

    #[Computed]
    public function regions()
    {
        return DeliveryZone::where('is_active', true)
            ->where('country', $this->country)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function countries()
    {
        return DeliveryZone::where('is_active', true)
            ->distinct()
            ->orderBy('country')
            ->pluck('country');
    }
}; ?>

<x-slot name="title">Address Management</x-slot>

<div class="bg-[#f1f1f2] min-h-screen pb-20 lg:pb-12 text-[#282828]">
    <div class="max-w-[1184px] mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4 items-start">
            <!-- Sidebar -->
            <aside class="hidden lg:block w-full lg:w-[240px] flex-shrink-0">
                @include('customer.sidebar')
            </aside>

            <!-- Main Content -->
            <main class="flex-1 w-full space-y-4">
                <div class="bg-white rounded shadow-sm overflow-hidden min-h-[500px]">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('customer.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fa-solid fa-arrow-left text-lg"></i>
                            </a>
                            <h1 class="text-[20px] font-medium text-gray-900 leading-tight">Addresses</h1>
                        </div>
                        @if(!$showForm)
                            <button wire:click="toggleForm()" class="text-[#2b1770] font-bold text-[14px] uppercase hover:bg-gray-50 px-3 py-1.5 rounded transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-plus"></i>
                                Add New Address
                            </button>
                        @endif
                    </div>

                    <div class="p-4">
                        @if (session('status'))
                            <div class="mb-4 p-4 bg-green-50 text-green-700 text-sm rounded border border-green-100 flex items-center gap-3">
                                <i class="fa-solid fa-circle-check"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        @if($showForm)
                            <!-- Address Form -->
                            <div class="max-w-2xl mx-auto py-4">
                                <h2 class="text-[16px] font-bold text-gray-800 uppercase mb-6">{{ $editingAddressId ? 'Edit Address' : 'Add New Address' }}</h2>
                                <form wire:submit="saveAddress" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[14px] font-bold text-gray-700 uppercase mb-2">First Name</label>
                                            <input type="text" wire:model="first_name" class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                            @error('first_name') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Last Name</label>
                                            <input type="text" wire:model="last_name" class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                            @error('last_name') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Phone Number</label>
                                            <input type="text" wire:model="phone" class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                            @error('phone') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Additional Phone Number</label>
                                            <input type="text" wire:model="additional_phone" class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Address</label>
                                        <textarea wire:model="address_line" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors"></textarea>
                                        @error('address_line') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Additional Info</label>
                                        <input type="text" wire:model="additional_info" class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Country</label>
                                            <select wire:model.live="country" class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                                <option value="">Select Country</option>
                                                @foreach($this->countries as $c)
                                                    <option value="{{ $c }}">{{ $c }}</option>
                                                @endforeach
                                            </select>
                                            @error('country') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Region</label>
                                            <select wire:model="region" class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                                <option value="">Select Region</option>
                                                @foreach($this->regions as $r)
                                                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('region') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[14px] font-bold text-gray-700 uppercase mb-2">City</label>
                                        <input type="text" wire:model="city" class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                        @error('city') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" wire:model="is_default" id="is_default" class="size-4 text-[#2b1770] focus:ring-[#2b1770] border-gray-300 rounded">
                                        <label for="is_default" class="text-[14px] text-gray-700">Set as default shipping address</label>
                                    </div>

                                    <div class="pt-6 flex gap-3">
                                        <button type="submit" class="px-12 py-3 bg-[#2b1770] text-white rounded font-bold text-[14px] uppercase tracking-wide hover:bg-[#3f238f] transition-all shadow-md">
                                            Save Address
                                        </button>
                                        <button type="button" wire:click="cancelForm" class="px-8 py-3 bg-white text-gray-500 border border-gray-200 rounded font-bold text-[14px] uppercase tracking-wide hover:bg-gray-50 transition-all">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <!-- Address List -->
                            @if($addresses->isEmpty())
                                <div class="flex flex-col items-center justify-center py-16 text-center">
                                    <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-location-dot text-4xl text-gray-100"></i>
                                    </div>
                                    <h3 class="text-[18px] font-medium text-gray-900 mb-2">No Saved Addresses</h3>
                                    <p class="text-[14px] text-gray-500 mb-8 max-w-sm">Add a delivery address to ensure a faster checkout experience on MedMall.</p>
                                    <button wire:click="toggleForm()" class="px-8 py-3 bg-[#2b1770] text-white rounded font-bold text-[14px] uppercase tracking-wide hover:bg-[#3f238f] transition-all shadow-md">
                                        Add New Address
                                    </button>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($addresses as $address)
                                        <div class="border {{ $address->is_default ? 'border-[#2b1770] ring-1 ring-[#2b1770]' : 'border-gray-100' }} rounded-lg overflow-hidden flex flex-col group transition-all hover:shadow-md">
                                            <div class="p-4 flex-1">
                                                <div class="flex items-start justify-between mb-3">
                                                    <div>
                                                        <p class="text-[16px] font-bold text-gray-900 leading-tight">{{ $address->first_name }} {{ $address->last_name }}</p>
                                                        @if($address->is_default)
                                                            <span class="inline-block mt-1 bg-[#2b1770]/10 text-[#2b1770] text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Default</span>
                                                        @endif
                                                    </div>
                                                    <i class="fa-solid fa-location-arrow {{ $address->is_default ? 'text-[#2b1770]' : 'text-gray-200' }}"></i>
                                                </div>
                                                
                                                <div class="space-y-1 text-[14px] text-gray-600">
                                                    <p class="line-clamp-2">{{ $address->address_line }}</p>
                                                    <p>{{ $address->city }}, {{ $address->region }}, {{ $address->country }}</p>
                                                    <p class="pt-2 text-gray-400"><i class="fa-solid fa-phone text-[10px] mr-1"></i> {{ $address->phone }}</p>
                                                </div>
                                            </div>

                                            <div class="p-3 bg-gray-50 flex items-center justify-between border-t border-gray-100">
                                                <div class="flex gap-4">
                                                    <button wire:click="toggleForm({{ $address->id }})" class="text-primary-500 text-[12px] font-bold uppercase hover:underline">Edit</button>
                                                    <button 
                                                        wire:click="deleteAddress({{ $address->id }})" 
                                                        wire:confirm="Are you sure you want to delete this address?"
                                                        class="text-primary-500 text-[12px] font-bold uppercase hover:underline">Delete</button>
                                                </div>
                                                @if(!$address->is_default)
                                                    <button wire:click="setAsDefault({{ $address->id }})" class="text-[#2b1770] text-[12px] font-bold uppercase hover:underline">Set as Default</button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
