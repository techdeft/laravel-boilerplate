<?php

use Livewire\Volt\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

new #[Layout('layouts.guest.app')] class extends Component {
    use WithPagination;

    public $filter = 'all'; // all, pending, processing, completed, cancelled

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function with()
    {
        $query = Auth::user()->orders()->with(['items.product'])->latest();

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        return [
            'orders' => $query->paginate(10),
        ];
    }
}; ?>

<x-slot name="title">My Orders</x-slot>

<div class="bg-[#f1f1f2] min-h-screen pb-20 lg:pb-12">
    <div class="max-w-[1184px] mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4 items-start">
            <!-- Sidebar -->
            <aside class="hidden lg:block w-full lg:w-[240px] flex-shrink-0">
                @include('customer.sidebar')
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 w-full space-y-4">
                <div class="bg-white rounded shadow-sm overflow-hidden min-h-[600px]">
                    <div class="p-4 border-b border-gray-100">
                        <h1
                            class="text-[18px] font-medium text-gray-900 border-b-2 border-primary-500 inline-block pb-2 px-1">
                            Orders</h1>
                    </div>

                    <!-- Filters -->
                    <div class="flex border-b border-gray-100 overflow-x-auto scrollbar-hide">
                        @foreach(['all' => 'All Orders', 'pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
                            <button wire:click="setFilter('{{ $key }}')" @class([
                                'px-6 py-3 text-[13px] font-bold uppercase transition-colors whitespace-nowrap',
                                'text-[#2b1770] border-b-2 border-[#2b1770]' => $filter === $key,
                                'text-gray-500 hover:text-[#2b1770]' => $filter !== $key
                            ])>
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    @if($orders->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <div class="p-4 sm:p-6 hover:bg-gray-50/50 transition-colors">
                                    <div class="flex flex-col sm:flex-row gap-4 sm:items-center justify-between">
                                        <div class="flex gap-4">
                                            <!-- Order Image Placeholder (First product image) -->
                                            <div
                                                class="size-20 bg-gray-50 rounded flex-shrink-0 overflow-hidden border border-gray-100">
                                                @if($order->items->first() && $order->items->first()->product && $order->items->first()->product->image_path)
                                                    <img src="{{ Storage::url($order->items->first()->product->image_path) }}"
                                                        class="w-full h-full object-contain">
                                                @elseif($order->items->first() && $order->items->first()->product && $order->items->first()->product->external_image_url)
                                                    <img src="{{ $order->items->first()->product->external_image_url }}"
                                                        class="w-full h-full object-contain">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                        <i class="fa-solid fa-box text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-[14px] font-medium text-gray-900 truncate mb-1">
                                                    {{ $order->items->first()->product->name ?? 'Order #' . $order->order_number }}
                                                    @if($order->items->count() > 1)
                                                        <span class="text-gray-400 text-[12px]"> (and
                                                            {{ $order->items->count() - 1 }} other items)</span>
                                                    @endif
                                                </h3>
                                                <p class="text-[12px] text-gray-500 mb-2">Order #{{ $order->order_number }}</p>

                                                <div class="flex items-center gap-2">
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
                                                        $statusClass = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700';
                                                    @endphp
                                                    <span
                                                        class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $statusClass }}">
                                                        {{ $order->status }}
                                                    </span>
                                                    <span class="text-[12px] text-gray-400">•</span>
                                                    <span
                                                        class="text-[12px] text-gray-500">{{ $order->created_at->format('d-m-Y') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2">
                                            <p class="text-[16px] font-black text-gray-900">₦
                                                {{ number_format($order->total_amount, 0) }}
                                            </p>
                                            <a href="{{ route('customer.order-details', $order->order_number) }}"
                                                class="text-[13px] font-bold text-[#2b1770] uppercase hover:underline">
                                                See Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-4 border-t border-gray-100">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="p-10 flex flex-col items-center justify-center text-center">
                            <div class="size-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fa-solid fa-box-open text-3xl text-gray-200"></i>
                            </div>
                            <p class="text-[16px] font-medium text-gray-900 mb-1">
                                @if($filter === 'all')
                                    You have no orders yet
                                @else
                                    No {{ $filter }} orders found
                                @endif
                            </p>
                            <p class="text-[14px] text-gray-500 mb-6 max-w-sm">
                                Check back here to track the status of your orders after you place them.
                            </p>
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