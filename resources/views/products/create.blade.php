<x-layouts.app title="Tambah Produk Baru - Toko Kelontong Mas Fadil" active="produk">
    <div class="px-4 pt-4 pb-28">
        <!-- Top Navigation Bar (Kembali, Tanpa Toko Aktif) -->
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Produk</span>
            </a>
        </div>

        <!-- Header -->
        <header class="mb-5">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Tambah Produk Baru</h1>
            <p class="text-xs text-gray-500 font-medium mt-1">
                Lengkapi informasi produk untuk katalog inventaris & kasir.
            </p>
        </header>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="form-product">
            @csrf

            <!-- Section 1: Foto Produk (Opsional) -->
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-gray-900">Foto Produk</h2>
                    </div>
                    <span class="text-[11px] font-medium text-gray-400">Opsional</span>
                </div>

                <!-- Hidden Input File -->
                <input type="file" name="image" id="product_image_input" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">

                <!-- Upload State Box (Sebelum Pilih Foto) -->
                <div id="upload-placeholder" onclick="document.getElementById('product_image_input').click()" class="border-2 border-dashed border-gray-200 hover:border-gray-300 rounded-xl p-5 text-center cursor-pointer transition-colors bg-gray-50/60">
                    <div class="w-10 h-10 rounded-full bg-white shadow-xs text-gray-400 mx-auto flex items-center justify-center mb-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Pilih atau unggah foto produk</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Ketuk untuk memilih file dari perangkat Anda</p>
                </div>

                <!-- Preview State Box (Setelah Foto Dipilih) -->
                <div id="upload-preview-box" class="border border-gray-200 rounded-xl p-3 bg-gray-50/70 hidden">
                    <div class="flex items-center gap-3">
                        <img id="image-preview" src="#" alt="Preview" class="w-16 h-16 rounded-lg object-cover border border-gray-200 bg-white">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <span id="file-name" class="text-xs font-bold text-gray-900 truncate">foto-produk.jpg</span>
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-1.5 py-0.5 rounded">Terunggah</span>
                            </div>
                            <p id="file-size" class="text-[11px] text-gray-400 mt-0.5">1.2 MB &bull; JPG</p>
                            <div class="flex items-center gap-3 mt-2">
                                <button type="button" onclick="document.getElementById('product_image_input').click()" class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-700 hover:text-gray-900 bg-white border border-gray-200 px-2.5 py-1 rounded-lg">
                                    <svg class="w-3 h-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <span>Ganti Foto</span>
                                </button>
                                <button type="button" onclick="removeImage()" class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-600 hover:text-rose-800">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 text-[11px] text-gray-400 mt-2.5 px-0.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Mendukung format JPG, PNG, atau WebP (Maks. 2 MB)</span>
                </div>
            </div>

            <!-- Section 2: Informasi Dasar -->
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 mb-4 space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-900">Informasi Dasar</h2>
                </div>

                <!-- Nama Produk -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="name" class="text-xs font-bold text-gray-700">Nama Produk <span class="text-rose-600">*</span></label>
                        <span class="text-[11px] text-gray-400 font-medium"><span id="name-count">0</span>/80</span>
                    </div>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}"
                        maxlength="80"
                        placeholder="Contoh: Beras Rojo Lele 5kg, Minyak Sania 2L" 
                        required
                        class="w-full bg-gray-50 border border-gray-200 focus:border-[#133c30] focus:bg-white text-gray-900 text-sm rounded-xl px-3.5 py-2.5 outline-none font-medium transition-all"
                        oninput="document.getElementById('name-count').textContent = this.value.length"
                    >
                    <p class="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Gunakan nama merek dan ukuran kemasan yang jelas.</span>
                    </p>
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-xs font-bold text-gray-700 mb-1.5">Kategori <span class="text-rose-600">*</span></label>
                    <div class="relative">
                        <select 
                            name="category_id" 
                            id="category_id" 
                            required
                            class="w-full bg-gray-50 border border-gray-200 focus:border-[#133c30] focus:bg-white text-gray-900 text-sm rounded-xl px-3.5 py-2.5 outline-none font-medium appearance-none transition-all cursor-pointer"
                        >
                            <option value="" disabled selected>Pilih Kategori Produk</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Satuan Kemasan (REVISI: Text Field) -->
                <div>
                    <label for="unit" class="block text-xs font-bold text-gray-700 mb-1.5">Satuan Kemasan <span class="text-rose-600">*</span></label>
                    <input 
                        type="text" 
                        name="unit" 
                        id="unit" 
                        value="{{ old('unit') }}"
                        placeholder="Contoh: pcs, kg, bks, btl, dus, renteng, galon" 
                        required
                        maxlength="50"
                        class="w-full bg-gray-50 border border-gray-200 focus:border-[#133c30] focus:bg-white text-gray-900 text-sm rounded-xl px-3.5 py-2.5 outline-none font-medium transition-all"
                    >
                    <p class="text-[11px] text-gray-400 mt-1">Tuliskan satuan jual barang di kasir (misal: pcs, kg, bungkus).</p>
                </div>
            </div>

            <!-- Section 3: Stok & Harga Warung -->
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 mb-6 space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-900">Stok & Harga Warung</h2>
                </div>

                <!-- Stok Fisik Awal -->
                <div>
                    <label for="stock" class="block text-xs font-bold text-gray-700 mb-1.5">Stok Fisik Awal <span class="text-rose-600">*</span></label>
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        value="{{ old('stock', 0) }}"
                        min="0"
                        required
                        class="w-full bg-gray-50 border border-gray-200 focus:border-[#133c30] focus:bg-white text-gray-900 text-sm rounded-xl px-3.5 py-2.5 outline-none font-medium transition-all"
                    >
                </div>

                <!-- Grid Harga Beli & Harga Jual -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="current_purchase_price" class="block text-xs font-bold text-gray-700 mb-1.5">Harga Modal / Beli <span class="text-rose-600">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs font-bold text-gray-400">Rp</div>
                            <input 
                                type="number" 
                                name="current_purchase_price" 
                                id="current_purchase_price" 
                                value="{{ old('current_purchase_price') }}"
                                min="0"
                                step="50"
                                placeholder="0" 
                                required
                                class="w-full bg-gray-50 border border-gray-200 focus:border-[#133c30] focus:bg-white text-gray-900 text-sm rounded-xl pl-9 pr-3.5 py-2.5 outline-none font-medium transition-all"
                            >
                        </div>
                    </div>
                    <div>
                        <label for="current_selling_price" class="block text-xs font-bold text-gray-700 mb-1.5">Harga Jual <span class="text-rose-600">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs font-bold text-gray-400">Rp</div>
                            <input 
                                type="number" 
                                name="current_selling_price" 
                                id="current_selling_price" 
                                value="{{ old('current_selling_price') }}"
                                min="0"
                                step="50"
                                placeholder="0" 
                                required
                                class="w-full bg-gray-50 border border-gray-200 focus:border-[#133c30] focus:bg-white text-gray-900 text-sm rounded-xl pl-9 pr-3.5 py-2.5 outline-none font-medium transition-all"
                            >
                        </div>
                    </div>
                </div>

                <!-- Status Penjualan Aktif -->
                <div class="pt-2 flex items-center justify-between border-t border-gray-100">
                    <div>
                        <span class="text-xs font-bold text-gray-900 block">Status Penjualan</span>
                        <span class="text-[11px] text-gray-400">Tampilkan produk di kasir untuk transaksi</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#133c30]"></div>
                    </label>
                </div>
            </div>

            <!-- Submit Button (Di Atas Bottom Nav, Bersih & Tidak Menimpa Navbar) -->
            <div class="mt-5 mb-8">
                <button 
                    type="submit" 
                    class="w-full bg-[#133c30] hover:bg-[#0e2a22] active:scale-[0.99] text-white text-sm font-semibold py-3.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-all shadow-xs cursor-pointer"
                >
                    <svg class="w-4 h-4 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span>Simpan Produk</span>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const imageInput = document.getElementById('product_image_input');
        const uploadPlaceholder = document.getElementById('upload-placeholder');
        const uploadPreviewBox = document.getElementById('upload-preview-box');
        const imagePreview = document.getElementById('image-preview');
        const fileNameLabel = document.getElementById('file-name');
        const fileSizeLabel = document.getElementById('file-size');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    fileNameLabel.textContent = file.name;
                    fileSizeLabel.textContent = (file.size / (1024 * 1024)).toFixed(1) + ' MB • ' + file.name.split('.').pop().toUpperCase();
                    uploadPlaceholder.classList.add('hidden');
                    uploadPreviewBox.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        function removeImage() {
            imageInput.value = '';
            imagePreview.src = '#';
            uploadPreviewBox.classList.add('hidden');
            uploadPlaceholder.classList.remove('hidden');
        }
    </script>
    @endpush
</x-layouts.app>

