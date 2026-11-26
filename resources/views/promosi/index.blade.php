<x-app-layout title="Promosi - Toko Kelontong Mas Fadil" active="poster">
    <div class="px-4 pt-4 pb-6 space-y-4">
        <!-- 1. Top Bar / Back to Lainnya -->
        <div class="flex items-center justify-between">
            <a href="{{ route('lainnya') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Lainnya</span>
            </a>

            <button id="reset-form-btn" type="button" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Reset</span>
            </button>
        </div>

        <!-- Header -->
        <header>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Pembuat Poster Promosi</h1>
            <p class="text-xs text-gray-500 font-medium mt-0.5">
                Desain poster promosi otomatis resolusi tinggi (PNG) siap kirim ke WhatsApp pelanggan.
            </p>
        </header>

        <!-- LANGKAH 1: PILIH TEMPLATE POSTER -->
        <section class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#173628] text-white text-[11px] font-bold flex items-center justify-center">1</span>
                    <h2 class="text-sm font-bold text-gray-900">Pilih Template Poster</h2>
                </div>
                <span class="text-[11px] font-medium text-gray-400">Desain tetap</span>
            </div>

            <!-- Grid 2x2 Template Tetap -->
            <div id="template-grid" class="grid grid-cols-2 gap-2.5">
                <!-- Dirender dinamis oleh JS -->
            </div>
        </section>

        <!-- LANGKAH 2: PILIH PRODUK -->
        <section class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#173628] text-white text-[11px] font-bold flex items-center justify-center">2</span>
                    <h2 class="text-sm font-bold text-gray-900">Pilih Produk</h2>
                </div>
                <span id="selected-counter-badge" class="px-2 py-0.5 rounded-full text-[11px] font-semibold font-mono tabular-nums bg-emerald-50 text-emerald-800 border border-emerald-200">
                    4 / 4 produk dipilih
                </span>
            </div>

            <!-- Segmented Control: Pilih Produk | Berdasarkan Kategori -->
            <div class="bg-gray-100 p-1 rounded-xl grid grid-cols-2 gap-1" role="tablist">
                <button type="button" data-mode="individual" class="product-mode-btn py-2 text-xs font-semibold rounded-lg text-gray-500 hover:text-gray-900 transition-all">
                    Pilih Produk
                </button>
                <button type="button" data-mode="category" class="product-mode-btn py-2 text-xs font-semibold rounded-lg bg-white text-[#173628] shadow-xs transition-all">
                    Berdasarkan Kategori
                </button>
            </div>

            <!-- Banner Peringatan Kapasitas -->
            <div id="capacity-warning-banner" class="hidden bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2.5" role="alert">
                <svg class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="space-y-1.5 text-xs text-amber-800 leading-snug flex-1">
                    <p id="capacity-warning-text" class="font-medium"></p>
                    <button id="switch-larger-template-btn" type="button" class="hidden inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-amber-300 text-amber-900 text-[11px] font-bold hover:bg-amber-50">
                        <span>Pindah ke Template Idulfitri (Maks. 6)</span>
                    </button>
                </div>
            </div>

            <!-- Panel Mode A: Pilih Produk Individual -->
            <div id="individual-product-panel" class="hidden space-y-2.5">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input id="product-search-input" type="text" placeholder="Cari nama produk..." class="w-full h-10 pl-9 pr-8 bg-gray-50 border border-gray-200 focus:bg-white focus:border-[#173628] focus:outline-none rounded-xl text-xs text-gray-900 placeholder:text-gray-400 transition-colors" />
                    <button id="clear-search-btn" type="button" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 p-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="product-list-container" class="max-h-[268px] overflow-y-auto divide-y divide-gray-100 border border-gray-100 rounded-xl bg-gray-50/50">
                    <!-- Dirender dinamis oleh JS -->
                </div>
            </div>

            <!-- Panel Mode B: Berdasarkan Kategori -->
            <div id="category-product-panel" class="space-y-2.5">
                <p class="text-xs text-gray-500">
                    Pilih kategori untuk otomatis menyertakan semua produk di dalamnya:
                </p>
                <div id="category-list-container" class="grid grid-cols-1 gap-2">
                    <!-- Dirender dinamis oleh JS -->
                </div>
            </div>
        </section>

        <!-- LANGKAH 3: INFORMASI PROMOSI -->
        <section class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 space-y-3">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-[#173628] text-white text-[11px] font-bold flex items-center justify-center">3</span>
                <h2 class="text-sm font-bold text-gray-900">Informasi Promosi</h2>
            </div>

            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <label for="promo-title-input" class="text-xs font-semibold text-gray-800">
                        Judul Promosi <span class="text-red-600">*</span>
                    </label>
                    <span id="title-char-count" class="text-[11px] font-mono tabular-nums text-gray-400">21/48</span>
                </div>
                <input id="promo-title-input" type="text" maxlength="48" value="Promo Berkah Ramadan!" placeholder="Contoh: Promo Berkah Ramadan!" class="w-full h-10 px-3 bg-gray-50 border border-gray-200 focus:bg-white focus:border-[#173628] focus:outline-none rounded-xl text-xs font-semibold text-gray-900 transition-colors" />
                <p id="title-error-msg" class="hidden text-[11px] text-red-600 font-medium">Judul promosi wajib diisi.</p>
            </div>

            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <label for="promo-desc-input" class="text-xs font-semibold text-gray-800">Deskripsi Promosi</label>
                    <span id="desc-char-count" class="text-[11px] font-mono tabular-nums text-gray-400">74/120</span>
                </div>
                <textarea id="promo-desc-input" rows="2" maxlength="120" placeholder="Belanja hemat kebutuhan pokok..." class="w-full px-3 py-2 bg-gray-50 border border-gray-200 focus:bg-white focus:border-[#173628] focus:outline-none rounded-xl text-xs text-gray-900 resize-none transition-colors">Belanja hemat kebutuhan pokok menyambut bulan suci hanya di Toko Mas Fadil.</textarea>
            </div>
        </section>

        <!-- LANGKAH 4: PRATINJAU POSTER -->
        <section class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#173628]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <h2 class="text-sm font-bold text-gray-900">Pratinjau Poster</h2>
                </div>
                <span class="px-2 py-0.5 rounded-md bg-gray-100 text-[10px] font-mono font-semibold text-gray-600">
                    1080 × 1350 px • Portrait
                </span>
            </div>

            <!-- Canvas Container -->
            <div class="w-full bg-gray-50 border border-gray-100 rounded-xl p-3 flex flex-col items-center">
                <div class="w-full max-w-[340px] aspect-[4/5] rounded-xl overflow-hidden shadow-lg bg-white relative">
                    <canvas id="poster-canvas" width="1080" height="1350" class="w-full h-full block object-contain"></canvas>
                </div>
                <p class="mt-2 text-[11px] text-gray-400 text-center">
                    Tata letak, font, dan warna mengikuti template secara otomatis.
                </p>
            </div>

            <div id="generate-error-box" class="hidden bg-red-50 border border-red-200 rounded-xl px-3 py-2 text-xs text-red-700 font-medium">
                <span id="generate-error-text"></span>
            </div>

            <button id="generate-poster-btn" type="button" class="w-full h-11 rounded-xl bg-[#173628] hover:bg-[#122b20] active:scale-[0.99] disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-xs font-bold flex items-center justify-center gap-2 shadow-xs transition-all">
                <svg class="w-4 h-4 stroke-[2.2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span id="generate-btn-text">Generate &amp; Unduh Poster (PNG)</span>
            </button>
        </section>
    </div>

    <!-- Script Logika Canvas & Interaksi -->
    <script>
        function formatRupiah(amount) {
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        }

        const POSTER_TEMPLATES = [
            { id: 'minimalis', name: 'Minimalis', badge: 'PROMO TOKO', maxProducts: 4, thumbBg: 'bg-white', thumbBorder: 'border-gray-200' },
            { id: 'ramadan', name: 'Promosi Ramadan', badge: 'SPESIAL RAMADAN', maxProducts: 4, thumbBg: 'bg-[#0D2E22]', thumbBorder: 'border-[#173628]' },
            { id: 'idul_fitri', name: 'Idulfitri', badge: 'SEMARAK IDULFITRI', maxProducts: 6, thumbBg: 'bg-emerald-50', thumbBorder: 'border-emerald-200' },
            { id: 'new_year', name: 'Tahun Baru', badge: 'PROMO TAHUN BARU', maxProducts: 4, thumbBg: 'bg-slate-900', thumbBorder: 'border-slate-800' },
        ];

        const SERVER_CATEGORIES = @json($categories ?? []);
        const SERVER_PRODUCTS = @json($products ?? []);

        const CATEGORIES = SERVER_CATEGORIES.map(c => ({
            id: String(c.id),
            name: c.name,
            productsCount: c.products_count ?? 0,
            icon: c.name.toLowerCase().includes('sembako') ? 'wheat' : 'box'
        }));

        const STORE_PRODUCTS = SERVER_PRODUCTS.map(p => ({
            id: String(p.id),
            name: p.name,
            price: Number(p.current_selling_price || p.price || 0),
            unit: p.unit || 'pcs',
            categoryId: String(p.category_id),
        }));

        // Default: Ramadan terpilih, kategori pertama (sembako) tercentang
        const defaultCat = CATEGORIES.find(c => c.name.toLowerCase().includes('sembako')) || CATEGORIES[0];
        const defaultCatId = defaultCat ? defaultCat.id : '1';

        const appState = {
            selectedTemplateId: 'ramadan',
            selectionMode: 'category',
            selectedCategoryId: defaultCatId,
            selectedProductIds: STORE_PRODUCTS.filter(p => p.categoryId === defaultCatId).map(p => p.id),
            searchQuery: '',
            promoTitle: 'Promo Berkah Ramadan!',
            promoDesc: 'Belanja hemat kebutuhan pokok menyambut bulan suci hanya di Toko Mas Fadil.',
            isGenerating: false,
        };

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
                return `
                    <button type="button" onclick="selectTemplate('${tpl.id}')" class="relative text-left p-2.5 rounded-xl border transition-all ${isSelected ? 'border-[#173628] bg-emerald-50/50 ring-2 ring-[#173628]' : 'border-gray-200 bg-white hover:bg-gray-50'}">
                        ${isSelected ? '<span class="absolute top-2 right-2 w-4 h-4 rounded-full bg-[#173628] text-white flex items-center justify-center text-[10px] font-bold">✓</span>' : ''}
                        <div class="w-full h-12 rounded-lg border ${tpl.thumbBorder} ${tpl.thumbBg} flex items-center justify-center mb-2">
                            <span class="text-[10px] font-bold ${tpl.id === 'ramadan' || tpl.id === 'new_year' ? 'text-amber-300' : 'text-gray-700'}">${tpl.badge}</span>
                        </div>
                        <div class="text-xs font-bold text-gray-900 truncate">${tpl.name}</div>
                        <div class="text-[10px] font-semibold text-gray-400 mt-0.5">Maks. ${tpl.maxProducts} produk</div>
                    </button>
                `;
            }).join('');
        }

        function selectTemplate(templateId) {
            appState.selectedTemplateId = templateId;
            renderTemplateGrid();
            updateValidationUI();
            renderPosterCanvas();
        }

        function renderCategoryList() {
            const container = document.getElementById('category-list-container');
            container.innerHTML = CATEGORIES.map((cat) => {
                const isSelected = appState.selectedCategoryId === cat.id;
                const count = STORE_PRODUCTS.filter(p => p.categoryId === cat.id).length;
                return `
                    <button type="button" onclick="selectCategory('${cat.id}')" class="w-full p-3 rounded-xl border flex items-center justify-between gap-2.5 text-left transition-all ${isSelected ? 'bg-emerald-50/70 border-[#173628] text-[#173628]' : 'bg-white border-gray-200 hover:bg-gray-50 text-gray-800'}">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 font-bold text-xs">
                                📦
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold truncate">${cat.name}</div>
                                <div class="text-[11px] ${isSelected ? 'text-emerald-700' : 'text-gray-400'}">${count} produk otomatis terpilih</div>
                            </div>
                        </div>
                        <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0 ${isSelected ? 'bg-[#173628] border-[#173628] text-white text-[11px]' : 'border-gray-300 bg-white'}">
                            ${isSelected ? '✓' : ''}
                        </div>
                    </button>
                `;
            }).join('');
        }

        function renderProductList() {
            const container = document.getElementById('product-list-container');
            const q = appState.searchQuery.trim().toLowerCase();
            const filtered = STORE_PRODUCTS.filter(p => p.name.toLowerCase().includes(q));

            if (filtered.length === 0) {
                container.innerHTML = `<div class="py-6 text-center text-xs text-gray-400">Produk tidak ditemukan</div>`;
                return;
            }

            container.innerHTML = filtered.map(prod => {
                const checked = appState.selectedProductIds.includes(prod.id);
                return `
                    <button type="button" onclick="toggleProduct('${prod.id}')" class="w-full px-3 py-2.5 flex items-center justify-between gap-2 text-left hover:bg-white transition-colors ${checked ? 'bg-emerald-50/60' : ''}">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-4 h-4 rounded border flex items-center justify-center shrink-0 ${checked ? 'bg-[#173628] border-[#173628] text-white text-[10px]' : 'border-gray-300 bg-white'}">
                                ${checked ? '✓' : ''}
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-semibold text-gray-900 truncate">${prod.name}</div>
                                <div class="text-[11px] font-mono text-emerald-800 font-bold">${formatRupiah(prod.price)}</div>
                            </div>
                        </div>
                    </button>
                `;
            }).join('');
        }

        function selectCategory(catId) {
            appState.selectedCategoryId = catId;
            appState.selectedProductIds = STORE_PRODUCTS.filter(p => p.categoryId === catId).map(p => p.id);
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
                ? 'px-2 py-0.5 rounded-full text-[11px] font-semibold font-mono tabular-nums bg-red-50 text-red-700 border border-red-200'
                : 'px-2 py-0.5 rounded-full text-[11px] font-semibold font-mono tabular-nums bg-emerald-50 text-emerald-800 border border-emerald-200';

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

        // Draw Canvas WYSIWYG
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

            const isRamadan = tpl.id === 'ramadan';
            const grad = ctx.createLinearGradient(0, 0, 0, H);
            grad.addColorStop(0, isRamadan ? '#0D2E22' : '#F8F9FA');
            grad.addColorStop(1, isRamadan ? '#173628' : '#FFFFFF');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, W, H);

            // Badge
            ctx.textAlign = 'center';
            ctx.font = '700 24px sans-serif';
            const badgeW = ctx.measureText(tpl.badge).width + 64;
            drawRoundRect(ctx, (W - badgeW) / 2, 86, badgeW, 52, 26);
            ctx.fillStyle = isRamadan ? '#D4AF37' : '#EBF3F0';
            ctx.fill();
            ctx.fillStyle = isRamadan ? '#0D2E22' : '#173628';
            ctx.fillText(tpl.badge, W / 2, 121);

            // Judul
            ctx.fillStyle = isRamadan ? '#FFFFFF' : '#173628';
            ctx.font = '800 56px sans-serif';
            const titleHeight = drawWrappedText(ctx, appState.promoTitle, W / 2, 210, W - 160, 68, 2);

            // Deskripsi
            ctx.fillStyle = isRamadan ? '#D1FAE5' : '#6B7280';
            ctx.font = '500 28px sans-serif';
            drawWrappedText(ctx, appState.promoDesc, W / 2, 210 + titleHeight + 14, W - 180, 38, 2);

            // Grid Produk (2x2)
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
                ctx.strokeStyle = isRamadan ? '#D4AF37' : '#E5E7EB';
                ctx.lineWidth = 3;
                ctx.stroke();

                ctx.fillStyle = '#173628';
                ctx.font = '700 28px sans-serif';
                drawWrappedText(ctx, p.name, x + cardW / 2, y + 140, cardW - 32, 34, 2);

                drawRoundRect(ctx, x + 24, y + cardH - 74, cardW - 48, 52, 12);
                ctx.fillStyle = '#173628';
                ctx.fill();
                ctx.fillStyle = isRamadan ? '#FDE68A' : '#FFFFFF';
                ctx.font = '700 30px monospace';
                ctx.fillText(formatRupiah(p.price), x + cardW / 2, y + cardH - 38);
            });

            // Footer
            ctx.fillStyle = isRamadan ? '#092219' : '#173628';
            ctx.fillRect(0, H - 110, W, 110);
            ctx.textAlign = 'left';
            ctx.fillStyle = isRamadan ? '#FDE68A' : '#FFFFFF';
            ctx.font = '800 28px sans-serif';
            ctx.fillText('TOKO KELONTONG MAS FADIL', 64, H - 56);
            ctx.fillStyle = '#C5DBD2';
            ctx.font = '500 20px sans-serif';
            ctx.fillText('Kebutuhan Pokok Terlengkap & Terpercaya', 64, H - 26);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.product-mode-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const mode = btn.getAttribute('data-mode');
                    appState.selectionMode = mode;
                    document.querySelectorAll('.product-mode-btn').forEach(b => {
                        const active = b.getAttribute('data-mode') === mode;
                        b.className = active
                            ? 'product-mode-btn py-2 text-xs font-semibold rounded-lg bg-white text-[#173628] shadow-xs transition-all'
                            : 'product-mode-btn py-2 text-xs font-semibold rounded-lg text-gray-500 hover:text-gray-900 transition-all';
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

            document.getElementById('generate-poster-btn').addEventListener('click', () => {
                const tpl = getActiveTemplate();
                const canvas = document.getElementById('poster-canvas');
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = `poster-${tpl.id}-${Date.now()}.png`;
                link.click();
            });

            document.getElementById('reset-form-btn').addEventListener('click', () => {
                selectCategory(defaultCatId);
                selectTemplate('ramadan');
            });

            renderTemplateGrid();
            renderCategoryList();
            renderProductList();
            updateValidationUI();
            renderPosterCanvas();
        });
    </script>
</x-app-layout>
