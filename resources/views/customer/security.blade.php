<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest.app')] class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
        ], [
            'current_password.current_password' => 'The provided password does not match your current password.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('status', 'Your password has been updated successfully.');
    }
}; ?>

<x-slot name="title">Login & Security</x-slot>

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
                    <div class="p-4 border-b border-gray-100 flex items-center gap-4">
                        <a href="{{ route('customer.dashboard') }}"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-arrow-left text-lg"></i>
                        </a>
                        <h1 class="text-[20px] font-medium text-gray-900 leading-tight">Login & Security</h1>
                    </div>

                    <div class="p-6">
                        @if (session('status'))
                            <div
                                class="mb-6 p-4 bg-green-50 text-green-700 text-sm rounded border border-green-100 flex items-center gap-3">
                                <i class="fa-solid fa-circle-check"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="max-w-xl">
                            <h2 class="text-[16px] font-bold text-gray-800 uppercase mb-6">Change Password</h2>

                            <form wire:submit="updatePassword" class="space-y-6">
                                <!-- Current Password -->
                                <div>
                                    <label for="current_password"
                                        class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Current
                                        Password</label>
                                    <div class="relative">
                                        <input type="password" wire:model="current_password" id="current_password"
                                            class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors pl-10">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fa-solid fa-lock"></i>
                                        </div>
                                    </div>
                                    @error('current_password') <p class="mt-1 text-xs text-red-500 font-medium">
                                        {{ $message }}
                                    </p> @enderror
                                </div>

                                <!-- New Password -->
                                <div>
                                    <label for="password"
                                        class="block text-[14px] font-bold text-gray-700 uppercase mb-2">New
                                        Password</label>
                                    <div class="relative">
                                        <input type="password" wire:model="password" id="password"
                                            class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors pl-10">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fa-solid fa-key"></i>
                                        </div>
                                    </div>
                                    @error('password') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}
                                    </p> @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-[14px] font-bold text-gray-700 uppercase mb-2">Retype New
                                        Password</label>
                                    <div class="relative">
                                        <input type="password" wire:model="password_confirmation"
                                            id="password_confirmation"
                                            class="w-full px-4 py-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] focus:ring-1 focus:ring-[#2b1770] transition-colors pl-10">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fa-solid fa-check-double"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-100">
                                    <button type="submit"
                                        class="w-full md:w-auto px-12 py-3.5 bg-[#2b1770] hover:bg-[#3f238f] text-white rounded font-bold text-[14px] uppercase tracking-wide transition-all shadow-md flex items-center justify-center gap-2">
                                        Update Password
                                        <div wire:loading wire:target="updatePassword"
                                            class="size-4 border-2 border-white/30 border-t-white rounded-full animate-spin">
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>