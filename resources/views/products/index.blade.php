<x-app-layout title="Kelola Produk - Toko Kelontong Mas Fadil" active="produk">
    <div class="px-4 pt-5 pb-6">
        <!-- Header -->
        <div class="flex items-start justify-between gap-3 mb-4">
            <div class="min-w-0">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Kelola Produk</h1>
                <p class="text-xs text-gray-500 font-medium mt-0.5 flex items-center gap-1.5">
                    <span>{{ $totalProductsCount }} total produk</span>
                    <span>&bull;</span>
                    @if ($lowStockCount > 0)
                        <span class="text-rose-600 font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600 inline-block"></span>
                            {{ $lowStockCount }} perlu restock
                        </span>
                    @else
                        <span class="text-emerald-700 font-semibold">Stok aman</span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a 
                    href="{{ route('categories.index') }}" 
                    class="bg-white hover:bg-gray-50 active:scale-[0.98] text-gray-700 border border-gray-200 text-xs font-semibold px-3 py-2.5 rounded-xl inline-flex items-center gap-1.5 shadow-xs transition-all cursor-pointer"
                >
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect width="7" height="7" x="3" y="3" rx="1"/>
                        <rect width="7" height="7" x="14" y="3" rx="1"/>
                        <rect width="7" height="7" x="14" y="14" rx="1"/>
                        <rect width="7" height="7" x="3" y="14" rx="1"/>
                    </svg>
                    <span>Kategori</span>
                </a>
                <a 
                    href="{{ route('products.create') }}" 
                    class="bg-[#133c30] hover:bg-[#0e2a22] active:scale-[0.98] text-white text-xs font-semibold px-3.5 py-2.5 rounded-xl inline-flex items-center gap-1.5 shadow-xs transition-all cursor-pointer"
                >
                    <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Produk</span>
                </a>
            </div>
        </div>

        <!-- Bar Pencarian & Dropdown Filter Stok -->
        <form action="{{ route('products.index') }}" method="GET" id="filter-form" class="mb-3 space-y-2">
            @if(request('category_id'))
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            @endif

            <div class="flex items-center gap-2">
                <!-- Input Cari Nama -->
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari nama produk, kategori..." 
                        class="w-full bg-white border border-gray-200 text-gray-900 text-xs rounded-xl pl-9 pr-8 py-2.5 placeholder-gray-400 focus:outline-none focus:border-[#133c30] transition-colors"
                        onkeydown="if(event.key === 'Enter'){ this.form.submit(); }"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('products.index', request()->except('search', 'page')) }}" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>

                <!-- Dropdown Filter Stok -->
                <div class="relative w-36 shrink-0">
                    <select 
                        name="stock_status" 
                        onchange="document.getElementById('filter-form').submit()"
                        class="w-full bg-white border border-gray-200 text-gray-800 text-xs rounded-xl pl-3 pr-7 py-2.5 focus:outline-none focus:border-[#133c30] appearance-none font-medium transition-colors cursor-pointer"
                    >
                        <option value="" @selected(!request('stock_status'))>Semua Stok</option>
                        <option value="low" @selected(request('stock_status') === 'low')>Perlu Restock</option>
                        <option value="out" @selected(request('stock_status') === 'out')>Stok Habis</option>
                        <option value="available" @selected(request('stock_status') === 'available')>Stok Tersedia</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
        </form>

        <!-- Kategori Filter Badges (Horizontal Scroll) -->
        <div class="overflow-x-auto no-scrollbar flex items-center gap-1.5 pb-2 mb-3">
            <a 
                href="{{ route('products.index', request()->except('category_id', 'page')) }}" 
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-colors {{ !request('category_id') ? 'bg-[#133c30] text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                Semua
            </a>
            @foreach ($categories as $cat)
                @php
                    $isCatActive = (request('category_id') == $cat->id);
                    $url = route('products.index', array_merge(request()->except('page'), ['category_id' => $cat->id]));
                @endphp
                <a 
                    href="{{ $url }}" 
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-colors {{ $isCatActive ? 'bg-[#133c30] text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                >
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Daftar Kartu Produk -->
        <div class="space-y-2.5 mb-5">
            @forelse ($products as $product)
                @php
                    $isLowStock = ($product->stock <= 10);
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-3.5 flex items-center justify-between gap-3 hover:border-gray-200 transition-all">
                    <!-- Foto & Informasi Produk -->
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Thumbnail Image -->
                        <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden shrink-0 text-gray-400">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 stroke-[1.6] text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            @endif
                        </div>

                        <!-- Teks Info -->
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">
                                {{ $product->category->name ?? 'Umum' }} &bull; Beli: Rp {{ number_format($product->current_purchase_price, 0, ',', '.') }}
                            </p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-sm font-bold text-gray-900">
                                    Rp {{ number_format($product->current_selling_price, 0, ',', '.') }}
                                </span>
                                @if ($isLowStock)
                                    <span class="bg-rose-50 text-rose-700 text-[11px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600 inline-block"></span>
                                        {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 text-[11px] font-medium px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 inline-block"></span>
                                        {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi di Kanan -->
                    <div class="flex items-center gap-1 shrink-0">
                        <!-- Edit Button -->
                        <a 
                            href="{{ route('products.edit', $product) }}"
                            title="Edit Produk"
                            class="p-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>

                        <!-- Delete Button -->
                        <button 
                            type="button" 
                            onclick="openDeleteProductModal({{ $product->id }}, '{{ addslashes($product->name) }}')"
                            title="Hapus Produk"
                            class="p-2.5 rounded-xl bg-gray-100 hover:bg-rose-50 text-gray-700 hover:text-rose-600 transition-colors cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-500">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p class="text-sm font-semibold text-gray-700">Tidak ada produk ditemukan</p>
                    <p class="text-xs text-gray-400 mt-0.5">Coba ubah kata kunci pencarian atau filter kategori.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="mb-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Hapus Produk (Presisi Sesuai Desain Figma) -->
    <x-modal name="modal-delete-product" maxWidth="sm">
        <form id="form-delete-product" method="POST" action="" class="p-6">
            @csrf
            @method('DELETE')
            <div class="py-1">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Produk?</h3>
                <p class="text-sm text-gray-500 leading-relaxed mb-6">
                    Hapus <span id="delete_product_name" class="font-medium text-gray-800">“Nama Produk”</span> dari produk? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex items-center gap-3 w-full">
                    <button 
                        type="button" 
                        x-on:click="$dispatch('close')" 
                        class="flex-1 py-3 px-4 border border-gray-300 rounded-2xl text-sm font-semibold text-gray-800 bg-white hover:bg-gray-50 transition-colors text-center cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 py-3 px-4 bg-[#b91c1c] hover:bg-[#991b1b] rounded-2xl text-sm font-semibold text-white transition-colors text-center cursor-pointer shadow-xs"
                    >
                        Hapus Produk
                    </button>
                </div>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function openDeleteProductModal(id, name) {
            const form = document.getElementById('form-delete-product');
            const label = document.getElementById('delete_product_name');
            form.action = `/products/${id}`;
            label.textContent = `“${name}”`;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modal-delete-product' }));
        }
    </script>
    @endpush
</x-app-layout>
