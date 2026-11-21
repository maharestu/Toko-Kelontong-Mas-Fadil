@props([
    'title' => 'Toko Kelontong Mas Fadil',
    'active' => '',
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">
    <!-- Responsive Mobile-First Centered Container -->
    <div class="max-w-md mx-auto min-h-screen bg-[#fafbfc] flex flex-col relative shadow-xl border-x border-gray-200/70">
        <!-- Main Flash Alerts -->
        <x-alert />

        <!-- Main Slot Content -->
        <main class="flex-1 pb-24">
            {{ $slot }}
        </main>

        <!-- Bottom Navigation Bar -->
        <x-bottom-nav :active="$active" />
    </div>

    @stack('scripts')
</body>
</html>

