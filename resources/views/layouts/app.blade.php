<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Toko Mas Adil') }} - Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 bg-[#f4f5f6] antialiased min-h-screen">
        <div class="min-h-screen flex flex-col items-center">
            <!-- Mobile/Tablet Container (Max Width 480px) for pixel-faithful app look, responsive on larger screens -->
            <div class="w-full max-w-[500px] min-h-screen bg-[#f8f9fa] shadow-xl sm:my-4 sm:rounded-3xl border border-gray-100 flex flex-col relative overflow-hidden">
                <!-- Page Content -->
                <main class="flex-1 pb-24">
                    {{ $slot }}
                </main>

                <!-- Bottom Navigation Bar (Fixed to Container) -->
                <nav class="sticky bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-100 px-3 py-2.5 z-30">
                    <div class="grid grid-cols-5 items-center justify-items-center">
                        <!-- Home (Active) -->
                        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 text-[#173628]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                            <span class="text-[10px] font-semibold tracking-tight">Home</span>
                        </a>

                        <!-- Produk -->
                        <a href="#" class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            <span class="text-[10px] font-medium tracking-tight">Produk</span>
                        </a>

                        <!-- Kasir -->
                        <a href="#" class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2"/>
                                <path d="M3 9h18"/>
                                <path d="M9 21V9"/>
                            </svg>
                            <span class="text-[10px] font-medium tracking-tight">Kasir</span>
                        </a>

                        <!-- Kategori -->
                        <a href="#" class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1"/>
                                <rect width="7" height="7" x="14" y="3" rx="1"/>
                                <rect width="7" height="7" x="14" y="14" rx="1"/>
                                <rect width="7" height="7" x="3" y="14" rx="1"/>
                            </svg>
                            <span class="text-[10px] font-medium tracking-tight">Kategori</span>
                        </a>

                        <!-- Lainnya / Profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-700 transition focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"/>
                                    <circle cx="19" cy="12" r="1"/>
                                    <circle cx="5" cy="12" r="1"/>
                                </svg>
                                <span class="text-[10px] font-medium tracking-tight">Lainnya</span>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false" 
                                 x-cloak
                                 class="absolute bottom-12 right-0 w-44 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 text-xs text-gray-700 z-50">
                                <div class="px-3.5 py-2 border-b border-gray-100">
                                    <p class="font-bold text-gray-900 truncate">{{ Auth::user()?->name ?? 'Mas Adil (Owner)' }}</p>
                                    <p class="text-[10px] text-gray-500 capitalize">{{ (Auth::user()?->role instanceof \App\Enums\UserRole ? Auth::user()?->role->value : Auth::user()?->role) ?? 'admin' }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3.5 py-2 hover:bg-gray-50 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>Profil Pengguna</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-3.5 py-2 text-rose-600 hover:bg-rose-50 transition text-left">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                        <span>Keluar Akun</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </body>
</html>
