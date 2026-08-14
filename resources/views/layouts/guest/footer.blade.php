<!-- ========== FOOTER ========== -->
<footer class="w-full bg-[#0a0a0a] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Top Section: 4-Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
            <!-- About Us -->
            <div class="space-y-6">
                <h3 class="text-xl font-bold tracking-tight">About Us</h3>
                <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                    Medmall is a one stop shop for medicines, beauty and cosmetics, groceries, bread and other
                    confectioneries
                </p>
                <div class="flex items-center gap-x-2 pt-4">
                    <div class="flex items-center gap-x-2">
                        <img src="{{ asset('images/logo_white.png') }}" alt="Logo" class="h-10">
                    </div>
                </div>
            </div>

            <!-- Quick Links & Opening Hours -->
            <div class="space-y-6">
                <h3 class="text-xl font-bold tracking-tight">Quick Links</h3>
                <ul class="space-y-4">
                    <li><a href="{{ route('register') }}"
                            class="text-gray-400 hover:text-white transition-colors text-sm" wire:navigate>Register</a>
                    </li>
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors text-sm"
                            wire:navigate>Login</a></li>
                    <li><a href="{{ route('telehealth') }}"
                            class="text-gray-400 hover:text-white transition-colors text-sm" wire:navigate>Talk to a
                            Pharmacist</a></li>
                </ul>
                <div class="pt-8 space-y-2">
                    <p class="text-gray-300 text-sm font-medium">Monday - Sundays, 8:00AM to 9:00PM</p>
                </div>
            </div>

            <!-- Company -->
            <div class="space-y-6">
                <h3 class="text-xl font-bold tracking-tight">Company</h3>
                <ul class="space-y-4">
                    <li><a href="{{ route('about') }}"
                            class="text-gray-400 hover:text-white transition-colors text-sm">About Us</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="text-gray-400 hover:text-white transition-colors text-sm" wire:navigate>Contact
                            Us</a></li>
                    <li><a href="{{ route('shop') }}" class="text-gray-400 hover:text-white transition-colors text-sm"
                            wire:navigate>Shop</a></li>
                </ul>
            </div>

            <!-- My Account -->
            <div class="space-y-6">
                <h3 class="text-xl font-bold tracking-tight">My Account</h3>
                <ul class="space-y-4">
                    <li><a href="{{ route('customer.dashboard') }}"
                            class="text-gray-400 hover:text-white transition-colors text-sm" wire:navigate>Profile</a>
                    </li>
                    <li><a href="{{ route('track-order') }}"
                            class="text-gray-400 hover:text-white transition-colors text-sm" wire:navigate>Track
                            Order</a>
                    </li>
                    <li><a href="{{ route('shop') }}" class="text-gray-400 hover:text-white transition-colors text-sm"
                            wire:navigate>Cart</a></li>
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <div class="mt-16 pt-8 border-t border-gray-800">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <!-- Copyright -->
                <p class="text-[11px] text-gray-500 order-2 md:order-1">
                    © 2023 Medmall pharmacy and store. All rights reserved.
                </p>

                <!-- Social Icons -->
                <div class="flex items-center gap-x-8 order-1 md:order-2">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.016 10.016 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-4.821 8.39a11.11 11.11 0 01-5.632-1.531l-.403-.24-4.188 1.097 1.117-4.085-.262-.418a11.08 11.08 0 01-1.701-5.91c0-6.133 4.992-11.125 11.127-11.125a11.12 11.12 0 0111.125 11.125c0 6.135-4.992 11.129-11.126 11.129m0-22c-6.115 0-11.088 4.973-11.088 11.088 0 2.227.658 4.301 1.794 6.046L0 24l6.19-1.623a11.05 11.05 0 005.18 1.288c6.115 0 11.088-4.973 11.088-11.088A11.09 11.09 0 0012.651 2.375z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.668-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                        </svg>
                    </a>
                </div>

                <!-- Vaticore Technologies -->
                <p class="text-[11px] text-gray-500 order-3">
                    By Vaticore Technologies
                </p>
            </div>
        </div>
    </div>

    <!-- Scroll to top button -->
    <div class="fixed bottom-8 right-8 z-50">
        <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            class="size-11 flex items-center justify-center rounded-full bg-white text-black shadow-lg hover:bg-gray-200 transition-all border border-gray-100">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m18 15-6-6-6 6" />
            </svg>
        </button>
    </div>
</footer>
<!-- ========== END FOOTER ========== -->