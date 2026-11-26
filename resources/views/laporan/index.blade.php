<x-app-layout :title="'Laporan - '.config('app.name')" active="lainnya">
    @push('styles')
        <style>
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            .tabular-nums { font-variant-numeric: tabular-nums; }
            .tap-scale { transition: transform 0.14s ease, background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease; }
            .tap-scale:active { transform: scale(0.97); }

            @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
            @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
            .animate-fade-in { animation: fadeIn 0.18s ease-out forwards; }
            .animate-slide-up { animation: slideUp 0.24s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        </style>
    @endpush

    <!-- 1. Header (Sticky) -->
    <header class="h-[58px] bg-white/95 backdrop-blur-md border-b border-gray-100 px-4 flex items-center justify-between sticky top-0 z-20">
        <a id="back-lainnya-btn" href="{{ route('lainnya') }}" class="flex items-center gap-1.5 text-[#173628] hover:text-[#10271c] text-sm font-semibold py-2 pr-2 -ml-1 rounded-lg tap-scale">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 19-7-7 7-7"/>
                <path d="M19 12H5"/>
            </svg>
            <span>Lainnya</span>
        </a>

        <h1 class="text-base font-bold tracking-tight text-gray-900 absolute left-1/2 -translate-x-1/2 pointer-events-none">
            Laporan
        </h1>

        <button id="open-export-btn" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200/70 text-[#173628] hover:bg-emerald-100 text-xs font-semibold tap-scale cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                <path d="M8 13h2"/>
                <path d="M14 13h2"/>
                <path d="M8 17h2"/>
                <path d="M14 17h2"/>
            </svg>
            <span>Excel</span>
        </button>
    </header>

    <!-- Main Content -->
    <div class="p-4 sm:p-5 space-y-4">

        <!-- 2. Segmented Filter Periode -->
        <section aria-label="Filter Periode" class="space-y-2">
            <div id="period-switcher" class="bg-gray-100 border border-gray-200/70 p-1 rounded-xl grid grid-cols-3 gap-1">
                <button type="button" data-period="today" class="period-btn py-2 text-xs font-semibold rounded-lg bg-white text-[#173628] border border-gray-100 shadow-xs transition-all tap-scale cursor-pointer">
                    Hari Ini
                </button>
                <button type="button" data-period="week" class="period-btn py-2 text-xs font-semibold rounded-lg text-gray-500 hover:text-gray-900 transition-all tap-scale cursor-pointer">
                    Minggu Ini
                </button>
                <button type="button" data-period="month" class="period-btn py-2 text-xs font-semibold rounded-lg text-gray-500 hover:text-gray-900 transition-all tap-scale cursor-pointer">
                    Bulan Ini
                </button>
            </div>

            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#173628]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4"/>
                        <path d="M16 2v4"/>
                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                        <path d="M3 10h18"/>
                    </svg>
                    <span id="date-range-label">{{ $reportData['today']['dateRange'] }}</span>
                </div>
                <span class="text-[11px] text-gray-400">Update otomatis</span>
            </div>
        </section>

        <!-- 3. Grid 4 Kartu KPI (2x2) -->
        <section aria-label="Ringkasan Penjualan" class="grid grid-cols-2 gap-2.5">
            <!-- Kartu 1: Total Omzet -->
            <div class="bg-white border border-gray-100 rounded-2xl p-3.5 flex flex-col justify-between shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between gap-1">
                    <span class="text-xs font-medium text-gray-500">Total Omzet</span>
                    <span id="kpi-omzet-trend" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $reportData['today']['omzetTrend']['positive'] ? 'bg-emerald-50 text-[#173628]' : 'bg-rose-50 text-rose-700' }} tabular-nums">
                        {{ $reportData['today']['omzetTrend']['text'] }}
                    </span>
                </div>
                <div id="kpi-omzet" class="mt-2 text-base font-bold text-gray-900 tabular-nums tracking-tight">
                    Rp {{ number_format($reportData['today']['omzet'], 0, ',', '.') }}
                </div>
                <span class="mt-1 text-[11px] text-gray-400">vs periode lalu</span>
            </div>

            <!-- Kartu 2: Laba Bersih -->
            <div class="bg-white border border-gray-100 rounded-2xl p-3.5 flex flex-col justify-between shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between gap-1">
                    <span class="text-xs font-medium text-gray-500">Laba Bersih</span>
                    <span id="kpi-laba-trend" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $reportData['today']['labaTrend']['positive'] ? 'bg-emerald-50 text-[#173628]' : 'bg-rose-50 text-rose-700' }} tabular-nums">
                        {{ $reportData['today']['labaTrend']['text'] }}
                    </span>
                </div>
                <div id="kpi-laba" class="mt-2 text-base font-bold text-[#173628] tabular-nums tracking-tight">
                    Rp {{ number_format($reportData['today']['laba'], 0, ',', '.') }}
                </div>
                <span id="kpi-margin" class="mt-1 text-[11px] text-[#173628] font-medium tabular-nums">
                    {{ $reportData['today']['margin'] }}
                </span>
            </div>

            <!-- Kartu 3: Total Transaksi -->
            <div class="bg-white border border-gray-100 rounded-2xl p-3.5 flex flex-col justify-between shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between gap-1">
                    <span class="text-xs font-medium text-gray-500">Total Transaksi</span>
                    <span id="kpi-trx-trend" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $reportData['today']['trxTrend']['positive'] ? 'bg-emerald-50 text-[#173628]' : 'bg-rose-50 text-rose-700' }} tabular-nums">
                        {{ $reportData['today']['trxTrend']['text'] }}
                    </span>
                </div>
                <div id="kpi-trx-count" class="mt-2 text-base font-bold text-gray-900 tabular-nums tracking-tight">
                    {{ $reportData['today']['trxCount'] }} Trx
                </div>
                <span class="mt-1 text-[11px] text-gray-400">Struk selesai</span>
            </div>

            <!-- Kartu 4: Rata-rata/Trx -->
            <div class="bg-white border border-gray-100 rounded-2xl p-3.5 flex flex-col justify-between shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between gap-1">
                    <span class="text-xs font-medium text-gray-500">Rata-rata/Trx</span>
                    <span id="kpi-avg-trend" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $reportData['today']['avgTrend']['positive'] ? 'bg-emerald-50 text-[#173628]' : 'bg-rose-50 text-rose-700' }} tabular-nums">
                        {{ $reportData['today']['avgTrend']['text'] }}
                    </span>
                </div>
                <div id="kpi-avg-trx" class="mt-2 text-base font-bold text-gray-900 tabular-nums tracking-tight">
                    Rp {{ number_format($reportData['today']['avgTrx'], 0, ',', '.') }}
                </div>
                <span class="mt-1 text-[11px] text-gray-400">Nilai per struk</span>
            </div>
        </section>

        <!-- 4. Quick Export Banner -->
        <section class="bg-white border border-gray-100 rounded-2xl p-3.5 flex flex-col gap-3 shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200/60 text-[#173628] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                        <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                        <path d="M8 13h2"/>
                        <path d="M14 13h2"/>
                        <path d="M8 17h2"/>
                        <path d="M14 17h2"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-xs font-bold text-gray-900 leading-tight">Unduh Laporan Excel (.xlsx)</h2>
                    <p id="quick-export-desc" class="text-[11px] text-gray-500 mt-0.5 truncate">
                        4 sheet lengkap • Periode Hari Ini
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-5 gap-2">
                <button id="quick-export-btn" type="button" class="col-span-3 h-[38px] rounded-xl bg-[#173628] hover:bg-[#10271c] text-white text-xs font-semibold flex items-center justify-center gap-1.5 tap-scale cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" x2="12" y1="15" y2="3"/>
                    </svg>
                    <span>Download .xlsx</span>
                </button>
                <button id="customize-export-btn" type="button" class="col-span-2 h-[38px] rounded-xl bg-gray-100 hover:bg-emerald-50 border border-gray-200/80 text-[#173628] text-xs font-semibold flex items-center justify-center gap-1.5 tap-scale cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="21" x2="14" y1="4" y2="4"/>
                        <line x1="10" x2="3" y1="4" y2="4"/>
                        <line x1="21" x2="12" y1="12" y2="12"/>
                        <line x1="8" x2="3" y1="12" y2="12"/>
                        <line x1="21" x2="16" y1="20" y2="20"/>
                        <line x1="12" x2="3" y1="20" y2="20"/>
                        <line x1="14" x2="14" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="10" y2="14"/>
                        <line x1="16" x2="16" y1="18" y2="22"/>
                    </svg>
                    <span>Atur Export</span>
                </button>
            </div>
        </section>

        <!-- 5. Grafik Penjualan Interaktif -->
        <section class="bg-white border border-gray-100 rounded-2xl p-4 shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#173628]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18"/>
                        <path d="M18 17V9"/>
                        <path d="M13 17V5"/>
                        <path d="M8 17v-3"/>
                    </svg>
                    <h2 class="text-sm font-bold text-gray-900">Grafik Penjualan</h2>
                </div>

                <!-- Switch Omzet | Laba -->
                <div class="bg-gray-100 border border-gray-200/70 p-0.5 rounded-lg inline-flex items-center">
                    <button type="button" data-metric="omzet" class="metric-btn px-2.5 py-1 text-[11px] font-semibold rounded-md text-gray-500 hover:text-gray-900 transition-all cursor-pointer">
                        Omzet
                    </button>
                    <button type="button" data-metric="laba" class="metric-btn px-2.5 py-1 text-[11px] font-semibold rounded-md bg-white text-[#173628] border border-gray-100 transition-all cursor-pointer">
                        Laba
                    </button>
                </div>
            </div>

            <!-- Highlight Bar Terpilih -->
            <div class="bg-gray-50 border border-gray-200/70 rounded-xl px-3 py-2 mb-3 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-2 h-2 rounded-full bg-[#173628] shrink-0"></span>
                    <span id="chart-selected-date" class="text-xs font-medium text-gray-500 truncate">
                        {{ $reportData['today']['dateRange'] }}
                    </span>
                </div>
                <span id="chart-selected-value" class="text-xs font-bold text-[#173628] tabular-nums shrink-0">
                    Rp {{ number_format($reportData['today']['laba'], 0, ',', '.') }}
                </span>
            </div>

            <!-- Batang Grafik -->
            <div class="relative pt-2">
                <div class="absolute inset-x-0 top-2 bottom-7 flex flex-col justify-between pointer-events-none">
                    <div class="border-b border-dashed border-gray-200/80 w-full"></div>
                    <div class="border-b border-dashed border-gray-200/80 w-full"></div>
                    <div class="border-b border-gray-200 w-full"></div>
                </div>

                <div id="bar-chart-container" class="relative z-10 h-[142px] flex items-end justify-between gap-2 pt-2">
                    <!-- Dirender dinamis oleh JS -->
                </div>
            </div>
        </section>

        <!-- 6. Detail Rincian Tabbed Section -->
        <section class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
            <div class="grid grid-cols-3 border-b border-gray-100 bg-gray-50/60">
                <button type="button" data-tab="products" class="detail-tab-btn py-3 text-xs font-bold text-[#173628] border-b-2 border-[#173628] transition-colors cursor-pointer">
                    Produk Terlaris
                </button>
                <button type="button" data-tab="transactions" class="detail-tab-btn py-3 text-xs font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-900 transition-colors cursor-pointer">
                    Transaksi
                </button>
                <button type="button" data-tab="payments" class="detail-tab-btn py-3 text-xs font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-900 transition-colors cursor-pointer">
                    Pembayaran
                </button>
            </div>

            <div id="detail-tab-content" class="p-3.5">
                <!-- Dirender dinamis oleh JS -->
            </div>
        </section>
    </div>

    <!-- Modal Export Excel SheetJS -->
    <div id="export-modal" class="hidden fixed inset-0 z-50 flex flex-col justify-end" role="dialog" aria-modal="true">
        <div id="export-backdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-[1.5px] animate-fade-in"></div>
        <div class="relative w-full max-w-[500px] mx-auto bg-white border-t border-gray-200 rounded-t-3xl pt-3 pb-6 px-4 shadow-2xl animate-slide-up max-h-[88vh] flex flex-col">
            <div class="w-10 h-1 bg-gray-300 rounded-full mx-auto mb-3 shrink-0"></div>
            <div class="flex items-start justify-between pb-3 border-b border-gray-100 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-[#173628] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                            <path d="M8 13h2"/>
                            <path d="M14 13h2"/>
                            <path d="M8 17h2"/>
                            <path d="M14 17h2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 leading-tight">Export ke Excel</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pilih periode, sheet, &amp; format file</p>
                    </div>
                </div>
                <button id="close-export-btn" type="button" class="p-1.5 -mr-1 text-gray-400 hover:text-gray-700 rounded-full cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto no-scrollbar py-3.5 space-y-4">
                <!-- Opsi Periode -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-600">Periode Laporan</label>
                    <div class="bg-gray-100 border border-gray-200/70 p-1 rounded-xl grid grid-cols-3 gap-1">
                        <button type="button" data-export-period="today" class="export-period-btn py-1.5 text-xs font-semibold rounded-lg bg-white text-[#173628] border border-gray-100 transition-all cursor-pointer">Hari Ini</button>
                        <button type="button" data-export-period="week" class="export-period-btn py-1.5 text-xs font-semibold rounded-lg text-gray-500 transition-all cursor-pointer">Minggu Ini</button>
                        <button type="button" data-export-period="month" class="export-period-btn py-1.5 text-xs font-semibold rounded-lg text-gray-500 transition-all cursor-pointer">Bulan Ini</button>
                    </div>
                </div>

                <!-- Opsi Sheet -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-semibold text-gray-600">Sheet yang Disertakan</label>
                        <span id="selected-sheets-count" class="text-[11px] font-semibold text-[#173628] tabular-nums">4/4 dipilih</span>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-2xl divide-y divide-gray-100 overflow-hidden">
                        <label class="flex items-center justify-between px-3.5 py-2.5 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox" value="summary" checked class="export-sheet-cb w-4 h-4 accent-[#173628] rounded cursor-pointer" />
                                <div>
                                    <div class="text-xs font-semibold text-gray-900">Ringkasan Keuangan</div>
                                    <div class="text-[11px] text-gray-400">Omzet, laba bersih, margin, &amp; grafik</div>
                                </div>
                            </div>
                            <span class="text-[10.5px] font-mono text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">Sheet 1</span>
                        </label>
                        <label class="flex items-center justify-between px-3.5 py-2.5 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox" value="products" checked class="export-sheet-cb w-4 h-4 accent-[#173628] rounded cursor-pointer" />
                                <div>
                                    <div class="text-xs font-semibold text-gray-900">Produk Terlaris</div>
                                    <div class="text-[11px] text-gray-400">Qty terjual &amp; total omzet per item</div>
                                </div>
                            </div>
                            <span class="text-[10.5px] font-mono text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">Sheet 2</span>
                        </label>
                        <label class="flex items-center justify-between px-3.5 py-2.5 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox" value="transactions" checked class="export-sheet-cb w-4 h-4 accent-[#173628] rounded cursor-pointer" />
                                <div>
                                    <div class="text-xs font-semibold text-gray-900">Riwayat Transaksi</div>
                                    <div class="text-[11px] text-gray-400">No. struk, metode, &amp; nominal</div>
                                </div>
                            </div>
                            <span class="text-[10.5px] font-mono text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">Sheet 3</span>
                        </label>
                        <label class="flex items-center justify-between px-3.5 py-2.5 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox" value="payments" checked class="export-sheet-cb w-4 h-4 accent-[#173628] rounded cursor-pointer" />
                                <div>
                                    <div class="text-xs font-semibold text-gray-900">Rekap Pembayaran</div>
                                    <div class="text-[11px] text-gray-400">Tunai, QRIS, &amp; Transfer</div>
                                </div>
                            </div>
                            <span class="text-[10.5px] font-mono text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">Sheet 4</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-2 shrink-0">
                <button id="confirm-download-btn" type="button" class="w-full h-[46px] rounded-xl bg-[#173628] hover:bg-[#10271c] text-white text-sm font-bold flex items-center justify-center gap-2 shadow-sm tap-scale cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" x2="12" y1="15" y2="3"/>
                    </svg>
                    <span>Download Excel (.xlsx)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast-container" class="pointer-events-none fixed bottom-20 inset-x-0 z-50 flex justify-center px-4 transition-all duration-200 opacity-0 translate-y-2">
        <div class="bg-gray-900 text-white text-xs font-medium px-4 py-2.5 rounded-full shadow-lg flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
            <span id="toast-message">Notifikasi</span>
        </div>
    </div>

    @push('scripts')
        <!-- SheetJS (XLSX) CDN -->
        <script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js"></script>
        <script>
            function formatRupiah(amount) {
                return 'Rp ' + Number(amount).toLocaleString('id-ID');
            }

            const REPORT_DATA = @json($reportData);

            const appState = {
                period: 'today',
                chartMetric: 'laba',
                selectedBarIndex: REPORT_DATA.today.defaultBarIndex ?? 0,
                activeDetailTab: 'products',
                exportPeriod: 'today',
                exportSheets: { summary: true, products: true, transactions: true, payments: true }
            };

            function showToast(msg) {
                const container = document.getElementById('toast-container');
                document.getElementById('toast-message').textContent = msg;
                container.classList.remove('opacity-0', 'translate-y-2');
                container.classList.add('opacity-100', 'translate-y-0');
                setTimeout(() => {
                    container.classList.remove('opacity-100', 'translate-y-0');
                    container.classList.add('opacity-0', 'translate-y-2');
                }, 2500);
            }

            function applyTrendBadge(elId, trend) {
                const el = document.getElementById(elId);
                el.textContent = trend.text;
                el.className = trend.positive
                    ? 'inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-[#173628] tabular-nums'
                    : 'inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 tabular-nums';
            }

            function renderAll() {
                const data = REPORT_DATA[appState.period];
                document.getElementById('date-range-label').textContent = data.dateRange;
                document.getElementById('quick-export-desc').textContent = `4 sheet lengkap • Periode ${data.label}`;

                document.getElementById('kpi-omzet').textContent = formatRupiah(data.omzet);
                applyTrendBadge('kpi-omzet-trend', data.omzetTrend);
                document.getElementById('kpi-laba').textContent = formatRupiah(data.laba);
                applyTrendBadge('kpi-laba-trend', data.labaTrend);
                document.getElementById('kpi-margin').textContent = data.margin;
                document.getElementById('kpi-trx-count').textContent = `${data.trxCount} Trx`;
                applyTrendBadge('kpi-trx-trend', data.trxTrend);
                document.getElementById('kpi-avg-trx').textContent = formatRupiah(data.avgTrx);
                applyTrendBadge('kpi-avg-trend', data.avgTrend);

                renderBarChart();
                renderDetailTab();
            }

            function renderBarChart() {
                const data = REPORT_DATA[appState.period];
                const chartItems = data.chart;
                const metric = appState.chartMetric;
                const container = document.getElementById('bar-chart-container');

                if (!chartItems || chartItems.length === 0) {
                    document.getElementById('chart-selected-date').textContent = data.dateRange;
                    document.getElementById('chart-selected-value').textContent = formatRupiah(metric === 'laba' ? data.laba : data.omzet);
                    container.innerHTML = `<div class="w-full h-full flex items-center justify-center text-xs text-gray-400">Belum ada data penjualan untuk periode ini.</div>`;
                    return;
                }

                const maxVal = Math.max(...chartItems.map(item => item[metric]), 1);
                const selectedItem = chartItems[appState.selectedBarIndex] || chartItems[0];

                document.getElementById('chart-selected-date').textContent = selectedItem.fullDate;
                document.getElementById('chart-selected-value').textContent = formatRupiah(selectedItem[metric]);

                container.innerHTML = chartItems.map((item, idx) => {
                    const isSelected = idx === appState.selectedBarIndex;
                    const heightPct = item[metric] > 0 ? Math.max(16, Math.round((item[metric] / maxVal) * 100)) : 8;

                    return `
                        <button type="button" onclick="selectChartBar(${idx})" class="flex-1 h-full flex flex-col items-center justify-end gap-2 group focus:outline-none cursor-pointer">
                            <div class="w-full flex-1 flex items-end justify-center">
                                <div style="height: ${heightPct}%" class="w-full max-w-[34px] rounded-t-md rounded-b-[3px] transition-all duration-300 ${isSelected ? 'bg-[#173628]' : 'bg-[#C8DDD5] group-hover:bg-[#A6C7BC]'}"></div>
                            </div>
                            <span class="text-[11px] leading-none ${isSelected ? 'font-bold text-[#173628]' : 'font-medium text-gray-400'}">
                                ${item.label}
                            </span>
                        </button>
                    `;
                }).join('');
            }

            function selectChartBar(idx) {
                appState.selectedBarIndex = idx;
                renderBarChart();
            }

            function renderDetailTab() {
                const data = REPORT_DATA[appState.period];
                const container = document.getElementById('detail-tab-content');

                if (appState.activeDetailTab === 'products') {
                    const products = data.products || [];
                    if (products.length === 0) {
                        container.innerHTML = `<p class="text-xs text-gray-400 text-center py-4">Belum ada data produk terjual untuk periode ini.</p>`;
                        return;
                    }
                    container.innerHTML = `
                        <div class="divide-y divide-gray-100">
                            ${products.map((p, idx) => `
                                <div class="py-2.5 first:pt-0.5 last:pb-0.5 flex items-center gap-3">
                                    <div class="w-6 h-6 rounded-lg ${idx === 0 ? 'bg-[#173628] text-white' : 'bg-gray-100 border border-gray-200/70 text-gray-600'} text-[11px] font-bold flex items-center justify-center shrink-0">
                                        ${idx + 1}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-semibold text-gray-900 truncate">${p.name}</div>
                                        <div class="text-[11px] text-gray-400">Terjual: ${p.sold} ${p.unit}</div>
                                    </div>
                                    <div class="text-xs font-bold text-[#173628] tabular-nums">${formatRupiah(p.revenue)}</div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else if (appState.activeDetailTab === 'transactions') {
                    const transactions = data.transactions || [];
                    if (transactions.length === 0) {
                        container.innerHTML = `<p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat transaksi untuk periode ini.</p>`;
                        return;
                    }
                    container.innerHTML = `
                        <div class="divide-y divide-gray-100">
                            ${transactions.map(t => `
                                <div class="py-2.5 first:pt-0.5 last:pb-0.5 flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-gray-900">${t.id} <span class="font-normal text-gray-400">• ${t.method}</span></div>
                                        <div class="text-[11px] text-gray-400 truncate">${t.fullTime} • ${t.itemsCount} item • ${t.customer}</div>
                                    </div>
                                    <div class="text-xs font-bold text-[#173628] tabular-nums shrink-0">${formatRupiah(t.total)}</div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else if (appState.activeDetailTab === 'payments') {
                    const payments = data.payments || [];
                    if (payments.length === 0) {
                        container.innerHTML = `<p class="text-xs text-gray-400 text-center py-4">Belum ada rekap pembayaran untuk periode ini.</p>`;
                        return;
                    }
                    container.innerHTML = `
                        <div class="divide-y divide-gray-100">
                            ${payments.map(pm => `
                                <div class="py-2.5 first:pt-0.5 last:pb-0.5 flex items-center justify-between gap-3">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-900">${pm.method}</div>
                                        <div class="text-[11px] text-gray-400">${pm.trx} transaksi (${pm.percent}%)</div>
                                    </div>
                                    <div class="text-xs font-bold text-[#173628] tabular-nums">${formatRupiah(pm.amount)}</div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                }
            }

            function syncExportModalUI() {
                document.querySelectorAll('.export-period-btn').forEach(btn => {
                    const active = btn.getAttribute('data-export-period') === appState.exportPeriod;
                    btn.className = active
                        ? 'export-period-btn py-1.5 text-xs font-semibold rounded-lg bg-white text-[#173628] border border-gray-100 transition-all cursor-pointer'
                        : 'export-period-btn py-1.5 text-xs font-semibold rounded-lg text-gray-500 transition-all cursor-pointer';
                });

                const checkboxes = document.querySelectorAll('.export-sheet-cb');
                let selectedCount = 0;
                checkboxes.forEach(cb => {
                    cb.checked = !!appState.exportSheets[cb.value];
                    if (cb.checked) selectedCount++;
                });

                document.getElementById('selected-sheets-count').textContent = `${selectedCount}/4 dipilih`;
                const downloadBtn = document.getElementById('confirm-download-btn');
                downloadBtn.disabled = selectedCount === 0;
                downloadBtn.classList.toggle('opacity-50', selectedCount === 0);
                downloadBtn.classList.toggle('cursor-not-allowed', selectedCount === 0);
            }

            function exportToExcel(periodKey, sheets) {
                if (typeof XLSX === 'undefined') {
                    showToast('Library Excel belum termuat. Periksa koneksi internet.');
                    return;
                }

                const data = REPORT_DATA[periodKey];
                const workbook = XLSX.utils.book_new();
                let sheetAdded = false;

                if (sheets.summary) {
                    const summaryRows = [
                        ['LAPORAN PENJUALAN TOKO'],
                        ['Periode', `${data.label} (${data.dateRange})`],
                        [],
                        ['Metrik', 'Nilai', 'Tren vs Periode Lalu'],
                        ['Total Omzet', data.omzet, data.omzetTrend.text],
                        ['Laba Kotor', data.laba, data.labaTrend.text],
                        ['Margin', data.margin, ''],
                        ['Total Transaksi', data.trxCount, data.trxTrend.text],
                        ['Rata-rata per Transaksi', data.avgTrx, data.avgTrend.text],
                    ];

                    if (data.chart && data.chart.length > 0) {
                        summaryRows.push([], ['Rincian Grafik', 'Omzet (Rp)', 'Laba (Rp)']);
                        data.chart.forEach(c => summaryRows.push([c.fullDate, c.omzet, c.laba]));
                    }

                    const wsSummary = XLSX.utils.aoa_to_sheet(summaryRows);
                    wsSummary['!cols'] = [{ wch: 32 }, { wch: 20 }, { wch: 20 }];
                    XLSX.utils.book_append_sheet(workbook, wsSummary, 'Ringkasan Keuangan');
                    sheetAdded = true;
                }

                if (sheets.products) {
                    const productRows = [
                        ['No', 'Nama Produk', 'Satuan', 'Qty Terjual', 'Total Omzet (Rp)', 'Estimasi Laba (Rp)'],
                        ...(data.products || []).map((p, idx) => [
                            idx + 1,
                            p.name,
                            p.unit,
                            p.sold,
                            p.revenue,
                            p.profit ?? 0,
                        ]),
                    ];
                    const wsProducts = XLSX.utils.aoa_to_sheet(productRows);
                    wsProducts['!cols'] = [{ wch: 6 }, { wch: 30 }, { wch: 10 }, { wch: 14 }, { wch: 18 }, { wch: 18 }];
                    XLSX.utils.book_append_sheet(workbook, wsProducts, 'Produk Terlaris');
                    sheetAdded = true;
                }

                if (sheets.transactions) {
                    const trxRows = [
                        ['No. Nota', 'Waktu', 'Pelanggan', 'Metode', 'Jumlah Item', 'Total (Rp)'],
                        ...(data.transactions || []).map(t => [
                            t.id,
                            t.fullTime,
                            t.customer,
                            t.method,
                            t.itemsCount,
                            t.total,
                        ]),
                    ];
                    const wsTrx = XLSX.utils.aoa_to_sheet(trxRows);
                    wsTrx['!cols'] = [{ wch: 14 }, { wch: 24 }, { wch: 22 }, { wch: 16 }, { wch: 14 }, { wch: 16 }];
                    XLSX.utils.book_append_sheet(workbook, wsTrx, 'Riwayat Transaksi');
                    sheetAdded = true;
                }

                if (sheets.payments) {
                    const payRows = [
                        ['Metode Pembayaran', 'Jumlah Transaksi', 'Total Nominal (Rp)', 'Persentase (%)'],
                        ...(data.payments || []).map(pm => [
                            pm.method,
                            pm.trx,
                            pm.amount,
                            pm.percent,
                        ]),
                    ];
                    const wsPay = XLSX.utils.aoa_to_sheet(payRows);
                    wsPay['!cols'] = [{ wch: 22 }, { wch: 18 }, { wch: 20 }, { wch: 16 }];
                    XLSX.utils.book_append_sheet(workbook, wsPay, 'Rekap Pembayaran');
                    sheetAdded = true;
                }

                if (!sheetAdded) {
                    showToast('Pilih minimal 1 sheet untuk diunduh.');
                    return;
                }

                const todayStamp = new Date().toISOString().slice(0, 10);
                const filename = `Laporan_Toko_${data.fileSuffix}_${todayStamp}.xlsx`;
                XLSX.writeFile(workbook, filename);
                showToast(`Berhasil mengunduh ${filename}`);
            }

            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.period-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const next = btn.getAttribute('data-period');
                        appState.period = next;
                        appState.selectedBarIndex = REPORT_DATA[next].defaultBarIndex ?? 0;

                        document.querySelectorAll('.period-btn').forEach(b => {
                            const active = b.getAttribute('data-period') === next;
                            b.className = active
                                ? 'period-btn py-2 text-xs font-semibold rounded-lg bg-white text-[#173628] border border-gray-100 shadow-xs transition-all tap-scale cursor-pointer'
                                : 'period-btn py-2 text-xs font-semibold rounded-lg text-gray-500 hover:text-gray-900 transition-all tap-scale cursor-pointer';
                        });
                        renderAll();
                    });
                });

                document.querySelectorAll('.metric-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const m = btn.getAttribute('data-metric');
                        appState.chartMetric = m;

                        document.querySelectorAll('.metric-btn').forEach(b => {
                            const active = b.getAttribute('data-metric') === m;
                            b.className = active
                                ? 'metric-btn px-2.5 py-1 text-[11px] font-semibold rounded-md bg-white text-[#173628] border border-gray-100 transition-all cursor-pointer'
                                : 'metric-btn px-2.5 py-1 text-[11px] font-semibold rounded-md text-gray-500 hover:text-gray-900 transition-all cursor-pointer';
                        });
                        renderBarChart();
                    });
                });

                document.querySelectorAll('.detail-tab-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const tab = btn.getAttribute('data-tab');
                        appState.activeDetailTab = tab;

                        document.querySelectorAll('.detail-tab-btn').forEach(b => {
                            const active = b.getAttribute('data-tab') === tab;
                            b.className = active
                                ? 'detail-tab-btn py-3 text-xs font-bold text-[#173628] border-b-2 border-[#173628] transition-colors cursor-pointer'
                                : 'detail-tab-btn py-3 text-xs font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-900 transition-colors cursor-pointer';
                        });
                        renderDetailTab();
                    });
                });

                const modal = document.getElementById('export-modal');
                const openExportModal = () => {
                    appState.exportPeriod = appState.period;
                    syncExportModalUI();
                    modal.classList.remove('hidden');
                };

                document.getElementById('open-export-btn').addEventListener('click', openExportModal);
                document.getElementById('customize-export-btn').addEventListener('click', openExportModal);
                document.getElementById('close-export-btn').addEventListener('click', () => modal.classList.add('hidden'));
                document.getElementById('export-backdrop').addEventListener('click', () => modal.classList.add('hidden'));

                document.querySelectorAll('.export-period-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        appState.exportPeriod = btn.getAttribute('data-export-period');
                        syncExportModalUI();
                    });
                });

                document.querySelectorAll('.export-sheet-cb').forEach(cb => {
                    cb.addEventListener('change', () => {
                        appState.exportSheets[cb.value] = cb.checked;
                        syncExportModalUI();
                    });
                });

                document.getElementById('quick-export-btn').addEventListener('click', () => {
                    exportToExcel(appState.period, { summary: true, products: true, transactions: true, payments: true });
                });

                document.getElementById('confirm-download-btn').addEventListener('click', () => {
                    modal.classList.add('hidden');
                    exportToExcel(appState.exportPeriod, appState.exportSheets);
                });

                renderAll();
            });
        </script>
    @endpush
</x-app-layout>
