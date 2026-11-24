<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Laporan — Toko Kelontong Mas Fadil</title>

  <!-- Google Fonts: Inter & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet" />

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- SheetJS (XLSX) CDN -->
  <script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js"></script>

  <!-- Konfigurasi Tema Tailwind POS Kelontong -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Inter"', 'system-ui', 'sans-serif'],
            mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
          },
          colors: {
            backdrop: '#EAE7DF',
            canvas: '#FAF9F5',
            surface: '#FFFFFF',
            soft: '#F3F2EC',
            borderline: '#E8E6DF',
            softborder: '#E2DFD7',
            forest: '#1B4D3E',
            foresthover: '#143B2F',
            sage: '#EBF3F0',
            baridle: '#C8DDD5',
            ink: '#1F2937',
            secondary: '#6B7280',
            muted: '#8C8B84',
            danger: '#B91C1C',
            dangerbg: '#FEF2F2',
          },
          borderRadius: {
            card: '16px',
            box: '12px',
          },
        },
      },
    };
  </script>

  <style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .tabular-nums { font-variant-numeric: tabular-nums; }
    .tap-scale { transition: transform 0.14s ease, background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease; }
    .tap-scale:active { transform: scale(0.97); }

    @keyframes popIn {
      from { opacity: 0; transform: translateY(12px) scale(0.96); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }

    .animate-pop-in { animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-fade-in { animation: fadeIn 0.18s ease-out forwards; }
    .animate-slide-up { animation: slideUp 0.24s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
  </style>
</head>

<body class="bg-backdrop text-ink font-sans antialiased selection:bg-sage selection:text-forest min-h-screen flex items-center justify-center sm:py-6 sm:px-4">

  <!-- Frame Mobile Wrapper -->
  <div id="app-frame" class="relative w-full min-h-screen sm:min-h-0 sm:max-w-[420px] sm:h-[870px] sm:max-h-[94vh] bg-canvas sm:rounded-[36px] sm:border sm:border-[#DCD8CF] sm:shadow-[0_24px_60px_-16px_rgba(27,77,62,0.18)] flex flex-col overflow-hidden">

    <!-- 1. Header (Sticky) -->
    <header class="h-[58px] shrink-0 bg-canvas border-b border-borderline px-4 flex items-center justify-between sticky top-0 z-20">
      <button id="back-lainnya-btn" type="button" class="flex items-center gap-1.5 text-forest hover:text-foresthover text-[14px] font-semibold py-2 pr-2 -ml-1 rounded-lg tap-scale">
        <i data-lucide="arrow-left" class="w-[18px] h-[18px]" stroke-width="2"></i>
        <span>Lainnya</span>
      </button>

      <h1 class="text-[17px] font-bold tracking-[-0.015em] text-ink absolute left-1/2 -translate-x-1/2 pointer-events-none">
        Laporan
      </h1>

      <button id="open-export-btn" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-[10px] bg-sage border border-forest/20 text-forest hover:bg-[#DFECE6] text-[12.5px] font-semibold tap-scale">
        <i data-lucide="file-spreadsheet" class="w-[15px] h-[15px]" stroke-width="2"></i>
        <span>Excel</span>
      </button>
    </header>

    <!-- Main Content (Scrollable) -->
    <main class="flex-1 overflow-y-auto no-scrollbar px-4 pt-3 pb-[84px] space-y-3.5">

      <!-- 2. Segmented Filter Periode -->
      <section aria-label="Filter Periode" class="space-y-2">
        <div id="period-switcher" class="bg-soft border border-softborder p-1 rounded-[13px] grid grid-cols-3 gap-1">
          <button type="button" data-period="today" class="period-btn py-2 text-[13px] font-semibold rounded-[9px] bg-surface text-forest border border-borderline shadow-[0_1px_2px_rgba(0,0,0,0.03)] transition-all tap-scale">
            Hari Ini
          </button>
          <button type="button" data-period="week" class="period-btn py-2 text-[13px] font-semibold rounded-[9px] text-secondary hover:text-ink transition-all tap-scale">
            Minggu Ini
          </button>
          <button type="button" data-period="month" class="period-btn py-2 text-[13px] font-semibold rounded-[9px] text-secondary hover:text-ink transition-all tap-scale">
            Bulan Ini
          </button>
        </div>

        <div class="flex items-center justify-between px-1">
          <div class="flex items-center gap-1.5 text-[12px] font-medium text-secondary">
            <i data-lucide="calendar" class="w-[14px] h-[14px] text-forest" stroke-width="2"></i>
            <span id="date-range-label">Selasa, 24 Nov 2026</span>
          </div>
          <span class="text-[11px] text-muted">Update otomatis</span>
        </div>
      </section>

      <!-- 3. Grid 4 Kartu KPI (2x2) -->
      <section aria-label="Ringkasan Penjualan" class="grid grid-cols-2 gap-2.5">
        <!-- Kartu 1: Total Omzet -->
        <div class="bg-surface border border-borderline rounded-card p-3.5 flex flex-col justify-between shadow-sm">
          <div class="flex items-center justify-between gap-1">
            <span class="text-[12px] font-medium text-secondary">Total Omzet</span>
            <span id="kpi-omzet-trend" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10.5px] font-semibold bg-sage text-forest font-mono tabular-nums">
              +8,6%
            </span>
          </div>
          <div id="kpi-omzet" class="mt-2 text-[16.5px] font-bold text-ink font-mono tabular-nums tracking-[-0.02em]">
            Rp 1.920.000
          </div>
          <span class="mt-1 text-[11px] text-muted">vs periode lalu</span>
        </div>

        <!-- Kartu 2: Laba Bersih -->
        <div class="bg-surface border border-borderline rounded-card p-3.5 flex flex-col justify-between shadow-sm">
          <div class="flex items-center justify-between gap-1">
            <span class="text-[12px] font-medium text-secondary">Laba Bersih</span>
            <span id="kpi-laba-trend" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10.5px] font-semibold bg-sage text-forest font-mono tabular-nums">
              +9,1%
            </span>
          </div>
          <div id="kpi-laba" class="mt-2 text-[16.5px] font-bold text-forest font-mono tabular-nums tracking-[-0.02em]">
            Rp 448.000
          </div>
          <span id="kpi-margin" class="mt-1 text-[11px] text-forest font-medium font-mono tabular-nums">
            Margin 23,3%
          </span>
        </div>

        <!-- Kartu 3: Total Transaksi -->
        <div class="bg-surface border border-borderline rounded-card p-3.5 flex flex-col justify-between shadow-sm">
          <div class="flex items-center justify-between gap-1">
            <span class="text-[12px] font-medium text-secondary">Total Transaksi</span>
            <span id="kpi-trx-trend" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10.5px] font-semibold bg-sage text-forest font-mono tabular-nums">
              +14,3%
            </span>
          </div>
          <div id="kpi-trx-count" class="mt-2 text-[16.5px] font-bold text-ink font-mono tabular-nums tracking-[-0.02em]">
            24 Trx
          </div>
          <span class="mt-1 text-[11px] text-muted">Struk selesai</span>
        </div>

        <!-- Kartu 4: Rata-rata/Trx -->
        <div class="bg-surface border border-borderline rounded-card p-3.5 flex flex-col justify-between shadow-sm">
          <div class="flex items-center justify-between gap-1">
            <span class="text-[12px] font-medium text-secondary">Rata-rata/Trx</span>
            <span id="kpi-avg-trend" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10.5px] font-semibold bg-dangerbg text-danger font-mono tabular-nums">
              -2,4%
            </span>
          </div>
          <div id="kpi-avg-trx" class="mt-2 text-[16.5px] font-bold text-ink font-mono tabular-nums tracking-[-0.02em]">
            Rp 80.000
          </div>
          <span class="mt-1 text-[11px] text-muted">Nilai per struk</span>
        </div>
      </section>

      <!-- 4. Quick Export Banner -->
      <section class="bg-surface border border-borderline rounded-card p-3.5 flex flex-col gap-3 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-[11px] bg-sage border border-forest/15 text-forest flex items-center justify-center shrink-0">
            <i data-lucide="file-spreadsheet" class="w-5 h-5" stroke-width="1.9"></i>
          </div>
          <div class="min-w-0 flex-1">
            <h2 class="text-[13.5px] font-bold text-ink leading-tight">Unduh Laporan Excel (.xlsx)</h2>
            <p id="quick-export-desc" class="text-[11.5px] text-secondary mt-0.5 truncate">
              4 sheet lengkap • Periode Hari Ini
            </p>
          </div>
        </div>

        <div class="grid grid-cols-5 gap-2">
          <button id="quick-export-btn" type="button" class="col-span-3 h-[38px] rounded-[10px] bg-forest hover:bg-foresthover text-white text-[12.5px] font-semibold flex items-center justify-center gap-1.5 tap-scale">
            <i data-lucide="download" class="w-[15px] h-[15px]" stroke-width="2"></i>
            <span>Download .xlsx</span>
          </button>
          <button id="customize-export-btn" type="button" class="col-span-2 h-[38px] rounded-[10px] bg-soft hover:bg-sage border border-softborder text-forest text-[12.5px] font-semibold flex items-center justify-center gap-1.5 tap-scale">
            <i data-lucide="sliders-horizontal" class="w-[14px] h-[14px]" stroke-width="2"></i>
            <span>Atur Export</span>
          </button>
        </div>
      </section>

      <!-- 5. Grafik Penjualan Interaktif -->
      <section class="bg-surface border border-borderline rounded-card p-4 shadow-sm">
        <div class="flex items-center justify-between gap-2 mb-3">
          <div class="flex items-center gap-1.5">
            <i data-lucide="bar-chart-3" class="w-[16px] h-[16px] text-forest" stroke-width="1.9"></i>
            <h2 class="text-[14.5px] font-bold text-ink">Grafik Penjualan</h2>
          </div>

          <!-- Switch Omzet | Laba -->
          <div class="bg-soft border border-softborder p-0.5 rounded-[9px] inline-flex items-center">
            <button type="button" data-metric="omzet" class="metric-btn px-2.5 py-1 text-[11.5px] font-semibold rounded-[7px] text-secondary hover:text-ink transition-all">
              Omzet
            </button>
            <button type="button" data-metric="laba" class="metric-btn px-2.5 py-1 text-[11.5px] font-semibold rounded-[7px] bg-surface text-forest border border-borderline transition-all">
              Laba
            </button>
          </div>
        </div>

        <!-- Highlight Bar Terpilih -->
        <div class="bg-soft border border-softborder rounded-[10px] px-3 py-2 mb-3 flex items-center justify-between gap-2">
          <div class="flex items-center gap-2 min-w-0">
            <span class="w-2 h-2 rounded-full bg-forest shrink-0"></span>
            <span id="chart-selected-date" class="text-[12px] font-medium text-secondary truncate">
              Selasa, 24 Nov • 14:00–16:00
            </span>
          </div>
          <span id="chart-selected-value" class="text-[13px] font-bold text-forest font-mono tabular-nums shrink-0">
            Rp 115.000
          </span>
        </div>

        <!-- Batang Grafik -->
        <div class="relative pt-2">
          <div class="absolute inset-x-0 top-2 bottom-7 flex flex-col justify-between pointer-events-none">
            <div class="border-b border-dashed border-borderline/90 w-full"></div>
            <div class="border-b border-dashed border-borderline/90 w-full"></div>
            <div class="border-b border-borderline w-full"></div>
          </div>

          <div id="bar-chart-container" class="relative z-10 h-[142px] flex items-end justify-between gap-2 pt-2">
            <!-- Dirender dinamis oleh JS -->
          </div>
        </div>
      </section>

      <!-- 6. Detail Rincian Tabbed Section -->
      <section class="bg-surface border border-borderline rounded-card overflow-hidden shadow-sm">
        <div class="grid grid-cols-3 border-b border-borderline bg-canvas/60">
          <button type="button" data-tab="products" class="detail-tab-btn py-3 text-[12.5px] font-bold text-forest border-b-2 border-forest transition-colors">
            Produk Terlaris
          </button>
          <button type="button" data-tab="transactions" class="detail-tab-btn py-3 text-[12.5px] font-semibold text-secondary border-b-2 border-transparent hover:text-ink transition-colors">
            Transaksi
          </button>
          <button type="button" data-tab="payments" class="detail-tab-btn py-3 text-[12.5px] font-semibold text-secondary border-b-2 border-transparent hover:text-ink transition-colors">
            Pembayaran
          </button>
        </div>

        <div id="detail-tab-content" class="p-3.5">
          <!-- Dirender dinamis oleh JS -->
        </div>
      </section>
    </main>

    <!-- 7. Bottom Navigation Bar -->
    <nav aria-label="Navigasi Utama" class="absolute bottom-0 inset-x-0 bg-canvas border-t border-borderline h-[64px] px-1 grid grid-cols-5 items-center z-30">
      <button type="button" onclick="handleBottomNav('Home')" class="flex flex-col items-center justify-center gap-1 py-1.5 text-muted hover:text-ink tap-scale">
        <i data-lucide="layout-grid" class="w-[20px] h-[20px]" stroke-width="1.75"></i>
        <span class="text-[11px] font-medium leading-none">Home</span>
      </button>

      <button type="button" onclick="handleBottomNav('Produk')" class="flex flex-col items-center justify-center gap-1 py-1.5 text-muted hover:text-ink tap-scale">
        <i data-lucide="package" class="w-[20px] h-[20px]" stroke-width="1.75"></i>
        <span class="text-[11px] font-medium leading-none">Produk</span>
      </button>

      <button type="button" onclick="handleBottomNav('Kasir')" class="flex flex-col items-center justify-center gap-1 py-1.5 text-muted hover:text-ink tap-scale">
        <i data-lucide="calculator" class="w-[20px] h-[20px]" stroke-width="1.75"></i>
        <span class="text-[11px] font-medium leading-none">Kasir</span>
      </button>

      <button type="button" onclick="handleBottomNav('Kategori')" class="flex flex-col items-center justify-center gap-1 py-1.5 text-muted hover:text-ink tap-scale">
        <i data-lucide="store" class="w-[20px] h-[20px]" stroke-width="1.75"></i>
        <span class="text-[11px] font-medium leading-none">Kategori</span>
      </button>

      <!-- Lainnya (Aktif) -->
      <button id="nav-lainnya-btn" type="button" class="flex flex-col items-center justify-center gap-1 py-1.5 text-forest tap-scale">
        <i data-lucide="grid-2x2" class="w-[20px] h-[20px]" stroke-width="2.1"></i>
        <span class="text-[11px] font-bold leading-none">Lainnya</span>
      </button>
    </nav>

    <!-- Modal Export Excel SheetJS -->
    <div id="export-modal" class="hidden absolute inset-0 z-50 flex flex-col justify-end" role="dialog" aria-modal="true">
      <div id="export-backdrop" class="absolute inset-0 bg-ink/40 backdrop-blur-[1.5px] animate-fade-in"></div>
      <div class="relative bg-canvas border-t border-borderline rounded-t-[24px] pt-3 pb-6 px-4 shadow-2xl animate-slide-up max-h-[88%] flex flex-col">
        <div class="w-10 h-1 bg-[#D6D3C8] rounded-full mx-auto mb-3 shrink-0"></div>
        <div class="flex items-start justify-between pb-3 border-b border-borderline shrink-0">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-[10px] bg-sage text-forest flex items-center justify-center">
              <i data-lucide="file-spreadsheet" class="w-[19px] h-[19px]" stroke-width="2"></i>
            </div>
            <div>
              <h3 class="text-[15.5px] font-bold text-ink leading-tight">Export ke Excel</h3>
              <p class="text-[12px] text-secondary mt-0.5">Pilih periode, sheet, & format file</p>
            </div>
          </div>
          <button id="close-export-btn" type="button" class="p-1.5 -mr-1 text-muted hover:text-ink rounded-full">
            <i data-lucide="x" class="w-5 h-5" stroke-width="1.9"></i>
          </button>
        </div>

        <div class="overflow-y-auto no-scrollbar py-3.5 space-y-4">
          <!-- Opsi Periode -->
          <div class="space-y-1.5">
            <label class="text-[12px] font-semibold text-secondary">Periode Laporan</label>
            <div class="bg-soft border border-softborder p-1 rounded-[11px] grid grid-cols-3 gap-1">
              <button type="button" data-export-period="today" class="export-period-btn py-1.5 text-[12.5px] font-semibold rounded-[8px] bg-surface text-forest border border-borderline transition-all">Hari Ini</button>
              <button type="button" data-export-period="week" class="export-period-btn py-1.5 text-[12.5px] font-semibold rounded-[8px] text-secondary transition-all">Minggu Ini</button>
              <button type="button" data-export-period="month" class="export-period-btn py-1.5 text-[12.5px] font-semibold rounded-[8px] text-secondary transition-all">Bulan Ini</button>
            </div>
          </div>

          <!-- Opsi Sheet -->
          <div class="space-y-1.5">
            <div class="flex items-center justify-between">
              <label class="text-[12px] font-semibold text-secondary">Sheet yang Disertakan</label>
              <span id="selected-sheets-count" class="text-[11.5px] font-semibold text-forest font-mono tabular-nums">4/4 dipilih</span>
            </div>
            <div class="bg-surface border border-borderline rounded-card divide-y divide-borderline overflow-hidden">
              <label class="flex items-center justify-between px-3.5 py-2.5 cursor-pointer hover:bg-canvas/60">
                <div class="flex items-center gap-2.5">
                  <input type="checkbox" value="summary" checked class="export-sheet-cb w-4 h-4 accent-[#1B4D3E] rounded cursor-pointer" />
                  <div>
                    <div class="text-[13px] font-semibold text-ink">Ringkasan Keuangan</div>
                    <div class="text-[11px] text-muted">Omzet, laba bersih, margin, & grafik</div>
                  </div>
                </div>
                <span class="text-[10.5px] font-mono text-muted bg-soft px-1.5 py-0.5 rounded">Sheet 1</span>
              </label>
              <label class="flex items-center justify-between px-3.5 py-2.5 cursor-pointer hover:bg-canvas/60">
                <div class="flex items-center gap-2.5">
                  <input type="checkbox" value="products" checked class="export-sheet-cb w-4 h-4 accent-[#1B4D3E] rounded cursor-pointer" />
                  <div>
                    <div class="text-[13px] font-semibold text-ink">Produk Terlaris</div>
                    <div class="text-[11px] text-muted">Qty terjual & total omzet per item</div>
                  </div>
                </div>
                <span class="text-[10.5px] font-mono text-muted bg-soft px-1.5 py-0.5 rounded">Sheet 2</span>
              </label>
              <label class="flex items-center justify-between px-3.5 py-2.5 cursor-pointer hover:bg-canvas/60">
                <div class="flex items-center gap-2.5">
                  <input type="checkbox" value="transactions" checked class="export-sheet-cb w-4 h-4 accent-[#1B4D3E] rounded cursor-pointer" />
                  <div>
                    <div class="text-[13px] font-semibold text-ink">Riwayat Transaksi</div>
                    <div class="text-[11px] text-muted">No. struk, metode, & nominal</div>
                  </div>
                </div>
                <span class="text-[10.5px] font-mono text-muted bg-soft px-1.5 py-0.5 rounded">Sheet 3</span>
              </label>
              <label class="flex items-center justify-between px-3.5 py-2.5 cursor-pointer hover:bg-canvas/60">
                <div class="flex items-center gap-2.5">
                  <input type="checkbox" value="payments" checked class="export-sheet-cb w-4 h-4 accent-[#1B4D3E] rounded cursor-pointer" />
                  <div>
                    <div class="text-[13px] font-semibold text-ink">Rekap Pembayaran</div>
                    <div class="text-[11px] text-muted">Tunai, QRIS, & Transfer</div>
                  </div>
                </div>
                <span class="text-[10.5px] font-mono text-muted bg-soft px-1.5 py-0.5 rounded">Sheet 4</span>
              </label>
            </div>
          </div>
        </div>

        <div class="pt-2 shrink-0">
          <button id="confirm-download-btn" type="button" class="w-full h-[46px] rounded-[12px] bg-forest hover:bg-foresthover text-white text-[14px] font-bold flex items-center justify-center gap-2 shadow-sm tap-scale">
            <i data-lucide="download" class="w-[18px] h-[18px]" stroke-width="2"></i>
            <span>Download Excel (.xlsx)</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div id="toast-container" class="pointer-events-none absolute bottom-[76px] left-4 right-4 z-50 flex justify-center transition-all duration-200 opacity-0 translate-y-2">
      <div class="bg-ink text-[#FAF9F5] text-[12.5px] font-medium px-4 py-2.5 rounded-full shadow-lg flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-[#34D399] shrink-0"></span>
        <span id="toast-message">Notifikasi</span>
      </div>
    </div>

  </div>

  <script>
    function formatRupiah(amount) {
      return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    const REPORT_DATA = {
      today: {
        label: 'Hari Ini',
        fileSuffix: 'Hari_Ini',
        dateRange: 'Selasa, 24 Nov 2026',
        omzet: 1920000,
        omzetTrend: { text: '+8,6%', positive: true },
        laba: 448000,
        labaTrend: { text: '+9,1%', positive: true },
        margin: 'Margin 23,3%',
        trxCount: 24,
        trxTrend: { text: '+14,3%', positive: true },
        avgTrx: 80000,
        avgTrend: { text: '-2,4%', positive: false },
        chart: [
          { label: '08:00', fullDate: 'Selasa, 24 Nov • 08:00–10:00', omzet: 245000, laba: 56000 },
          { label: '10:00', fullDate: 'Selasa, 24 Nov • 10:00–12:00', omzet: 410000, laba: 98000 },
          { label: '12:00', fullDate: 'Selasa, 24 Nov • 12:00–14:00', omzet: 365000, laba: 84000 },
          { label: '14:00', fullDate: 'Selasa, 24 Nov • 14:00–16:00', omzet: 485000, laba: 115000 },
          { label: '16:00', fullDate: 'Selasa, 24 Nov • 16:00–18:00', omzet: 270000, laba: 62000 },
          { label: '18:00', fullDate: 'Selasa, 24 Nov • 18:00–20:00', omzet: 145000, laba: 33000 },
        ],
        defaultBarIndex: 3,
        products: [
          { name: 'Beras Ramos Setra 5kg', unit: 'krg', sold: 7, revenue: 504000 },
          { name: 'Minyak Goreng Tropical 2L', unit: 'btl', sold: 11, revenue: 418000 },
          { name: 'Telur Ayam 1kg', unit: 'kg', sold: 10, revenue: 290000 },
          { name: 'Indomie Goreng 1 Dus', unit: 'dus', sold: 2, revenue: 236000 },
          { name: 'Gula Pasir Gulaku 1kg', unit: 'pcs', sold: 12, revenue: 216000 },
          { name: 'Gas LPG 3kg', unit: 'tbg', sold: 8, revenue: 176000 },
        ],
        transactions: [
          { id: '#TRX-1042', time: '14:20', fullTime: '24 Nov 2026 • 14:20 WIB', method: 'Tunai', itemsCount: 4, total: 148000 },
          { id: '#TRX-1041', time: '13:55', fullTime: '24 Nov 2026 • 13:55 WIB', method: 'QRIS', itemsCount: 2, total: 76000 },
        ],
        payments: [
          { method: 'Tunai', trx: 15, amount: 1120000, percent: 58 },
          { method: 'QRIS', trx: 7, amount: 564000, percent: 29 },
          { method: 'Transfer', trx: 2, amount: 236000, percent: 13 },
        ],
      },
      week: {
        label: 'Minggu Ini',
        fileSuffix: 'Minggu_Ini',
        dateRange: '18 Nov – 24 Nov 2026',
        omzet: 12450000,
        omzetTrend: { text: '+14,2%', positive: true },
        laba: 2840000,
        labaTrend: { text: '+11,5%', positive: true },
        margin: 'Margin 22,8%',
        trxCount: 148,
        trxTrend: { text: '+9,6%', positive: true },
        avgTrx: 84121,
        avgTrend: { text: '-1,8%', positive: false },
        chart: [
          { label: 'Sen', fullDate: 'Senin, 18 Nov 2026', omzet: 1420000, laba: 325000 },
          { label: 'Sel', fullDate: 'Selasa, 19 Nov 2026', omzet: 1680000, laba: 382000 },
          { label: 'Rab', fullDate: 'Rabu, 20 Nov 2026', omzet: 1540000, laba: 348000 },
          { label: 'Kam', fullDate: 'Kamis, 21 Nov 2026', omzet: 1810000, laba: 415000 },
          { label: 'Jum', fullDate: 'Jumat, 22 Nov 2026', omzet: 1740000, laba: 396000 },
          { label: 'Sab', fullDate: 'Sabtu, 23 Nov 2026', omzet: 2340000, laba: 538000 },
          { label: 'Min', fullDate: 'Minggu, 24 Nov 2026', omzet: 1920000, laba: 436000 },
        ],
        defaultBarIndex: 5,
        products: [],
        transactions: [],
        payments: [],
      },
      month: {
        label: 'Bulan Ini',
        fileSuffix: 'Bulan_Ini',
        dateRange: '1 Nov – 24 Nov 2026',
        omzet: 46820000,
        omzetTrend: { text: '+18,9%', positive: true },
        laba: 10860000,
        labaTrend: { text: '+16,4%', positive: true },
        margin: 'Margin 23,2%',
        trxCount: 542,
        trxTrend: { text: '+12,1%', positive: true },
        avgTrx: 86383,
        avgTrend: { text: '+5,2%', positive: true },
        chart: [],
        defaultBarIndex: 0,
        products: [],
        transactions: [],
        payments: [],
      }
    };

    const appState = {
      period: 'today',
      chartMetric: 'laba',
      selectedBarIndex: 3,
      activeDetailTab: 'products',
      exportPeriod: 'today',
      exportFormat: 'xlsx',
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

    function renderAll() {
      const data = REPORT_DATA[appState.period];
      document.getElementById('date-range-label').textContent = data.dateRange;
      document.getElementById('quick-export-desc').textContent = `4 sheet lengkap • Periode ${data.label}`;

      document.getElementById('kpi-omzet').textContent = formatRupiah(data.omzet);
      document.getElementById('kpi-omzet-trend').textContent = data.omzetTrend.text;
      document.getElementById('kpi-laba').textContent = formatRupiah(data.laba);
      document.getElementById('kpi-laba-trend').textContent = data.labaTrend.text;
      document.getElementById('kpi-margin').textContent = data.margin;
      document.getElementById('kpi-trx-count').textContent = `${data.trxCount} Trx`;
      document.getElementById('kpi-trx-trend').textContent = data.trxTrend.text;
      document.getElementById('kpi-avg-trx').textContent = formatRupiah(data.avgTrx);
      document.getElementById('kpi-avg-trend').textContent = data.avgTrend.text;

      renderBarChart();
      renderDetailTab();
      if (window.lucide) window.lucide.createIcons();
    }

    function renderBarChart() {
      const data = REPORT_DATA[appState.period];
      const chartItems = data.chart;
      const metric = appState.chartMetric;
      if (!chartItems || chartItems.length === 0) return;

      const maxVal = Math.max(...chartItems.map(item => item[metric]), 1);
      const selectedItem = chartItems[appState.selectedBarIndex] || chartItems[0];

      document.getElementById('chart-selected-date').textContent = selectedItem.fullDate;
      document.getElementById('chart-selected-value').textContent = formatRupiah(selectedItem[metric]);

      const container = document.getElementById('bar-chart-container');
      container.innerHTML = chartItems.map((item, idx) => {
        const isSelected = idx === appState.selectedBarIndex;
        const heightPct = Math.max(16, Math.round((item[metric] / maxVal) * 100));

        return `
          <button type="button" onclick="selectChartBar(${idx})" class="flex-1 h-full flex flex-col items-center justify-end gap-2 group focus:outline-none">
            <div class="w-full flex-1 flex items-end justify-center">
              <div style="height: ${heightPct}%" class="w-full max-w-[34px] rounded-t-[7px] rounded-b-[3px] transition-all duration-300 ${isSelected ? 'bg-forest' : 'bg-baridle group-hover:bg-[#A6C7BC]'}"></div>
            </div>
            <span class="text-[11px] leading-none ${isSelected ? 'font-bold text-forest' : 'font-medium text-muted'}">
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
        container.innerHTML = `
          <div class="divide-y divide-borderline/80">
            ${(data.products || []).map((p, idx) => `
              <div class="py-2.5 first:pt-0.5 last:pb-0.5 flex items-center gap-3">
                <div class="w-6 h-6 rounded-[7px] ${idx === 0 ? 'bg-forest text-white' : 'bg-soft border border-softborder text-secondary'} text-[11.5px] font-bold font-mono flex items-center justify-center shrink-0">
                  ${idx + 1}
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-[13.5px] font-semibold text-ink truncate">${p.name}</div>
                  <div class="text-[11.5px] text-muted font-mono">Terjual: ${p.sold} ${p.unit}</div>
                </div>
                <div class="text-[13px] font-bold font-mono text-forest">${formatRupiah(p.revenue)}</div>
              </div>
            `).join('')}
          </div>
        `;
      }
    }

    function handleBottomNav(menu) {
      showToast(`Membuka menu ${menu}...`);
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const next = btn.getAttribute('data-period');
          appState.period = next;
          appState.selectedBarIndex = REPORT_DATA[next].defaultBarIndex;

          document.querySelectorAll('.period-btn').forEach(b => {
            const active = b.getAttribute('data-period') === next;
            b.className = active
              ? 'period-btn py-2 text-[13px] font-semibold rounded-[9px] bg-surface text-forest border border-borderline shadow-[0_1px_2px_rgba(0,0,0,0.03)] transition-all tap-scale'
              : 'period-btn py-2 text-[13px] font-semibold rounded-[9px] text-secondary hover:text-ink transition-all tap-scale';
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
              ? 'metric-btn px-2.5 py-1 text-[11.5px] font-semibold rounded-[7px] bg-surface text-forest border border-borderline transition-all'
              : 'metric-btn px-2.5 py-1 text-[11.5px] font-semibold rounded-[7px] text-secondary hover:text-ink transition-all';
          });
          renderBarChart();
        });
      });

      const modal = document.getElementById('export-modal');
      document.getElementById('open-export-btn').addEventListener('click', () => modal.classList.remove('hidden'));
      document.getElementById('customize-export-btn').addEventListener('click', () => modal.classList.remove('hidden'));
      document.getElementById('close-export-btn').addEventListener('click', () => modal.classList.add('hidden'));
      document.getElementById('export-backdrop').addEventListener('click', () => modal.classList.add('hidden'));

      document.getElementById('quick-export-btn').addEventListener('click', () => {
        showToast('Mengunduh Laporan_Warung_Hari_Ini.xlsx...');
      });
      document.getElementById('confirm-download-btn').addEventListener('click', () => {
        modal.classList.add('hidden');
        showToast('Berhasil mengunduh file Excel!');
      });

      renderAll();
    });
  </script>
</body>
</html>

