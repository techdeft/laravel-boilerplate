<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} | {{ $title ?? 'Premium Pharmacy' }}</title>
    <meta name="description"
        content="{{ $description ?? \App\Models\SiteSetting::getValue('meta_description', 'MedMall - Your trusted online pharmacy and wellness store.') }}">
    <meta name="keywords" content="{{ \App\Models\SiteSetting::getValue('meta_keywords', 'pharmacy, medicine, wellness, nigeria') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $description ?? 'MedMall - Your trusted online pharmacy and wellness store.' }}">
    <meta property="og:image" content="{{ asset('assets/img/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $title ?? config('app.name') }}">
    <meta property="twitter:description" content="{{ $description ?? 'MedMall - Your trusted online pharmacy and wellness store.' }}">
    <meta property="twitter:image" content="{{ asset('assets/img/og-image.jpg') }}">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="body">
    <x-guest-nav />
    {{ $slot }}
    <x-guest-footer />
    @livewireScripts
</body>

</html>