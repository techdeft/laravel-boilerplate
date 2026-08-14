<?php

use Livewire\Volt\Component;
use App\Models\Address;
use App\Models\Order;
use App\Models\DeliveryZone;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

new #[Layout('layouts.guest.app')] class extends Component {
    public $step = 1; // 1: Address, 2: Delivery, 3: Payment, 4: Summary
    public $selectedAddressId = null;
    public $deliveryMethod = 'home_delivery'; // home_delivery, local_park
    public $paymentMethod = 'paystack';
    public $notes = '';
    
    // Address Form fields
    public $showAddressForm = false;
    public $first_name = '';
    public $last_name = '';
    public $phone = '';
    public $additional_phone = '';
    public $address_line = '';
    public $additional_info = '';
    public $region = '';
    public $city = '';
    public $country = 'Nigeria';
    public $is_default = true;

    protected $paymentService;

    public function boot(\App\Services\PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function mount(CartService $cartService)
    {
        $cart = $cartService->getCart();
        if (!$cart || $cart->items()->count() === 0) {
            return $this->redirect(route('customer.cart'), navigate: true);
        }

        // Set default address if exists
        $defaultAddress = Auth::user()->addresses()->where('is_default', true)->first();
        if ($defaultAddress) {
            $this->selectedAddressId = $defaultAddress->id;
        } else {
            $this->showAddressForm = true;
            $user = Auth::user();
            $nameParts = explode(' ', $user->name, 2);
            $this->first_name = $nameParts[0] ?? '';
            $this->last_name = $nameParts[1] ?? '';
            $this->phone = $user->phone;
        }
    }

    public function selectAddress($id)
    {
        $this->selectedAddressId = $id;
    }

    public function setStep($step)
    {
        if ($step > 1 && !$this->selectedAddressId) {
            session()->flash('error', 'Please select a delivery address.');
            return;
        }
        $this->step = $step;
    }

    public function toggleAddressForm()
    {
        $this->showAddressForm = !$this->showAddressForm;
        if ($this->showAddressForm) {
            $this->resetAddressForm();
            $user = Auth::user();
            $nameParts = explode(' ', $user->name, 2);
            $this->first_name = $nameParts[0] ?? '';
            $this->last_name = $nameParts[1] ?? '';
            $this->phone = $user->phone;
        }
    }

    public function resetAddressForm()
    {
        $this->reset(['first_name', 'last_name', 'phone', 'additional_phone', 'address_line', 'additional_info', 'region', 'city', 'country', 'is_default']);
        $this->country = 'Nigeria';
        $this->is_default = true;
    }

    public function saveAddress()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'additional_phone' => 'nullable|string|max:20',
            'address_line' => 'required|string',
            'region' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        if ($this->is_default) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        $address = Address::create([
            'user_id' => Auth::id(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'additional_phone' => $this->additional_phone,
            'address_line' => $this->address_line,
            'additional_info' => $this->additional_info,
            'region' => $this->region,
            'city' => $this->city,
            'country' => $this->country,
            'is_default' => $this->is_default,
        ]);

        $this->selectedAddressId = $address->id;
        $this->showAddressForm = false;
        $this->setStep(2);
    }

    #[Computed]
    public function addresses()
    {
        return Auth::user()->addresses()->latest()->get();
    }

    #[Computed]
    public function selectedAddress()
    {
        return $this->selectedAddressId ? Address::find($this->selectedAddressId) : null;
    }

    #[Computed]
    public function cart()
    {
        return app(CartService::class)->getCart();
    }

    #[Computed]
    public function selectedZone()
    {
        if (!$this->selectedAddress)
            return null;

        return DeliveryZone::where('name', $this->selectedAddress->region)
            ->where('is_active', true)
            ->first();
    }

    #[Computed]
    public function regions()
    {
        return DeliveryZone::where('is_active', true)
            ->where('country', $this->country)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function countries()
    {
        return DeliveryZone::where('is_active', true)
            ->distinct()
            ->orderBy('country')
            ->pluck('country');
    }

    #[Computed]
    public function deliveryFee()
    {
        $zone = $this->selectedZone;
        if (!$zone)
            return 1000; // Default fallback

        return $this->deliveryMethod === 'home_delivery'
            ? $zone->delivery_fee
            : $zone->local_park_fee;
    }

    #[Computed]
    public function subtotal()
    {
        return $this->cart->items->sum(fn($item) => $item->price * $item->quantity);
    }

    #[Computed]
    public function total()
    {
        return $this->subtotal + $this->deliveryFee;
    }

    public function placeOrder(CartService $cartService)
    {
        if (!$this->selectedAddressId) {
            session()->flash('error', 'Please select an address.');
            return;
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'subtotal' => $this->subtotal,
            'delivery_fee' => $this->deliveryFee,
            'delivery_method' => $this->deliveryMethod,
            'total_amount' => $this->total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $this->paymentMethod,
            'shipping_address_id' => $this->selectedAddressId,
            'notes' => $this->notes,
        ]);

        foreach ($this->cart->items as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->price * $item->quantity,
            ]);
        }

        if ($this->paymentMethod === 'paystack') {
            $result = $this->paymentService->initializePaystack($order);
            if ($result['success'])
                return redirect()->away($result['url']);
            session()->flash('error', 'Paystack Error: ' . $result['error']);
            return;
        }

        if ($this->paymentMethod === 'monnify') {
            $result = $this->paymentService->initializeMonnify($order);
            if ($result['success'])
                return redirect()->away($result['url']);
            session()->flash('error', 'Monnify Error: ' . $result['error']);
            return;
        }

        $cartService->clear();

        session()->flash('order_success', 'Your order has been placed successfully!');
        return $this->redirect(route('customer.order-success', $order->order_number), navigate: true);
    }

}; ?>

