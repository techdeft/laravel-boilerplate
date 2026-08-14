<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app.app')] class extends Component {
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user->load(['orders', 'addresses']);
    }

    public function with()
    {
        return [
            'recentOrders' => $this->user->orders()->latest()->take(5)->with(['items.product'])->get(),
            'totalSpent' => $this->user->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'ordersCount' => $this->user->orders()->count(),
            'pendingOrders' => $this->user->orders()->whereIn('status', ['pending', 'processing'])->count(),
        ];
    }
}; ?>

<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 ">
        <a href="{{ route('admin.customers.index') }}" wire:navigate
            class="p-2 hover:bg-gray-100 rounded-xl transition-all text-gray-400 hover:text-gray-900 border-none outline-none">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 border-none outline-none">Customer Details</h1>
            <p class="text-gray-500 text-sm">Review detailed information and activity for {{ $user->name }}.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-2xl border border-gray-100  flex flex-col items-center text-center">
                <div
                    class="size-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold uppercase mb-4 border-none outline-none">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <h2 class="text-xl font-bold text-gray-900 border-none outline-none">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 border-none outline-none">{{ $user->email }}</p>
                <div
                    class="mt-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700 border-none outline-none">
                    {{ $user->status ?? 'Active' }}
                </div>

                <div class="w-full grid grid-cols-2 gap-4 mt-8 pt-8 border-t border-gray-50">
                    <div class="text-left">
                        <p
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-none outline-none">
                            Joined On</p>
                        <p class="text-sm font-bold text-gray-900 border-none outline-none">
                            {{ $user->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-none outline-none">
                            Customer ID</p>
                        <p class="text-sm font-bold text-gray-900 border-none outline-none">
                            #{{ substr($user->uuid, 0, 8) }}</p>
                    </div>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100  space-y-4">
                <h3 class="text-sm font-bold text-gray-900 border-none outline-none">Contact Information</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="size-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="border-none outline-none">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="size-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span class="border-none outline-none">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>

            {{-- Saved Addresses --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100  space-y-4">
                <h3 class="text-sm font-bold text-gray-900 border-none outline-none">Delivery Addresses</h3>
                <div class="space-y-4">
                    @forelse($user->addresses as $address)
                        <div class="p-4 bg-gray-50 rounded-xl relative group">
                            @if($address->is_default)
                                <span
                                    class="absolute top-2 right-2 px-2 py-0.5 bg-blue-100 text-blue-600 text-[8px] font-bold uppercase rounded-md border-none outline-none">Default</span>
                            @endif
                            <p class="text-sm font-bold text-gray-800 border-none outline-none">{{ $address->first_name }}
                                {{ $address->last_name }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1 border-none outline-none">{{ $address->address_line }}</p>
                            <p class="text-xs text-gray-500 border-none outline-none">{{ $address->city }},
                                {{ $address->region }}
                            </p>
                            @if($address->additional_info)
                                <p class="text-[10px] text-gray-400 mt-1 italic border-none outline-none">
                                    {{ $address->additional_info }}
                                </p>
                            @endif
                            <div class="mt-2 text-[10px] text-gray-500 flex flex-wrap gap-2 border-none outline-none">
                                <span class="flex items-center gap-1 border-none outline-none">
                                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $address->phone }}
                                </span>
                                @if($address->additional_phone)
                                    <span class="flex items-center gap-1 border-none outline-none">
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $address->additional_phone }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic border-none outline-none">No saved addresses found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Main Stats & Activity --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 ">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-none outline-none">
                        Total Spent</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1 border-none outline-none">
                        ₦{{ number_format($totalSpent, 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 ">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-none outline-none">
                        Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1 border-none outline-none">{{ $ordersCount }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 ">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-none outline-none">
                        Pending Orders</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1 border-none outline-none">{{ $pendingOrders }}</p>
                </div>
            </div>

            {{-- Recent Orders Table --}}
            <div class="bg-white rounded-2xl border border-gray-100  overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900 border-none outline-none">Recent Activity</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none">
                                    Order ID</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none">
                                    Items</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none">
                                    Date</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none text-center">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none text-right">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm font-bold text-gray-900 border-none outline-none">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex -space-x-2">
                                            @foreach($order->items->take(3) as $item)
                                                <div
                                                    class="size-8 rounded-lg border-2 border-white bg-gray-100 overflow-hidden ">
                                                    <img src="{{ Storage::url($item->product->image_path) }}"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            @endforeach
                                            @if($order->items->count() > 3)
                                                <div
                                                    class="size-8 rounded-lg border-2 border-white bg-gray-50 flex items-center justify-center text-[10px] font-bold text-gray-400 border-none outline-none">
                                                    +{{ $order->items->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm text-gray-600 border-none outline-none">{{ $order->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span @class([
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border-none outline-none',
                                            'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                                            'bg-blue-100 text-blue-700' => $order->status === 'processing',
                                            'bg-green-100 text-green-700' => $order->status === 'completed',
                                            'bg-red-100 text-red-700' => in_array($order->status, ['cancelled', 'failed']),
                                        ])>
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-sm font-bold text-gray-900 border-none outline-none">₦{{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-12 text-center text-gray-400 italic text-sm border-none outline-none">
                                        No recent orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>