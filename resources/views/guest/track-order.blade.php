<?php

use Livewire\Volt\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest.app')] class extends Component {
    public $orderNumber = '';
    public $order = null;
    public $error = null;

    public function trackOrder()
    {
        $this->validate([
            'orderNumber' => 'required',
        ]);

        $this->order = Order::where('order_number', $this->orderNumber)
            ->with(['items.product'])
            ->first();

        if (!$this->order) {
            $this->error = 'Order not found. Please check the order number and try again.';
        } else {
            $this->error = null;
        }
    }
}; ?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        {{-- Tracking Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl font-extrabold text-blue-900 border-none outline-none">Track Your Order</h1>
            <p class="mt-2 text-gray-600 border-none outline-none">Enter your order number to see the current status of
                your delivery.</p>
        </div>

        {{-- Tracking Form --}}
        <div class="bg-white p-8 rounded shadow-sm border border-gray-100 mb-8 border-none outline-none">
            <form wire:submit="trackOrder" class="flex flex-col md:flex-row gap-4 border-none outline-none">
                <div class="flex-1 border-none outline-none">
                    <label for="order_number" class="sr-only">Order Number</label>
                    <div class="relative border-none outline-none">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 border-none outline-none">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                        </div>
                        <input type="text" id="order_number" wire:model="orderNumber"
                            placeholder="e.g. ORD-ABC12345-123456"
                            class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 rounded text-lg focus:ring-2 focus:ring-blue-500/10 focus:border-blue-900 transition-all outline-none">
                    </div>
                </div>
                <button type="submit"
                    class="inline-flex items-center justify-center px-8 py-4 bg-blue-900 text-white text-lg font-bold rounded hover:bg-blue-800 transition-all border-none outline-none">
                    <span wire:loading.remove wire:target="trackOrder">Track Order</span>
                    <span wire:loading wire:target="trackOrder"
                        class="flex items-center gap-2 border-none outline-none">
                        <svg class="animate-spin size-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Searching...
                    </span>
                </button>
            </form>

            @if($error)
                <div
                    class="mt-6 flex items-center gap-3 p-4 bg-red-50 text-red-700 rounded border border-red-100 border-none outline-none">
                    <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium border-none outline-none">{{ $error }}</p>
                </div>
            @endif
        </div>

        {{-- Tracking Results --}}
        @if($order)
            <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500 border-none outline-none">
                {{-- Status Timeline --}}
                <div class="bg-white p-8 rounded shadow-sm border border-gray-100 border-none outline-none">
                    <div class="flex items-center justify-between mb-8 border-none outline-none">
                        <h2 class="text-xl font-bold text-gray-900 border-none outline-none">Order Tracking</h2>
                        <span @class([
                            'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border-none outline-none',
                            'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                            'bg-blue-100 text-blue-700' => $order->status === 'processing',
                            'bg-indigo-100 text-indigo-700' => $order->status === 'shipped',
                            'bg-green-100 text-green-700' => in_array($order->status, ['delivered', 'completed']),
                            'bg-red-100 text-red-700' => $order->status === 'cancelled',
                        ])>
                            {{ $order->status }}
                        </span>
                    </div>

                    @php
                        $steps = [
                            'pending' => ['title' => 'Order Placed', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'processing' => ['title' => 'Processing', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                            'shipped' => ['title' => 'Shipped', 'icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.806H14.25M16.5 18.75h-2.25m0-11.25v11.25m-14.25-4.5h14.25'],
                            'delivered' => ['title' => 'Delivered', 'icon' => 'M5 13l4 4L19 7'],
                        ];

                        $statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
                        $currentIndex = array_search($order->status, $statusOrder);
                        if ($order->status === 'completed')
                            $currentIndex = 3;
                        if ($order->status === 'cancelled')
                            $currentIndex = -1;
                    @endphp

                    <div class="relative border-none outline-none">
                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-100 border-none outline-none"></div>
                        <div class="space-y-10 border-none outline-none">
                            @foreach($steps as $key => $step)
                                @php
                                    $index = array_search($key, $statusOrder);
                                    $isActive = $currentIndex >= $index;
                                @endphp
                                <div class="relative flex items-center gap-6 group border-none outline-none">
                                    <div @class([
                                        'size-12 rounded-full flex items-center justify-center z-10 transition-all border-none outline-none',
                                        'bg-blue-900 text-white shadow-lg shadow-blue-900/20' => $isActive,
                                        'bg-gray-100 text-gray-400' => !$isActive,
                                    ])>
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $step['icon'] }}" />
                                        </svg>
                                    </div>
                                    <div class="border-none outline-none">
                                        <h3 @class([
                                            'text-lg font-bold border-none outline-none',
                                            'text-gray-900 font-extrabold' => $isActive,
                                            'text-gray-400' => !$isActive,
                                        ])>{{ $step['title'] }}</h3>
                                        @if($isActive)
                                            <p class="text-sm text-gray-500 border-none outline-none">Checked on
                                                {{ $order->updated_at->format('M d, Y') }}</p>
                                        @else
                                            <p class="text-sm text-gray-300 border-none outline-none">Pending update</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="bg-white p-8 rounded shadow-sm border border-gray-100 border-none outline-none">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-none outline-none">Order Summary</h2>
                    <div class="space-y-4 border-none outline-none">
                        @foreach($order->items->take(3) as $item)
                            <div class="flex items-center gap-4 border-none outline-none">
                                <div class="size-16 rounded-xl bg-gray-50 overflow-hidden shrink-0 border-none outline-none">
                                    <img src="{{ Storage::url($item->product->image_path) }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 border-none outline-none">
                                    <p class="text-sm font-bold text-gray-900 border-none outline-none">
                                        {{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500 border-none outline-none">Quantity: {{ $item->quantity }}
                                    </p>
                                </div>
                                <p class="text-sm font-bold text-gray-900 border-none outline-none">
                                    ₦{{ number_format($item->total, 2) }}</p>
                            </div>
                        @endforeach
                        @if($order->items->count() > 3)
                            <p class="text-xs text-gray-400 italic text-center pt-2 border-none outline-none">+
                                {{ $order->items->count() - 3 }} more items in this order</p>
                        @endif

                        <div class="pt-6 border-t border-gray-50 border-none outline-none text-right">
                            <p class="text-sm text-gray-500 border-none outline-none">Total Package Value</p>
                            <p class="text-2xl font-extrabold text-blue-900 border-none outline-none">
                                ₦{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($orderNumber && !$error)
            {{-- Initial state - no results yet --}}
        @endif

        {{-- Help Section --}}
        <div class="mt-12 text-center border-none outline-none">
            <p class="text-gray-500 text-sm border-none outline-none">Need help? <a href="#"
                    class="text-blue-900 font-bold hover:underline">Contact our support team</a></p>
        </div>
    </div>
</div>