<x-slot name="title">Checkout</x-slot>

<div class="bg-[#f1f1f2] min-h-screen pb-12 text-[#282828]">
    <div class="max-w-[1184px] mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Checkout Steps -->
            <div class="flex-1 space-y-4">
                @if(session()->has('error'))
                    <div
                        class="flex items-center gap-2 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm shadow-sm animate-pulse">
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Address Section -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span
                                class="size-6 rounded-full bg-[#2b1770] text-white flex items-center justify-center text-xs font-bold">1</span>
                            <h2 class="text-[16px] font-bold uppercase">Address Details</h2>
                        </div>
                        @if($step > 1)
                            <button wire:click="setStep(1)"
                                class="text-[#2b1770] text-[12px] font-bold uppercase hover:underline">Change</button>
                        @endif
                    </div>

                    @if($step == 1)
                        <div class="p-4 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @forelse($this->addresses as $address)
                                    <div wire:click="selectAddress({{ $address->id }})"
                                        class="p-4 border rounded cursor-pointer transition-all {{ $selectedAddressId == $address->id ? 'border-[#2b1770] bg-purple-50 ring-1 ring-[#2b1770]' : 'border-gray-200 hover:border-gray-300' }}">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-bold text-[14px]">{{ $address->first_name }}
                                                {{ $address->last_name }}
                                            </h3>
                                            @if($address->is_default)
                                                <span
                                                    class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded uppercase font-bold">Default</span>
                                            @endif
                                        </div>
                                        <p class="text-[12px] text-gray-600 mb-1">{{ $address->address_line }}</p>
                                        <p class="text-[12px] text-gray-600">{{ $address->city }}, {{ $address->region }},
                                            {{ $address->country }}
                                        </p>
                                        <p class="text-[12px] text-gray-600 mt-2">{{ $address->phone }}</p>
                                    </div>
                                @empty
                                    {{-- Empty state is handled by the form below --}}
                                @endforelse
                            </div>

                            @if($showAddressForm || $this->addresses->isEmpty())
                                <div class="mt-4 p-6 border rounded-lg bg-gray-50/50">
                                    <h3 class="text-[14px] font-bold uppercase mb-4">{{ $this->addresses->isEmpty() ? 'Add Your Delivery Address' : 'Add New Address' }}</h3>
                                    <form wire:submit="saveAddress" class="space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[12px] font-bold text-gray-700 uppercase mb-1">First Name</label>
                                                <input type="text" wire:model="first_name" class="w-full px-3 py-2 border border-gray-200 rounded text-[13px] focus:outline-none focus:border-[#2b1770]">
                                                @error('first_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-[12px] font-bold text-gray-700 uppercase mb-1">Last Name</label>
                                                <input type="text" wire:model="last_name" class="w-full px-3 py-2 border border-gray-200 rounded text-[13px] focus:outline-none focus:border-[#2b1770]">
                                                @error('last_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[12px] font-bold text-gray-700 uppercase mb-1">Phone Number</label>
                                                <input type="text" wire:model="phone" class="w-full px-3 py-2 border border-gray-200 rounded text-[13px] focus:outline-none focus:border-[#2b1770]">
                                                @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-[12px] font-bold text-gray-700 uppercase mb-1">Additional Phone</label>
                                                <input type="text" wire:model="additional_phone" class="w-full px-3 py-2 border border-gray-200 rounded text-[13px] focus:outline-none focus:border-[#2b1770]">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[12px] font-bold text-gray-700 uppercase mb-1">Address</label>
                                            <textarea wire:model="address_line" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded text-[13px] focus:outline-none focus:border-[#2b1770]"></textarea>
                                            @error('address_line') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[12px] font-bold text-gray-700 uppercase mb-1">Country</label>
                                                <select wire:model.live="country" class="w-full px-3 py-2 border border-gray-200 rounded text-[13px] focus:outline-none focus:border-[#2b1770]">
                                                    <option value="">Select Country</option>
                                                    @foreach($this->countries as $c)
                                                        <option value="{{ $c }}">{{ $c }}</option>
                                                    @endforeach
                                                </select>
                                                @error('country') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-[12px] font-bold text-gray-700 uppercase mb-1">Region</label>
                                                <select wire:model="region" class="w-full px-3 py-2 border border-gray-200 rounded text-[13px] focus:outline-none focus:border-[#2b1770]">
                                                    <option value="">Select Region</option>
                                                    @foreach($this->regions as $r)
                                                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('region') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[12px] font-bold text-gray-700 uppercase mb-1">City</label>
                                            <input type="text" wire:model="city" class="w-full px-3 py-2 border border-gray-200 rounded text-[13px] focus:outline-none focus:border-[#2b1770]">
                                            @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="flex justify-between items-center pt-2">
                                            @if(!$this->addresses->isEmpty())
                                                <button type="button" wire:click="toggleAddressForm" class="text-gray-500 text-[12px] font-bold uppercase hover:underline">Cancel</button>
                                            @else
                                                <div></div>
                                            @endif
                                            <button type="submit" class="bg-[#2b1770] text-white px-6 py-2 rounded font-bold uppercase text-[12px] hover:bg-[#3f238f] transition-all">
                                                Save and Continue
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @elseif(!$showAddressForm && !$this->addresses->isEmpty())
                                <div class="mt-4 flex justify-between items-center">
                                    <button wire:click="toggleAddressForm" class="text-[#2b1770] text-[12px] font-bold uppercase hover:underline flex items-center gap-1">
                                        <i class="fa-solid fa-plus size-3"></i>
                                        Add New Address
                                    </button>

                                    @if($selectedAddressId)
                                        <button wire:click="setStep(2)" wire:loading.attr="disabled" wire:target="setStep(2)"
                                            class="bg-[#2b1770] text-white px-8 py-3 rounded font-bold uppercase text-[14px] hover:bg-[#3f238f] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span wire:loading.remove wire:target="setStep(2)">Proceed to Delivery</span>
                                            <span wire:loading wire:target="setStep(2)">Processing...</span>
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @elseif($this->selectedAddress)
                        <div class="p-4 bg-gray-50/50">
                            <p class="text-[14px] font-bold">{{ $this->selectedAddress->first_name }}
                                {{ $this->selectedAddress->last_name }}
                            </p>
                            <p class="text-[12px] text-gray-600">{{ $this->selectedAddress->address_line }} |
                                {{ $this->selectedAddress->city }}, {{ $this->selectedAddress->region }},
                                {{ $this->selectedAddress->country }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Delivery Section -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                        <span
                            class="size-6 rounded-full {{ $step >= 2 ? 'bg-[#2b1770] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center text-xs font-bold">2</span>
                        <h2 class="text-[16px] font-bold uppercase {{ $step < 2 ? 'text-gray-400' : '' }}">Delivery
                            Method</h2>
                    </div>

                    @if($step == 2)
                        <div class="p-4 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Home Delivery -->
                                <label
                                    class="p-4 border rounded cursor-pointer transition-all {{ $deliveryMethod == 'home_delivery' ? 'border-[#2b1770] bg-purple-50 ring-1 ring-[#2b1770]' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <div class="flex items-center gap-3 mb-2">
                                        <input type="radio" wire:model.live="deliveryMethod" value="home_delivery"
                                            class="accent-[#2b1770]">
                                        <div
                                            class="size-8 bg-white rounded-full flex items-center justify-center text-[#2b1770] shadow-sm shrink-0">
                                            <i class="fa-solid fa-house-chimney text-sm"></i>
                                        </div>
                                        <h3 class="font-bold text-[14px]">Home Delivery</h3>
                                    </div>
                                    <p class="text-[12px] text-gray-600 mb-2 pl-7">Delivered directly to your door within 1
                                        to 3 business days.</p>
                                    <p class="font-bold text-[#2b1770] pl-7">₦
                                        {{ number_format($this->selectedZone->delivery_fee ?? 1000, 0) }}
                                    </p>
                                </label>

                                <!-- Local Park -->
                                <label
                                    class="p-4 border rounded cursor-pointer transition-all {{ $deliveryMethod == 'local_park' ? 'border-[#2b1770] bg-purple-50 ring-1 ring-[#2b1770]' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <div class="flex items-center gap-3 mb-2">
                                        <input type="radio" wire:model.live="deliveryMethod" value="local_park"
                                            class="accent-[#2b1770]">
                                        <div
                                            class="size-8 bg-white rounded-full flex items-center justify-center text-[#2b1770] shadow-sm shrink-0">
                                            <i class="fa-solid fa-bus text-sm"></i>
                                        </div>
                                        <h3 class="font-bold text-[14px]">Local Park Pickup</h3>
                                    </div>
                                    <p class="text-[12px] text-gray-600 mb-2 pl-7">Pickup at a designated transit park in
                                        your region.</p>
                                    <p class="font-bold text-[#2b1770] pl-7">₦
                                        {{ number_format($this->selectedZone->local_park_fee ?? 0, 0) }}
                                    </p>

                                    @if($this->selectedZone && $this->selectedZone->local_park_instructions)
                                        <div class="mt-3 pl-7 pt-3 border-t border-gray-100 italic text-[11px] text-gray-500">
                                            <span class="font-bold not-italic">Instructions:</span>
                                            {{ $this->selectedZone->local_park_instructions }}
                                        </div>
                                    @endif
                                </label>
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button wire:click="setStep(3)" wire:loading.attr="disabled" wire:target="setStep(3)"
                                    class="bg-[#2b1770] text-white px-8 py-3 rounded font-bold uppercase text-[14px] hover:bg-[#3f238f] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="setStep(3)">Proceed to Payment</span>
                                    <span wire:loading wire:target="setStep(3)">Processing...</span>
                                </button>
                            </div>
                        </div>
                    @elseif($this->selectedAddress && $step > 2)
                        <div class="p-4 bg-gray-50/50 flex justify-between items-center">
                            <div>
                                <p class="text-[14px] font-bold">
                                    {{ $deliveryMethod == 'home_delivery' ? 'Home Delivery' : 'Local Park Pickup' }}
                                </p>
                                <p class="text-[12px] text-gray-600">₦ {{ number_format($this->deliveryFee, 0) }}</p>
                            </div>
                            <button wire:click="setStep(2)"
                                class="text-[#2b1770] text-[12px] font-bold uppercase hover:underline">Change</button>
                        </div>
                    @endif
                </div>

                <!-- Payment Section -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                        <span
                            class="size-6 rounded-full {{ $step >= 3 ? 'bg-[#2b1770] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center text-xs font-bold">3</span>
                        <h2 class="text-[16px] font-bold uppercase {{ $step < 3 ? 'text-gray-400' : '' }}">Payment
                            Method</h2>
                    </div>

                    @if($step == 3)
                        <div class="p-4 space-y-4">
                            <div class="space-y-3">
                                @if(\App\Models\SiteSetting::getValue('paystack_enabled', true))
                                    <label
                                        class="flex items-center gap-3 p-4 border rounded cursor-pointer transition-all {{ $paymentMethod == 'paystack' ? 'border-[#2b1770] bg-purple-50 ring-1 ring-[#2b1770]' : 'border-gray-200 hover:bg-gray-50' }}">
                                        <input type="radio" wire:model.live="paymentMethod" value="paystack"
                                            class="accent-[#2b1770]">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-[14px] font-bold uppercase">Pay with Card / USSD (Paystack)</p>
                                                <img src="https://checkout.paystack.com/static/media/paystack-logo.5d9e5d4a.svg"
                                                    class="h-4">
                                            </div>
                                            <p class="text-[12px] text-gray-500">Pay securely with your credit/debit card, USSD,
                                                or
                                                Bank</p>
                                        </div>
                                    </label>
                                @endif

                                @if(\App\Models\SiteSetting::getValue('monnify_enabled', true))
                                    <label
                                        class="flex items-center gap-3 p-4 border rounded cursor-pointer transition-all {{ $paymentMethod == 'monnify' ? 'border-[#2b1770] bg-purple-50 ring-1 ring-[#2b1770]' : 'border-gray-200 hover:bg-gray-50' }}">
                                        <input type="radio" wire:model.live="paymentMethod" value="monnify"
                                            class="accent-[#2b1770]">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-[14px] font-bold uppercase">Pay with Monnify</p>
                                                <img src="https://monnify.com/assets/images/monnify-logo.svg" class="h-4">
                                            </div>
                                            <p class="text-[12px] text-gray-500">Pay securely with Cards, Account Transfer or
                                                USSD
                                                via Monnify</p>
                                        </div>
                                    </label>
                                @endif

                                @if(\App\Models\SiteSetting::getValue('transfer_enabled', true))
                                    <label
                                        class="flex items-center gap-3 p-4 border rounded cursor-pointer transition-all {{ $paymentMethod == 'transfer' ? 'border-[#2b1770] bg-purple-50 ring-1 ring-[#2b1770]' : 'border-gray-200 hover:bg-gray-50' }}">
                                        <input type="radio" wire:model.live="paymentMethod" value="transfer"
                                            class="accent-[#2b1770]">
                                        <div class="flex-1">
                                            <p class="text-[14px] font-bold uppercase">Manual Bank Transfer</p>
                                            <p class="text-[12px] text-gray-500">Fast and secure bank transfer</p>
                                        </div>
                                    </label>
                                @endif

                                @if(\App\Models\SiteSetting::getValue('pod_enabled', true))
                                    <label
                                        class="flex items-center gap-3 p-4 border rounded cursor-pointer transition-all {{ $paymentMethod == 'pod' ? 'border-[#2b1770] bg-purple-50 ring-1 ring-[#2b1770]' : 'border-gray-200 hover:bg-gray-50' }}">
                                        <input type="radio" wire:model.live="paymentMethod" value="pod"
                                            class="accent-[#2b1770]">
                                        <div class="flex-1">
                                            <p class="text-[14px] font-bold uppercase">Pay on Delivery (POD)</p>
                                            <p class="text-[12px] text-gray-500">Pay when your order arrives at your door</p>
                                        </div>
                                    </label>
                                @endif
                            </div>
                            @if($paymentMethod)
                                <div class="pt-4 flex justify-end">
                                    <button wire:click="setStep(4)" wire:loading.attr="disabled" wire:target="setStep(4)"
                                        class="bg-[#2b1770] text-white px-8 py-3 rounded font-bold uppercase text-[14px] hover:bg-[#3f238f] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="setStep(4)">Review Order</span>
                                        <span wire:loading wire:target="setStep(4)">Processing...</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @elseif($step > 3)
                        <div class="p-4 bg-gray-50/50 flex justify-between items-center">
                            <div>
                                <p class="text-[14px] font-bold uppercase">
                                    @if($paymentMethod == 'paystack') Paystack
                                    @elseif($paymentMethod == 'monnify') Monnify
                                    @elseif($paymentMethod == 'transfer') Bank Transfer
                                    @elseif($paymentMethod == 'pod') Pay on Delivery
                                    @endif
                                </p>
                                <p class="text-[12px] text-gray-600">Selected Payment Method</p>
                            </div>
                            <button wire:click="setStep(3)"
                                class="text-[#2b1770] text-[12px] font-bold uppercase hover:underline">Change</button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Side Summary -->
            <div class="w-full lg:w-[380px] space-y-4">
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h2 class="text-[16px] font-bold uppercase">Order Summary</h2>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between text-[14px]">
                            <span class="text-gray-600">Items ({{ $this->cart->items->sum('quantity') }})</span>
                            <span class="font-bold">₦ {{ number_format($this->subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-[14px]">
                            <span class="text-gray-600">Delivery Fee</span>
                            <span class="font-bold">₦ {{ number_format($this->deliveryFee, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-[14px] border-t border-gray-50 pt-3">
                            <span class="text-gray-600">Method</span>
                            <span class="font-bold text-right truncate ml-4"
                                title="{{ $deliveryMethod == 'home_delivery' ? 'Home Delivery' : 'Local Park Pickup' }}">
                                {{ $deliveryMethod == 'home_delivery' ? 'Home Delivery' : 'Local Park Pickup' }}
                            </span>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-[16px] font-bold uppercase">Total</span>
                            <span class="text-[20px] font-black text-[#2b1770]">₦
                                {{ number_format($this->total, 0) }}</span>
                        </div>

                        @if($step == 4)
                            <div class="pt-4">
                                <textarea wire:model="notes" placeholder="Additional notes (optional)"
                                    class="w-full p-3 border border-gray-200 rounded text-[14px] focus:outline-none focus:border-[#2b1770] mb-4 h-24"></textarea>

                                <button wire:click="placeOrder" wire:loading.attr="disabled" wire:target="placeOrder"
                                    class="w-full bg-[#2b1770] text-white py-4 rounded font-bold uppercase text-[15px] hover:bg-[#3f238f] transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="placeOrder">Confirm Order</span>
                                    <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Placing Order...
                                    </span>
                                </button>
                                <p class="text-[10px] text-center text-gray-400 mt-3 italic">
                                    By clicking "Confirm Order" you agree to our terms and conditions.
                                </p>
                            </div>
                        @else
                            <p class="text-[11px] text-gray-500 text-center py-2 bg-gray-50 rounded">
                                Complete all steps to finish your order.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Products Mini List -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-3 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-[12px] font-bold uppercase text-gray-600">Your Items</h3>
                    </div>
                    <div class="p-2 divide-y divide-gray-50">
                        @foreach($this->cart->items as $item)
                            <div class="p-2 flex gap-3 text-[12px]">
                                <img src="{{ $item->product->image_path ? Storage::url($item->product->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($item->product->name) }}"
                                    class="size-12 rounded object-contain bg-gray-50">
                                <div class="flex-1 min-w-0">
                                    <p class="truncate font-medium">{{ $item->product->name }}</p>
                                    <p class="text-gray-500">Qty: {{ $item->quantity }} x ₦
                                        {{ number_format($item->price, 0) }}
                                    </p>
                                </div>
                                <div class="text-right font-bold text-[#2b1770]">
                                    ₦ {{ number_format($item->price * $item->quantity, 0) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>