<x-app-layout :title="'Tambah Restock - '.config('app.name')" active="lainnya">
    @php
        // Serialisasikan data produk katalog ke JSON untuk Alpine.js
        $catalogProducts = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'unit' => $p->unit ?? 'pcs',
            'stock' => $p->stock,
            'purchase_price' => (float) $p->current_purchase_price,
            'selling_price' => (float) $p->current_selling_price,
        ]);

        // Default 2 produk pertama jika ada untuk inisialisasi awal seperti di screenshot
        $initialItems = [];
        if ($catalogProducts->count() >= 2) {
            $p1 = $catalogProducts[0];
            $p2 = $catalogProducts[1];
            $initialItems = [
                [
                    'product_id' => $p1['id'],
                    'name' => $p1['name'],
                    'unit' => $p1['unit'],
                    'quantity' => 10,
                    'purchase_price' => $p1['purchase_price'],
                    'selling_price' => $p1['selling_price'],
                ],
                [
                    'product_id' => $p2['id'],
                    'name' => $p2['name'],
                    'unit' => $p2['unit'],
                    'quantity' => 24,
                    'purchase_price' => $p2['purchase_price'],
                    'selling_price' => $p2['selling_price'],
                ],
            ];
        } elseif ($catalogProducts->count() === 1) {
            $p1 = $catalogProducts[0];
            $initialItems = [
                [
                    'product_id' => $p1['id'],
                    'name' => $p1['name'],
                    'unit' => $p1['unit'],
                    'quantity' => 10,
                    'purchase_price' => $p1['purchase_price'],
                    'selling_price' => $p1['selling_price'],
                ],
            ];
        }
    @endphp

    <div x-data="{
        catalog: {{ Js::from($catalogProducts) }},
        searchProductQuery: '',
        selectedItems: {{ Js::from($initialItems) }},
        
        get filteredCatalog() {
            const q = this.searchProductQuery.toLowerCase().trim();
            if (!q) {
                // Tampilkan produk yang belum dipilih atau maksimal 5 produk pertama
                return this.catalog.slice(0, 5);
            }
            return this.catalog.filter(p => p.name.toLowerCase().includes(q));
        },

        addItem(product) {
            const existingIndex = this.selectedItems.findIndex(i => i.product_id === product.id);
            if (existingIndex > -1) {
                this.selectedItems[existingIndex].quantity++;
            } else {
                this.selectedItems.push({
                    product_id: product.id,
                    name: product.name,
                    unit: product.unit,
                    quantity: 1,
                    purchase_price: product.purchase_price,
                    selling_price: product.selling_price,
                });
            }
        },

        removeItem(index) {
            this.selectedItems.splice(index, 1);
        },

        incrementQty(index) {
            this.selectedItems[index].quantity++;
        },

        decrementQty(index) {
            if (this.selectedItems[index].quantity > 1) {
                this.selectedItems[index].quantity--;
            }
        },

        calculateTotal() {
            return this.selectedItems.reduce((sum, item) => {
                return sum + ((parseFloat(item.quantity) || 0) * (parseFloat(item.purchase_price) || 0));
            }, 0);
        }
    }" class="p-4 sm:p-5 space-y-4 pb-28">

        <!-- Top Header Navigation -->
        <div class="flex items-center justify-between pb-1">
            <a href="{{ route('restocks.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 hover:text-black transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                <span>Barang Masuk</span>
            </a>
            <h1 class="text-base font-bold text-gray-900 tracking-tight">Tambah Restock</h1>
            <div class="w-12"></div> <!-- Spacer penyeimbang -->
        </div>

        <form action="{{ route('restocks.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- 1. Card: Informasi Restock -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] space-y-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-800 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-700" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                            <line x1="16" x2="16" y1="2" y2="6" />
                            <line x1="8" x2="8" y1="2" y2="6" />
                            <line x1="3" x2="21" y1="10" y2="10" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-900">Informasi Restock</h2>
                </div>

                <!-- Input Tanggal Restock -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-600">Tanggal Restock</label>
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                <line x1="16" x2="16" y1="2" y2="6" />
                                <line x1="8" x2="8" y1="2" y2="6" />
                                <line x1="3" x2="21" y1="10" y2="10" />
                            </svg>
                        </div>
                        <input type="date" name="restock_date" value="{{ date('Y-m-d') }}" required
                            class="w-full bg-[#f9fafb] border border-gray-200/90 rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#123b28] focus:border-[#123b28] transition font-medium">
                    </div>
                </div>

                <!-- Input Catatan Kulakan (opsional) -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-600">Catatan Kulakan (opsional)</label>
                    <input type="text" name="notes" placeholder="Contoh: Kulakan mingguan pasar induk..."
                        class="w-full bg-[#f9fafb] border border-gray-200/90 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#123b28] focus:border-[#123b28] transition font-medium">
                </div>
            </div>

            <!-- 2. Section: Pilih Produk Kulakan -->
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-gray-700">Pilih Produk Kulakan</label>

                <!-- Input Search Katalog -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" stroke-linecap="round" />
                        </svg>
                    </div>
                    <input type="text" x-model="searchProductQuery"
                        placeholder="Cari &amp; pilih produk dari katalog..."
                        class="w-full bg-white border border-gray-200/90 rounded-xl pl-9 pr-9 py-2.5 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#123b28] focus:border-[#123b28] shadow-[0_1px_2px_rgba(0,0,0,0.02)] transition">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m7 15 5 5 5-5" />
                            <path d="m7 9 5-5 5 5" />
                        </svg>
                    </div>
                </div>

                <!-- Daftar Pilihan Produk Cepat (Dropdown List Card) -->
                <div class="bg-white rounded-2xl border border-gray-100 divide-y divide-gray-50 shadow-[0_1px_4px_rgba(0,0,0,0.02)] overflow-hidden">
                    <template x-for="product in filteredCatalog" :key="product.id">
                        <div class="p-3 flex items-center justify-between hover:bg-gray-50/80 transition gap-2">
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-bold text-gray-900 truncate" x-text="product.name"></h4>
                                <p class="text-[11px] text-gray-400 mt-0.5">
                                    <span x-text="'Stok saat ini: ' + product.stock + ' ' + product.unit"></span>
                                    <span>&bull;</span>
                                    <span x-text="'Satuan: ' + product.unit"></span>
                                </p>
                            </div>
                            <button type="button" @click="addItem(product)"
                                class="w-8 h-8 rounded-full border border-emerald-600/30 text-emerald-700 hover:bg-emerald-50 flex items-center justify-center transition shrink-0 cursor-pointer"
                                title="Tambah ke daftar restock">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-800" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 8v8" />
                                    <path d="M8 12h8" />
                                </svg>
                            </button>
                        </div>
                    </template>
                    <div x-show="filteredCatalog.length === 0" class="p-4 text-center text-xs text-gray-400">
                        Tidak ada produk ditemukan di katalog
                    </div>
                </div>
            </div>

            <!-- 3. Section: Daftar Produk Masuk -->
            <div class="space-y-3 pt-1">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-900">Daftar Produk Masuk</h2>
                    <span class="text-xs font-medium text-gray-500" x-text="selectedItems.length + ' Produk'"></span>
                </div>

                <!-- Template Kartu Produk Masuk -->
                <template x-for="(item, index) in selectedItems" :key="index">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] space-y-3">
                        <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">

                        <!-- Header Kartu: Nama Produk, Satuan Badge, Tombol Hapus -->
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                <h3 class="text-sm font-bold text-gray-900 leading-snug" x-text="item.name"></h3>
                                <span class="bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-0.5 rounded-md"
                                    x-text="item.unit"></span>
                            </div>
                            <button type="button" @click="removeItem(index)"
                                class="text-gray-400 hover:text-rose-600 transition p-1 shrink-0 cursor-pointer"
                                title="Hapus produk ini">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                </svg>
                            </button>
                        </div>

                        <!-- Stepper: Jumlah Masuk -->
                        <div class="bg-[#f9fafb] rounded-xl p-2.5 px-3 flex items-center justify-between border border-gray-100">
                            <span class="text-xs text-gray-600 font-medium">Jumlah Masuk</span>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="decrementQty(index)"
                                    class="w-7 h-7 rounded-lg bg-white border border-gray-200 text-gray-700 flex items-center justify-center font-bold text-sm shadow-xs hover:bg-gray-50 active:scale-95 transition cursor-pointer">
                                    -
                                </button>
                                <div class="flex items-center gap-1">
                                    <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity"
                                        min="1" required
                                        class="w-10 text-center font-bold text-sm text-gray-900 bg-transparent focus:outline-none p-0">
                                    <span class="text-xs text-gray-500 font-medium" x-text="item.unit"></span>
                                </div>
                                <button type="button" @click="incrementQty(index)"
                                    class="w-7 h-7 rounded-lg bg-white border border-gray-200 text-gray-700 flex items-center justify-center font-bold text-sm shadow-xs hover:bg-gray-50 active:scale-95 transition cursor-pointer">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Dua Kolom: Harga Beli & Harga Jual Baru -->
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="space-y-1">
                                <label class="block text-[11px] font-medium text-gray-500">Harga Beli (Rp)</label>
                                <div class="relative">
                                    <input type="number" :name="'items[' + index + '][purchase_price]'"
                                        x-model.number="item.purchase_price" min="0" required
                                        class="w-full bg-[#f9fafb] border border-gray-200/90 rounded-xl px-3 py-2 text-xs text-gray-900 font-semibold focus:outline-none focus:ring-1 focus:ring-[#123b28] focus:border-[#123b28] transition">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[11px] font-medium text-gray-500">Harga Jual Baru (Rp)</label>
                                <div class="relative">
                                    <input type="number" :name="'items[' + index + '][selling_price]'"
                                        x-model.number="item.selling_price" min="0"
                                        class="w-full bg-[#f9fafb] border border-gray-200/90 rounded-xl px-3 py-2 text-xs text-gray-900 font-semibold focus:outline-none focus:ring-1 focus:ring-[#123b28] focus:border-[#123b28] transition">
                                </div>
                            </div>
                        </div>

                        <!-- Baris Subtotal -->
                        <div class="flex items-center justify-between pt-1 border-t border-gray-50 text-xs">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-bold text-gray-900 font-mono"
                                x-text="'Rp ' + ((item.quantity * item.purchase_price) || 0).toLocaleString('id-ID')"></span>
                        </div>
                    </div>
                </template>

                <!-- Empty State jika belum ada item dipilih -->
                <div x-show="selectedItems.length === 0"
                    class="bg-white rounded-2xl p-8 border border-dashed border-gray-200 text-center space-y-1">
                    <p class="text-xs font-semibold text-gray-700">Belum ada produk yang dimasukkan</p>
                    <p class="text-[11px] text-gray-400">Pilih produk pada daftar di atas lalu klik tombol (+) untuk menambahkan.</p>
                </div>
            </div>

            <!-- Ringkasan Total & Tombol Simpan -->
            <div class="space-y-3 pt-2">
                <div class="bg-[#123b28] text-white rounded-2xl p-4 flex items-center justify-between shadow-[0_2px_10px_rgba(18,59,40,0.15)]">
                    <div>
                        <span class="text-[10px] font-bold text-emerald-300 uppercase tracking-wider block">
                            Total Biaya Kulakan
                        </span>
                        <span class="text-xl font-black text-white font-mono tracking-tight"
                            x-text="'Rp ' + calculateTotal().toLocaleString('id-ID')">
                        </span>
                    </div>
                    <span class="text-xs text-white/80 font-medium"
                        x-text="selectedItems.length + ' Produk'"></span>
                </div>

                <button type="submit" :disabled="selectedItems.length === 0"
                    :class="selectedItems.length === 0 ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-[#0a2316] hover:bg-[#123624] cursor-pointer active:scale-[0.99]'"
                    class="w-full text-white py-3.5 px-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all">
                    <span>Simpan Restock &amp; Tambah Stok</span>
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
