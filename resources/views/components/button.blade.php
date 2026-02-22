@props([
    'type'    => 'submit',
    'variant' => 'primary',   // primary | secondary | danger | ghost
    'size'    => 'md',        // sm | md | lg
    'full'    => false,
    'target'  => null,        // wire:target e.g. 'login' or 'save'
])

@php
    $base = 'inline-flex justify-center items-center gap-x-2 font-medium rounded-lg transition disabled:opacity-50 disabled:pointer-events-none focus:outline-none';

    $variants = [
        'primary'   => 'bg-primary-500 border border-primary-600 text-white hover:bg-primary-600 focus:bg-primary-600',
        'secondary' => 'bg-gray-100 border border-gray-200 text-foreground hover:bg-gray-200 focus:bg-gray-200',
        'danger'    => 'bg-red-500 border border-red-600 text-white hover:bg-red-600 focus:bg-red-600',
        'ghost'     => 'bg-transparent border border-gray-200 text-foreground hover:bg-gray-100 focus:bg-gray-100',
    ];

    $sizes = [
        'sm' => 'py-1.5 px-3 text-xs',
        'md' => 'py-2.5 px-4 text-sm',
        'lg' => 'py-3 px-5 text-base',
    ];

    $classes = implode(' ', array_filter([
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
        $full ? 'w-full' : '',
    ]));
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
    wire:loading.attr="disabled"
    @if($target) wire:target="{{ $target }}" @endif
>
    {{-- Spinner: hidden by default, shown during loading --}}
    <svg
        wire:loading.class.remove="hidden"
        @if($target) wire:target="{{ $target }}" @endif
        class="hidden animate-spin size-4 shrink-0"
        xmlns="http://www.w3.org/2000/svg"
        fill="none" viewBox="0 0 24 24"
        aria-hidden="true"
    >
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>

    {{-- Label: visible by default, hidden during loading --}}
    <span
        wire:loading.class="hidden"
        @if($target) wire:target="{{ $target }}" @endif
    >{{ $slot }}</span>
</button>