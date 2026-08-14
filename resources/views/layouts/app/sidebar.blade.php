{{-- ============================================================
SIDEBAR — fixed left navigation for the app layout
============================================================ --}}
<aside id="sidebar" x-data="{ open: false }" @keydown.escape.window="open = false"
    :class="open ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 start-0 z-50 w-64 bg-background border-e border-gray-200
           flex flex-col transition-transform duration-300
           lg:translate-x-0">

    {{-- ── Logo ──────────────────────────────────────────────── --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-200 shrink-0">
        <div class="flex items-center justify-center size-8 rounded-lg bg-blue-900">
            <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.423 15.621a2 2 0 00.394-3.115l-1.233-1.233a2 2 0 010-2.828l1.233-1.233a2 2 0 00-.394-3.115 2 2 0 00-3.115-.394l-1.233 1.233a2 2 0 01-2.828 0l-1.233-1.233a2 2 0 00-3.115.394 2 2 0 00-.394 3.115l1.233 1.233a2 2 0 010 2.828l-1.233 1.233a2 2 0 00.394 3.115 2 2 0 003.115.394l1.233-1.233a2 2 0 012.828 0l1.233 1.233a2 2 0 003.115-.394zM10 10l-1 1" />
            </svg>
        </div>
        <span class="text-base font-semibold text-foreground tracking-tight">MedMall Admin</span>
    </div>

    {{-- ── Scrollable nav ────────────────────────────────────── --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">

        {{-- Main --}}
        <div>
            <p class="px-3 mb-2 text-[0.65rem] font-semibold uppercase tracking-widest text-muted-foreground">
                Main
            </p>
            <ul class="space-y-0.5">
                <li>
                    <a href="{{ route('dashboard') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('dashboard'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('dashboard'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.media') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-blue-50 text-blue-900 font-semibold' => request()->routeIs('admin.media'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('admin.media'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Media Library
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.home.slider') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-blue-50 text-blue-900 font-semibold' => request()->routeIs('admin.home.slider'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('admin.home.slider'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        Home Slider
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-blue-50 text-blue-900 font-semibold' => request()->routeIs('admin.orders.index') || request()->routeIs('admin.orders.details'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !(request()->routeIs('admin.orders.index') || request()->routeIs('admin.orders.details')),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Orders
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-blue-50 text-blue-900 font-semibold' => request()->routeIs('admin.products.index') || request()->routeIs('admin.products.create') || request()->routeIs('admin.products.edit'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !(request()->routeIs('admin.products.index') || request()->routeIs('admin.products.create') || request()->routeIs('admin.products.edit')),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Products
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.customers.index') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-blue-50 text-blue-900 font-semibold' => request()->routeIs('admin.customers.index') || request()->routeIs('admin.customers.details'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !(request()->routeIs('admin.customers.index') || request()->routeIs('admin.customers.details')),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Customers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.delivery-fees') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-blue-50 text-blue-900 font-semibold' => request()->routeIs('admin.delivery-fees'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('admin.delivery-fees'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.806H14.25M16.5 18.75h-2.25m0-11.25v11.25m-14.25-4.5h14.25" />
                        </svg>
                        Delivery Fees
                    </a>
                </li>
                <li x-data="{ open: @js(request()->routeIs('admin.bookings.*')) }">
                    <button @click="open = !open"
                        :class="open ? 'text-foreground bg-gray-50' : 'text-muted-foreground hover:bg-gray-100 hover:text-foreground'"
                        class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group">
                        <div class="flex items-center gap-3">
                            <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Bookings
                        </div>
                        <svg class="size-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 px-2 space-y-1">
                        <li>
                            <a href="{{ route('admin.bookings.index') }}" wire:navigate @class([
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors',
                                'text-blue-700 font-semibold bg-blue-50/50' => request()->routeIs('admin.bookings.index'),
                                'text-muted-foreground hover:text-foreground hover:bg-gray-50' => !request()->routeIs('admin.bookings.index'),
                            ])>
                                <div class="size-1.5 rounded-full border border-current"></div>
                                All Bookings
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.bookings.settings') }}" wire:navigate @class([
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors',
                                'text-blue-700 font-semibold bg-blue-50/50' => request()->routeIs('admin.bookings.settings'),
                                'text-muted-foreground hover:text-foreground hover:bg-gray-50' => !request()->routeIs('admin.bookings.settings'),
                            ])>
                                <div class="size-1.5 rounded-full border border-current"></div>
                                Booking Settings
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('app.settings', ['tab' => 'system']) }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-blue-50 text-blue-900 font-semibold' => request()->query('tab') === 'system',
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => request()->query('tab') !== 'system',
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        System Settings
                    </a>
                </li>
                <li
                    x-data="{ open: @js(request()->routeIs('admin.categories') || request()->routeIs('admin.brands')) }">
                    <button @click="open = !open"
                        :class="open ? 'text-foreground bg-gray-50' : 'text-muted-foreground hover:bg-gray-100 hover:text-foreground'"
                        class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group">
                        <div class="flex items-center gap-3">
                            <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Catalog
                        </div>
                        <svg class="size-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 px-2 space-y-1">
                        <li>
                            <a href="{{ route('admin.categories') }}" wire:navigate @class([
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors',
                                'text-blue-700 font-semibold bg-blue-50/50' => request()->routeIs('admin.categories'),
                                'text-muted-foreground hover:text-foreground hover:bg-gray-50' => !request()->routeIs('admin.categories'),
                            ])>
                                <div class="size-1.5 rounded-full border border-current"></div>
                                Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.brands') }}" wire:navigate @class([
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors',
                                'text-blue-700 font-semibold bg-blue-50/50' => request()->routeIs('admin.brands'),
                                'text-muted-foreground hover:text-foreground hover:bg-gray-50' => !request()->routeIs('admin.brands'),
                            ])>
                                <div class="size-1.5 rounded-full border border-current"></div>
                                Brands
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>


        {{-- Account --}}
        <div>
            <p class="px-3 mb-2 text-[0.65rem] font-semibold uppercase tracking-widest text-muted-foreground">
                Account
            </p>
            <ul class="space-y-0.5">
                <li>
                    <a href="{{ route('app.settings') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('app.settings'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('app.settings'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                </li>

            </ul>
        </div>
    </nav>

    {{-- ── User section ───────────────────────────────────────── --}}
    <div class="shrink-0 border-t border-gray-200 px-3 py-3">
        <div x-data="{ menuOpen: false }" class="relative">
            <button @click="menuOpen = !menuOpen"
                class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm text-foreground hover:bg-gray-100 transition-colors">
                <div
                    class="flex items-center justify-center size-8 rounded-full bg-primary-500 text-white text-xs font-semibold shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 text-start min-w-0">
                    <p class="text-sm font-medium text-foreground truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-muted-foreground truncate">{{ auth()->user()->email }}</p>
                </div>
                <svg class="size-4 text-muted-foreground shrink-0 transition-transform"
                    :class="menuOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Dropdown --}}
            <div x-show="menuOpen" @click.outside="menuOpen = false" x-transition
                class="absolute bottom-full left-0 right-0 mb-1 bg-card border border-gray-200 rounded-lg shadow-lg overflow-hidden z-10">
                <a href="{{ route('app.settings') }}" wire:navigate
                    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-foreground hover:bg-gray-100 transition-colors">
                    <svg class="size-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    My Profile
                </a>
                <a href="{{ route('app.settings') }}" wire:navigate
                    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-foreground hover:bg-gray-100 transition-colors">
                    <svg class="size-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
                <div class="border-t border-gray-200"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- ── Mobile backdrop ─────────────────────────────────────────── --}}
<div x-show="open" @click="open = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"
    x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>