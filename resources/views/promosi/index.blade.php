<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Promosi — Toko Kelontong Mas Fadil</title>

  <!-- Google Fonts: Inter & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet" />

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>

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
            borderline: '#E5E3DC',
            softborder: '#DFDDD5',
            forest: '#1B4332',
            foresthover: '#143326',
            sage: '#EBF3F0',
            ink: '#1F2937',
            secondary: '#6B7280',
            muted: '#8C8B84',
            warnbg: '#FFFBEB',
            warnborder: '#FDE68A',
            warntext: '#92400E',
            dangerbg: '#FEF2F2',
            dangerborder: '#FECACA',
            dangertext: '#B91C1C',
          },
          borderRadius: {
            card: '15px',
            box: '11px',
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
    .tap-scale:active { transform: scale(0.975); }

    @keyframes popIn {
      from { opacity: 0; transform: translateY(12px) scale(0.96); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .animate-pop-in { animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-fade-in { animation: fadeIn 0.18s ease-out forwards; }
  </style>
</head>

<body class="bg-backdrop text-ink font-sans antialiased selection:bg-sage selection:text-forest min-h-screen flex items-center justify-center sm:py-6 sm:px-4">

  <!-- Bingkai Aplikasi Mobile -->
  <div id="app-frame" class="relative w-full min-h-screen sm:min-h-0 sm:max-w-[430px] sm:h-[864px] sm:max-h-[94vh] bg-canvas sm:rounded-[32px] sm:border sm:border-[#DCD8CF] sm:shadow-[0_24px_60px_-16px_rgba(27,67,50,0.16)] flex flex-col overflow-hidden">

    <!-- 1. TOP APP BAR (Sticky) -->
    <header class="h-[58px] shrink-0 bg-canvas border-b border-borderline px-4 flex items-center justify-between sticky top-0 z-20">
      <a href="{{ route('lainnya') }}" class="flex items-center gap-1.5 text-forest hover:text-foresthover text-[14px] font-semibold py-2 pr-2 -ml-1 rounded-lg tap-scale">
        <i data-lucide="arrow-left" class="w-[18px] h-[18px]" stroke-width="2"></i>
        <span>Lainnya</span>
      </a>

      <h1 class="text-[17px] font-bold tracking-[-0.015em] text-ink absolute left-1/2 -translate-x-1/2 pointer-events-none">
        Promosi
      </h1>

      <button id="reset-form-btn" type="button" title="Reset pengaturan promosi" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-[9px] text-secondary hover:text-forest hover:bg-soft text-[12px] font-medium tap-scale">
        <i data-lucide="rotate-ccw" class="w-[14px] h-[14px]" stroke-width="2"></i>
        <span>Reset</span>
      </button>
    </header>

    <!-- KONTEN UTAMA (Scrollable) -->
    <main class="flex-1 overflow-y-auto no-scrollbar px-4 pt-4 pb-[92px] space-y-4">

      <!-- Banner Info Singkat Fitur -->
      <div class="bg-sage/80 border border-forest/15 rounded-card px-3.5 py-2.5 flex items-start gap-2.5">
        <div class="w-7 h-7 rounded-[8px] bg-surface border border-forest/15 text-forest flex items-center justify-center shrink-0 mt-0.5">
          <i data-lucide="sparkles" class="w-4 h-4" stroke-width="2"></i>
        </div>
        <div class="text-[12px] text-forest/90 leading-relaxed">
          <span class="font-bold text-forest">Pembuat Poster Instan.</span>
          Pilih template, tentukan produk, isi teks promosi, lalu unduh poster PNG siap kirim ke WhatsApp.
        </div>
      </div>

      <!-- LANGKAH 1: PILIH TEMPLATE POSTER -->
      <section aria-label="Pilih Template Poster" class="bg-surface border border-borderline rounded-card p-3.5 space-y-3">
        <div class="flex items-center justify-between gap-2">
          <div class="flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-forest text-white text-[11px] font-bold flex items-center justify-center shrink-0">1</span>
            <h2 class="text-[14px] font-bold text-ink">Pilih Template Poster</h2>
          </div>
          <span class="text-[11px] text-muted font-medium">Desain tetap</span>
        </div>

        <!-- Grid 2x2 Template Tetap -->
        <div id="template-grid" class="grid grid-cols-2 gap-2.5">
          <!-- Dirender dinamis oleh JS -->
        </div>
      </section>

      <!-- LANGKAH 2: PILIH PRODUK -->
      <section aria-label="Pilih Produk Promosi" class="bg-surface border border-borderline rounded-card p-3.5 space-y-3">
        <div class="flex items-center justify-between gap-2">
          <div class="flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-forest text-white text-[11px] font-bold flex items-center justify-center shrink-0">2</span>
            <h2 class="text-[14px] font-bold text-ink">Pilih Produk</h2>
          </div>
          <span id="selected-counter-badge" class="px-2 py-0.5 rounded-full text-[11px] font-semibold font-mono tabular-nums bg-sage text-forest border border-forest/15">
            4 / 4 produk dipilih
          </span>
        </div>

        <!-- Segmented Control: Pilih Produk | Berdasarkan Kategori -->
        <div class="bg-soft border border-softborder p-1 rounded-[11px] grid grid-cols-2 gap-1" role="tablist">
          <button type="button" data-mode="individual" role="tab" class="product-mode-btn py-2 text-[12.5px] font-semibold rounded-[8px] text-secondary hover:text-ink transition-all tap-scale">
            Pilih Produk
          </button>
          <button type="button" data-mode="category" role="tab" class="product-mode-btn py-2 text-[12.5px] font-semibold rounded-[8px] bg-surface text-forest border border-borderline shadow-[0_1px_2px_rgba(0,0,0,0.03)] transition-all tap-scale">
            Berdasarkan Kategori
          </button>
        </div>

        <!-- Banner Peringatan Kapasitas -->
        <div id="capacity-warning-banner" class="hidden bg-warnbg border border-warnborder rounded-[11px] p-3 flex items-start gap-2.5" role="alert">
          <i data-lucide="alert-triangle" class="w-[18px] h-[18px] text-warntext shrink-0 mt-0.5" stroke-width="2"></i>
          <div class="space-y-1.5 text-[12px] text-warntext leading-snug flex-1">
            <p id="capacity-warning-text" class="font-medium"></p>
            <button id="switch-larger-template-btn" type="button" class="hidden inline-flex items-center gap-1 px-2.5 py-1 rounded-[8px] bg-surface border border-warnborder text-warntext text-[11.5px] font-bold hover:bg-warnbg tap-scale">
              <i data-lucide="arrow-right-left" class="w-3.5 h-3.5" stroke-width="2"></i>
              <span>Pindah ke Template Idulfitri (Maks. 6)</span>
            </button>
          </div>
        </div>

        <!-- Panel Mode A: Pilih Produk Individual -->
        <div id="individual-product-panel" class="hidden space-y-2.5">
          <div class="relative">
            <i data-lucide="search" class="w-4 h-4 text-muted absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" stroke-width="1.9"></i>
            <input id="product-search-input" type="text" placeholder="Cari produk di katalog toko..." class="w-full h-[40px] pl-9 pr-8 bg-soft border border-softborder focus:bg-surface focus:border-forest focus:outline-none rounded-[10px] text-[13px] text-ink placeholder:text-muted transition-colors" />
            <button id="clear-search-btn" type="button" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-muted hover:text-ink p-0.5">
              <i data-lucide="x" class="w-3.5 h-3.5" stroke-width="2"></i>
            </button>
          </div>

          <div id="product-list-container" class="max-h-[268px] overflow-y-auto no-scrollbar divide-y divide-borderline/80 border border-borderline rounded-[12px] bg-canvas/40">
            <!-- Dirender dinamis oleh JS -->
          </div>
        </div>

        <!-- Panel Mode B: Pilih Berdasarkan Kategori -->
        <div id="category-product-panel" class="space-y-2.5">
          <p class="text-[12px] text-secondary">
            Pilih satu kategori untuk langsung memasukkan seluruh produk di dalamnya ke dalam poster:
          </p>
          <div id="category-list-container" class="grid grid-cols-1 gap-2">
            <!-- Dirender dinamis oleh JS -->
          </div>
        </div>
      </section>

      <!-- LANGKAH 3: INFORMASI PROMOSI -->
      <section aria-label="Informasi Promosi" class="bg-surface border border-borderline rounded-card p-3.5 space-y-3">
        <div class="flex items-center gap-2">
          <span class="w-5 h-5 rounded-full bg-forest text-white text-[11px] font-bold flex items-center justify-center shrink-0">3</span>
          <h2 class="text-[14px] font-bold text-ink">Informasi Promosi</h2>
        </div>

        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <label for="promo-title-input" class="text-[12.5px] font-semibold text-ink">
              Judul Promosi <span class="text-dangertext">*</span>
            </label>
            <span id="title-char-count" class="text-[11px] font-mono tabular-nums text-muted">21/48</span>
          </div>
          <input id="promo-title-input" type="text" maxlength="48" value="Promo Berkah Ramadan!" placeholder="Contoh: Promo Berkah Ramadan!" class="w-full h-[42px] px-3 bg-soft border border-softborder focus:bg-surface focus:border-forest focus:outline-none rounded-[10px] text-[13.5px] font-medium text-ink placeholder:text-muted transition-colors" />
          <p id="title-error-msg" class="hidden text-[11.5px] text-dangertext font-medium flex items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0" stroke-width="2"></i>
            <span>Judul promosi wajib diisi sebelum membuat poster.</span>
          </p>
        </div>

        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <label for="promo-desc-input" class="text-[12.5px] font-semibold text-ink">
              Deskripsi Promosi
            </label>
            <span id="desc-char-count" class="text-[11px] font-mono tabular-nums text-muted">74/120</span>
          </div>
          <textarea id="promo-desc-input" rows="2" maxlength="120" placeholder="Belanja hemat kebutuhan pokok menyambut bulan suci hanya di Toko Mas Fadil." class="w-full px-3 py-2.5 bg-soft border border-softborder focus:bg-surface focus:border-forest focus:outline-none rounded-[10px] text-[13px] text-ink placeholder:text-muted resize-none leading-relaxed transition-colors">Belanja hemat kebutuhan pokok menyambut bulan suci hanya di Toko Mas Fadil.</textarea>
        </div>
      </section>

      <!-- LANGKAH 4: PRATINJAU POSTER -->
      <section aria-label="Pratinjau Poster" class="bg-surface border border-borderline rounded-card p-3.5 space-y-3">
        <div class="flex items-center justify-between gap-2">
          <div class="flex items-center gap-1.5">
            <i data-lucide="eye" class="w-4 h-4 text-forest" stroke-width="2"></i>
            <h2 class="text-[14px] font-bold text-ink">Pratinjau Poster</h2>
          </div>
          <span class="px-2 py-0.5 rounded-[7px] bg-soft border border-softborder text-[10.5px] font-mono font-semibold text-secondary tabular-nums">
            1080 × 1350 px • PNG • Portrait
          </span>
        </div>

        <div class="w-full bg-soft border border-softborder rounded-[13px] p-2.5 flex flex-col items-center">
          <div class="w-full max-w-[340px] aspect-[4/5] rounded-[10px] overflow-hidden shadow-[0_10px_28px_-8px_rgba(31,41,55,0.18)] bg-surface border border-borderline relative">
            <canvas id="poster-canvas" width="1080" height="1350" class="w-full h-full block object-contain"></canvas>
          </div>
          <p class="mt-2.5 text-[11px] text-muted text-center">
            Tata letak, font, dan warna mengikuti template terpilih secara otomatis.
          </p>
        </div>

        <div id="generate-error-box" class="hidden bg-dangerbg border border-dangerborder rounded-[11px] px-3 py-2.5 flex items-start gap-2 text-[12px] text-dangertext font-medium" role="alert">
          <i data-lucide="alert-octagon" class="w-4 h-4 shrink-0 mt-0.5" stroke-width="2"></i>
          <span id="generate-error-text"></span>
        </div>

        <button id="generate-poster-btn" type="button" class="w-full h-[48px] rounded-[12px] bg-forest hover:bg-foresthover disabled:bg-[#9CA3AF] disabled:cursor-not-allowed text-white text-[14px] font-bold flex items-center justify-center gap-2 shadow-sm tap-scale">
          <i id="generate-btn-icon" data-lucide="download" class="w-[18px] h-[18px]" stroke-width="2.1"></i>
          <span id="generate-btn-text">Generate &amp; Unduh Poster (PNG)</span>
        </button>

        <p class="text-[11px] text-muted text-center leading-relaxed">
          Poster langsung terunduh otomatis ke perangkat dan tidak disimpan di riwayat database.
        </p>
      </section>

    </main>

    <!-- 5. BOTTOM NAVIGATION BAR -->
    <nav aria-label="Navigasi Utama" class="absolute bottom-0 inset-x-0 bg-canvas border-t border-borderline h-[64px] px-1 grid grid-cols-5 items-center z-30">
      <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 py-1.5 text-muted hover:text-ink tap-scale">
        <i data-lucide="layout-grid" class="w-[20px] h-[20px]" stroke-width="1.75"></i>
        <span class="text-[11px] font-medium leading-none">Home</span>
      </a>

      <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center gap-1 py-1.5 text-muted hover:text-ink tap-scale">
        <i data-lucide="package" class="w-[20px] h-[20px]" stroke-width="1.75"></i>
        <span class="text-[11px] font-medium leading-none">Produk</span>
      </a>

      <a href="{{ route('cashier.index') }}" class="flex flex-col items-center justify-center gap-1 py-1.5 text-muted hover:text-ink tap-scale">
        <i data-lucide="calculator" class="w-[20px] h-[20px]" stroke-width="1.75"></i>
        <span class="text-[11px] font-medium leading-none">Kasir</span>
      </a>

      <a href="{{ route('categories.index') }}" class="flex flex-col items-center justify-center gap-1 py-1.5 text-muted hover:text-ink tap-scale">
        <i data-lucide="store" class="w-[20px] h-[20px]" stroke-width="1.75"></i>
        <span class="text-[11px] font-medium leading-none">Kategori</span>
      </a>

      <!-- Lainnya (Aktif) -->
      <a href="{{ route('lainnya') }}" class="flex flex-col items-center justify-center gap-1 py-1.5 text-forest tap-scale">
        <i data-lucide="grid-2x2" class="w-[20px] h-[20px]" stroke-width="2.1"></i>
        <span class="text-[11px] font-bold leading-none">Lainnya</span>
      </a>
    </nav>

    <!-- Toast Notifikasi -->
    <div id="toast-container" class="pointer-events-none absolute bottom-[76px] left-4 right-4 z-50 flex justify-center transition-all duration-200 opacity-0 translate-y-2" aria-live="polite">
      <div class="bg-ink text-[#FAF9F5] text-[12.5px] font-medium px-4 py-2.5 rounded-full shadow-lg flex items-center gap-2 max-w-full">
        <span id="toast-dot" class="w-2 h-2 rounded-full bg-[#34D399] shrink-0"></span>
        <span id="toast-message" class="truncate">Notifikasi</span>
      </div>
    </div>

  </div>

  <script>
    function formatRupiah(amount) {
      return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    const POSTER_TEMPLATES = [
      {
        id: 'minimalis',
        name: 'Minimalis',
        badge: 'PROMO TOKO',
        maxProducts: 4,
        desc: 'Bersih & modern',
        thumbBg: 'bg-[#FAF9F5]',
        thumbBorder: 'border-[#E5E3DC]',
      },
      {
        id: 'ramadan',
        name: 'Promosi Ramadan',
        badge: 'SPESIAL RAMADAN',
        maxProducts: 4,
        desc: 'Hijau zamrud & emas',
        thumbBg: 'bg-[#12372A]',
        thumbBorder: 'border-[#1B4332]',
      },
      {
        id: 'idul_fitri',
        name: 'Idulfitri',
        badge: 'SEMARAK IDULFITRI',
        maxProducts: 6,
        desc: 'Sage, emas & ketupat',
        thumbBg: 'bg-[#EBF3F0]',
        thumbBorder: 'border-[#C5DBD2]',
      },
      {
        id: 'new_year',
        name: 'Tahun Baru',
        badge: 'PROMO TAHUN BARU',
        maxProducts: 4,
        desc: 'Navy malam & emas',
        thumbBg: 'bg-[#0F172A]',
        thumbBorder: 'border-[#1E293B]',
      },
    ];

    // Data dari Controller Laravel (dengan fallback default jika kosong)
    const SERVER_CATEGORIES = @json($categories ?? []);
    const SERVER_PRODUCTS = @json($products ?? []);

    const CATEGORIES = SERVER_CATEGORIES.length > 0 ? SERVER_CATEGORIES.map(c => ({
      id: String(c.id),
      name: c.name,
      icon: c.name.toLowerCase().includes('sembako') ? 'wheat' : (c.name.toLowerCase().includes('minyak') ? 'droplet' : 'box')
    })) : [
      { id: '1', name: 'Sembako', icon: 'wheat' },
      { id: '2', name: 'Minyak & Bumbu', icon: 'droplet' },
      { id: '3', name: 'Mie & Makanan Instan', icon: 'box' },
      { id: '4', name: 'Minuman & Susu', icon: 'cup-soda' },
    ];

    const STORE_PRODUCTS = SERVER_PRODUCTS.length > 0 ? SERVER_PRODUCTS.map(p => ({
      id: String(p.id),
      name: p.name,
      price: Number(p.selling_price || p.price || 0),
      unit: p.unit || 'pcs',
      categoryId: String(p.category_id),
      hasPhoto: Boolean(p.image),
      illustration: 'item'
    })) : [
      { id: '1', name: 'Beras Ramos Setra 5kg', price: 72000, unit: 'krg', categoryId: '1', hasPhoto: true, illustration: 'rice' },
      { id: '2', name: 'Minyak Goreng Tropical 2L', price: 38000, unit: 'btl', categoryId: '2', hasPhoto: true, illustration: 'oil' },
      { id: '3', name: 'Gula Pasir Gulaku 1kg', price: 17500, unit: 'pcs', categoryId: '1', hasPhoto: true, illustration: 'sugar' },
      { id: '4', name: 'Telur Ayam Negeri 1kg', price: 29000, unit: 'kg', categoryId: '1', hasPhoto: true, illustration: 'eggs' },
    ];

    // State persis seperti pada Screenshot
    const appState = {
      selectedTemplateId: 'ramadan', // Screenshot menunjukkan "Promosi Ramadan" terpilih
      selectionMode: 'category',     // Screenshot menunjukkan tab "Berdasarkan Kategori" aktif
      selectedCategoryId: '1',       // Screenshot menunjukkan "Sembako (4 produk)" tercentang
      selectedProductIds: [],
      searchQuery: '',
      promoTitle: 'Promo Berkah Ramadan!',
      promoDesc: 'Belanja hemat kebutuhan pokok menyambut bulan suci hanya di Toko Mas Fadil.',
      isGenerating: false,
    };

    // Sinkronkan produk terpilih dari kategori awal
    const initCatProducts = STORE_PRODUCTS.filter(p => p.categoryId === appState.selectedCategoryId);
    appState.selectedProductIds = initCatProducts.map(p => p.id);

    let toastTimer = null;
    function showToast(message, type = 'success') {
      const container = document.getElementById('toast-container');
      const textEl = document.getElementById('toast-message');
      const dotEl = document.getElementById('toast-dot');
      textEl.textContent = message;
      dotEl.className = type === 'error'
        ? 'w-2 h-2 rounded-full bg-[#F87171] shrink-0'
        : 'w-2 h-2 rounded-full bg-[#34D399] shrink-0';
      container.classList.remove('opacity-0', 'translate-y-2');
      container.classList.add('opacity-100', 'translate-y-0');
      if (toastTimer) clearTimeout(toastTimer);
      toastTimer = setTimeout(() => {
        container.classList.remove('opacity-100', 'translate-y-0');
        container.classList.add('opacity-0', 'translate-y-2');
      }, 3000);
    }

    function getActiveTemplate() {
      return POSTER_TEMPLATES.find(t => t.id === appState.selectedTemplateId) || POSTER_TEMPLATES[1];
    }

    function getSelectedProducts() {
      return STORE_PRODUCTS.filter(p => appState.selectedProductIds.includes(p.id));
    }

    function renderTemplateGrid() {
      const container = document.getElementById('template-grid');
      container.innerHTML = POSTER_TEMPLATES.map((tpl) => {
        const isSelected = tpl.id === appState.selectedTemplateId;
        let miniDecor = '';
        if (tpl.id === 'minimalis') {
          miniDecor = `
            <div class="h-2 w-full bg-[#1B4332] rounded-t-[6px]"></div>
            <div class="p-1.5 space-y-1">
              <div class="h-1.5 w-10 bg-[#1B4332] rounded-full mx-auto"></div>
              <div class="grid grid-cols-2 gap-1 pt-0.5">
                <div class="h-4 rounded-[3px] bg-white border border-[#E5E3DC]"></div>
                <div class="h-4 rounded-[3px] bg-white border border-[#E5E3DC]"></div>
                <div class="h-4 rounded-[3px] bg-white border border-[#E5E3DC]"></div>
                <div class="h-4 rounded-[3px] bg-white border border-[#E5E3DC]"></div>
              </div>
            </div>
          `;
        } else if (tpl.id === 'ramadan') {
          miniDecor = `
            <div class="p-1.5 space-y-1 relative">
              <div class="flex items-center justify-between px-0.5">
                <span class="w-2.5 h-2.5 rounded-full border-r-2 border-[#FACC15]"></span>
                <div class="h-1.5 w-11 bg-[#FDE68A] rounded-full"></div>
                <span class="w-1.5 h-1.5 rotate-45 bg-[#FACC15]"></span>
              </div>
              <div class="grid grid-cols-2 gap-1 pt-0.5">
                <div class="h-4 rounded-[3px] bg-[#FFFDF7] border border-[#D4AF37]/60"></div>
                <div class="h-4 rounded-[3px] bg-[#FFFDF7] border border-[#D4AF37]/60"></div>
                <div class="h-4 rounded-[3px] bg-[#FFFDF7] border border-[#D4AF37]/60"></div>
                <div class="h-4 rounded-[3px] bg-[#FFFDF7] border border-[#D4AF37]/60"></div>
              </div>
            </div>
          `;
        } else if (tpl.id === 'idul_fitri') {
          miniDecor = `
            <div class="p-1.5 space-y-1">
              <div class="flex items-center justify-between px-0.5">
                <span class="w-2 h-2 rotate-45 bg-[#D4AF37]"></span>
                <div class="h-1.5 w-10 bg-[#1B4332] rounded-full"></div>
                <span class="w-2 h-2 rotate-45 bg-[#D4AF37]"></span>
              </div>
              <div class="grid grid-cols-3 gap-0.5 pt-0.5">
                <div class="h-3.5 rounded-[2px] bg-white border border-[#C5DBD2]"></div>
                <div class="h-3.5 rounded-[2px] bg-white border border-[#C5DBD2]"></div>
                <div class="h-3.5 rounded-[2px] bg-white border border-[#C5DBD2]"></div>
                <div class="h-3.5 rounded-[2px] bg-white border border-[#C5DBD2]"></div>
                <div class="h-3.5 rounded-[2px] bg-white border border-[#C5DBD2]"></div>
                <div class="h-3.5 rounded-[2px] bg-white border border-[#C5DBD2]"></div>
              </div>
            </div>
          `;
        } else {
          miniDecor = `
            <div class="p-1.5 space-y-1">
              <div class="flex items-center justify-between px-0.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FACC15]"></span>
                <div class="h-1.5 w-11 bg-[#FDE047] rounded-full"></div>
                <span class="w-1.5 h-1.5 rounded-full bg-[#FACC15]"></span>
              </div>
              <div class="grid grid-cols-2 gap-1 pt-0.5">
                <div class="h-4 rounded-[3px] bg-white/95"></div>
                <div class="h-4 rounded-[3px] bg-white/95"></div>
                <div class="h-4 rounded-[3px] bg-white/95"></div>
                <div class="h-4 rounded-[3px] bg-white/95"></div>
              </div>
            </div>
          `;
        }

        return `
          <button
            type="button"
            onclick="selectTemplate('${tpl.id}')"
            class="relative text-left p-2.5 rounded-[13px] border transition-all tap-scale ${
              isSelected
                ? 'border-[#1B4332] bg-[#FAF9F5] ring-2 ring-[#1B4332]'
                : 'border-borderline bg-canvas/50 hover:bg-soft'
            }"
          >
            ${
              isSelected
                ? `<span class="absolute top-2 right-2 w-5 h-5 rounded-full bg-[#1B4332] text-white flex items-center justify-center shadow-sm z-10">
                    <i data-lucide="check" class="w-3 h-3" stroke-width="2.6"></i>
                   </span>`
                : ''
            }
            <div class="w-full h-[64px] rounded-[8px] border ${tpl.thumbBorder} ${tpl.thumbBg} overflow-hidden mb-2">
              ${miniDecor}
            </div>
            <div class="text-[12.5px] font-bold text-ink leading-tight truncate">${tpl.name}</div>
            <div class="mt-1 flex items-center justify-between gap-1">
              <span class="inline-block px-1.5 py-0.5 rounded-[5px] text-[10px] font-semibold font-mono tabular-nums ${
                isSelected ? 'bg-surface text-forest border border-forest/20' : 'bg-soft text-secondary'
              }">
                Maks. ${tpl.maxProducts} produk
              </span>
            </div>
          </button>
        `;
      }).join('');

      if (window.lucide) window.lucide.createIcons();
    }

    function selectTemplate(templateId) {
      appState.selectedTemplateId = templateId;
      hideGenerateError();
      renderTemplateGrid();
      updateValidationUI();
      renderPosterCanvas();
    }

    function renderCategoryList() {
      const container = document.getElementById('category-list-container');
      container.innerHTML = CATEGORIES.map((cat) => {
        const productsInCat = STORE_PRODUCTS.filter((p) => p.categoryId === cat.id);
        const isSelected = appState.selectedCategoryId === cat.id;

        return `
          <button
            type="button"
            onclick="selectCategory('${cat.id}')"
            class="w-full p-3 rounded-[12px] border flex items-center justify-between gap-2.5 text-left tap-scale ${
              isSelected
                ? 'bg-sage/65 border-forest text-forest'
                : 'bg-canvas/50 border-borderline hover:bg-soft text-ink'
            }"
          >
            <div class="flex items-center gap-2.5 min-w-0">
              <div class="w-9 h-9 rounded-[9px] flex items-center justify-center shrink-0 ${
                isSelected
                  ? 'bg-surface border border-forest/20 text-forest'
                  : 'bg-surface border border-borderline text-forest'
              }">
                <i data-lucide="${cat.icon}" class="w-[17px] h-[17px]" stroke-width="1.9"></i>
              </div>
              <div class="min-w-0">
                <div class="text-[13px] font-bold truncate">${cat.name}</div>
                <div class="text-[11.5px] ${isSelected ? 'text-forest/80' : 'text-muted'} truncate">
                  ${productsInCat.length} produk otomatis terpilih
                </div>
              </div>
            </div>
            <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0 ${
              isSelected ? 'bg-forest border-forest text-white' : 'border-[#C7C4B8] bg-surface'
            }">
              ${isSelected ? '<i data-lucide="check" class="w-3 h-3" stroke-width="2.8"></i>' : ''}
            </div>
          </button>
        `;
      }).join('');

      if (window.lucide) window.lucide.createIcons();
    }

    function renderProductList() {
      const container = document.getElementById('product-list-container');
      const q = appState.searchQuery.trim().toLowerCase();
      const filtered = STORE_PRODUCTS.filter(p => p.name.toLowerCase().includes(q));

      if (filtered.length === 0) {
        container.innerHTML = `<div class="py-6 text-center text-[12px] text-muted">Produk tidak ditemukan</div>`;
        return;
      }

      container.innerHTML = filtered.map(prod => {
        const checked = appState.selectedProductIds.includes(prod.id);
        return `
          <button type="button" onclick="toggleProduct('${prod.id}')" class="w-full px-3 py-2.5 flex items-center justify-between gap-2 text-left hover:bg-canvas transition-colors ${checked ? 'bg-sage/40' : ''}">
            <div class="flex items-center gap-2.5 min-w-0">
              <div class="w-5 h-5 rounded-[5px] border flex items-center justify-center shrink-0 ${checked ? 'bg-forest border-forest text-white' : 'border-[#C7C4B8] bg-surface'}">
                ${checked ? '<i data-lucide="check" class="w-3.5 h-3.5" stroke-width="2.8"></i>' : ''}
              </div>
              <div class="min-w-0">
                <div class="text-[13px] font-semibold text-ink truncate">${prod.name}</div>
                <div class="text-[11.5px] font-mono text-forest font-bold">${formatRupiah(prod.price)}</div>
              </div>
            </div>
          </button>
        `;
      }).join('');
      if (window.lucide) window.lucide.createIcons();
    }

    function selectCategory(catId) {
      appState.selectedCategoryId = catId;
      appState.selectedProductIds = STORE_PRODUCTS.filter(p => p.categoryId === catId).map(p => p.id);
      hideGenerateError();
      renderCategoryList();
      renderProductList();
      updateValidationUI();
      renderPosterCanvas();
    }

    function toggleProduct(prodId) {
      appState.selectedCategoryId = null;
      const idx = appState.selectedProductIds.indexOf(prodId);
      if (idx >= 0) appState.selectedProductIds.splice(idx, 1);
      else appState.selectedProductIds.push(prodId);

      hideGenerateError();
      renderProductList();
      renderCategoryList();
      updateValidationUI();
      renderPosterCanvas();
    }

    function updateValidationUI() {
      const tpl = getActiveTemplate();
      const count = appState.selectedProductIds.length;
      const isOver = count > tpl.maxProducts;

      const badge = document.getElementById('selected-counter-badge');
      badge.textContent = `${count} / ${tpl.maxProducts} produk dipilih`;
      badge.className = isOver
        ? 'px-2 py-0.5 rounded-full text-[11px] font-semibold font-mono tabular-nums bg-dangerbg text-dangertext border border-dangerborder'
        : 'px-2 py-0.5 rounded-full text-[11px] font-semibold font-mono tabular-nums bg-sage text-forest border border-forest/15';

      const warnBanner = document.getElementById('capacity-warning-banner');
      const warnText = document.getElementById('capacity-warning-text');
      const switchBtn = document.getElementById('switch-larger-template-btn');

      if (isOver) {
        warnBanner.classList.remove('hidden');
        warnText.textContent = `Jumlah produk (${count}) melebihi kapasitas template ${tpl.name} (maks. ${tpl.maxProducts}).`;
        switchBtn.classList.toggle('hidden', !(tpl.maxProducts < 6 && count <= 6));
      } else {
        warnBanner.classList.add('hidden');
      }

      document.getElementById('generate-poster-btn').disabled = isOver || appState.isGenerating;
    }

    function hideGenerateError() {
      document.getElementById('generate-error-box').classList.add('hidden');
      document.getElementById('title-error-msg').classList.add('hidden');
    }

    function showGenerateError(msg) {
      document.getElementById('generate-error-text').textContent = msg;
      document.getElementById('generate-error-box').classList.remove('hidden');
      if (window.lucide) window.lucide.createIcons();
    }

    // Canvas Draw Helpers
    function drawRoundRect(ctx, x, y, w, h, r) {
      const radius = Math.min(r, w / 2, h / 2);
      ctx.beginPath();
      ctx.moveTo(x + radius, y);
      ctx.arcTo(x + w, y, x + w, y + h, radius);
      ctx.arcTo(x + w, y + h, x, y + h, radius);
      ctx.arcTo(x, y + h, x, y, radius);
      ctx.arcTo(x, y, x + w, y, radius);
      ctx.closePath();
    }

    function drawWrappedText(ctx, text, x, y, maxWidth, lineHeight, maxLines) {
      const words = String(text || '').trim().split(/\s+/);
      let line = '';
      let lines = [];
      for (let i = 0; i < words.length; i++) {
        const testLine = line ? line + ' ' + words[i] : words[i];
        if (ctx.measureText(testLine).width > maxWidth && line) {
          lines.push(line);
          line = words[i];
          if (lines.length === maxLines - 1) break;
        } else {
          line = testLine;
        }
      }
      if (line) lines.push(line);
      lines.slice(0, maxLines).forEach((l, idx) => {
        ctx.fillText(l, x, y + idx * lineHeight);
      });
      return lines.length * lineHeight;
    }

    function renderPosterCanvas() {
      const canvas = document.getElementById('poster-canvas');
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      const W = 1080;
      const H = 1350;
      const tpl = getActiveTemplate();
      const products = getSelectedProducts().slice(0, tpl.maxProducts);

      ctx.clearRect(0, 0, W, H);

      // Background
      const isRamadan = tpl.id === 'ramadan';
      const grad = ctx.createLinearGradient(0, 0, 0, H);
      grad.addColorStop(0, isRamadan ? '#0D2E22' : '#FAF9F5');
      grad.addColorStop(1, isRamadan ? '#1B4D3E' : '#F5F3EC');
      ctx.fillStyle = grad;
      ctx.fillRect(0, 0, W, H);

      // Ornamen Ramadan
      if (isRamadan) {
        ctx.fillStyle = '#FACC15';
        ctx.beginPath();
        ctx.arc(118, 124, 44, 0, Math.PI * 2);
        ctx.fill();
        ctx.fillStyle = '#0D2E22';
        ctx.beginPath();
        ctx.arc(136, 114, 38, 0, Math.PI * 2);
        ctx.fill();
      }

      // Badge
      ctx.textAlign = 'center';
      ctx.font = '700 24px Inter, sans-serif';
      const badgeW = ctx.measureText(tpl.badge).width + 64;
      drawRoundRect(ctx, (W - badgeW) / 2, 86, badgeW, 52, 26);
      ctx.fillStyle = isRamadan ? '#D4AF37' : '#EBF3F0';
      ctx.fill();
      ctx.fillStyle = isRamadan ? '#0D2E22' : '#1B4332';
      ctx.fillText(tpl.badge, W / 2, 121);

      // Judul
      ctx.fillStyle = isRamadan ? '#FFFFFF' : '#1B4332';
      ctx.font = '800 58px Inter, sans-serif';
      const titleHeight = drawWrappedText(ctx, appState.promoTitle, W / 2, 210, W - 160, 68, 2);

      // Deskripsi
      ctx.fillStyle = isRamadan ? '#D1FAE5' : '#4B5563';
      ctx.font = '500 28px Inter, sans-serif';
      drawWrappedText(ctx, appState.promoDesc, W / 2, 210 + titleHeight + 14, W - 180, 38, 2);

      // Kartu Produk Grid (2x2)
      const gridTop = 380;
      const marginX = 68;
      const gridWidth = W - marginX * 2;
      const cardW = (gridWidth - 28) / 2;
      const cardH = 360;

      products.forEach((p, idx) => {
        const col = idx % 2;
        const row = Math.floor(idx / 2);
        const x = marginX + col * (cardW + 28);
        const y = gridTop + row * (cardH + 28);

        drawRoundRect(ctx, x, y, cardW, cardH, 20);
        ctx.fillStyle = isRamadan ? '#FFFDF7' : '#FFFFFF';
        ctx.fill();
        ctx.strokeStyle = isRamadan ? '#D4AF37' : '#E5E3DC';
        ctx.lineWidth = 3;
        ctx.stroke();

        // Nama Produk
        ctx.fillStyle = '#143326';
        ctx.font = '700 28px Inter, sans-serif';
        drawWrappedText(ctx, p.name, x + cardW / 2, y + 140, cardW - 32, 34, 2);

        // Badge Harga
        drawRoundRect(ctx, x + 24, y + cardH - 74, cardW - 48, 52, 12);
        ctx.fillStyle = '#1B4332';
        ctx.fill();
        ctx.fillStyle = isRamadan ? '#FDE68A' : '#FFFFFF';
        ctx.font = '700 30px "JetBrains Mono", monospace';
        ctx.fillText(formatRupiah(p.price), x + cardW / 2, y + cardH - 38);
      });

      // Footer
      ctx.fillStyle = isRamadan ? '#092219' : '#1B4332';
      ctx.fillRect(0, H - 110, W, 110);
      ctx.textAlign = 'left';
      ctx.fillStyle = isRamadan ? '#FDE68A' : '#FFFFFF';
      ctx.font = '800 28px Inter, sans-serif';
      ctx.fillText('TOKO KELONTONG MAS FADIL', 64, H - 56);
      ctx.fillStyle = '#C5DBD2';
      ctx.font = '500 20px Inter, sans-serif';
      ctx.fillText('Kebutuhan Pokok Terlengkap & Terpercaya', 64, H - 26);
    }

    function handleGeneratePoster() {
      const tpl = getActiveTemplate();
      const count = appState.selectedProductIds.length;
      if (count === 0) return showGenerateError('Pilih minimal 1 produk.');
      if (count > tpl.maxProducts) return showGenerateError('Jumlah produk melebihi kapasitas.');
      if (!appState.promoTitle.trim()) return showGenerateError('Judul wajib diisi.');

      const canvas = document.getElementById('poster-canvas');
      const dataUrl = canvas.toDataURL('image/png');
      const link = document.createElement('a');
      link.href = dataUrl;
      link.download = `poster-${tpl.id}-${Date.now()}.png`;
      link.click();
      showToast('Poster berhasil diunduh!');
    }

    document.addEventListener('DOMContentLoaded', () => {
      // Switch Mode
      document.querySelectorAll('.product-mode-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const mode = btn.getAttribute('data-mode');
          appState.selectionMode = mode;
          document.querySelectorAll('.product-mode-btn').forEach(b => {
            const active = b.getAttribute('data-mode') === mode;
            b.className = active
              ? 'product-mode-btn py-2 text-[12.5px] font-semibold rounded-[8px] bg-surface text-forest border border-borderline shadow-[0_1px_2px_rgba(0,0,0,0.03)] transition-all tap-scale'
              : 'product-mode-btn py-2 text-[12.5px] font-semibold rounded-[8px] text-secondary hover:text-ink transition-all tap-scale';
          });
          document.getElementById('individual-product-panel').classList.toggle('hidden', mode !== 'individual');
          document.getElementById('category-product-panel').classList.toggle('hidden', mode !== 'category');
        });
      });

      document.getElementById('promo-title-input').addEventListener('input', e => {
        appState.promoTitle = e.target.value;
        document.getElementById('title-char-count').textContent = `${e.target.value.length}/48`;
        renderPosterCanvas();
      });

      document.getElementById('promo-desc-input').addEventListener('input', e => {
        appState.promoDesc = e.target.value;
        document.getElementById('desc-char-count').textContent = `${e.target.value.length}/120`;
        renderPosterCanvas();
      });

      document.getElementById('switch-larger-template-btn').addEventListener('click', () => {
        selectTemplate('idul_fitri');
      });

      document.getElementById('generate-poster-btn').addEventListener('click', handleGeneratePoster);

      renderTemplateGrid();
      renderCategoryList();
      renderProductList();
      updateValidationUI();
      renderPosterCanvas();
    });
  </script>
</body>
</html>
