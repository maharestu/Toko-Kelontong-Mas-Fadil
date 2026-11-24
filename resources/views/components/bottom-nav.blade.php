@props([
    'active' => 'kategori',
])

@php
    $navItems = [
        [
            'id' => 'home',
            'label' => 'Home',
            'url' => url('/'),
            'icon' => 'home',
        ],
        [
            'id' => 'produk',
            'label' => 'Produk',
            'url' => Route::has('products.index') ? route('products.index') : '#',
            'icon' => 'box',
        ],
        [
            'id' => 'kasir',
            'label' => 'Kasir',
            'url' => Route::has('cashier.index') ? route('cashier.index') : (Route::has('sales.index') ? route('sales.index') : '#'),
            'icon' => 'register',
        ],
        [
            'id' => 'kategori',
            'label' => 'Kategori',
            'url' => route('categories.index'),
            'icon' => 'store',
        ],
        [
            'id' => 'lainnya',
            'label' => 'Lainnya',
            'url' => route('lainnya'),
            'icon' => 'grid',
        ],
    ];
@endphp

<nav class="fixed bottom-0 left-0 right-0 z-40">
    <div class="max-w-md mx-auto bg-white/95 backdrop-blur-md border-t border-gray-200 shadow-lg px-2 py-2 flex items-center justify-around">
        @foreach ($navItems as $item)
            @php
                $isActive = ($active === $item['id']);
            @endphp
            <a href="{{ $item['url'] }}" 
               class="flex flex-col items-center justify-center flex-1 py-1 px-1 transition-colors {{ $isActive ? 'text-[#133c30] font-bold' : 'text-gray-400 hover:text-gray-600 font-medium' }}">
                <div class="w-6 h-6 flex items-center justify-center mb-0.5">
                    @if ($item['icon'] === 'home')
                        <svg class="w-5 h-5 {{ $isActive ? 'stroke-[#133c30] stroke-[2.2]' : 'stroke-gray-400 stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    @elseif ($item['icon'] === 'box')
                        <svg class="w-5 h-5 {{ $isActive ? 'stroke-[#133c30] stroke-[2.2]' : 'stroke-gray-400 stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    @elseif ($item['icon'] === 'register')
                        <svg class="w-5 h-5 {{ $isActive ? 'stroke-[#133c30] stroke-[2.2]' : 'stroke-gray-400 stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    @elseif ($item['icon'] === 'store')
                        <svg class="w-5 h-5 {{ $isActive ? 'stroke-[#133c30] stroke-[2.2]' : 'stroke-gray-400 stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 {{ $isActive ? 'stroke-[#133c30] stroke-[2.2]' : 'stroke-gray-400 stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    @endif
                </div>
                <span class="text-[11px] leading-tight">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>

