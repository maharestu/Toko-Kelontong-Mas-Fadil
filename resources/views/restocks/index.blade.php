<x-app-layout>
    @php
        // Siapkan data riwayat restock dari database untuk Alpine.js
        $mergedList = collect();

        if ($restocks && $restocks->count() > 0) {
            foreach ($restocks as $r) {
                $detailsSummary = $r->restockDetails->map(fn($d) => $d->product?->name ?? 'Produk')->take(3)->implode(', ');
                if ($r->restockDetails->count() > 3) {
                    $detailsSummary .= ' +' . ($r->restockDetails->count() - 3) . ' produk lainnya';
                }
                $datePart = \Carbon\Carbon::parse($r->restock_date)->translatedFormat('d M Y');
                $timePart = $r->created_at ? $r->created_at->format('H:i') . ' WIB' : '';

                $mergedList->push([
                    'id' => $r->id,
                    'code' => '#RST-' . date('Y', strtotime($r->restock_date)) . '-' . str_pad($r->id, 3, '0', STR_PAD_LEFT),
                    'time' => trim($datePart . ($timePart !== '' ? ', ' . $timePart : '')),
                    'raw_date' => \Carbon\Carbon::parse($r->restock_date)->format('Y-m-d'),
                    'total_cost' => (float) $r->total_cost,
                    'summary' => !empty($detailsSummary) ? $detailsSummary : ($r->notes ?? 'Pencatatan barang masuk'),
                    'author' => $r->user?->name ?? 'Mas Adil',
                    'notes' => $r->notes ?? '',
                    'url' => route('restocks.show', $r->id),
                ]);
            }
        }
    @endphp

    <div x-data="{
<<<<<<< HEAD
        showCreateModal: false,
=======
>>>>>>> afb036ce7eeef87ec3e9c3b5649b20b4f3efaf65
        searchQuery: '',
        activeFilter: 'all',
        customDate: '',
        allRestocks: {{ Js::from($mergedList) }},
<<<<<<< HEAD
        items: [
            { product_id: '{{ $products->first()?->id ?? 1 }}', quantity: 10, purchase_price: 15000, selling_price: 18000 }
        ],
        addItem() {
            this.items.push({ product_id: '{{ $products->first()?->id ?? 1 }}', quantity: 1, purchase_price: 0, selling_price: 0 });
        },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
=======
        matchesItem(item) {
            const q = this.searchQuery.toLowerCase().trim();
            if (q) {
                const inCode = item.code && item.code.toLowerCase().includes(q);
                const inNotes = item.notes && item.notes.toLowerCase().includes(q);
                const inAuthor = item.author && item.author.toLowerCase().includes(q);
                const inSummary = item.summary && item.summary.toLowerCase().includes(q);
                if (!inCode && !inNotes && !inAuthor && !inSummary) {
                    return false;
                }
>>>>>>> afb036ce7eeef87ec3e9c3b5649b20b4f3efaf65
            }

            if (this.activeFilter === 'all') {
                return true;
            }

            if (!item.raw_date) return false;

            const today = new Date();
            const target = new Date(item.raw_date + 'T00:00:00');

            if (this.activeFilter === 'month') {
                return target.getFullYear() === today.getFullYear() && target.getMonth() === today.getMonth();
            }

            if (this.activeFilter === 'week') {
                const sevenDaysAgo = new Date();
                sevenDaysAgo.setDate(today.getDate() - 7);
                sevenDaysAgo.setHours(0, 0, 0, 0);
                return target >= sevenDaysAgo && target <= new Date(today.getTime() + 86400000);
            }

            if (this.activeFilter === 'custom' && this.customDate) {
                return item.raw_date === this.customDate;
            }

            return true;
        },
<<<<<<< HEAD
        calculateTotal() {
            return this.items.reduce((sum, item) => sum + ((parseFloat(item.quantity) || 0) * (parseFloat(item.purchase_price) || 0)), 0);
        },
        matchesItem(item) {
            const q = this.searchQuery.toLowerCase().trim();
            if (q) {
                const inCode = item.code && item.code.toLowerCase().includes(q);
                const inNotes = item.notes && item.notes.toLowerCase().includes(q);
                const inAuthor = item.author && item.author.toLowerCase().includes(q);
                const inSummary = item.summary && item.summary.toLowerCase().includes(q);
                if (!inCode && !inNotes && !inAuthor && !inSummary) {
                    return false;
                }
            }

            if (this.activeFilter === 'all') {
                return true;
            }

            if (!item.raw_date) return false;

            const today = new Date();
            const target = new Date(item.raw_date + 'T00:00:00');

            if (this.activeFilter === 'month') {
                return target.getFullYear() === today.getFullYear() && target.getMonth() === today.getMonth();
            }

            if (this.activeFilter === 'week') {
                const sevenDaysAgo = new Date();
                sevenDaysAgo.setDate(today.getDate() - 7);
                sevenDaysAgo.setHours(0, 0, 0, 0);
                return target >= sevenDaysAgo && target <= new Date(today.getTime() + 86400000);
            }

            if (this.activeFilter === 'custom' && this.customDate) {
                return item.raw_date === this.customDate;
            }

            return true;
        },
