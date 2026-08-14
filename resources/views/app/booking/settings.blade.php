<?php

use function Livewire\Volt\{state, with, rules, action, mount};
use App\Models\BookingSetting;
use App\Models\PharmacistSchedule;

state([
    'package_name' => '',
    'duration_minutes' => 15,
    'price' => 0.00,
    'editing_id' => null
]);

mount(function () {
    // Initialize default weekly schedule if it doesn't exist
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    foreach ($days as $day) {
        // Weekends inactive by default
        $isActive = in_array($day, ['Saturday', 'Sunday']) ? false : true;
        PharmacistSchedule::firstOrCreate(
            ['day_of_week' => $day],
            ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'is_active' => $isActive]
        );
    }
});

with(fn() => [
    'settings' => BookingSetting::all(),
    'schedules' => PharmacistSchedule::all()->sortBy(function ($schedule) {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return array_search($schedule->day_of_week, $days);
    })->values()
]);

rules([
    'package_name' => 'required|string|max:255',
    'duration_minutes' => 'required|integer|min:1',
    'price' => 'required|numeric|min:0',
]);

$save = action(function () {
    $this->validate();

    BookingSetting::updateOrCreate(
        ['id' => $this->editing_id],
        [
            'package_name' => $this->package_name,
            'duration_minutes' => $this->duration_minutes,
            'price' => $this->price,
            'is_active' => true,
        ]
    );

    $this->reset(['package_name', 'duration_minutes', 'price', 'editing_id']);
    $this->dispatch('package-saved');
});

$edit = action(function ($id) {
    $setting = BookingSetting::find($id);
    $this->editing_id = $id;
    $this->package_name = $setting->package_name;
    $this->duration_minutes = $setting->duration_minutes;
    $this->price = $setting->price;
});

$toggleActive = action(function ($id) {
    $setting = BookingSetting::find($id);
    $setting->update(['is_active' => !$setting->is_active]);
});

$delete = action(function ($id) {
    BookingSetting::find($id)->delete();
});

$updateSchedule = action(function ($id, $field, $value) {
    PharmacistSchedule::find($id)->update([$field => $value]);
});

$toggleScheduleActive = action(function ($id) {
    $schedule = PharmacistSchedule::find($id);
    $schedule->update(['is_active' => !$schedule->is_active]);
});

?>

<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Booking Settings</h1>
        <p class="text-sm text-gray-500">Configure consultation packages and pricing.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">{{ $editing_id ? 'Edit Package' : 'Add New Package' }}</h2>
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Package Name</label>
                        <input wire:model="package_name" type="text" placeholder="e.g. One-on-One Consultation"
                            class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('package_name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Minutes)</label>
                        <input wire:model="duration_minutes" type="number"
                            class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('duration_minutes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm">₦</span>
                            <input wire:model="price" type="number" step="0.01"
                                class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        @error('price') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-blue-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition-colors">
                            {{ $editing_id ? 'Update Package' : 'Create Package' }}
                        </button>
                        @if($editing_id)
                            <button type="button"
                                wire:click="$reset(['package_name', 'duration_minutes', 'price', 'editing_id'])"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- List --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Package</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($settings as $setting)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $setting->package_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $setting->duration_minutes }} mins
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₦{{ number_format($setting->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="toggleActive({{ $setting->id }})" @class([
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer',
                                        'bg-green-100 text-green-800' => $setting->is_active,
                                        'bg-gray-100 text-gray-800' => !$setting->is_active,
                                    ])>
                                        {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $setting->id }})"
                                        class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button wire:click="delete({{ $setting->id }})"
                                        wire:confirm="Are you sure you want to delete this package?"
                                        class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    No consultation packages configured.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Weekly Schedule Management --}}
    <div class="mt-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900">Weekly Availability Schedule</h2>
                <span class="text-xs text-gray-500">Changes to times are saved automatically.</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($schedules as $schedule)
                        <div
                            class="border {{ $schedule->is_active ? 'border-primary-200 bg-primary-50/10' : 'border-gray-200 bg-gray-50 opacity-75' }} rounded-lg p-4 transition-all">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-bold text-gray-900">{{ $schedule->day_of_week }}</span>
                                <button wire:click="toggleScheduleActive({{ $schedule->id }})" @class([
                                    'px-2 py-1 text-xs font-semibold rounded-full cursor-pointer transition-colors',
                                    'bg-green-100 text-green-800 hover:bg-green-200' => $schedule->is_active,
                                    'bg-gray-200 text-gray-600 hover:bg-gray-300' => !$schedule->is_active,
                                ])>
                                    {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Start Time</label>
                                    <input type="time"
                                        wire:change="updateSchedule({{ $schedule->id }}, 'start_time', $event.target.value)"
                                        value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}"
                                        class="block w-full border-gray-300 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500 {{ !$schedule->is_active ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ !$schedule->is_active ? 'disabled' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">End Time</label>
                                    <input type="time"
                                        wire:change="updateSchedule({{ $schedule->id }}, 'end_time', $event.target.value)"
                                        value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}"
                                        class="block w-full border-gray-300 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500 {{ !$schedule->is_active ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ !$schedule->is_active ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>