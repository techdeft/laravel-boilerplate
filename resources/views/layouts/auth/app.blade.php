<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ $title }}</title>
    <meta name="description" content="{{ $description }}">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="body h-screen bg-gray-50 justify-center items-center flex">

    <div class="bg-card  border-card-line rounded-xl shadow-2xs w-md m-auto">
        {{ $slot }}
    </div>


    @livewireScripts
</body>

</html>