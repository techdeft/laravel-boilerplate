<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\BookingSetting;
use App\Models\Booking;
use App\Models\PharmacistSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest.app')] class extends Component {
    use WithFileUploads;

    public int $currentStep = 1;

    // Step 1: Bio Data
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public $prescription; // For file upload

    // Step 2: Package & Schedule
    public $package_id = null;
    public string $booking_date = '';
    public string $booking_time = '';
    public string $contact_method = '';
    public $packages = [];
    public array $availableTimeSlots = [];

    // Summary data
    public $selectedPackage = null;

    public function mount()
    {
        $this->packages = BookingSetting::where('is_active', true)->get();

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';
        }

        // Default to next week for a random date demo, or leave empty
    }

    public function updatedPackageId($value)
    {
        $this->selectedPackage = collect($this->packages)->firstWhere('id', $value);
        $this->updateTimeSlots();
    }

    public function updatedBookingDate($value)
    {
        $this->updateTimeSlots();
    }

    public function updateTimeSlots()
    {
        $this->availableTimeSlots = [];
        $this->booking_time = '';
        
        if (!$this->booking_date || !$this->package_id) {
            return;
        }

        $package = collect($this->packages)->firstWhere('id', $this->package_id);
        if (!$package) return;

        $duration = $package->duration_minutes;
        $dayOfWeek = Carbon::parse($this->booking_date)->format('l');

        $schedule = PharmacistSchedule::where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return;
        }

        $startTime = Carbon::parse($this->booking_date . ' ' . $schedule->start_time);
        $endTime = Carbon::parse($this->booking_date . ' ' . $schedule->end_time);

        $existingBookings = Booking::whereDate('booking_date', $this->booking_date)
            ->where('booking_status', '!=', 'cancelled')
            ->get();

        $slots = [];
        $currentTime = $startTime->copy();

        while ($currentTime->copy()->addMinutes($duration)->lte($endTime)) {
            $slotStart = $currentTime->copy();
            $slotEnd = $currentTime->copy()->addMinutes($duration);
            
            if ($slotStart->isPast()) {
                $currentTime->addMinutes($duration);
                continue;
            }
            
            $isOverlapping = false;
            foreach ($existingBookings as $booking) {
                // If package got deleted, default to 30 mins
                $bookingSetting = BookingSetting::where('package_name', $booking->booking_type)->first();
                $bDuration = $bookingSetting ? $bookingSetting->duration_minutes : 30;
                
                $bStart = Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
                $bEnd = $bStart->copy()->addMinutes($bDuration);

                // Overlap formula: StartA < EndB && EndA > StartB
                if ($slotStart->lt($bEnd) && $slotEnd->gt($bStart)) {
                    $isOverlapping = true;
                    break;
                }
            }

            if (!$isOverlapping) {
                $slots[] = $slotStart->format('h:i A');
            }

            $currentTime->addMinutes($duration);
        }

        $this->availableTimeSlots = $slots;
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'prescription' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            ]);
            $this->currentStep++;
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'package_id' => 'required|exists:booking_settings,id',
                'booking_date' => 'required|date|after_or_equal:today',
                'booking_time' => 'required|string',
                'contact_method' => 'required|in:whatsapp,zoom,google_meet',
            ]);
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function processPayment(\App\Services\PaymentService $paymentService)
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'package_id' => 'required',
            'booking_date' => 'required',
            'booking_time' => 'required',
            'contact_method' => 'required|in:whatsapp,zoom,google_meet',
            'prescription' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if (!$this->selectedPackage) {
            session()->flash('error', 'Please select a package.');
            return;
        }

        $prescriptionPath = null;
        if ($this->prescription) {
            $prescriptionPath = $this->prescription->store('prescriptions', 'public');
        }

        $booking = Booking::create([
            'user_id' => Auth::id(), // null if guest
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'prescription' => $prescriptionPath,
            'contact_method' => $this->contact_method,
            'booking_type' => $this->selectedPackage->package_name,
            'booking_date' => $this->booking_date,
            'booking_time' => $this->booking_time,
            'payment_currency' => 'NGN',
            'payment_amount' => $this->selectedPackage->price,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
        ]);

        // Attempt to start payment
        $response = $paymentService->initializeBookingPaystack($booking);

        if ($response['success']) {
            return redirect()->away($response['url']);
        }

        session()->flash('error', 'Payment initialization failed: ' . $response['error']);
    }
}; ?>

<x-slot name="title">Book Consultation | Medmall</x-slot>

