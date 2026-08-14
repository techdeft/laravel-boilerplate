<!-- ========== HEADER ========== -->
<header class="z-50 w-full bg-white border-b border-gray-200">
    <!-- Top Row: Logo, Search, Actions -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4 lg:flex-nowrap">
            <!-- Logo Section -->
            <div class="flex items-center gap-x-2 shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-x-2 focus:outline-hidden" wire:navigate>
                    @php
                        $siteLogo = \App\Models\SiteSetting::getValue('site_logo');
                    @endphp
                    @if($siteLogo)
                        <img src="{{ Storage::url($siteLogo) }}" alt="Logo" class="h-10">
                    @else
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
                    @endif
                </a>
            </div>

            <!-- Search Bar Section -->
            <livewire:layouts.guest.nav-search />

            <!-- Actions Section -->
            <div class="flex items-center gap-x-6 order-2 lg:order-3">

                <a href="{{ route('customer.dashboard') }}"
                    class="group flex items-center gap-x-3 text-sm font-medium text-gray-700 hover:text-blue-900 transition-colors"
                    wire:navigate>
                    <div
                        class="size-10 flex items-center justify-center rounded-full bg-gray-100 group-hover:bg-blue-50 transition-colors">
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        @auth
                            <p class="text-[11px] text-gray-400 font-normal">Signed In as</p>
                            @php
                                $name = auth()->user()->name;
                                $firstName = explode(' ', $name)[0];
                            @endphp
                            <a href="{{ route('customer.dashboard') }}" wire:navigate>
                                <p class="font-bold text-gray-800">{{ $firstName }}</p>
                            </a>
                        @else
                            <p class="text-[11px] text-gray-400 font-normal">Sign In</p>
                            <p class="font-bold text-gray-800">My Account</p>
                        @endauth
                    </div>
                </a>

                <livewire:layouts.guest.cart-icon />
            </div>
        </div>
    </div>

    <!-- Bottom Row: Navigation Links & Promo -->
    <div class="border-t border-gray-100 bg-primary-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-3.5">
                <!-- Nav Links -->
                <nav class="flex items-center flex-wrap gap-x-6 lg:gap-x-8 overflow-y-hidden overflow-x-auto">
                    <a href="{{ route('home') }}"
                        class="text-sm font-medium text-white hover:text-gray-50 transition-colors"
                        wire:navigate>Home</a>
                    <!-- <a href="{{ route('track-order') }}"
                        class="text-sm font-medium text-white hover:text-gray-50 transition-colors" wire:navigate>Track
                        Order</a> -->
                    <a href="{{ route('shop') }}"
                        class="text-sm font-medium text-white hover:text-gray-50 transition-colors"
                        wire:navigate>Shop</a>
                    <a href="{{ route('telehealth') }}"
                        class="text-sm font-medium text-white hover:text-gray-50 transition-colors" wire:navigate>Talk
                        to Pharmacist</a>

                </nav>

                <!-- Promo Text -->
                <div class="hidden md:flex items-center gap-x-3 text-sm font-medium text-white">
                    <div class="p-1 rounded-full bg-pink-50 text-pink-500">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5" />
                            <path d="M9 18h6" />
                            <path d="M10 22h4" />
                        </svg>
                    </div>
                    <span>Special up to <span class="text-pink-500 font-bold text-base">60% Off</span> all item</span>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- ========== END HEADER ========== -->