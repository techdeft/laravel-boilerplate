<?php

use Livewire\Volt\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest.app')] class extends Component {
    public $order;

    public function mount($orderNumber)
    {
        $this->order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->with(['items.product', 'address'])
            ->firstOrFail();
    }
}; ?>

<x-slot name="title">Order Details #{{ $order->order_number }}</x-slot>

<div class="bg-[#f1f1f2] min-h-screen pb-20 lg:pb-12">
    <div class="max-w-[1184px] mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4 items-start">
            <!-- Sidebar -->
            <aside class="hidden lg:block w-full lg:w-[240px] flex-shrink-0">
                @include('customer.sidebar')
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 w-full space-y-4">
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('customer.orders') }}" class="text-gray-400 hover:text-[#2b1770]">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>
                            <h1 class="text-[18px] font-medium text-gray-900">Order Details</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[14px] text-gray-500 font-bold uppercase tracking-tight">Order
                                #{{ $order->order_number }}</span>
                            <button onclick="window.print()"
                                class="px-3 py-1.5 bg-[#2b1770] hover:bg-[#1f1052] text-white text-xs font-bold rounded flex items-center gap-1.5 transition-all shadow-sm">
                                <i class="fa-solid fa-print text-xs"></i>
                                <span>Print Receipt</span>
                            </button>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 border-b border-gray-100">
                        <div class="flex flex-wrap gap-y-4 items-center justify-between mb-8">
                            <div>
                                <p class="text-[12px] text-gray-500 font-bold uppercase mb-1">Items placed on:</p>
                                <p class="text-[14px] font-medium">{{ $order->created_at->format('d-m-Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[12px] text-gray-500 font-bold uppercase mb-1">Total Amount:</p>
                                <p class="text-[20px] font-black text-[#2b1770]">₦
                                    {{ number_format($order->total_amount, 0) }}</p>
                            </div>
                        </div>

                        <!-- Order Status Timeline -->
                        <div class="relative py-4">
                            <div
                                class="absolute top-[26px] left-[15px] sm:left-0 sm:right-0 h-full sm:h-auto sm:top-1/2 w-0.5 sm:w-auto sm:border-t-2 border-gray-200 -z-0">
                            </div>

                            <div
                                class="flex flex-col sm:flex-row gap-8 sm:gap-2 justify-between items-start relative z-10">
                                @php
                                    $statuses = [
                                        ['key' => 'pending', 'label' => 'Order Placed', 'icon' => 'fa-clipboard-list'],
                                        ['key' => 'processing', 'label' => 'Processing', 'icon' => 'fa-gears'],
                                        ['key' => 'shipped', 'label' => 'Shipped', 'icon' => 'fa-truck-fast'],
                                        ['key' => 'delivered', 'label' => 'Delivered', 'icon' => 'fa-circle-check'],
                                    ];

                                    $statusOrder = ['pending', 'processing', 'shipped', 'delivered', 'completed'];
                                    $currentIndex = array_search($order->status, $statusOrder);
                                    if ($order->status === 'completed') $currentIndex = 3;
                                    if ($order->status === 'cancelled') $currentIndex = -1;
                                @endphp

                                @foreach($statuses as $index => $status)
                                    <div class="flex sm:flex-col items-center gap-4 sm:gap-2 flex-1 text-center">
                                        <div @class([
                                            'size-10 rounded-full flex items-center justify-center text-sm shadow-sm border-2 transition-all',
                                            'bg-[#2b1770] text-white border-[#2b1770]' => $currentIndex >= array_search($status['key'], $statusOrder),
                                            'bg-white text-gray-300 border-gray-200' => $currentIndex < array_search($status['key'], $statusOrder)
                                        ])>
                                            <i class="fa-solid {{ $status['icon'] }}"></i>
                                        </div>
                                        <div class="text-left sm:text-center">
                                            <p @class([
                                                'text-[12px] font-bold uppercase tracking-tight',
                                                'text-[#2b1770]' => $currentIndex >= array_search($status['key'], $statusOrder),
                                                'text-gray-400' => $currentIndex < array_search($status['key'], $statusOrder)
                                            ])>{{ $status['label'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="p-4 sm:p-6 border-b border-gray-100">
                        <h3 class="text-[14px] font-bold text-gray-900 uppercase mb-4">Items in your order</h3>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex gap-4 p-4 border border-gray-50 rounded bg-gray-50/30 font-medium">
                                    <div
                                        class="size-20 bg-white rounded overflow-hidden border border-gray-100 flex-shrink-0">
                                        @if($item->product->image_path)
                                            <img src="{{ Storage::url($item->product->image_path) }}"
                                                class="w-full h-full object-contain">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-200">
                                                <i class="fa-solid fa-image text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span
                                            @class([
                                                'text-[10px] px-2 py-0.5 rounded font-bold uppercase mb-1 inline-block',
                                                'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                                                'bg-blue-100 text-blue-700' => $order->status === 'processing',
                                                'bg-indigo-100 text-indigo-700' => $order->status === 'shipped',
                                                'bg-green-100 text-green-700' => in_array($order->status, ['delivered', 'completed', 'delivered']),
                                                'bg-red-100 text-red-700' => $order->status === 'cancelled',
                                            ])>
                                            {{ $order->status }}
                                        </span>
                                        <h4 class="text-[14px] text-gray-900 mb-1 line-clamp-2">{{ $item->product->name }}
                                        </h4>
                                        <p class="text-[12px] text-gray-500">Qty: {{ $item->quantity }}</p>
                                        <p class="text-[14px] font-bold text-[#2b1770] mt-2 sm:hidden">₦
                                            {{ number_format($item->total, 0) }}</p>
                                    </div>
                                    <div class="hidden sm:block text-right">
                                        <p class="text-[16px] font-black text-[#2b1770]">₦
                                            {{ number_format($item->total, 0) }}</p>
                                        <p class="text-[12px] text-gray-400">₦ {{ number_format($item->price, 0) }} x
                                            {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <!-- Left: Address & Payment -->
                        <div class="p-4 sm:p-6 border-r border-gray-100 space-y-8">
                            <div>
                                <h3
                                    class="text-[14px] font-bold text-gray-900 uppercase mb-3 border-b border-gray-50 pb-2">
                                    Payment Information</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-[13px]">
                                        <span class="text-gray-500">Payment Method</span>
                                        <span
                                            class="font-medium text-gray-900 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-[13px]">
                                        <span class="text-gray-500">Payment Status</span>
                                        <span @class([
                                            'px-2 py-0.5 rounded text-[10px] font-bold uppercase',
                                            'bg-green-100 text-green-700' => $order->payment_status === 'paid',
                                            'bg-yellow-100 text-yellow-700' => $order->payment_status === 'unpaid',
                                            'bg-red-100 text-red-700' => $order->payment_status === 'failed',
                                        ])>{{ $order->payment_status }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3
                                    class="text-[14px] font-bold text-gray-900 uppercase mb-3 border-b border-gray-50 pb-2">
                                    Delivery Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-[12px] text-gray-400 uppercase font-bold mb-1">Delivery Method
                                        </p>
                                        <p class="text-[13px] font-medium text-gray-900 uppercase">
                                            {{ str_replace('_', ' ', $order->delivery_method) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[12px] text-gray-400 uppercase font-bold mb-1">Shipping Address
                                        </p>
                                        <div class="text-[13px] text-gray-600 space-y-0.5">
                                            <p class="font-bold text-gray-900">{{ $order->address->first_name }}
                                                {{ $order->address->last_name }}</p>
                                            <p>{{ $order->address->address_line }}</p>
                                            <p>{{ $order->address->city }}, {{ $order->address->region }}</p>
                                            <p>{{ $order->address->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Total Summary -->
                        <div class="p-4 sm:p-6 bg-gray-50/30">
                            <h3
                                class="text-[14px] font-bold text-gray-900 uppercase mb-3 border-b border-gray-100 pb-2">
                                Order Summary</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between text-[14px]">
                                    <span class="text-gray-500">Subtotal</span>
                                    <span class="font-bold text-gray-900">₦
                                        {{ number_format($order->subtotal, 0) }}</span>
                                </div>
                                <div class="flex justify-between text-[14px]">
                                    <span class="text-gray-500">Delivery Fee</span>
                                    <span class="font-bold text-gray-900">₦
                                        {{ number_format($order->delivery_fee, 0) }}</span>
                                </div>
                                <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                                    <span class="text-[16px] font-bold text-gray-900 uppercase">Total</span>
                                    <span class="text-[20px] font-black text-[#2b1770]">₦
                                        {{ number_format($order->total_amount, 0) }}</span>
                                </div>
                            </div>

                            @if($order->notes)
                                <div class="mt-6 p-4 bg-white border border-gray-100 rounded">
                                    <p class="text-[12px] text-gray-400 uppercase font-bold mb-1">Special Notes</p>
                                    <p class="text-[13px] text-gray-600 italic">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Printable Receipt Template (Rendered only on print) --}}
    <div id="printable-receipt" class="hidden print:block p-8 bg-white text-gray-900 max-w-3xl mx-auto font-sans">
        <div class="flex justify-between items-start border-b-2 border-gray-900 pb-6 mb-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-blue-950 uppercase">{{ config('app.name', 'MEDMALL') }}</h1>
                <p class="text-xs text-gray-500 font-medium">Healthcare & Pharmacy Services</p>
                <p class="text-xs text-gray-500 mt-1">Official Customer Order Receipt</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-900">RECEIPT</h2>
                <p class="text-sm font-mono text-gray-600 mt-1">#{{ $order->order_number }}</p>
                <p class="text-xs text-gray-500 mt-1">Date: {{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8 text-sm">
            <div>
                <h3 class="text-xs font-bold uppercase text-gray-400 tracking-wider mb-2">Billed / Shipped To</h3>
                <p class="font-bold text-gray-900">{{ $order->address?->first_name ?? Auth::user()->name }} {{ $order->address?->last_name }}</p>
                <p class="text-gray-600">{{ Auth::user()->email }}</p>
                @if($order->address)
                    <p class="text-gray-600">{{ $order->address->address_line }}</p>
                    <p class="text-gray-600">{{ $order->address->city }}, {{ $order->address->region }}</p>
                    <p class="text-gray-600">Phone: {{ $order->address->phone }}</p>
                @endif
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold uppercase text-gray-400 tracking-wider mb-2">Order Summary</h3>
                <p class="text-gray-600"><span class="font-semibold">Order Status:</span> <span class="uppercase font-bold text-gray-900">{{ $order->status }}</span></p>
                <p class="text-gray-600"><span class="font-semibold">Payment Method:</span> <span class="uppercase text-gray-900">{{ str_replace('_', ' ', $order->payment_method ?? 'Paystack') }}</span></p>
                <p class="text-gray-600"><span class="font-semibold">Payment Status:</span> <span class="uppercase font-bold text-green-700">{{ $order->payment_status }}</span></p>
                <p class="text-gray-600"><span class="font-semibold">Delivery Method:</span> <span class="uppercase text-gray-900">{{ str_replace('_', ' ', $order->delivery_method) }}</span></p>
            </div>
        </div>

        <table class="w-full text-left mb-8 border-collapse">
            <thead>
                <tr class="border-b-2 border-gray-900 text-xs font-bold uppercase tracking-wider text-gray-700">
                    <th class="py-3">Item Description</th>
                    <th class="py-3 text-center">Qty</th>
                    <th class="py-3 text-right">Unit Price</th>
                    <th class="py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @foreach($order->items as $item)
                    <tr>
                        <td class="py-3 font-medium text-gray-900">{{ $item->product->name }}</td>
                        <td class="py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                        <td class="py-3 text-right text-gray-600">₦{{ number_format($item->price, 2) }}</td>
                        <td class="py-3 text-right font-bold text-gray-900">₦{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end mb-8">
            <div class="w-64 space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal:</span>
                    <span class="font-medium text-gray-900">₦{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Delivery Fee:</span>
                    <span class="font-medium text-gray-900">₦{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-black text-gray-900 pt-2 border-t-2 border-gray-900">
                    <span>Total Paid:</span>
                    <span>₦{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6 text-center text-xs text-gray-500 space-y-1">
            <p class="font-bold text-gray-700">Thank you for ordering with {{ config('app.name', 'Medmall') }}!</p>
            <p>Please keep this receipt for your records.</p>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden !important;
        }
        #printable-receipt, #printable-receipt * {
            visibility: visible !important;
        }
        #printable-receipt {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            display: block !important;
            background: white !important;
            padding: 20px !important;
        }
    }
</style>