<div class="min-h-screen py-12 relative bg-cover bg-center bg-fixed" style="background-image: url('{{ asset('images/bg.jpg') }}');">
    <!-- Overlay for readability -->
    <div class="absolute inset-0 bg-white/85 backdrop-blur-sm"></div>
    
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Book Your Consultation</h1>
            <p class="mt-2 text-gray-600">Get expert advice from licensed pharmacists.</p>
        </div>

        <!-- Progress Steps -->
        <!-- <div class="mb-10 max-w-2xl mx-auto">
            <ul class="relative flex flex-row gap-x-2"> -->
                <!-- Step 1 -->
                <!-- <li class="shrink basis-0 flex-1 group">
                    <div class="min-w-8 min-h-8 w-full inline-flex items-center text-xs align-middle">
                        <span
                            class="size-8 flex justify-center items-center shrink-0 bg-primary-500 font-medium text-white rounded-full">
                            1
                        </span>
                        <div class="ms-2 w-full h-1 flex-1 rounded-full bg-gray-200 group-last:hidden overflow-hidden">
                            <div
                                class="h-full bg-primary-500 transition-all duration-300 {{ $currentStep >= 2 ? 'w-full' : 'w-0' }}">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span
                            class="block text-sm font-semibold {{ $currentStep >= 1 ? 'text-gray-900' : 'text-gray-500' }}">
                            Your Details
                        </span>
                    </div>
                </li> -->
                <!-- Step 2 -->
                <!-- <li class="shrink basis-0 flex-1 group">
                    <div class="min-w-8 min-h-8 w-full inline-flex items-center text-xs align-middle">
                        <span
                            class="size-8 flex justify-center items-center shrink-0 font-medium rounded-full transition-colors duration-300 {{ $currentStep >= 2 ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                            2
                        </span>
                        <div class="ms-2 w-full h-1 flex-1 rounded-full bg-gray-200 group-last:hidden overflow-hidden">
                            <div
                                class="h-full bg-primary-500 transition-all duration-300 {{ $currentStep >= 3 ? 'w-full' : 'w-0' }}">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span
                            class="block text-sm font-semibold {{ $currentStep >= 2 ? 'text-gray-900' : 'text-gray-500' }}">
                            Schedule
                        </span>
                    </div>
                </li> -->
                <!-- Step 3 -->
                <!-- <li class="shrink basis-0 flex-1 group">
                    <div class="min-w-8 min-h-8 w-full inline-flex items-center text-xs align-middle">
                        <span
                            class="size-8 flex justify-center items-center shrink-0 font-medium rounded-full transition-colors duration-300 {{ $currentStep >= 3 ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                            3
                        </span>
                        <div class="ms-2 w-full h-1 flex-1 bg-gray-200 group-last:hidden"></div>
                    </div>
                    <div class="mt-3">
                        <span
                            class="block text-sm font-semibold {{ $currentStep >= 3 ? 'text-gray-900' : 'text-gray-500' }}">
                            Payment
                        </span>
                    </div>
                </li> -->
            <!-- </ul>
        </div> -->

        <div class="bg-white rounded-2xl border border-primary-100 p-6 md:p-8 ">

            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Step 1: Bio Data -->
            @if ($currentStep === 1)
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-900 border-b pb-2">Your Information</h2>
                    @guest
                        <div class="bg-blue-50 text-blue-700 p-4 rounded-lg text-sm flex items-start gap-3">
                            <i class="fa-solid fa-circle-info mt-0.5"></i>
                            <p>You are booking as a guest. <a href="{{ route('login') }}"
                                    class="font-bold underline hover:text-blue-900">Log in</a> for a faster experience.</p>
                        </div>
                    @endguest

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Full Name</label>
                        <input type="text" wire:model="name" placeholder="Enter your full name"
                            class="py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:bg-gray-50"
                            {{ Auth::check() ? 'readonly disabled' : '' }}>
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Email Address</label>
                        <input type="email" wire:model="email" placeholder="Enter your email"
                            class="py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:bg-gray-50"
                            {{ Auth::check() ? 'readonly disabled' : '' }}>
                        @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Phone Number</label>
                        <input type="tel" wire:model="phone" placeholder="Enter your phone number"
                            class="py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50">
                        @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Prescription / Test Result
                            (Optional)</label>
                        <input type="file" wire:model="prescription"
                            class="block w-full border border-gray-300 shadow-sm rounded-lg text-sm focus:z-10 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none file:bg-gray-50 file:border-0 file:border-r file:border-gray-300 file:me-4 file:py-3 file:px-4 hover:file:bg-gray-100">
                        <div class="text-xs text-gray-500 mt-2">Accepted formats: PDF, JPG, PNG (Max 5MB)</div>
                        <div wire:loading wire:target="prescription" class="text-xs text-primary-600 mt-2 font-medium">
                            Uploading...</div>
                        @error('prescription') <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span> @enderror

                        @if ($prescription)
                            <div class="mt-4 p-3 border border-gray-200 rounded-lg bg-gray-50 flex items-center justify-between">
                                <div class="flex items-center space-x-3 overflow-hidden">
                                    @php
                                        $mimeType = $prescription->getMimeType();
                                        $isImage = str_starts_with($mimeType, 'image/');
                                    @endphp
                                    @if ($isImage)
                                        <img src="{{ $prescription->temporaryUrl() }}" class="w-12 h-12 object-cover rounded shadow-sm border border-gray-200">
                                    @else
                                        <div class="w-12 h-12 flex items-center justify-center bg-white border border-gray-200 rounded shadow-sm text-red-500 text-xl">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </div>
                                    @endif
                                    <div class="truncate">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $prescription->getClientOriginalName() }}</p>
                                        <p class="text-xs text-gray-500">{{ round($prescription->getSize() / 1024, 1) }} KB</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="$set('prescription', null)" class="text-gray-400 hover:text-red-500 p-2 transition-colors">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button wire:click="nextStep"
                            class="px-6 py-3 bg-primary-500 text-white rounded-lg font-bold hover:bg-primary-600 transition-colors">
                            Continue to Schedule
                        </button>
                    </div>
                </div>
            @endif

            <!-- Step 2: Package & Schedule -->
            @if ($currentStep === 2)
                <div class="space-y-6 animate-[fadeIn_0.3s_ease-out]">
                    <h2 class="text-xl font-bold text-gray-900 border-b pb-2">Select Package & Time</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-3">Available Packages</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($packages as $pkg)
                                <label
                                    class="relative flex cursor-pointer rounded-xl border p-5 shadow-sm focus:outline-none transition-all hover:bg-gray-50 {{ $package_id == $pkg->id ? 'border-primary-500 ring-1 ring-primary-500 bg-primary-50/10' : 'border-gray-200 bg-white' }}">
                                    <input type="radio" wire:model.live="package_id" value="{{ $pkg->id }}" class="sr-only">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-bold text-gray-900">{{ $pkg->package_name }}</span>
                                            <span class="mt-1 flex items-center text-xs text-gray-500"><i
                                                    class="far fa-clock mr-1.5"></i> {{ $pkg->duration_minutes }} minutes</span>
                                            <span
                                                class="mt-3 text-lg font-black text-primary-600">₦{{ number_format($pkg->price, 0) }}</span>
                                        </span>
                                    </span>
                                    @if ($package_id == $pkg->id)
                                        <i class="fa-solid fa-circle-check text-primary-500 text-xl absolute top-5 right-5"></i>
                                    @else
                                        <div class="size-5 rounded-full border-2 border-gray-200 absolute top-5 right-5"></div>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                        @error('package_id') <span class="text-xs text-red-500 block mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-800 mb-2">Preferred Date</label>
                            <input type="date" wire:model.live="booking_date" min="{{ date('Y-m-d') }}"
                                class="py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('booking_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-800 mb-2">Preferred Time</label>
                            <select wire:model="booking_time"
                                class="py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:bg-gray-50"
                                {{ count($availableTimeSlots) === 0 ? 'disabled' : '' }}>
                                <option value="">{{ count($availableTimeSlots) === 0 ? ($booking_date ? 'No slots available' : 'Select a date first') : 'Select a time' }}</option>
                                @foreach($availableTimeSlots as $slot)
                                    <option value="{{ $slot }}">{{ $slot }}</option>
                                @endforeach
                            </select>
                            @error('booking_time') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-800 mb-2">Meeting Platform</label>
                            <select wire:model="contact_method"
                                class="py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Select platform</option>
                                <option value="whatsapp">WhatsApp Call</option>
                                <option value="zoom">Zoom</option>
                                <option value="google_meet">Google Meet</option>
                            </select>
                            @error('contact_method') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between">
                        <button wire:click="previousStep"
                            class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition-colors">
                            Back
                        </button>
                        <button wire:click="nextStep"
                            class="px-6 py-3 bg-primary-500 text-white rounded-lg font-bold hover:bg-primary-600 transition-colors">
                            Review & Pay
                        </button>
                    </div>
                </div>
            @endif

            <!-- Step 3: Payment -->
            @if ($currentStep === 3)
                <div class="space-y-6 animate-[fadeIn_0.3s_ease-out]">
                    <h2 class="text-xl font-bold text-gray-900 border-b pb-2">Review & Payment</h2>

                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $selectedPackage->package_name ?? 'Consultation' }}
                                </h3>
                                <p class="text-sm text-gray-500"><i class="far fa-calendar-alt mr-1"></i>
                                    {{ $booking_date }} at {{ $booking_time }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-black text-primary-600">
                                    ₦{{ number_format($selectedPackage->price ?? 0, 0) }}</p>
                            </div>
                        </div>
                        <div class="pt-2">
                            <p class="text-sm font-bold text-gray-900">Patient Details:</p>
                            <p class="text-sm text-gray-600">{{ $name }} • {{ $email }} • {{ $phone }}</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 text-blue-800 p-4 rounded-lg text-sm">
                        <p class="font-medium"><i class="fa-solid fa-lock mr-2"></i> Secure Payment</p>
                        <p class="mt-1 text-blue-700">You will be redirected to our secure payment gateway to complete your
                            booking.</p>
                    </div>

                    <div class="pt-4 flex justify-between">
                        <button wire:click="previousStep"
                            class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition-colors">
                            Back
                        </button>
                        <button wire:click="processPayment" wire:loading.attr="disabled"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition-colors flex items-center gap-2">
                            <span wire:loading.remove>Pay ₦{{ number_format($selectedPackage->price ?? 0, 0) }}</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>