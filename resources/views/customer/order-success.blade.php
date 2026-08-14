<?php

use Livewire\Volt\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest.app')] class extends Component {
    public $orderNumber;

    public function mount($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        // Ensure order exists and belongs to user
        $order = Order::where('order_number', $this->orderNumber)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            return $this->redirect(route('customer.dashboard'), navigate: true);
        }
    }
}; ?>

<x-slot name="title">Order Success</x-slot>

<div class="bg-[#f1f1f2] min-h-screen pb-12 text-[#282828]">
    <div class="max-w-[800px] mx-auto px-4 py-16">
        <div class="bg-white rounded shadow-sm p-8 text-center space-y-6">
            <div class="size-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-check text-4xl"></i>
            </div>

            <h1 class="text-[24px] font-black text-gray-900 uppercase">Thank You for Your Order!</h1>
            <p class="text-gray-600 max-w-md mx-auto">
                Your order has been placed successfully. We'll send you a confirmation email with the details.
            </p>

            <div class="py-6 border-y border-gray-100">
                <p class="text-[14px] text-gray-500 uppercase font-bold mb-1">Order Number</p>
                <p class="text-[20px] font-black text-[#2b1770]">{{ $orderNumber }}</p>
            </div>

            <div class="space-y-4 pt-4">
                <a href="{{ route('customer.dashboard') }}" wire:navigate
                    class="inline-block bg-[#2b1770] text-white px-12 py-3.5 rounded font-bold uppercase text-[15px] hover:bg-[#3f238f] transition-all shadow-md">
                    Track My Order
                </a>
                <br>
                <a href="{{ route('home') }}" wire:navigate
                    class="inline-block text-[#2b1770] font-bold uppercase text-[14px] hover:underline">
                    Continue Shopping
                </a>
            </div>
        </div>

        <!-- Next Steps Card -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded shadow-sm flex items-center gap-3">
                <div class="size-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <h4 class="text-[12px] font-bold">Email Confirmed</h4>
                    <p class="text-[11px] text-gray-500">Check your inbox</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded shadow-sm flex items-center gap-3">
                <div
                    class="size-10 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-truck"></i>
                </div>
                <div>
                    <h4 class="text-[12px] font-bold">Processing</h4>
                    <p class="text-[11px] text-gray-500">Usually within 24h</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded shadow-sm flex items-center gap-3">
                <div class="size-10 bg-green-50 text-green-600 rounded-full flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h4 class="text-[12px] font-bold">Support 24/7</h4>
                    <p class="text-[11px] text-gray-500">We are here to help</p>
                </div>
            </div>
        </div>
    </div>
</div>