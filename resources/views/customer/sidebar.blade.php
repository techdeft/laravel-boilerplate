<!-- Desktop Sidebar -->
<div class="hidden lg:block bg-white rounded shadow-sm overflow-hidden py-2">
    <nav class="flex flex-col">
        <a href="{{ route('customer.dashboard') }}" @class([
            'flex items-center gap-3 px-4 py-3 text-[14px] transition-colors group',
            'bg-gray-100 text-[#2b1770] border-l-4 border-[#2b1770] font-bold' => request()->routeIs('customer.dashboard'),
            'text-gray-600 hover:bg-gray-50 border-l-4 border-transparent' => !request()->routeIs('customer.dashboard')
        ])>
            <i class="fa-solid fa-gauge-high size-5 flex items-center justify-center"></i>
            My Medmall account
        </a>

        <a href="{{ route('customer.orders') }}" @class([
            'flex items-center gap-3 px-4 py-3 text-[14px] transition-colors group',
            'bg-gray-100 text-[#2b1770] border-l-4 border-[#2b1770] font-bold' => request()->routeIs('customer.orders'),
            'text-gray-600 hover:bg-gray-50 border-l-4 border-transparent' => !request()->routeIs('customer.orders')
        ])>
            <i class="fa-solid fa-cart-shopping size-5 flex items-center justify-center"></i>
            Orders
        </a>

        <a href="{{ route('customer.wishlist') }}" @class([
            'flex items-center gap-3 px-4 py-3 text-[14px] transition-colors group',
            'bg-gray-100 text-[#2b1770] border-l-4 border-[#2b1770] font-bold' => request()->routeIs('customer.wishlist'),
            'text-gray-600 hover:bg-gray-50 border-l-4 border-transparent' => !request()->routeIs('customer.wishlist')
        ])>
            <i class="fa-solid fa-heart size-5 flex items-center justify-center"></i>
            Saved Items
        </a>

        <div class="mt-2 pt-2 border-t border-gray-100">
            <a href="{{ route('customer.addresses') }}" @class([
                'flex items-center gap-3 px-4 py-3 text-[14px] transition-colors group',
                'bg-gray-100 text-[#2b1770] border-l-4 border-[#2b1770] font-bold' => request()->routeIs('customer.addresses'),
                'text-gray-600 hover:bg-gray-50 border-l-4 border-transparent' => !request()->routeIs('customer.addresses')
            ])>
                Address Management
            </a>

            <a href="{{ route('customer.profile') }}" @class([
                'flex items-center gap-3 px-4 py-3 text-[14px] transition-colors group',
                'bg-gray-100 text-[#2b1770] border-l-4 border-[#2b1770] font-bold' => request()->routeIs('customer.profile'),
                'text-gray-600 hover:bg-gray-50 border-l-4 border-transparent' => !request()->routeIs('customer.profile')
            ])>
                My Profile
            </a>

            <a href="{{ route('customer.security') }}" @class([
                'flex items-center gap-3 px-4 py-3 text-[14px] transition-colors group',
                'bg-gray-100 text-[#2b1770] border-l-4 border-[#2b1770] font-bold' => request()->routeIs('customer.security'),
                'text-gray-600 hover:bg-gray-50 border-l-4 border-transparent' => !request()->routeIs('customer.security')
            ])>
                Login & Security
            </a>
        </div>

        <div class="mt-2 pt-2 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-4 text-[14px] font-bold text-[#2b1770] hover:bg-gray-50 border-l-4 border-transparent transition-colors text-left uppercase tracking-tight">
                    Logout
                </button>
            </form>
        </div>
    </nav>
</div>

<!-- Mobile Bottom Navigation -->
<div
    class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 pb-safe shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
    <div class="flex items-center justify-around h-16">
        <a href="{{ route('customer.dashboard') }}" @class([
            'flex flex-col items-center justify-center w-full h-full gap-1 transition-all',
            'text-[#2b1770]' => request()->routeIs('customer.dashboard'),
            'text-gray-400' => !request()->routeIs('customer.dashboard')
        ]) wire:navigate>
            <i class="fa-solid fa-gauge-high text-lg"></i>
            <span class="text-[10px] font-black uppercase tracking-tighter">Account</span>
        </a>

        <a href="{{ route('customer.orders') }}" @class([
            'flex flex-col items-center justify-center w-full h-full gap-1 transition-all',
            'text-[#2b1770]' => request()->routeIs('customer.orders'),
            'text-gray-400' => !request()->routeIs('customer.orders')
        ]) wire:navigate>
            <i class="fa-solid fa-cart-shopping text-lg"></i>
            <span class="text-[10px] font-black uppercase tracking-tighter">Orders</span>
        </a>

        <a href="{{ route('customer.wishlist') }}" @class([
            'flex flex-col items-center justify-center w-full h-full gap-1 transition-all',
            'text-[#2b1770]' => request()->routeIs('customer.wishlist'),
            'text-gray-400' => !request()->routeIs('customer.wishlist')
        ]) wire:navigate>
            <i class="fa-solid fa-heart text-lg"></i>
            <span class="text-[10px] font-black uppercase tracking-tighter">Saved</span>
        </a>

        <a href="{{ route('customer.profile') }}" @class([
            'flex flex-col items-center justify-center w-full h-full gap-1 transition-all',
            'text-[#2b1770]' => request()->routeIs('customer.profile', 'customer.addresses', 'customer.security'),
            'text-gray-400' => !request()->routeIs('customer.profile', 'customer.addresses', 'customer.security')
        ]) wire:navigate>
            <i class="fa-solid fa-user text-lg"></i>
            <span class="text-[10px] font-black uppercase tracking-tighter">Profile</span>
        </a>
    </div>
</div>