=======
>>>>>>> afb036ce7eeef87ec3e9c3b5649b20b4f3efaf65
        get filteredRestocks() {
            return this.allRestocks.filter(item => this.matchesItem(item));
        },
        formatDate(d) {
            if (!d) return '';
            const parts = d.split('-');
            if (parts.length === 3) {
                return `${parts[2]}/${parts[1]}`;
            }
            return d;
        }
    }" class="p-4 sm:p-5 space-y-4">

        <!-- 1. Tombol Kembali / Header Nav -->
        <div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 hover:text-black mb-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                <span>Menu Lainnya</span>
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight leading-tight">Barang Masuk</h1>
            <p class="text-xs text-gray-500 mt-0.5">Riwayat pencatatan barang datang &amp; penambahan stok...</p>
        </div>

        <!-- 2. Tombol Utama: + Catat Restock -->
<<<<<<< HEAD
        <button type="button" @click="showCreateModal = true"
=======
        <a href="{{ route('restocks.create') }}"
>>>>>>> afb036ce7eeef87ec3e9c3b5649b20b4f3efaf65
            class="w-full bg-[#0a2316] hover:bg-[#123624] text-white py-3 px-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.99] cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 8v8" />
                <path d="M8 12h8" />
            </svg>
            <span>+ Catat Restock</span>
        </a>

        <!-- 3. Highlight Card: Bulan Ini (Total Harga) -->
        <div class="bg-[#123b28] text-white rounded-2xl p-4 shadow-[0_2px_10px_rgba(18,59,40,0.15)] space-y-3.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5 text-emerald-100 text-[11px] font-bold tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-300" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect width="18" height="14" x="3" y="5" rx="2" />
                        <path d="M7 15h4M15 15h2M7 11h2M13 11h4" />
                    </svg>
                    <span>BULAN INI ({{ strtoupper(\Carbon\Carbon::now()->translatedFormat('M Y')) }})</span>
                </div>
                <span
                    class="bg-white/10 text-white/95 text-[10px] font-medium px-2.5 py-0.5 rounded-full border border-white/10">
                    {{ $monthlyCount }} Restock
                </span>
            </div>

            <div class="flex items-baseline justify-between pt-0.5">
                <span class="text-xs text-white/70 font-normal">Total Harga</span>
                <span class="text-xl sm:text-2xl font-black tracking-tight text-white font-mono">
                    Rp {{ number_format($monthlyTotal, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- 4. Search & Filter Bar -->
        <div class="space-y-2.5">
            <!-- Search Input -->
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="text" x-model="searchQuery" placeholder="Cari no. restock, catatan, atau admin..."
                    class="w-full bg-white border border-gray-200/90 rounded-xl pl-10 pr-9 py-2.5 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#123b28] focus:border-[#123b28] shadow-[0_1px_2px_rgba(0,0,0,0.02)] transition">
                <button type="button" x-show="searchQuery" @click="searchQuery = ''"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-0.5 cursor-pointer"
                    title="Hapus pencarian">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Filter Chips (Horizontal Scroll) -->
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5">
                <button type="button" @click="activeFilter = 'all'; customDate = '';"
                    :class="activeFilter === 'all' ? 'bg-[#0a2316] text-white font-bold shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 font-medium'"
                    class="text-[11px] px-3.5 py-1.5 rounded-lg flex-shrink-0 transition cursor-pointer">
                    Semua Tanggal
                </button>
                <button type="button" @click="activeFilter = 'month'; customDate = '';"
                    :class="activeFilter === 'month' ? 'bg-[#0a2316] text-white font-bold shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 font-medium'"
                    class="text-[11px] px-3.5 py-1.5 rounded-lg flex-shrink-0 transition cursor-pointer">
                    Bulan Ini ({{ \Carbon\Carbon::now()->translatedFormat('M') }})
                </button>
                <button type="button" @click="activeFilter = 'week'; customDate = '';"
                    :class="activeFilter === 'week' ? 'bg-[#0a2316] text-white font-bold shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 font-medium'"
                    class="text-[11px] px-3.5 py-1.5 rounded-lg flex-shrink-0 transition cursor-pointer">
                    7 Hari Terakhir
                </button>

                <!-- Tombol Pilih Tanggal (Kalender) -->
                <div class="relative flex-shrink-0">
                    <button type="button"
                        @click="$refs.datePicker.showPicker ? $refs.datePicker.showPicker() : $refs.datePicker.click()"
                        :class="activeFilter === 'custom' ? 'bg-[#0a2316] text-white font-bold shadow-xs' : 'bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium'"
                        class="p-1.5 px-2 rounded-lg transition cursor-pointer flex items-center gap-1 text-[11px]"
                        title="Pilih Tanggal Tertentu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                            <line x1="16" x2="16" y1="2" y2="6" />
                            <line x1="8" x2="8" y1="2" y2="6" />
                            <line x1="3" x2="21" y1="10" y2="10" />
                        </svg>
                        <span x-show="activeFilter === 'custom' && customDate" x-text="formatDate(customDate)" class="text-[10px]"></span>
                    </button>
                    <input type="date" x-ref="datePicker" x-model="customDate"
                        @change="if (customDate) activeFilter = 'custom';"
                        class="absolute inset-0 opacity-0 w-0 h-0 pointer-events-none">
                </div>
            </div>
        </div>

        <!-- 5. List Riwayat Transaksi Barang Masuk -->
        <div class="space-y-3 pt-1">
            <template x-for="item in filteredRestocks" :key="item.id">
                <div
                    class="bg-white rounded-2xl p-4 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] space-y-2 hover:border-gray-200 transition">
                    <!-- Header Kode & Waktu -->
                    <div class="flex items-center justify-between">
                        <span
                            class="font-extrabold text-xs text-gray-900 tracking-tight font-mono"
                            x-text="item.code"></span>
                        <span class="text-[11px] text-gray-400 font-normal" x-text="item.time"></span>
                    </div>

                    <!-- Nominal Total Harga -->
                    <div>
                        <span class="text-[10px] text-gray-400 block font-normal">Total Harga</span>
                        <span class="text-xl font-black text-gray-900 tracking-tight font-mono"
                            x-text="'Rp ' + Number(item.total_cost).toLocaleString('id-ID')">
                        </span>
                    </div>

                    <!-- Ringkasan Produk -->
                    <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed pt-0.5" x-text="item.summary"></p>

                    <!-- Footer: Admin Pembuat & Link Detail -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-50 text-[11px]">
                        <span class="text-gray-400" x-text="'Oleh: ' + item.author"></span>
                        <a :href="item.url"
                            class="font-bold text-[#123b28] hover:text-black flex items-center gap-0.5 transition cursor-pointer">
                            <span>Lihat Detail</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </template>
<<<<<<< HEAD

            <!-- Empty State: Belum ada data di database -->
            <div x-show="allRestocks.length === 0" class="bg-white rounded-2xl p-8 border border-gray-100 text-center space-y-1">
                <p class="text-sm font-bold text-gray-700">Belum ada riwayat restock</p>
                <p class="text-xs text-gray-400">Klik tombol "+ Catat Restock" di atas untuk menambahkan barang masuk.</p>
            </div>

            <!-- Empty State: Hasil Pencarian / Filter Tidak Ditemukan -->
            <div x-show="allRestocks.length > 0 && filteredRestocks.length === 0"
                class="bg-white rounded-2xl p-8 border border-gray-100 text-center space-y-2">
                <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke-width="2" />
                        <path d="m21 21-4.3-4.3" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-800">Tidak ada riwayat restock yang cocok</p>
                <p class="text-xs text-gray-400">Coba ubah kata kunci pencarian atau ganti filter tanggal.</p>
                <button type="button" @click="searchQuery = ''; activeFilter = 'all'; customDate = '';"
                    class="mt-2 inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                    <span>Reset Filter</span>
                </button>
            </div>
        </div>

        <!-- 6. MODAL: Catat Restock Baru -->
        <div x-show="showCreateModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div @click.away="showCreateModal = false"
                class="bg-white w-full max-w-[460px] rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col animate-in fade-in zoom-in-95 duration-200">
                <!-- Header Modal -->
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="font-extrabold text-sm text-gray-900">Catat Barang Masuk (Restock)</h3>
                        <p class="text-[11px] text-gray-400">Tambah stok &amp; perbarui harga beli modal</p>
                    </div>
                    <button type="button" @click="showCreateModal = false"
                        class="text-gray-400 hover:text-gray-600 text-lg font-bold p-1">&times;</button>
                </div>

                <!-- Form -->
                <form action="{{ route('restocks.store') }}" method="POST"
                    class="p-5 overflow-y-auto space-y-4 flex-1 text-xs">
                    @csrf
                    <!-- Tanggal & Catatan -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Tanggal Masuk</label>
                            <input type="date" name="restock_date" value="{{ date('Y-m-d') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#123b28]">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">No. Faktur / Catatan</label>
                            <input type="text" name="notes" placeholder="Contoh: Faktur #AG-882"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#123b28]">
                        </div>
                    </div>

                    <!-- Daftar Item Barang -->
                    <div class="space-y-3 pt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900">Daftar Item Masuk</span>
                            <button type="button" @click="addItem()"
                                class="text-[11px] font-bold text-[#123b28] hover:underline flex items-center gap-1">
                                + Tambah Baris
                            </button>
                        </div>

                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-3 bg-gray-50/80 rounded-2xl border border-gray-100 space-y-2 relative">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-gray-400"
                                        x-text="'Item #' + (index + 1)"></span>
                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                        class="text-rose-500 hover:text-rose-700 text-[10px] font-bold">Hapus</button>
                                </div>

                                <div>
                                    <label class="block text-[10px] text-gray-500 mb-0.5">Pilih Produk</label>
                                    <select :name="'items[' + index + '][product_id]'" x-model="item.product_id"
                                        required
                                        class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs text-gray-800">
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->name }} (Stok saat ini:
                                                {{ $prod->stock }} {{ $prod->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-[10px] text-gray-500 mb-0.5">Jumlah Masuk</label>
                                        <input type="number" :name="'items[' + index + '][quantity]'"
                                            x-model.number="item.quantity" min="1" required
                                            class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs text-gray-800">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-500 mb-0.5">Harga Beli Satuan
                                            (Rp)</label>
                                        <input type="number" :name="'items[' + index + '][purchase_price]'"
                                            x-model.number="item.purchase_price" min="0" required
                                            class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs text-gray-800">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-500 mb-0.5">Harga Jual Baru
                                            (Rp)</label>
                                        <input type="number" :name="'items[' + index + '][selling_price]'"
                                            x-model.number="item.selling_price" min="0"
                                            class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs text-gray-800">
                                    </div>
                                </div>

                                <div class="text-right text-[10px] text-gray-500">
                                    Subtotal: <span class="font-bold text-gray-800 font-mono"
                                        x-text="'Rp ' + ((item.quantity * item.purchase_price) || 0).toLocaleString('id-ID')"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Total Belanja Kulakan -->
                    <div
                        class="p-3 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center justify-between">
                        <span class="text-xs font-semibold text-emerald-900">Total Harga</span>
                        <span class="text-sm font-black text-emerald-900 font-mono"
                            x-text="'Rp ' + calculateTotal().toLocaleString('id-ID')"></span>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-[#0a2316] hover:bg-[#123624] text-white py-3 rounded-xl font-bold text-xs shadow-md transition active:scale-[0.99]">
                            Simpan Restock &amp; Tambah Stok
                        </button>
                    </div>
                </form>
=======

            <!-- Empty State: Belum ada data di database -->
            <div x-show="allRestocks.length === 0" class="bg-white rounded-2xl p-8 border border-gray-100 text-center space-y-1">
                <p class="text-sm font-bold text-gray-700">Belum ada riwayat restock</p>
                <p class="text-xs text-gray-400">Klik tombol "+ Catat Restock" di atas untuk menambahkan barang masuk.</p>
>>>>>>> afb036ce7eeef87ec3e9c3b5649b20b4f3efaf65
            </div>

<<<<<<< HEAD
=======
            <!-- Empty State: Hasil Pencarian / Filter Tidak Ditemukan -->
            <div x-show="allRestocks.length > 0 && filteredRestocks.length === 0"
                class="bg-white rounded-2xl p-8 border border-gray-100 text-center space-y-2">
                <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke-width="2" />
                        <path d="m21 21-4.3-4.3" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-800">Tidak ada riwayat restock yang cocok</p>
                <p class="text-xs text-gray-400">Coba ubah kata kunci pencarian atau ganti filter tanggal.</p>
                <button type="button" @click="searchQuery = ''; activeFilter = 'all'; customDate = '';"
                    class="mt-2 inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                    <span>Reset Filter</span>
                </button>
            </div>
        </div>

>>>>>>> afb036ce7eeef87ec3e9c3b5649b20b4f3efaf65
    </div>
</x-app-layout>