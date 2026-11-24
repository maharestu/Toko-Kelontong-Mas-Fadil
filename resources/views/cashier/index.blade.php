<x-app-layout>
    <div x-data="{
        search: '',
        selectedCategory: 'all',
        cart: {},
        showCheckoutDrawer: false,
        showReceiptModal: {{ session('sale_receipt') ? 'true' : 'false' }},
        orderType: 'direct',
        customerType: 'umum',
        customerId: '',
        customerName: '',
        paymentType: 'langsung',
        paymentMethod: 'cash',
        paidAmount: 0,
        quickCashNominals: [20000, 50000, 100000, 150000, 200000],

        products: [
            @foreach($products as $p)
            {
                id: {{ $p->id }},
                name: {{ json_encode($p->name) }},
                category_id: {{ $p->category_id ?? 0 }},
                category_name: {{ json_encode($p->category->name ?? 'Umum') }},
                unit: {{ json_encode($p->unit ?? 'pcs') }},
                stock: {{ (int) $p->stock }},
                price: {{ (float) $p->current_selling_price }},
                image_url: {{ json_encode($p->image_url) }}
            },
            @endforeach
        ],

        get filteredProducts() {
            return this.products.filter(p => {
                const matchCategory = (this.selectedCategory === 'all') || (p.category_id == this.selectedCategory);
                const query = this.search.toLowerCase().trim();
                const matchSearch = !query || p.name.toLowerCase().includes(query) || p.category_name.toLowerCase().includes(query);
                return matchCategory && matchSearch;
            });
        },

        get cartItems() {
            return Object.keys(this.cart)
                .map(id => {
                    const product = this.products.find(p => p.id == id);
                    const qty = this.cart[id] || 0;
                    if (!product || qty <= 0) return null;
                    return {
                        ...product,
                        qty: qty,
                        subtotal: qty * product.price
                    };
                })
                .filter(item => item !== null);
        },

        get totalItemCount() {
            return Object.values(this.cart).reduce((sum, qty) => sum + (parseInt(qty) || 0), 0);
        },

        get totalUniqueCount() {
            return Object.values(this.cart).filter(qty => qty > 0).length;
        },

        get totalCartPrice() {
            return this.cartItems.reduce((sum, item) => sum + item.subtotal, 0);
        },

        get calculatedChange() {
            if (this.paymentType === 'kredit') return 0;
            const paid = parseFloat(this.paidAmount) || 0;
            return Math.max(0, paid - this.totalCartPrice);
        },

        addToCart(product) {
            if (product.stock <= 0) return;
            const currentQty = this.cart[product.id] || 0;
            if (currentQty < product.stock) {
                this.cart[product.id] = currentQty + 1;
                this.setDefaultPaidAmount();
            }
        },

        decreaseCart(product) {
            const currentQty = this.cart[product.id] || 0;
            if (currentQty > 1) {
                this.cart[product.id] = currentQty - 1;
            } else if (currentQty === 1) {
                delete this.cart[product.id];
            }
            this.setDefaultPaidAmount();
        },

        removeFromCart(productId) {
            delete this.cart[productId];
            this.setDefaultPaidAmount();
        },

        setQuickCash(amount) {
            this.paidAmount = amount;
        },

        setDefaultPaidAmount() {
            this.paidAmount = this.totalCartPrice;
        },

        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    }" class="min-h-full flex flex-col justify-between">

        <!-- Konten Utama Halaman Kasir -->
        <div class="p-4 sm:p-5 space-y-3.5">

            <!-- 1. Header Judul & Status Kasir -->
            <div class="flex items-center justify-between pt-0.5">
                <div>
                    <h1 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kasir</h1>
                    <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight leading-tight">Transaksi Toko</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                        SIAP LAYANI
                    </span>
                </div>
            </div>

            <!-- Flash Message Sukses -->
            @if(session('success') && !session('sale_receipt'))
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <span class="font-bold cursor-pointer text-emerald-600 hover:text-emerald-900" @click="$el.parentElement.remove()">&times;</span>
                </div>
            @endif

            <!-- 2. Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Cari nama produk..." 
                    class="w-full bg-white border border-gray-200/90 rounded-2xl pl-10 pr-9 py-2.5 sm:py-3 text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#173628]/10 focus:border-[#173628] shadow-[0_1px_3px_rgba(0,0,0,0.03)] transition-all font-medium"
                >
                <button 
                    type="button" 
                    x-show="search.length > 0" 
                    @click="search = ''" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <!-- 3. Kategori Chips (Horizontal Scroll) -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5 -mx-1 px-1">
                <button 
                    type="button" 
                    @click="selectedCategory = 'all'" 
                    :class="selectedCategory === 'all' 
                        ? 'bg-[#173628] text-white shadow-xs font-semibold' 
                        : 'bg-white border border-gray-200/70 text-gray-600 hover:bg-gray-50 font-medium'"
                    class="text-xs px-4 py-2 rounded-xl flex-shrink-0 transition-all cursor-pointer"
                >
                    Semua
                </button>
                @foreach($categories as $category)
                    <button 
                        type="button" 
                        @click="selectedCategory = '{{ $category->id }}'" 
                        :class="selectedCategory == '{{ $category->id }}' 
                            ? 'bg-[#173628] text-white shadow-xs font-semibold' 
                            : 'bg-white border border-gray-200/70 text-gray-600 hover:bg-gray-50 font-medium'"
                        class="text-xs px-3.5 py-2 rounded-xl flex-shrink-0 transition-all cursor-pointer whitespace-nowrap"
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- 4. Subheader Info Katalog -->
            <div class="flex items-center justify-between text-xs px-0.5 pt-1">
                <div class="text-gray-400 font-bold uppercase tracking-wider text-[10px] sm:text-[11px]">
                    KATALOG KASIR &bull; <span class="text-gray-600 font-extrabold" x-text="filteredProducts.length"></span> PRODUK AKTIF
                </div>
                <div class="flex items-center gap-1 text-[11px] font-semibold text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-500 fill-amber-500" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                    <span>Tap Cepat</span>
                </div>
            </div>

            <!-- 5. Grid Katalog Produk (2 Kolom) -->
            <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="bg-white rounded-2xl p-2.5 sm:p-3 border border-gray-100 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.04)] flex flex-col justify-between hover:border-gray-200 hover:shadow-md transition-all relative group">
                        
                        <!-- Thumbnail Produk & Badge Overlay -->
                        <div class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-gray-50 border border-gray-100/80 mb-2 flex items-center justify-center">
                            
                            <!-- Foto Produk -->
                            <template x-if="product.image_url">
                                <img :src="product.image_url" :alt="product.name" class="w-full h-full object-cover">
                            </template>

                            <!-- Placeholder Foto Elegan jika belum ada foto -->
                            <template x-if="!product.image_url">
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100/80 p-2 text-center select-none">
                                    <div class="w-9 h-9 rounded-xl bg-white border border-gray-200/60 shadow-xs flex items-center justify-center text-gray-500 mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-[1.6]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21.12 6.4-6-3.4a2 2 0 0 0-2 0l-6 3.4a2 2 0 0 0-1 1.7v6.8a2 2 0 0 0 1 1.7l6 3.4a2 2 0 0 0 2 0l6-3.4a2 2 0 0 0 1-1.7V8.1a2 2 0 0 0-1-1.7z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.3 7 8.7 5 8.7-5"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 22V12"/>
                                        </svg>
                                    </div>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider truncate max-w-full" x-text="product.category_name"></span>
                                </div>
                            </template>

                            <!-- Badge Kiri Atas: 'X di keranjang' -->
                            <div 
                                x-show="cart[product.id] > 0" 
                                class="absolute top-1.5 left-1.5 bg-[#173628]/95 backdrop-blur-xs text-white text-[9px] sm:text-[10px] font-bold px-2 py-0.5 rounded-lg shadow-sm flex items-center gap-1 z-10 transition-all"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="8" cy="21" r="1"/>
                                    <circle cx="19" cy="21" r="1"/>
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                </svg>
                                <span x-text="cart[product.id] + ' di keranjang'"></span>
                            </div>

                            <!-- Badge Kiri Atas: 'Stok Menipis' (jika stok <= 10 dan tidak di keranjang) -->
                            <div 
                                x-show="!cart[product.id] && product.stock <= 10 && product.stock > 0" 
                                class="absolute top-1.5 left-1.5 bg-amber-600/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-md shadow-xs flex items-center gap-1 z-10"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                    <line x1="12" y1="9" x2="12" y2="13"/>
                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                                <span>Stok Menipis</span>
                            </div>

                            <!-- Badge Kanan Bawah: 'Sisa X btl/kg/pcs' -->
                            <div 
                                x-show="product.stock > 0" 
                                class="absolute bottom-1.5 right-1.5 bg-white/95 backdrop-blur-xs text-gray-800 text-[9px] font-bold px-1.5 py-0.5 rounded-md shadow-xs border border-gray-100 z-10"
                            >
                                <span x-text="'Sisa ' + product.stock + ' ' + (product.unit || 'pcs')"></span>
                            </div>

                            <!-- Overlay jika Stok Habis -->
                            <div 
                                x-show="product.stock <= 0" 
                                class="absolute inset-0 bg-gray-900/50 backdrop-blur-[1px] flex items-center justify-center z-20"
                            >
                                <span class="bg-gray-900/90 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md tracking-wider uppercase shadow-xs">
                                    HABIS
                                </span>
                            </div>
                        </div>

                        <!-- Info Produk -->
                        <div class="space-y-1">
                            <!-- Kategori & Satuan -->
                            <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400 truncate">
                                <span x-text="product.category_name"></span> &bull; <span x-text="product.unit"></span>
                            </div>

                            <!-- Nama Produk -->
                            <h3 class="text-xs sm:text-sm font-bold text-gray-900 leading-snug line-clamp-2 h-8 sm:h-9" :title="product.name" x-text="product.name"></h3>

                            <!-- Status Stok Dot -->
                            <div class="pt-0.5">
                                <template x-if="product.stock > 10">
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        <span x-text="product.stock > 30 ? 'Stok Cukup' : 'Tersedia'"></span>
                                    </span>
                                </template>
                                <template x-if="product.stock > 0 && product.stock <= 10">
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                        <span x-text="'Sisa ' + product.stock + ' ' + product.unit"></span>
                                    </span>
                                </template>
                                <template x-if="product.stock <= 0">
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-medium bg-rose-50 text-rose-800 border border-rose-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                        <span>Stok Kosong</span>
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Baris Bawah: Harga & Tombol Stepper / Tambah -->
                        <div class="flex items-end justify-between gap-1.5 mt-2.5 pt-2 border-t border-gray-50">
                            <!-- Harga Satuan -->
                            <div class="min-w-0">
                                <span class="text-[9px] text-gray-400 block leading-tight">Harga Satuan</span>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-900 tracking-tight block truncate" x-text="'Rp ' + formatRupiah(product.price)"></span>
                            </div>

                            <!-- Tombol Aksi Kasir -->
                            <div class="shrink-0">
                                <!-- Belum Ada di Keranjang -->
                                <template x-if="!cart[product.id] || cart[product.id] === 0">
                                    <button 
                                        type="button" 
                                        @click="addToCart(product)"
                                        :disabled="product.stock <= 0"
                                        class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed text-gray-800 flex items-center justify-center font-bold text-sm transition-all cursor-pointer shadow-2xs"
                                        title="Tambah ke Keranjang"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </template>

                                <!-- Sudah Ada di Keranjang: Stepper [-] [qty] [+] -->
                                <template x-if="cart[product.id] > 0">
                                    <div class="flex items-center gap-1 bg-gray-50 p-0.5 rounded-xl border border-gray-100 shadow-2xs">
                                        <!-- Tombol Kurang -->
                                        <button 
                                            type="button" 
                                            @click="decreaseCart(product)"
                                            class="w-6 h-6 rounded-lg bg-white hover:bg-gray-200 active:scale-90 text-gray-700 flex items-center justify-center font-bold text-xs shadow-xs transition-all cursor-pointer"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                            </svg>
                                        </button>

                                        <!-- Jumlah Item -->
                                        <span class="text-xs font-bold text-gray-900 px-1 min-w-[14px] text-center" x-text="cart[product.id]"></span>

                                        <!-- Tombol Tambah -->
                                        <button 
                                            type="button" 
                                            @click="addToCart(product)"
                                            :disabled="cart[product.id] >= product.stock"
                                            class="w-6 h-6 rounded-lg bg-[#173628] hover:bg-[#10271c] active:scale-90 disabled:opacity-40 text-white flex items-center justify-center font-bold text-xs shadow-xs transition-all cursor-pointer"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </template>
            </div>

            <!-- Pesan jika Pencarian / Filter Kosong -->
            <div x-show="filteredProducts.length === 0" class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-500 my-4 shadow-2xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                <p class="text-sm font-semibold text-gray-700">Produk Tidak Ditemukan</p>
                <p class="text-xs text-gray-400 mt-0.5">Coba gunakan kata kunci pencarian lain atau pilih kategori Semua.</p>
            </div>
        </div>

        <!-- 6. Floating Cart Bar (Sticky di atas Bottom Navigation) -->
        <div 
            x-show="totalItemCount > 0" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-6"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-6"
            class="sticky bottom-16 sm:bottom-18 z-30 px-3.5 pb-2 pt-1"
        >
            <div class="bg-[#173628] text-white rounded-2xl p-3 sm:p-3.5 shadow-[0_8px_25px_-5px_rgba(23,54,40,0.6)] border border-emerald-950/40 flex items-center justify-between gap-3">
                
                <!-- Sisi Kiri: Ikon Keranjang + Info Jumlah & Total -->
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="8" cy="21" r="1"/>
                            <circle cx="19" cy="21" r="1"/>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>
                        <!-- Badge Jumlah Item Oranye -->
                        <span 
                            class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-amber-500 text-white font-black text-[10px] flex items-center justify-center ring-2 ring-[#173628] shadow-xs"
                            x-text="totalItemCount"
                        ></span>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-[11px] text-emerald-200/90 font-medium truncate" x-text="totalItemCount + ' Item (' + totalUniqueCount + ' Jenis Produk)'"></p>
                        <p class="text-base sm:text-lg font-black tracking-tight text-white leading-tight" x-text="'Rp ' + formatRupiah(totalCartPrice)"></p>
                    </div>
                </div>

                <!-- Sisi Kanan: Tombol Buka Keranjang / Checkout -->
                <button 
                    type="button" 
                    @click="showCheckoutDrawer = true" 
                    class="bg-white hover:bg-gray-100 active:scale-95 text-[#173628] text-xs font-bold px-4 py-2.5 rounded-xl inline-flex items-center gap-1.5 shadow-sm transition-all shrink-0 cursor-pointer"
                >
                    <span>Keranjang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- 7. Drawer Modal Keranjang & Konfirmasi Pembayaran -->
        <div 
            x-show="showCheckoutDrawer" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-hidden"
            aria-labelledby="slide-over-title" 
            role="dialog" 
            aria-modal="true"
        >
            <!-- Backdrop -->
            <div 
                x-show="showCheckoutDrawer" 
                x-transition:enter="ease-in-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in-out duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="showCheckoutDrawer = false" 
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="fixed inset-x-0 bottom-0 max-w-[500px] mx-auto flex flex-col max-h-[92vh] bg-white rounded-t-3xl shadow-2xl overflow-hidden">
                <!-- Header Drawer -->
                <div class="px-4 py-3.5 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-[#173628] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <circle cx="8" cy="21" r="1"/>
                                <circle cx="19" cy="21" r="1"/>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Keranjang Transaksi</h3>
                            <p class="text-[10px] text-gray-400" x-text="totalItemCount + ' Total Produk Terpilih'"></p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        @click="showCheckoutDrawer = false" 
                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition cursor-pointer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                <!-- Form Checkout Pembayaran Kasir -->
                <form action="{{ route('cashier.store') }}" method="POST" class="flex-1 overflow-y-auto no-scrollbar p-4 space-y-4">
                    @csrf

                    <!-- List Item Keranjang -->
                    <div class="space-y-2">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400 px-0.5">
                            DAFTAR ITEM BELANJA
                        </div>
                        
                        <div class="divide-y divide-gray-100 bg-gray-50/70 border border-gray-100 rounded-2xl overflow-hidden">
                            <template x-for="item in cartItems" :key="item.id">
                                <div class="p-3 flex items-center justify-between gap-2 bg-white">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-gray-900 truncate" x-text="item.name"></p>
                                        <p class="text-[10px] text-gray-400">
                                            <span x-text="'Rp ' + formatRupiah(item.price)"></span> &times; <span x-text="item.qty + ' ' + item.unit"></span>
                                        </p>
                                    </div>

                                    <!-- Stepper Mini di Keranjang -->
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button 
                                            type="button" 
                                            @click="decreaseCart(item)"
                                            class="w-6 h-6 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center font-bold text-xs"
                                        >
                                            -
                                        </button>
                                        <span class="text-xs font-bold text-gray-900 w-5 text-center" x-text="item.qty"></span>
                                        <button 
                                            type="button" 
                                            @click="addToCart(item)"
                                            :disabled="item.qty >= item.stock"
                                            class="w-6 h-6 rounded-lg bg-[#173628] hover:bg-[#10271c] disabled:opacity-40 text-white flex items-center justify-center font-bold text-xs"
                                        >
                                            +
                                        </button>
                                    </div>

                                    <!-- Subtotal & Tombol Hapus -->
                                    <div class="text-right shrink-0 pl-1">
                                        <p class="text-xs font-bold text-gray-900" x-text="'Rp ' + formatRupiah(item.subtotal)"></p>
                                        <button 
                                            type="button" 
                                            @click="removeFromCart(item.id)" 
                                            class="text-[10px] font-semibold text-rose-500 hover:text-rose-700"
                                        >
                                            Hapus
                                        </button>
                                    </div>

                                    <!-- Input Hidden Form untuk Server -->
                                    <input type="hidden" :name="'items[' + item.id + '][product_id]'" :value="item.id">
                                    <input type="hidden" :name="'items[' + item.id + '][quantity]'" :value="item.qty">
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Informasi Pelanggan & Layanan -->
                    <div class="space-y-3 pt-1">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400 px-0.5">
                            INFORMASI TRANSAKSI
                        </div>

                        <!-- Tipe Pesanan: Langsung vs Delivery -->
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="order_type" value="direct" x-model="orderType" class="sr-only">
                                <div 
                                    :class="orderType === 'direct' ? 'bg-[#173628] text-white border-[#173628]' : 'bg-white text-gray-700 border-gray-200'"
                                    class="p-2.5 rounded-xl border text-center text-xs font-bold transition flex items-center justify-center gap-1.5"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                                        <path d="M3 9h18"/>
                                    </svg>
                                    <span>Langsung di Toko</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="order_type" value="delivery" x-model="orderType" class="sr-only">
                                <div 
                                    :class="orderType === 'delivery' ? 'bg-[#173628] text-white border-[#173628]' : 'bg-white text-gray-700 border-gray-200'"
                                    class="p-2.5 rounded-xl border text-center text-xs font-bold transition flex items-center justify-center gap-1.5"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M10 17h4V5H2v12h3"/>
                                        <path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h1"/>
                                        <circle cx="7.5" cy="17.5" r="2.5"/>
                                        <circle cx="17.5" cy="17.5" r="2.5"/>
                                    </svg>
                                    <span>Antar / Titip</span>
                                </div>
                            </label>
                        </div>

                        <!-- Pilihan Pelanggan: Umum / Terdaftar -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <label class="inline-flex items-center gap-1 text-xs font-semibold text-gray-700">
                                    <input type="radio" name="customer_type" value="umum" x-model="customerType" class="text-[#173628] focus:ring-[#173628]">
                                    <span>Pelanggan Umum</span>
                                </label>
                                <label class="inline-flex items-center gap-1 text-xs font-semibold text-gray-700 ml-3">
                                    <input type="radio" name="customer_type" value="registered" x-model="customerType" class="text-[#173628] focus:ring-[#173628]">
                                    <span>Pelanggan Langganan</span>
                                </label>
                            </div>

                            <!-- Dropdown Pelanggan Terdaftar -->
                            <div x-show="customerType === 'registered'">
                                <select 
                                    name="customer_id" 
                                    x-model="customerId"
                                    class="w-full bg-white border border-gray-200 text-gray-900 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#173628]"
                                >
                                    <option value="">-- Pilih Nama Pelanggan --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?? 'Tanpa No' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Input Nama Pelanggan Manual Opsional -->
                            <div x-show="customerType === 'umum'">
                                <input 
                                    type="text" 
                                    name="customer_name" 
                                    x-model="customerName"
                                    placeholder="Nama pembeli (opsional, misal: Mas Joko)" 
                                    class="w-full bg-white border border-gray-200 text-gray-900 text-xs rounded-xl px-3.5 py-2 placeholder-gray-400 focus:outline-none focus:border-[#173628]"
                                >
                            </div>
                        </div>

                        <!-- Metode Pembayaran: Tunai vs Kasbon (Hutang) -->
                        <div class="space-y-2 pt-1">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">
                                METODE PEMBAYARAN
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_type" value="langsung" x-model="paymentType" class="sr-only">
                                    <div 
                                        :class="paymentType === 'langsung' ? 'bg-emerald-50 text-[#173628] border-emerald-600 ring-1 ring-emerald-600' : 'bg-white text-gray-700 border-gray-200'"
                                        class="p-2.5 rounded-xl border text-center text-xs font-bold transition flex items-center justify-center gap-1.5"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect width="20" height="12" x="2" y="6" rx="2"/>
                                            <circle cx="12" cy="12" r="2"/>
                                        </svg>
                                        <span>Bayar Langsung</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_type" value="kredit" x-model="paymentType" class="sr-only">
                                    <div 
                                        :class="paymentType === 'kredit' ? 'bg-amber-50 text-amber-900 border-amber-600 ring-1 ring-amber-600' : 'bg-white text-gray-700 border-gray-200'"
                                        class="p-2.5 rounded-xl border text-center text-xs font-bold transition flex items-center justify-center gap-1.5"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                        </svg>
                                        <span>Kasbon / Hutang</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Channel Pembayaran Langsung (Cash / QRIS / Transfer) -->
                            <div x-show="paymentType === 'langsung'" class="space-y-2 pt-1">
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="cash" x-model="paymentMethod" class="sr-only">
                                        <div 
                                            :class="paymentMethod === 'cash' ? 'bg-[#173628] text-white' : 'bg-gray-100 text-gray-700'"
                                            class="p-2 rounded-lg text-center text-[11px] font-bold transition"
                                        >
                                            💵 Tunai
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" class="sr-only">
                                        <div 
                                            :class="paymentMethod === 'qris' ? 'bg-[#173628] text-white' : 'bg-gray-100 text-gray-700'"
                                            class="p-2 rounded-lg text-center text-[11px] font-bold transition"
                                        >
                                            📱 QRIS
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="transfer" x-model="paymentMethod" class="sr-only">
                                        <div 
                                            :class="paymentMethod === 'transfer' ? 'bg-[#173628] text-white' : 'bg-gray-100 text-gray-700'"
                                            class="p-2 rounded-lg text-center text-[11px] font-bold transition"
                                        >
                                            🏦 Transfer
                                        </div>
                                    </label>
                                </div>

                                <!-- Kalkulator Nominal Bayar & Kembalian (Jika Tunai) -->
                                <div x-show="paymentMethod === 'cash'" class="bg-gray-50 p-3 rounded-2xl border border-gray-100 space-y-2.5">
                                    <div>
                                        <label class="text-[10px] font-bold text-gray-500 uppercase">UANG YANG DITERIMA (RP)</label>
                                        <input 
                                            type="number" 
                                            name="paid_amount" 
                                            x-model="paidAmount" 
                                            class="w-full bg-white border border-gray-200 text-gray-900 text-sm font-black rounded-xl px-3.5 py-2 mt-1 focus:outline-none focus:border-[#173628]"
                                        >
                                    </div>

                                    <!-- Quick Chips Uang Pas / Pecahan -->
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <button 
                                            type="button" 
                                            @click="setQuickCash(totalCartPrice)" 
                                            class="text-[10px] font-bold bg-white border border-gray-200 text-gray-700 px-2.5 py-1 rounded-lg hover:bg-gray-100 active:scale-95"
                                        >
                                            Uang Pas
                                        </button>
                                        <template x-for="nom in quickCashNominals" :key="nom">
                                            <button 
                                                type="button" 
                                                x-show="nom >= totalCartPrice"
                                                @click="setQuickCash(nom)" 
                                                class="text-[10px] font-bold bg-white border border-gray-200 text-gray-700 px-2.5 py-1 rounded-lg hover:bg-gray-100 active:scale-95"
                                                x-text="'Rp ' + formatRupiah(nom)"
                                            ></button>
                                        </template>
                                    </div>

                                    <!-- Tampilan Kembalian -->
                                    <div class="flex items-center justify-between pt-1.5 border-t border-gray-200/70 text-xs">
                                        <span class="text-gray-500 font-semibold">Kembalian:</span>
                                        <span 
                                            class="font-black text-sm"
                                            :class="calculatedChange >= 0 ? 'text-emerald-700' : 'text-rose-600'"
                                            x-text="'Rp ' + formatRupiah(calculatedChange)"
                                        ></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Jatuh Tempo jika Kasbon -->
                            <div x-show="paymentType === 'kredit'" class="bg-amber-50/70 p-3 rounded-2xl border border-amber-200/80 space-y-2">
                                <label class="text-[10px] font-bold text-amber-900 uppercase">Jatuh Tempo Kasbon (Opsional)</label>
                                <input 
                                    type="date" 
                                    name="due_date" 
                                    class="w-full bg-white border border-amber-200 text-gray-900 text-xs rounded-xl px-3 py-2 focus:outline-none focus:border-amber-600"
                                >
                                <p class="text-[10px] text-amber-800">
                                    Catatan kasbon akan masuk ke buku hutang pelanggan terpilih.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Total & Tombol Submit Transaksi -->
                    <div class="pt-2 sticky bottom-0 bg-white pb-1">
                        <div class="flex items-baseline justify-between mb-3 px-1">
                            <span class="text-xs text-gray-500 font-semibold">Total Tagihan</span>
                            <span class="text-xl font-black text-gray-900" x-text="'Rp ' + formatRupiah(totalCartPrice)"></span>
                        </div>

                        <button 
                            type="submit" 
                            :disabled="totalItemCount === 0 || (paymentType === 'langsung' && paymentMethod === 'cash' && paidAmount < totalCartPrice)"
                            class="w-full bg-[#173628] hover:bg-[#10271c] active:scale-[0.99] disabled:opacity-50 disabled:cursor-not-allowed text-white font-black text-sm py-3.5 px-4 rounded-2xl flex items-center justify-center gap-2 shadow-md transition-all cursor-pointer"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Selesaikan Transaksi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 8. Modal Nota / Struk Sukses Transaksi -->
        @if(session('sale_receipt'))
            <div 
                x-show="showReceiptModal" 
                x-cloak 
                class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs"
            >
                <div 
                    @click.away="showReceiptModal = false"
                    class="bg-white rounded-3xl p-5 max-w-sm w-full shadow-2xl border border-gray-100 space-y-4 text-center animate-in fade-in zoom-in-95 duration-200"
                >
                    <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-700 mx-auto flex items-center justify-center ring-8 ring-emerald-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-black text-gray-900">Transaksi Berhasil!</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Nota Penjualan #{{ session('sale_receipt.sale_id') }} telah dicatat.</p>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-3.5 text-left text-xs space-y-2 border border-gray-100">
                        <div class="flex justify-between text-gray-500">
                            <span>Pelanggan</span>
                            <span class="font-bold text-gray-800">{{ session('sale_receipt.customer_name') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Total Item</span>
                            <span class="font-bold text-gray-800">{{ session('sale_receipt.total_qty') }} pcs ({{ session('sale_receipt.item_count') }} produk)</span>
                        </div>
                        <div class="flex justify-between text-gray-900 font-extrabold pt-2 border-t border-gray-200 text-sm">
                            <span>Total Belanja</span>
                            <span>Rp {{ number_format(session('sale_receipt.total_amount'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Dibayar</span>
                            <span>Rp {{ number_format(session('sale_receipt.paid_amount'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-emerald-700 font-bold">
                            <span>Kembalian</span>
                            <span>Rp {{ number_format(session('sale_receipt.change'), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <button 
                            type="button" 
                            onclick="window.print()" 
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs py-2.5 rounded-xl transition cursor-pointer"
                        >
                            🖨️ Cetak Struk
                        </button>
                        <button 
                            type="button" 
                            @click="showReceiptModal = false" 
                            class="bg-[#173628] hover:bg-[#10271c] text-white font-bold text-xs py-2.5 rounded-xl transition cursor-pointer"
                        >
                            Transaksi Baru
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
