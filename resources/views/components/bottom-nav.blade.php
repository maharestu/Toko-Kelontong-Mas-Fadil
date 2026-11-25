@props([
    'active' => null,
])

@php
    $isHome = $active ? $active === 'home' : request()->routeIs('dashboard');
    $isProduk = $active ? $active === 'produk' : (request()->routeIs('products.*') || request()->routeIs('categories.*'));
    $isKasir = $active ? $active === 'kasir' : (request()->routeIs('cashier.*') || request()->routeIs('sales.*'));
    $isPoster = $active ? $active === 'poster' : false;
    $isLainnya = $active
        ? $active === 'lainnya'
        : (request()->routeIs('lainnya') || request()->routeIs('restocks.*') || request()->routeIs('laporan*') || request()->routeIs('profile.*'));
@endphp

<!-- Bottom Navigation Bar (Fixed at Viewport Bottom, Aligned to 500px Container) -->
<nav aria-label="Navigasi Utama" class="fixed bottom-0 left-0 right-0 z-40 pointer-events-none">
    <div class="pointer-events-auto max-w-[500px] mx-auto bg-white/95 backdrop-blur-md border-t border-x border-gray-100 px-3 py-2.5 sm:rounded-t-2xl shadow-[0_-4px_20px_-4px_rgba(0,0,0,0.06)]">
        <div class="grid grid-cols-5 items-center justify-items-center">
            <!-- Home -->
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ $isHome ? 'text-[#173628]' : 'text-gray-400 hover:text-gray-700 transition' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $isHome ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span class="text-[10px] {{ $isHome ? 'font-semibold text-[#173628]' : 'font-medium' }} tracking-tight">Home</span>
            </a>

            <!-- Produk -->
            <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-1 {{ $isProduk ? 'text-[#173628]' : 'text-gray-400 hover:text-gray-700 transition' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $isProduk ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                <span class="text-[10px] {{ $isProduk ? 'font-semibold text-[#173628]' : 'font-medium' }} tracking-tight">Produk</span>
            </a>

            <!-- Kasir -->
            <a href="{{ route('cashier.index') }}" class="flex flex-col items-center gap-1 {{ $isKasir ? 'text-[#173628]' : 'text-gray-400 hover:text-gray-700 transition' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $isKasir ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M3 9h18"/>
                    <path d="M9 21V9"/>
                </svg>
                <span class="text-[10px] {{ $isKasir ? 'font-semibold text-[#173628]' : 'font-medium' }} tracking-tight">Kasir</span>
            </a>

            <!-- Poster -->
            <a href="#" class="flex flex-col items-center gap-1 {{ $isPoster ? 'text-[#173628]' : 'text-gray-400 hover:text-gray-700 transition' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $isPoster ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                    <circle cx="9" cy="9" r="2"/>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                </svg>
                <span class="text-[10px] {{ $isPoster ? 'font-semibold text-[#173628]' : 'font-medium' }} tracking-tight">Poster</span>
            </a>

            <!-- Lainnya -->
            <a href="{{ route('lainnya') }}" class="flex flex-col items-center gap-1 {{ $isLainnya ? 'text-[#173628]' : 'text-gray-400 hover:text-gray-700 transition' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $isLainnya ? '2.2' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"/>
                    <circle cx="19" cy="12" r="1"/>
                    <circle cx="5" cy="12" r="1"/>
                </svg>
                <span class="text-[10px] {{ $isLainnya ? 'font-semibold text-[#173628]' : 'font-medium' }} tracking-tight">Lainnya</span>
            </a>
        </div>
    </div>
</nav>
