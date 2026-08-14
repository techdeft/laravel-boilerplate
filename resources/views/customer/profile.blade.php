<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest.app')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
        ]);

        $user->update($validated);

        session()->flash('status', 'Profile updated successfully.');
    }
}; ?>

<x-slot name="title">My Profile</x-slot>

<div class="bg-[#f1f1f2] min-h-screen pb-20 lg:pb-12">
    <div class="max-w-[1184px] mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4 items-start">
            <!-- Sidebar -->
            <aside class="hidden lg:block w-full lg:w-[240px] flex-shrink-0">
                @include('customer.sidebar')
            </aside>

            <!-- Main Content -->
            <main class="flex-1 w-full space-y-4">
                <div class="bg-white rounded shadow-sm overflow-hidden min-h-[500px]">
                    <div class="p-4 border-b border-gray-100">
                        <h1 class="text-[20px] font-medium text-gray-900 leading-tight">Account Information</h1>
                    </div>

                    <div class="p-6">
                        @if (session('status'))
                            <div
                                class="mb-6 p-4 bg-green-50 text-green-700 text-sm rounded border border-green-100 flex items-center gap-3">
                                <i class="fa-solid fa-circle-check"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <form wire:submit="updateProfile" class="max-w-xl space-y-6">
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name"
                                        class="block text-[14px] font-bold text-gray-700 uppercase mb-2">First Name /
                                        Last Name</label>
                                    <input type="text" wire:model="name" id="name"
                                        class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                    @error('name') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email"
                                        class="block text-[14px] font-bold text-gray-700 uppercase mb-2">E-mail</label>
                                    <input type="email" wire:model="email" id="email"
                                        class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                    @error('email') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone"
                                        class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Mobile
                                        Number</label>
                                    <input type="text" wire:model="phone" id="phone"
                                        class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors">
                                    @error('phone') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <button type="submit"
                                    class="w-full md:w-auto px-12 py-3.5 bg-[#2b1770] hover:bg-[#3f238f] text-white rounded font-bold text-[14px] uppercase tracking-wide transition-all shadow-md flex items-center justify-center gap-2">
                                    Save Changes
                                    <div wire:loading wire:target="updateProfile"
                                        class="size-4 border-2 border-white/30 border-t-white rounded-full animate-spin">
                                    </div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>