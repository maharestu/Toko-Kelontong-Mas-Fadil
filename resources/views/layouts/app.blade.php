<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Toko Mas Adil') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans text-gray-900 bg-[#f4f5f6] antialiased min-h-screen">
        <div class="min-h-screen flex flex-col items-center">
            <!-- Mobile/Tablet Container (Max Width 500px) for pixel-faithful app look, responsive on larger screens -->
            <div class="w-full max-w-[500px] min-h-screen bg-[#f8f9fa] shadow-xl sm:my-4 sm:rounded-3xl border border-gray-100 flex flex-col relative overflow-hidden">
                <!-- Global Flash Alerts -->
                <x-alert />

                <!-- Page Content -->
                <main class="flex-1 pb-24">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Fixed Bottom Navigation Component -->
        <x-bottom-nav :active="$active ?? null" />

        @stack('scripts')
    </body>
</html>
