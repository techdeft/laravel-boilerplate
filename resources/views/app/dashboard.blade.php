<?php

use Livewire\Volt\Component;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app.app')] class extends Component {
    public function with()
    {
        return [
            'totalRevenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'totalCustomers' => User::role('user')->count(),
            'recentOrders' => Order::with('user')->latest()->take(6)->get(),
            'topProducts' => OrderItem::query()
                ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->with('product')
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get(),
            'customerGrowth' => User::role('user')
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
        ];
    }
}; ?>

<div class="p-6 space-y-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="text-gray-500 text-sm">Welcome back! Here's what's happening with your store today.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1.5 rounded-full">
                Last updated: {{ now()->format('M d, H:i') }}
            </span>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Revenue --}}
        <div
            class="bg-white p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:border-blue-200 transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="size-20 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17.93c-3.95-.49-7-3.85-7-7.93s3.05-7.44 7-7.93v15.86zm2-15.86c3.95.49 7 3.85 7 7.93s-3.05 7.44-7 7.93V4.07z" />
                </svg>
            </div>
            <div class="space-y-4">
                <div class="size-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="fa-solid fa-naira-sign text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">₦{{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="flex items-center gap-1 text-[10px] font-bold text-green-600">
                    <i class="fa-solid fa-arrow-up"></i>
                    <span>Paid Transactions</span>
                </div>
            </div>
        </div>

        {{-- Orders --}}
        <div
            class="bg-white p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:border-purple-200 transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="size-20 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                </svg>
            </div>
            <div class="space-y-4">
                <div class="size-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <i class="fa-solid fa-shopping-bag text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Orders</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalOrders) }}</p>
                        <span class="text-xs text-orange-500 font-medium">({{ $pendingOrders }} pending)</span>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-[10px] font-bold text-gray-500">
                    <span>Lifetime transactions</span>
                </div>
            </div>
        </div>

        {{-- Customers --}}
        <div
            class="bg-white p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:border-emerald-200 transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="size-20 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.61.02-.95.05 1.14.85 1.95 2.06 1.95 3.45V19h5v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                </svg>
            </div>
            <div class="space-y-4">
                <div class="size-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalCustomers) }}</p>
                </div>
                <div class="flex items-center gap-1 text-[10px] font-bold text-emerald-600">
                    <i class="fa-solid fa-plus"></i>
                    <span>{{ $customerGrowth }} new this month</span>
                </div>
            </div>
        </div>

        {{-- Performance --}}
        <div
            class="bg-white p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:border-orange-200 transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="size-20 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.09-4-4L2 17.08l1.5 1.41z" />
                </svg>
            </div>
            <div class="space-y-4">
                <div class="size-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Products</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ App\Models\Product::count() }}</p>
                </div>
                <div class="flex items-center gap-1 text-[10px] font-bold text-orange-600">
                    <span>Active in inventory</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Orders --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" wire:navigate
                    class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">View All</a>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Order
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Customer
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                    Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900">#{{ $order->order_number }}</span>
                                            <span
                                                class="text-[10px] text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="size-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-[10px] font-bold uppercase">
                                                {{ substr($order->user->name ?? 'Guest', 0, 2) }}
                                            </div>
                                            <span
                                                class="text-xs font-medium text-gray-700">{{ $order->user->name ?? 'Guest' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span @class([
                                            'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider',
                                            'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                                            'bg-blue-100 text-blue-700' => $order->status === 'processing',
                                            'bg-green-100 text-green-700' => $order->status === 'completed',
                                            'bg-red-100 text-red-700' => in_array($order->status, ['cancelled', 'failed']),
                                        ])>
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-sm text-gray-900">
                                        ₦{{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic text-sm">
                                        No orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Top Selling Products</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 space-y-6">
                @forelse($topProducts as $item)
                    <div class="flex items-center gap-4 group">
                        <div class="size-12 rounded-xl bg-gray-50 flex-shrink-0 overflow-hidden border border-gray-100">
                            <img src="{{ Storage::url($item->product->image_path) }}" alt=""
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->total_sold }} units sold</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-emerald-600">
                                ₦{{ number_format($item->product->price * $item->total_sold, 2) }}</p>
                            <div class="w-16 h-1 bg-gray-100 rounded-full mt-1 overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full"
                                    style="width: {{ min(100, ($item->total_sold / ($topProducts->first()->total_sold ?? 1)) * 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-400 italic text-sm">
                        No product data yet.
                    </div>
                @endforelse

                @if($topProducts->isNotEmpty())
                    <div class="pt-4 border-t border-gray-50">
                        <a href="{{ route('admin.products.index') }}" wire:navigate
                            class="flex items-center justify-center gap-2 w-full py-2 text-xs font-bold text-gray-600 hover:text-blue-600 transition-colors group">
                            Full Inventory
                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Activity Feed Placeholder --}}
            <div class="bg-blue-600 rounded-2xl p-6 text-white relative overflow-hidden group">
                <div class="relative z-10 space-y-4">
                    <h4 class="text-sm font-bold">Pro Tip</h4>
                    <p class="text-xs text-blue-100 leading-relaxed italic">
                        "Delivered products show up in 'Completed' status. Keep an eye on pending orders to maintain
                        high customer satisfaction."
                    </p>
                    <button class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        Inventory Guide
                    </button>
                </div>
                <div class="absolute -bottom-4 -right-4 opacity-10 group-hover:rotate-12 transition-transform">
                    <i class="fa-solid fa-lightbulb text-6xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>