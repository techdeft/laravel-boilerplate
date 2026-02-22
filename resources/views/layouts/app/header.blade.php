{{-- ============================================================
HEADER — top bar for the app layout (mobile toggle + actions)
============================================================ --}}
<header class="sticky top-0 z-30 flex items-center gap-4 h-16 px-4 sm:px-6
               bg-background/80 backdrop-blur-sm border-b border-gray-200">

    {{-- ── Mobile hamburger (triggers sidebar open via parent x-data) ── --}}
    <button @click="open = true" class="lg:hidden flex items-center justify-center size-9 rounded-lg border border-gray-200
               text-muted-foreground hover:bg-gray-100 hover:text-foreground transition-colors">
        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- ── Page title / breadcrumb ──────────────────────────────── --}}
    <div class="flex-1 min-w-0">
        {{-- Show $title if passed from parent layout, else fall back to route name --}}
        @isset($title)
            <h1 class="text-base font-semibold text-foreground truncate">{{ $title }}</h1>
        @else
            <h1 class="text-base font-semibold text-foreground truncate">
                {{ ucwords(str_replace(['-', '_', '.'], ' ', request()->route()->getName() ?? 'Dashboard')) }}
            </h1>
        @endisset
    </div>

    {{-- ── Right-side actions ───────────────────────────────────── --}}
    <div class="flex items-center gap-2 shrink-0">

        {{-- Search button --}}
        <button class="flex items-center justify-center size-9 rounded-lg border border-gray-200
                   text-muted-foreground hover:bg-gray-100 hover:text-foreground transition-colors">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>

        {{-- Notifications --}}
        <div x-data="{ notifOpen: false }" class="relative">
            <button @click="notifOpen = !notifOpen" class="relative flex items-center justify-center size-9 rounded-lg border border-gray-200
                       text-muted-foreground hover:bg-gray-100 hover:text-foreground transition-colors">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                {{-- Unread badge --}}
                <span
                    class="absolute top-1.5 right-1.5 size-2 rounded-full bg-primary-500 ring-2 ring-background"></span>
            </button>

            {{-- Notification panel --}}
            <div x-show="notifOpen" @click.outside="notifOpen = false" x-transition
                class="absolute end-0 mt-2 w-80 bg-card border border-gray-200 rounded-xl shadow-lg overflow-hidden z-50">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-foreground">Notifications</h3>
                    <span class="text-xs text-primary-500 cursor-pointer hover:underline">Mark all read</span>
                </div>
                <ul class="divide-y divide-gray-100 max-h-64 overflow-y-auto">
                    <li class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                        <div
                            class="mt-0.5 flex items-center justify-center size-8 rounded-full bg-primary-100 text-primary-600 shrink-0">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-foreground">Bot trade completed</p>
                            <p class="text-xs text-muted-foreground mt-0.5">Your Alpha Bot closed a trade with +$42.00
                                profit.</p>
                            <p class="text-xs text-muted-foreground mt-1">2 min ago</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                        <div
                            class="mt-0.5 flex items-center justify-center size-8 rounded-full bg-green-100 text-green-600 shrink-0">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-foreground">Deposit confirmed</p>
                            <p class="text-xs text-muted-foreground mt-0.5">$500.00 has been credited to your wallet.
                            </p>
                            <p class="text-xs text-muted-foreground mt-1">1 hour ago</p>
                        </div>
                    </li>
                </ul>
                <div class="px-4 py-3 border-t border-gray-200 text-center">
                    <a href="#" class="text-xs text-primary-500 hover:underline font-medium">View all notifications</a>
                </div>
            </div>
        </div>

        {{-- User avatar (desktop — quick access) --}}
        <div x-data="{ avatarOpen: false }" class="relative hidden lg:block">
            <button @click="avatarOpen = !avatarOpen" class="flex items-center justify-center size-9 rounded-full bg-primary-500 text-white text-xs font-semibold
                       hover:bg-primary-600 transition-colors ring-2 ring-transparent hover:ring-primary-200">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </button>

            <div x-show="avatarOpen" @click.outside="avatarOpen = false" x-transition
                class="absolute end-0 mt-2 w-52 bg-card border border-gray-200 rounded-xl shadow-lg overflow-hidden z-50">
                <div class="px-4 py-3 border-b border-gray-200">
                    <p class="text-sm font-semibold text-foreground truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-muted-foreground truncate">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" wire:navigate
                    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-foreground hover:bg-gray-100 transition-colors">
                    <svg class="size-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
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
</header>