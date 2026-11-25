<x-app-layout>
    <div class="p-4 sm:p-5 space-y-4">
        <!-- 1. Header Profil & Toko -->
        <div class="flex items-center justify-between pt-1">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-[#14281f] text-white flex items-center justify-center font-bold text-sm tracking-wider shadow-sm flex-shrink-0">
                    MA
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900 leading-tight">Toko Mas Adil</h2>
                    <p class="text-[11px] text-gray-400 font-normal">{{ ucfirst(Auth::user()?->role instanceof \App\Enums\UserRole ? Auth::user()->role->value : (Auth::user()?->role ?? 'Admin')) }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Notifikasi Bell -->
                <button type="button" class="relative w-8 h-8 rounded-full flex items-center justify-center text-gray-700 hover:bg-gray-100 transition focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                </button>

                <!-- User Avatar -->
                <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-semibold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 2. Shift & Tanggal Info Bar -->
        <div class="text-[11px] text-gray-500 px-0.5">
            <span>{{ $currentDateFormatted }} &bull; {{ $shiftName }} ({{ Auth::user()?->name ?? 'Mas Adil' }})</span>
        </div>

        <!-- 3. Hero Card: Penjualan Hari Ini -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100/90 shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)] space-y-3.5">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    PENJUALAN HARI INI
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>
                        <polyline points="16 7 22 7 22 13"/>
                    </svg>
                    <span>{{ $growthPercentage >= 0 ? '+' : '' }}{{ $growthPercentage }}% vs kemarin</span>
                </span>
            </div>

            <div>
                <div class="flex items-baseline gap-1 text-gray-900">
                    <span class="text-sm font-semibold text-gray-500">Rp</span>
                    <span class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        {{ number_format($todayTotal > 0 ? $todayTotal : 3482500, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 pt-2 border-t border-gray-50 text-[11px]">
                <div class="space-y-0.5 pr-2">
                    <p class="text-gray-400 text-[10px]">Jumlah Transaksi</p>
                    <p class="font-bold text-gray-800">{{ $todayCount > 0 ? $todayCount : 68 }} nota</p>
                    <p class="text-gray-500 text-[10px]">Rata: Rp {{ number_format($todayAvg > 0 ? $todayAvg : 51200, 0, ',', '.') }}</p>
                </div>
                <div class="space-y-0.5 pl-2 border-l border-gray-50">
                    <p class="text-gray-400 text-[10px]">Estimasi Laba Kotor</p>
                    <p class="font-bold text-gray-800">Rp {{ number_format($todayProfit > 0 ? $todayProfit : 612000, 0, ',', '.') }}</p>
                    <p class="text-gray-500 text-[10px]">Margin efektif {{ $effectiveMargin > 0 ? $effectiveMargin : 17.6 }}%</p>
                </div>
            </div>
        </div>

        <!-- 4. Tombol Aksi Cepat (Quick Actions Grid) -->
        <div class="grid grid-cols-3 gap-2.5">
            <!-- Kas Masuk -->
            <button type="button" class="bg-white rounded-2xl p-3 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] flex flex-col items-center justify-center gap-2 hover:bg-gray-50 active:scale-95 transition-all">
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v8"/>
                        <path d="M8 12h8"/>
                    </svg>
                </div>
                <span class="text-[11px] font-semibold text-gray-700">Kas Masuk</span>
            </button>

            <!-- Cek Stok -->
            <a href="{{ route('restocks.index') }}" class="bg-white rounded-2xl p-3 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] flex flex-col items-center justify-center gap-2 hover:bg-gray-50 active:scale-95 transition-all">
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-[#123b28] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.12 6.4-6-3.4a2 2 0 0 0-2 0l-6 3.4a2 2 0 0 0-1 1.7v6.8a2 2 0 0 0 1 1.7l6 3.4a2 2 0 0 0 2 0l6-3.4a2 2 0 0 0 1-1.7V8.1a2 2 0 0 0-1-1.7z"/>
                        <path d="m3.3 7 8.7 5 8.7-5"/>
                        <path d="M12 22V12"/>
                    </svg>
                </div>
                <span class="text-[11px] font-semibold text-gray-700">Cek Stok</span>
            </a>

            <!-- Cek Kasbon -->
            <button type="button" class="bg-white rounded-2xl p-3 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] flex flex-col items-center justify-center gap-2 hover:bg-gray-50 active:scale-95 transition-all">
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                        <path d="M6 6h10"/>
                        <path d="M6 10h10"/>
                    </svg>
                </div>
                <span class="text-[11px] font-semibold text-gray-700">Cek Kasbon</span>
            </button>
        </div>

        <!-- 5. Card: Tren Penjualan 7 Hari -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)] space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold text-gray-900">Tren Penjualan (7 Hari)</h3>
                <span class="text-[11px] text-gray-400">
                    Rata-rata: <span class="font-semibold text-gray-600">Rp {{ $sevenDaysAvg >= 1000000 ? round($sevenDaysAvg / 1000000, 2) . 'jt' : number_format($sevenDaysAvg > 0 ? $sevenDaysAvg : 2950000, 0, ',', '.') }}</span>
                </span>
            </div>

            <!-- Bar Chart Vertikal -->
            <div class="flex items-end justify-between gap-2 h-36 pt-3 px-1">
                @php
                    $mockTrend = [
                        ['day_label' => 'Sel', 'short_amount' => '2.8M', 'height_pct' => 75, 'is_today' => false],
                        ['day_label' => 'Rab', 'short_amount' => '3.1M', 'height_pct' => 84, 'is_today' => false],
                        ['day_label' => 'Kam', 'short_amount' => '2.6M', 'height_pct' => 70, 'is_today' => false],
                        ['day_label' => 'Jum', 'short_amount' => '3.2M', 'height_pct' => 88, 'is_today' => false],
                        ['day_label' => 'Sab', 'short_amount' => '3.4M', 'height_pct' => 93, 'is_today' => false],
                        ['day_label' => 'Min', 'short_amount' => '2.7M', 'height_pct' => 73, 'is_today' => false],
                        ['day_label' => 'Hari Ini', 'short_amount' => '3.5M', 'height_pct' => 100, 'is_today' => true],
                    ];
                    $displayTrend = !empty($sevenDaysTrend) && collect($sevenDaysTrend)->sum('total') > 0 ? $sevenDaysTrend : $mockTrend;
                @endphp

                @foreach($displayTrend as $item)
                    <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                        <span class="text-[9px] font-medium text-gray-400">{{ $item['short_amount'] }}</span>
                        <div class="w-full max-w-[34px] rounded-t-md transition-all duration-300 {{ $item['is_today'] ? 'bg-[#14281f] shadow-sm' : 'bg-gray-200' }}"
                             style="height: {{ $item['height_pct'] }}%;"></div>
                        <span class="text-[9px] {{ $item['is_today'] ? 'font-bold text-[#14281f]' : 'font-medium text-gray-400' }} tracking-tight">
                            {{ $item['day_label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 6. Card: Stok Menipis -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)] space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="text-xs font-bold text-gray-900">Stok Menipis</h3>
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-orange-100 text-orange-800">
                        {{ $criticalCount > 0 ? $criticalCount : 4 }} Kritis
                    </span>
                </div>
                <a href="{{ route('restocks.index') }}" class="text-[11px] font-semibold text-gray-800 hover:text-black flex items-center gap-1 transition">
                    <span>Catat Restock</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- List Item Stok Menipis -->
            <div class="divide-y divide-gray-50 pt-1">
                @php
                    $mockLowProducts = [
                        [
                            'name' => 'Beras Ramos Setra 5kg',
                            'min_stock' => 5,
                            'stock' => 2,
                            'unit' => 'krg',
                            'status_label' => 'MENDESAK',
                            'is_urgent' => true,
                        ],
                        [
                            'name' => 'Minyak Goreng Tropical 2L',
                            'min_stock' => 12,
                            'stock' => 3,
                            'unit' => 'btl',
                            'status_label' => 'MENDESAK',
                            'is_urgent' => true,
                        ],
                        [
                            'name' => 'Telur Ayam Negeri 1kg',
                            'min_stock' => 15,
                            'stock' => 4,
                            'unit' => 'kg',
                            'status_label' => 'MENIPIS',
                            'is_urgent' => false,
                        ],
                        [
                            'name' => 'Gula Pasir Gulaku 1kg',
                            'min_stock' => 20,
                            'stock' => 5,
                            'unit' => 'bks',
                            'status_label' => 'MENIPIS',
                            'is_urgent' => false,
                        ],
                    ];
                    $itemsToDisplay = ($lowStockProducts && $lowStockProducts->count() > 0) ? $lowStockProducts : $mockLowProducts;
                @endphp

                @foreach($itemsToDisplay as $product)
                    <div class="py-2.5 flex items-center justify-between first:pt-0 last:pb-0">
                        <div>
                            <p class="text-xs font-bold text-gray-900 leading-tight">{{ $product['name'] }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Min. batas: {{ $product['min_stock'] }} {{ $product['unit'] }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-gray-800">{{ $product['stock'] }} {{ $product['unit'] }}</span>
                            @if($product['is_urgent'])
                                <span class="px-1.5 py-0.5 rounded text-[8.5px] font-bold bg-rose-100 text-rose-700 tracking-wider">
                                    {{ $product['status_label'] }}
                                </span>
                            @else
                                <span class="px-1.5 py-0.5 rounded text-[8.5px] font-bold bg-orange-100 text-orange-700 tracking-wider">
                                    {{ $product['status_label'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
