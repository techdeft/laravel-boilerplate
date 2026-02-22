<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? '' }}">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

{{-- x-data:open controls sidebar visibility on mobile --}}

<body class="bg-background" x-data="{ open: false }">

    {{-- Sidebar (includes its own mobile backdrop) --}}
    @include('layouts.app.sidebar')

    {{-- Main content wrapper pushed right on desktop --}}
    <div class="lg:ps-64 min-h-screen flex flex-col">

        {{-- Sticky top header --}}
        @include('layouts.app.header')

        {{-- Page content --}}
        <main class="flex-1 p-4 sm:p-6 space-y-4 sm:space-y-6">
            {{ $slot }}
        </main>

    </div>

    @livewireScripts
</body>

</html>