<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest.app')] class extends Component {
    public function with()
    {
        $user = Auth::user();
        return [
            'user' => $user,
            'orderCount' => $user->orders()->count(),
            'recentOrders' => $user->orders()->latest()->take(3)->get(),
            'prescriptionCount' => 0, // Placeholder
            'walletBalance' => 0.00, // Placeholder
            'wishlistCount' => $user->wishlist()->count(),
        ];
    }
}; ?>

<x-slot name="title">Customer Dashboard</x-slot>
<x-slot name="description">Manage your orders and profile</x-slot>

<div class="bg-[#f1f1f2] min-h-screen pb-20 lg:pb-12">
    <div class="max-w-[1184px] mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4 items-start">
            <!-- Sidebar -->
            <aside class="hidden lg:block w-full lg:w-[240px] flex-shrink-0">
                @include('customer.sidebar')
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 w-full space-y-4">
                <div class="bg-white rounded shadow-sm overflow-hidden p-6 mb-4">
                    <h2 class="text-[18px] font-medium text-gray-900 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('shop') }}" class="group p-4 bg-blue-50/50 rounded-xl border border-blue-100 hover:bg-blue-900 transition-all duration-300">
                            <i class="fa-solid fa-cart-plus text-blue-900 group-hover:text-white transition-colors mb-3 block text-xl"></i>
                            <p class="text-gray-900 font-black text-xs uppercase tracking-tight group-hover:text-white transition-colors">Start Shopping</p>
                            <p class="text-[10px] text-gray-400 group-hover:text-blue-100 transition-colors uppercase font-bold mt-1">Explore Products</p>
                        </a>

                        <a href="{{ route('customer.wishlist') }}" class="group p-4 bg-pink-50/50 rounded-xl border border-pink-100 hover:bg-pink-500 transition-all duration-300">
                            <i class="fa-solid fa-heart text-pink-500 group-hover:text-white transition-colors mb-3 block text-xl"></i>
                            <p class="text-gray-900 font-black text-xs uppercase tracking-tight group-hover:text-white transition-colors">Saved Items</p>
                            <p class="text-[10px] text-gray-400 group-hover:text-pink-100 transition-colors uppercase font-bold mt-1">{{ $wishlistCount }} Items</p>
                        </a>

                        <a href="{{ route('track-order') }}" class="group p-4 bg-orange-50/50 rounded-xl border border-orange-100 hover:bg-orange-500 transition-all duration-300">
                            <i class="fa-solid fa-truck-fast text-orange-500 group-hover:text-white transition-colors mb-3 block text-xl"></i>
                            <p class="text-gray-900 font-black text-xs uppercase tracking-tight group-hover:text-white transition-colors">Track Order</p>
                            <p class="text-[10px] text-gray-400 group-hover:text-orange-100 transition-colors uppercase font-bold mt-1">Real-time update</p>
                        </a>

                        <a href="{{route('telehealth')}}" class="group p-4 bg-emerald-50/50 rounded-xl border border-emerald-100 hover:bg-emerald-600 transition-all duration-300">
                            <i class="fa-solid fa-headset text-emerald-600 group-hover:text-white transition-colors mb-3 block text-xl"></i>
                            <p class="text-gray-900 font-black text-xs uppercase tracking-tight group-hover:text-white transition-colors">Health Talk</p>
                            <p class="text-[10px] text-gray-400 group-hover:text-emerald-100 transition-colors uppercase font-bold mt-1">Professional Advice</p>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded shadow-sm overflow-hidden p-6">
                    <h1 class="text-[18px] font-medium text-gray-900 mb-4">Account Overview</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Account Details Card -->
                        <div class="border border-gray-100 rounded p-4 flex flex-col h-full">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-3">
                                <h2 class="text-[14px] font-bold text-gray-900 uppercase">Account Details</h2>
                                <a href="{{ route('customer.profile') }}"
                                    class="text-primary-500 hover:text-[#e07e1b]"><i
                                        class="fa-solid fa-pen text-xs"></i></a>
                            </div>
                            <div class="flex-1">
                                <p class="text-[14px] font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-[14px] text-gray-500">{{ $user->email }}</p>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('customer.security') }}"
                                    class="text-[13px] font-bold text-primary-500 uppercase hover:underline">Change
                                    Password</a>
                            </div>
                        </div>

                        <!-- Address Book Card -->
                        <div class="border border-gray-100 rounded p-4 flex flex-col h-full">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-3">
                                <h2 class="text-[14px] font-bold text-gray-900 uppercase">Address Book</h2>
                                <a href="{{ route('customer.addresses') }}"
                                    class="text-primary-500 hover:text-[#e07e1b]"><i
                                        class="fa-solid fa-pen text-xs"></i></a>
                            </div>
                            <div class="flex-1">
                                <p class="text-[13px] text-gray-500 font-bold uppercase mb-1">Your default shipping
                                    address:</p>
                                @if($user->defaultAddress())
                                    <p class="text-[14px] text-gray-900 font-medium">
                                        {{ $user->defaultAddress()->first_name }} {{ $user->defaultAddress()->last_name }}
                                    </p>
                                    <p class="text-[14px] text-gray-600 line-clamp-2 mt-1">
                                        {{ $user->defaultAddress()->address_line }}</p>
                                    <p class="text-[14px] text-gray-600">{{ $user->defaultAddress()->city }},
                                        {{ $user->defaultAddress()->region }}</p>
                                    <p class="text-[14px] text-gray-600 mt-2">{{ $user->defaultAddress()->phone }}</p>
                                @else
                                    <p class="text-[14px] text-gray-500 italic">No default shipping address available.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Medmall Store Credit -->
                        <div class="border border-gray-100 rounded p-4 flex flex-col h-full">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-3">
                                <h2 class="text-[14px] font-bold text-gray-900 uppercase">Medmall Store Credit</h2>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-10 rounded-full bg-blue-50 flex items-center justify-center text-[#2b1770]">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <p class="text-[14px] font-bold text-gray-900">₦ {{ number_format($walletBalance, 0) }}
                                </p>
                            </div>
                        </div>

                        <!-- Newsletter Preferences -->
                        <div class="border border-gray-100 rounded p-4 flex flex-col h-full">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-3">
                                <h2 class="text-[14px] font-bold text-gray-900 uppercase">Newsletter Preferences</h2>
                                <a href="#" class="text-primary-500 hover:text-[#e07e1b]"><i
                                        class="fa-solid fa-pen text-xs"></i></a>
                            </div>
                            <div class="flex-1">
                                <p class="text-[14px] text-gray-900">You are currently not subscribed to any of our
                                    newsletters.</p>
                            </div>
                            <div class="mt-4">
                                <p class="text-[12px] text-gray-400">Edit your preferences to stay updated on our latest
                                    offers.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Section -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-[16px] font-bold text-gray-900 uppercase">Recent Orders</h3>
                        @if($orderCount > 0)
                            <a href="{{ route('customer.orders') }}"
                                class="text-[13px] font-bold text-[#2b1770] uppercase hover:underline">View All</a>
                        @endif
                    </div>

                    @if($recentOrders->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($recentOrders as $recent)
                                <div class="p-4 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="size-12 bg-gray-50 rounded flex-shrink-0 flex items-center justify-center border border-gray-100">
                                            <i class="fa-solid fa-box text-gray-300"></i>
                                        </div>
                                        <div>
                                            <p class="text-[14px] font-medium text-gray-900">Order #{{ $recent->order_number }}
                                            </p>
                                            <p class="text-[12px] text-gray-500">{{ $recent->created_at->format('d M, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[14px] font-black text-[#2b1770]">₦
                                            {{ number_format($recent->total_amount, 0) }}</p>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-700',
                                                'processing' => 'bg-blue-100 text-blue-700',
                                                'shipped' => 'bg-indigo-100 text-indigo-700',
                                                'delivered' => 'bg-green-100 text-green-700',
                                                'completed' => 'bg-green-100 text-green-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                'failed' => 'bg-red-100 text-red-700',
                                            ];
                                            $statusClass = $statusClasses[$recent->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $statusClass }}">
                                            {{ $recent->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-10 flex flex-col items-center justify-center text-center">
                            <div class="size-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fa-solid fa-box-open text-3xl text-gray-200"></i>
                            </div>
                            <p class="text-[16px] font-medium text-gray-900 mb-1">You have no recent orders</p>
                            <p class="text-[14px] text-gray-500 mb-6 max-w-sm">When you place an order, it will appear here
                                for you to track and manage.</p>
                            <a href="{{ route('home') }}"
                                class="px-8 py-3 bg-[#2b1770] text-white rounded font-bold text-[14px] uppercase tracking-wide hover:bg-[#3f238f] transition-all shadow-sm">
                                Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</div>