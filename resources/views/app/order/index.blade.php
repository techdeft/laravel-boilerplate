<?php

use Livewire\Volt\Component;
use App\Models\Order;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $status = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function with()
    {
        $query = Order::with(['user'])->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($uq) {
                        $uq->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        return [
            'orders' => $query->paginate(15),
        ];
    }
}; ?>

<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-none outline-none">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 border-none outline-none">Order Management</h1>
            <p class="text-gray-500 text-sm">Monitor and manage all customer orders on the platform.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row gap-4 justify-between">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search by order number or customer..."
                    class="block w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
            </div>

            <div class="flex items-center gap-2">
                <select wire:model.live="status"
                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none">
                            Order Details</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none">
                            Customer</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none">
                            Date</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none">
                            Payment</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none text-center">
                            Status</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none text-right">
                            Total</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-none outline-none text-right">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 border-none outline-none">
                                    #{{ $order->order_number }}</div>
                                <div class="text-[10px] text-gray-400 font-mono uppercase border-none outline-none">
                                    {{ $order->delivery_method }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 border-none outline-none">
                                    {{ $order->user->name }}</div>
                                <div class="text-xs text-gray-500 border-none outline-none">{{ $order->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="text-xs text-gray-600 border-none outline-none">{{ $order->created_at->format('M d, Y') }}</span>
                                <div class="text-[10px] text-gray-400 border-none outline-none">
                                    {{ $order->created_at->format('H:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span
                                        class="text-xs font-medium text-gray-700 capitalize border-none outline-none">{{ $order->payment_method }}</span>
                                    <span @class([
                                        'text-[10px] font-bold uppercase border-none outline-none',
                                        'text-green-600' => $order->payment_status === 'paid',
                                        'text-yellow-600' => $order->payment_status === 'unpaid',
                                        'text-red-600' => $order->payment_status === 'failed',
                                    ])>
                                        {{ $order->payment_status }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border-none outline-none',
                                    'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                                    'bg-blue-100 text-blue-700' => $order->status === 'processing',
                                    'bg-indigo-100 text-indigo-700' => $order->status === 'shipped',
                                    'bg-green-100 text-green-700' => in_array($order->status, ['delivered', 'completed']),
                                    'bg-red-100 text-red-700' => $order->status === 'cancelled',
                                ])>
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="text-sm font-bold text-gray-900 border-none outline-none">₦{{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.details', $order->order_number) }}" wire:navigate
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors">
                                    <span>Manage</span>
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-24 text-center border-none outline-none">
                                <div class="flex flex-col items-center gap-2 border-none outline-none">
                                    <div class="p-4 bg-gray-50 rounded-full border-none outline-none">
                                        <svg class="size-10 text-gray-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium border-none outline-none">No orders found matching
                                        your criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-6 border-t border-gray-100 border-none outline-none">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>