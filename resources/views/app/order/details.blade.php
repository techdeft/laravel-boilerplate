<?php

use Livewire\Volt\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app.app')] class extends Component {
    public Order $order;
    public $status;
    public $payment_status;

    public function mount(Order $order)
    {
        $this->order = $order->load(['user', 'items.product', 'address']);
        $this->status = $order->status;
        $this->payment_status = $order->payment_status;
    }

    public function updateStatus()
    {
        $this->order->update([
            'status' => $this->status,
        ]);

        session()->flash('status_updated', 'Order status updated successfully.');
    }

    public function updatePaymentStatus()
    {
        $this->order->update([
            'payment_status' => $this->payment_status,
        ]);

        session()->flash('payment_updated', 'Payment status updated successfully.');
    }
}; ?>

<div class="p-6 space-y-6">
    {{-- Header --}}
    <div
        class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 border-none outline-none">
        <div class="flex items-center gap-4 border-none outline-none">
            <a href="{{ route('admin.orders.index') }}" wire:navigate
                class="p-2 hover:bg-gray-100 rounded-xl transition-all text-gray-400 hover:text-gray-900 border-none outline-none">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 border-none outline-none">Order Details</h1>
                <p class="text-gray-500 text-sm border-none outline-none">Order #{{ $order->order_number }} • Placed on
                    {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 border-none outline-none">
            <span @class([
                'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border-none outline-none',
                'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                'bg-blue-100 text-blue-700' => $order->status === 'processing',
                'bg-green-100 text-green-700' => in_array($order->status, ['delivered', 'completed']),
            ])>
                {{ $order->status }}
            </span>
        </div>
    </div>

    @if(session()->has('status_updated'))
        <div
            class="p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl text-sm font-medium border-none outline-none">
            {{ session('status_updated') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 border-none outline-none">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6 border-none outline-none">
            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden border-none outline-none">
                <div class="p-6 border-b border-gray-100 border-none outline-none">
                    <h3 class="font-bold text-gray-900 border-none outline-none">Order Items</h3>
                </div>
                <div class="p-6 space-y-6 border-none outline-none">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 group border-none outline-none">
                            <div
                                class="size-20 rounded-xl bg-gray-50 overflow-hidden flex-shrink-0 border-none outline-none">
                                <img src="{{ Storage::url($item->product->image_path) }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 border-none outline-none">
                                <h4
                                    class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors border-none outline-none">
                                    {{ $item->product->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1 border-none outline-none">Unit Price:
                                    ₦{{ number_format($item->price, 2) }}</p>
                            </div>
                            <div class="text-right border-none outline-none">
                                <p class="text-sm font-bold text-gray-900 border-none outline-none">
                                    ₦{{ number_format($item->total, 2) }}</p>
                                <p class="text-xs text-gray-500 border-none outline-none">Qty: {{ $item->quantity }}</p>
                            </div>
                        </div>
                    @endforeach

                    <div class="pt-6 border-t border-gray-100 space-y-3 border-none outline-none">
                        <div class="flex justify-between text-sm border-none outline-none">
                            <span class="text-gray-500 border-none outline-none">Subtotal</span>
                            <span
                                class="font-medium text-gray-900 border-none outline-none">₦{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm border-none outline-none">
                            <span class="text-gray-500 border-none outline-none">Delivery Fee
                                ({{ $order->delivery_method }})</span>
                            <span
                                class="font-medium text-gray-900 border-none outline-none">₦{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-none outline-none">
                            <span class="text-gray-900 border-none outline-none">Total</span>
                            <span
                                class="text-blue-600 border-none outline-none">₦{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($order->notes)
                <div class="bg-white p-6 rounded-2xl border border-gray-100 border-none outline-none">
                    <h3 class="font-bold text-gray-900 mb-4 border-none outline-none">Order Notes</h3>
                    <p class="text-sm text-gray-600 border-none outline-none">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6 border-none outline-none">
            {{-- Status Management --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100 space-y-4 border-none outline-none">
                <h3 class="text-sm font-bold text-gray-900 border-none outline-none">Order Management</h3>

                <div class="space-y-3 border-none outline-none">
                    <label
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-none outline-none">Order
                        Status</label>
                    <select wire:model="status"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <button wire:click="updateStatus"
                        class="w-full py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all border-none outline-none">
                        Update Status
                    </button>
                </div>

                <div class="pt-4 border-t border-gray-50 space-y-3 border-none outline-none">
                    <label
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-none outline-none">Payment
                        Status</label>
                    <select wire:model="payment_status"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                    </select>
                    <button wire:click="updatePaymentStatus"
                        class="w-full py-2 bg-gray-100 text-gray-800 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all border-none outline-none">
                        Update Payment
                    </button>
                </div>
            </div>

            {{-- Customer Card --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100 border-none outline-none">
                <h3 class="text-sm font-bold text-gray-900 mb-4 border-none outline-none">Customer Information</h3>
                <div class="flex items-center gap-3 border-none outline-none">
                    <div
                        class="size-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold uppercase border-none outline-none">
                        {{ substr($order->user->name, 0, 2) }}
                    </div>
                    <div class="border-none outline-none">
                        <p class="text-sm font-bold text-gray-900 border-none outline-none">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-500 border-none outline-none">{{ $order->user->email }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-50 border-none outline-none">
                    <a href="{{ route('admin.customers.details', $order->user->uuid) }}" wire:navigate
                        class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-700 border-none outline-none">
                        <span>View Customer Profile</span>
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100 border-none outline-none">
                <h3 class="text-sm font-bold text-gray-900 mb-4 border-none outline-none">Shipping Details</h3>
                <div class="space-y-4 border-none outline-none">
                    <div class="border-none outline-none">
                        <p
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-none outline-none">
                            Delivery Method</p>
                        <p class="text-sm font-bold text-gray-900 border-none outline-none capitalize">
                            {{ str_replace('_', ' ', $order->delivery_method) }}</p>
                    </div>

                    @if($order->address)
                        <div class="border-none outline-none">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-none outline-none">Shipping Address</p>
                            <div class="text-sm text-gray-600 mt-2 border-none outline-none">
                                <p class="font-bold text-gray-900 border-none outline-none">
                                    {{ $order->address->first_name }} {{ $order->address->last_name }}</p>
                                <p class="border-none outline-none">{{ $order->address->address_line }}</p>
                                <p class="border-none outline-none">{{ $order->address->city }},
                                    {{ $order->address->region }}</p>
                                @if($order->address->additional_info)
                                    <p class="text-xs text-gray-500 mt-1 italic border-none outline-none">{{ $order->address->additional_info }}</p>
                                @endif
                                <div class="mt-3 flex flex-col gap-1 border-none outline-none">
                                    <p class="text-xs text-gray-500 border-none outline-none font-medium">Primary: {{ $order->address->phone }}</p>
                                    @if($order->address->additional_phone)
                                        <p class="text-xs text-gray-500 border-none outline-none">Secondary: {{ $order->address->additional_phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>