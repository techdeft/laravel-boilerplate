<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ $title ?? config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? 'Medmall - Secure Access' }}">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="body min-h-screen bg-gray-50 dark:bg-slate-900 font-sans antialiased">
    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2">

        <!-- Left Half: Solid Primary Color Background -->

        <div class="hidden md:block bg-primary-500" style="position: relative; overflow: hidden; min-height: 100vh;">
            <!-- Top Right Icon -->
            <i class="fa-solid fa-prescription-bottle-medical"
                style="position: absolute; top: -40px; right: -40px; font-size: 240px; color: white; opacity: 0.15; pointer-events: none;"></i>

            <!-- Bottom Left Icon -->
            <i class="fa-solid fa-stethoscope"
                style="position: absolute; bottom: -40px; left: -40px; font-size: 240px; color: white; opacity: 0.15; pointer-events: none;"></i>

            <!-- Middle Right Icon -->
            <i class="fa-solid fa-pills"
                style="position: absolute; top: 35%; right: -40px; font-size: 180px; color: white; opacity: 0.15; pointer-events: none;"></i>

            <!-- Middle Left Icon -->
            <i class="fa-solid fa-heart-pulse"
                style="position: absolute; bottom: 30%; left: -40px; font-size: 180px; color: white; opacity: 0.15; pointer-events: none;"></i>

            <!-- Top Center Icon -->
            <i class="fa-solid fa-capsules"
                style="position: absolute; top: 60px; left: 30%; font-size: 160px; color: white; opacity: 0.15; pointer-events: none;"></i>

            <!-- Bottom Center Icon -->
            <i class="fa-solid fa-kit-medical"
                style="position: absolute; bottom: 60px; left: 40%; font-size: 160px; color: white; opacity: 0.15; pointer-events: none;"></i>
        </div>



        <!-- Right Half: Form Container -->
        <div
            class="flex flex-col justify-center items-center p-6 sm:p-12 lg:p-16 bg-white dark:bg-slate-900 min-h-screen overflow-y-auto">

            <style>
                @media (min-width: 768px) {
                    .auth-mobile-logo {
                        display: none !important;
                    }
                }
            </style>

            <!-- Mobile Top Logo (Visible on mobile screens under 768px only) -->
            <div class="auth-mobile-logo w-full max-w-md text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-2">
                    <img src="/images/logo.png" alt="Medmall Logo" class="h-10 w-auto mx-auto"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <span class="hidden text-2xl font-bold text-slate-900 dark:text-white">
                        <span class="text-primary-500">Med</span>Mall
                    </span>
                </a>
            </div>

            <!-- Auth Form Card Container -->
            <div class="w-full max-w-md bg-white dark:bg-slate-800/80 p-6 sm:p-8">
                {{ $slot }}
            </div>

            <!-- Back to Home Link -->
            <div class="mt-2 text-center">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-primary-500 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Home Store
                </a>
            </div>
        </div>

    </div>

    @livewireScripts
</body>

</html>