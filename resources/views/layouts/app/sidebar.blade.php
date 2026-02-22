{{-- ============================================================
SIDEBAR — fixed left navigation for the app layout
============================================================ --}}
<aside id="sidebar" x-data="{ open: false }" @keydown.escape.window="open = false"
    :class="open ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 start-0 z-50 w-64 bg-background border-e border-gray-200
           flex flex-col transition-transform duration-300
           lg:translate-x-0">

    {{-- ── Logo ──────────────────────────────────────────────── --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-200 shrink-0">
        <div class="flex items-center justify-center size-8 rounded-lg bg-primary-500">
            <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        <span class="text-base font-semibold text-foreground tracking-tight">TradeFlow</span>
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
                    <a href="#" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('wallet*'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('wallet*'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Wallet
                    </a>
                </li>
                <li>
                    <a href="#" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('deposit*'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('deposit*'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Deposit
                    </a>
                </li>
                <li>
                    <a href="#" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('withdraw*'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('withdraw*'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m8 8l8-8-8-8" />
                        </svg>
                        Withdraw
                    </a>
                </li>
            </ul>
        </div>

        {{-- Trading --}}
        <div>
            <p class="px-3 mb-2 text-[0.65rem] font-semibold uppercase tracking-widest text-muted-foreground">
                Trading
            </p>
            <ul class="space-y-0.5">
                <li>
                    <a href="#" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('bots*'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('bots*'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2" />
                        </svg>
                        Trading Bots
                    </a>
                </li>
                <li>
                    <a href="#" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('trade-history*'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('trade-history*'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Trade History
                    </a>
                </li>
                <li>
                    <a href="#" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('referrals*'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('referrals*'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Referrals
                    </a>
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
                    <a href="{{ route('profile.edit') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('profile.edit'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('profile.edit'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.settings') }}" wire:navigate @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        'bg-primary-50 text-primary-600 font-semibold' => request()->routeIs('app.settings'),
                        'text-muted-foreground hover:bg-gray-100 hover:text-foreground' => !request()->routeIs('app.settings'),
                    ])>
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        Appearance
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
                <a href="{{ route('profile.edit') }}" wire:navigate
                    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-foreground hover:bg-gray-100 transition-colors">
                    <svg class="size-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    My Profile
                </a>
                <a href="{{ route('appearance.edit') }}" wire:navigate
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