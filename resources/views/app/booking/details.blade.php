<?php

use Livewire\Volt\Component;
use App\Models\Booking;

new class extends Component {
    public Booking $booking;

    public function updateBookingStatus($status)
    {
        $this->booking->update(['booking_status' => $status]);
        session()->flash('success', 'Booking status updated successfully.');
    }

    public function updatePaymentStatus($status)
    {
        $this->booking->update(['payment_status' => $status]);
        session()->flash('success', 'Payment status updated successfully.');
    }
}; ?>

<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto min-h-screen bg-gray-50/50">

    <!-- Page header with subtle gradient -->
    <div
        class="relative bg-gradient-to-r from-[#2b1770] to-[#3f238f] rounded-2xl p-8 mb-8 overflow-hidden shadow-lg border border-indigo-900/20">
        <!-- Abstract background pattern -->
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;">
        </div>
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>

        <div class="relative z-10 sm:flex sm:justify-between sm:items-center">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.bookings.index') }}"
                                class="text-sm font-medium text-white hover:text-white transition-colors duration-200">
                                <i class="fa-solid fa-arrow-left mr-2"></i> All Bookings
                            </a>
                        </li>
                    </ol>
                </nav>
                <div class="flex items-end gap-4 mt-2">
                    <h1 class="text-3xl md:text-4xl text-white font-black tracking-tight tracking-[-0.02em]">
                        Booking Details
                    </h1>
                    <span
                        class="text-white font-mono text-lg bg-indigo-900/40 px-3 py-1 rounded-lg border border-indigo-500/30">
                        #BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>

            <!-- Right: Status Badge -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <span @class([
                    'inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold shadow-sm backdrop-blur-md border border-white/20 uppercase tracking-widest',
                    'bg-yellow-500/20 text-yellow-300' => $booking->booking_status === 'pending',
                    'bg-blue-500/20 text-white' => $booking->booking_status === 'confirmed',
                    'bg-green-500/80 text-white border-green-400' => $booking->booking_status === 'completed',
                    'bg-red-500/20 text-red-300' => $booking->booking_status === 'cancelled',
                ])>
                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2 animate-pulse"></span>
                    {{ $booking->booking_status }}
                </span>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div
            class="mb-8 p-4 bg-green-50 text-green-700 rounded-xl flex items-center border border-green-200 shadow-sm animate-[fadeIn_0.5s_ease-out]">
            <i class="fa-solid fa-circle-check text-xl mr-3 text-green-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left column (Primary Info) -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Patient Information Card -->
            <div
                class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                <div
                    class="px-8 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900 tracking-tight flex items-center">
                        <i class="fa-solid fa-user-circle text-primary-500 mr-2 text-xl"></i> Patient Profile
                    </h2>
                    @if($booking->user_id)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                            Registered Member
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-600 border border-gray-200">
                            Guest Checkout
                        </span>
                    @endif
                </div>
                <div class="p-8">
                    <div class="flex items-center space-x-6">
                        <!-- <div class="relative flex-shrink-0">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-primary-100 to-primary-200 text-primary-700 rounded-2xl flex items-center justify-center font-black text-3xl shadow-inner border border-primary-50">
                                {{ substr($booking->name, 0, 1) }}
                            </div>
                            <div
                                class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-500 border-2 border-white rounded-full">
                            </div>
                        </div> -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight">
                                {{ $booking->name }}
                            </h3>
                            <div class="mt-2 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                                <a href="mailto:{{ $booking->email }}"
                                    class="text-sm text-gray-600 hover:text-primary-600 transition-colors flex items-center group">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center mr-2 group-hover:bg-primary-50 transition-colors">
                                        <i
                                            class="fa-regular fa-envelope text-gray-400 group-hover:text-primary-500"></i>
                                    </div>
                                    {{ $booking->email }}
                                </a>
                                <a href="tel:{{ $booking->phone }}"
                                    class="text-sm text-gray-600 hover:text-primary-600 transition-colors flex items-center group">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center mr-2 group-hover:bg-primary-50 transition-colors">
                                        <i class="fa-solid fa-phone text-gray-400 group-hover:text-primary-500"></i>
                                    </div>
                                    {{ $booking->phone }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultation Details Card -->
            <div
                class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                <div
                    class="px-8 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center">
                    <h2 class="text-lg font-bold text-gray-900 tracking-tight flex items-center">
                        <i class="fa-solid fa-stethoscope text-primary-500 mr-2 text-xl"></i> Consultation Details
                    </h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Package Type -->
                        <div
                            class="bg-gray-50 hover:bg-white rounded-xl p-5 border border-gray-100 relative overflow-hidden group hover:border-primary-200 transition-colors hover:shadow-sm">
                            <div
                                class="absolute right-0 top-0 w-24 h-24 bg-primary-500/5 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                            </div>
                            <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Package Selected
                            </dt>
                            <dd class="text-lg text-gray-900 font-bold relative z-10">{{ $booking->booking_type }}</dd>
                        </div>

                        <!-- Platform -->
                        <div
                            class="bg-gray-50 hover:bg-white rounded-xl p-5 border border-gray-100 relative overflow-hidden group hover:border-primary-200 transition-colors hover:shadow-sm">
                            <div
                                class="absolute right-0 top-0 w-24 h-24 bg-primary-500/5 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                            </div>
                            <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Meeting Platform
                            </dt>
                            <dd class="text-lg text-gray-900 font-bold flex items-center relative z-10">
                                @if($booking->contact_method === 'whatsapp')
                                    <div
                                        class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-2">
                                        <i class="fa-brands fa-whatsapp text-lg"></i>
                                    </div>
                                    WhatsApp Call
                                @elseif($booking->contact_method === 'zoom')
                                    <div
                                        class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-2">
                                        <i class="fa-solid fa-video text-lg"></i>
                                    </div>
                                    Zoom Video
                                @elseif($booking->contact_method === 'google_meet')
                                    <div
                                        class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mr-2">
                                        <i class="fa-solid fa-video text-lg"></i>
                                    </div>
                                    Google Meet
                                @else
                                    {{ ucfirst($booking->contact_method) }}
                                @endif
                            </dd>
                        </div>

                        <!-- Date -->
                        <div
                            class="bg-gray-50 hover:bg-white rounded-xl p-5 border border-gray-100 relative overflow-hidden group hover:border-primary-200 transition-colors hover:shadow-sm">
                            <div
                                class="absolute right-0 top-0 w-24 h-24 bg-primary-500/5 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                            </div>
                            <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Scheduled Date
                            </dt>
                            <dd class="text-lg text-gray-900 font-bold flex items-center relative z-10">
                                <i class="fa-regular fa-calendar text-primary-500 mr-2"></i>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('l, jS M Y') }}
                            </dd>
                        </div>

                        <!-- Time -->
                        <div
                            class="bg-gray-50 hover:bg-white rounded-xl p-5 border border-gray-100 relative overflow-hidden group hover:border-primary-200 transition-colors hover:shadow-sm">
                            <div
                                class="absolute right-0 top-0 w-24 h-24 bg-primary-500/5 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                            </div>
                            <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Scheduled Time
                            </dt>
                            <dd class="text-lg text-gray-900 font-bold flex items-center relative z-10">
                                <i class="fa-regular fa-clock text-primary-500 mr-2"></i>
                                {{ $booking->booking_time }}
                            </dd>
                        </div>
                    </div>

                    <!-- Prescription Box -->
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <dt class="text-sm font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fa-solid fa-file-medical text-gray-400 mr-2"></i> Supporting Documents
                            (Prescription / Tests)
                        </dt>
                        <dd>
                            @if($booking->prescription)
                                <div
                                    class="flex items-center p-4 border border-gray-200 rounded-xl bg-white max-w-lg shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_4px_20px_rgb(0,0,0,0.06)] transition-all group">
                                    @php
                                        $ext = strtolower(pathinfo($booking->prescription, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp

                                    @if($isImage)
                                        <a href="{{ asset('storage/' . $booking->prescription) }}" target="_blank"
                                            class="block shrink-0 relative overflow-hidden rounded-lg">
                                            <div
                                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <i class="fa-solid fa-magnifying-glass-plus text-white"></i>
                                            </div>
                                            <img src="{{ asset('storage/' . $booking->prescription) }}"
                                                class="h-16 w-16 object-cover border border-gray-200" alt="Prescription">
                                        </a>
                                    @else
                                        <div
                                            class="h-16 w-16 rounded-lg border border-gray-200 flex items-center justify-center bg-red-50 text-red-500 shadow-sm">
                                            <i class="fa-solid fa-file-pdf text-3xl"></i>
                                        </div>
                                    @endif

                                    <div class="ml-4 flex-1 min-w-0">
                                        <p class="text-base font-bold text-gray-900 truncate">Patient Uploaded Document</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Click to view securely in full screen</p>
                                    </div>

                                    <a href="{{ asset('storage/' . $booking->prescription) }}" target="_blank"
                                        class="ml-4 w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>
                            @else
                                <div
                                    class="flex items-center justify-center p-8 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                                    <div class="text-center">
                                        <div
                                            class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm border border-gray-100 text-gray-400">
                                            <i class="fa-regular fa-folder-open"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">No documents attached.</p>
                                    </div>
                                </div>
                            @endif
                        </dd>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right column (Metrics & Actions) -->
        <div class="space-y-8">

            <!-- Payment Information Card -->
            <div
                class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden relative transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                <!-- Decorative top border -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-emerald-500"></div>

                <div class="p-8">
                    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Financial Overview</h2>

                    <div class="mb-8 text-center">
                        <span
                            class="block text-4xl font-black text-gray-900 tracking-tight">₦{{ number_format($booking->payment_amount, 0) }}</span>
                        <div class="mt-3 inline-flex items-center justify-center space-x-2">
                            @if($booking->payment_status === 'paid')
                                <span class="flex h-3 w-3 relative">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                <span class="text-sm font-bold text-green-600 uppercase tracking-wider">Payment
                                    Verified</span>
                            @elseif($booking->payment_status === 'unpaid')
                                <span class="flex h-3 w-3 relative">
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                                <span class="text-sm font-bold text-red-600 uppercase tracking-wider">Unpaid</span>
                            @else
                                <span class="flex h-3 w-3 relative">
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-400"></span>
                                </span>
                                <span class="text-sm font-bold text-yellow-600 uppercase tracking-wider">Pending
                                    Gateway</span>
                            @endif
                        </div>
                    </div>

                    <div
                        class="bg-gray-50 rounded-xl p-4 mb-6 text-sm flex justify-between items-center border border-gray-100">
                        <span class="text-gray-500 font-medium font-mono border-r border-gray-200 pr-4">REF</span>
                        <span class="text-gray-900 font-medium truncate pl-4"
                            title="{{ $booking->payment_id }}">{{ $booking->payment_id ?? 'Awaiting ID' }}</span>
                    </div>

                    <!-- Payment Toggles -->
                    <div class="pt-6 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Manual
                            Override</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button wire:click="updatePaymentStatus('paid')" @class([
                                'col-span-1 px-4 py-3 rounded-xl text-sm font-bold transition-all focus:ring-2 focus:ring-offset-2 flex items-center justify-center',
                                'bg-green-600 text-white shadow-lg shadow-green-500/30 ring-green-500 focus:ring-green-500' => $booking->payment_status === 'paid',
                                'bg-white border text-gray-700 border-gray-200 hover:bg-green-50 hover:border-green-300 hover:text-green-700' => $booking->payment_status !== 'paid',
                            ])>
                                <i class="fa-solid fa-check-double mr-2"></i> Paid
                            </button>
                            <button wire:click="updatePaymentStatus('unpaid')" @class([
                                'col-span-1 px-4 py-3 rounded-xl text-sm font-bold transition-all focus:ring-2 focus:ring-offset-2 flex items-center justify-center',
                                'bg-red-600 text-white shadow-lg shadow-red-500/30 ring-red-500 focus:ring-red-500' => $booking->payment_status === 'unpaid',
                                'bg-white border text-gray-700 border-gray-200 hover:bg-red-50 hover:border-red-300 hover:text-red-700' => $booking->payment_status !== 'unpaid',
                            ])>
                                <i class="fa-solid fa-ban mr-2"></i> Unpaid
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Control Center -->
            <div class="bg-[#2b1770] text-white rounded-2xl shadow-xl overflow-hidden relative">
                <!-- Abstract glowing orb -->
                <div
                    class="absolute -right-20 -top-20 w-64 h-64 bg-primary-500 opacity-20 rounded-full blur-3xl mix-blend-screen pointer-events-none">
                </div>
                <div
                    class="absolute -left-20 -bottom-20 w-48 h-48 bg-purple-500 opacity-20 rounded-full blur-3xl mix-blend-screen pointer-events-none">
                </div>

                <div class="p-8 relative z-10">
                    <h2 class="text-sm font-bold text-indigo-200 uppercase tracking-widest mb-6">Admin Controls</h2>

                    <div class="space-y-3">
                        <button wire:click="updateBookingStatus('confirmed')" @class([
                            'w-full relative overflow-hidden group px-6 py-4 rounded-xl flex items-center justify-between font-bold transition-all duration-300',
                            'bg-white/20 ring-2 ring-white text-white' => $booking->booking_status === 'confirmed',
                            'bg-white/5 hover:bg-white/10 text-white' => $booking->booking_status !== 'confirmed',
                        ])>
                            <span class="relative z-10 flex items-center">
                                <i class="fa-regular fa-calendar-check text-xl mr-3 w-6 text-white"></i>
                                Confirm Session
                            </span>
                            @if($booking->booking_status === 'confirmed')
                                <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-check text-xs text-[#2b1770]"></i>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 opacity-0 group-hover:opacity-20 transition-opacity">
                            </div>
                        </button>

                        <button wire:click="updateBookingStatus('completed')" @class([
                            'w-full relative overflow-hidden group px-6 py-4 rounded-xl flex items-center justify-between font-bold transition-all duration-300',
                            'bg-white/20 ring-2 ring-green-400 text-white' => $booking->booking_status === 'completed',
                            'bg-white/5 hover:bg-white/10 text-white' => $booking->booking_status !== 'completed',
                        ])>
                            <span class="relative z-10 flex items-center">
                                <i class="fa-regular fa-flag text-xl mr-3 w-6 text-white"></i>
                                Mark Completed
                            </span>
                            @if($booking->booking_status === 'completed')
                                <div class="w-6 h-6 bg-green-400 rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-check text-xs text-[#2b1770]"></i>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-green-400 to-emerald-500 opacity-0 group-hover:opacity-20 transition-opacity">
                            </div>
                        </button>

                        <div class="w-full h-px bg-white/10 my-4"></div>

                        <button wire:click="updateBookingStatus('cancelled')" @class([
                            'w-full relative overflow-hidden group px-6 py-4 rounded-xl flex items-center justify-between font-bold transition-all duration-300',
                            'bg-red-500 text-white' => $booking->booking_status === 'cancelled',
                            'bg-red-500/10 hover:bg-red-500/20 text-red-100 hover:text-white' => $booking->booking_status !== 'cancelled',
                        ])>
                            <span class="relative z-10 flex items-center">
                                <i class="fa-solid fa-ban text-xl mr-3 w-6 text-white"></i>
                                Cancel Booking
                            </span>
                            @if($booking->booking_status === 'cancelled')
                                <div class="w-6 h-6 bg-white/30 rounded-full flex items-center justify-center text-white">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                            @endif
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>