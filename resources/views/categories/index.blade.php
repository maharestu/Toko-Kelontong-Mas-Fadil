<x-app-layout title="Kelola Kategori - Toko Kelontong Mas Fadil" active="kategori">
    <div class="px-4 pt-5 pb-6">
        <!-- Header -->
        <header class="mb-4">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Kelola Kategori</h1>
            <p class="text-xs text-gray-500 font-medium mt-0.5">
                Total {{ $categories->total() }} Kategori &bull; <span class="text-emerald-700 font-semibold">Database Aktif</span>
            </p>
        </header>

        <!-- Form Tambah Cepat -->
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-3.5 mb-4">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="flex items-center gap-2">
                    <input 
                        type="text" 
                        name="name" 
                        id="category_name_input"
                        value="{{ old('name') }}"
                        placeholder="Tambah kategori baru..." 
                        required
                        maxlength="255"
                        class="flex-1 bg-gray-100/90 border border-transparent focus:border-[#133c30] focus:bg-white text-gray-900 text-sm rounded-xl px-3.5 py-2.5 placeholder-gray-400 font-medium outline-none transition-all"
                    >
                    <button 
                        type="submit" 
                        class="bg-[#133c30] hover:bg-[#0e2a22] active:scale-[0.98] text-white text-sm font-semibold px-4 py-2.5 rounded-xl inline-flex items-center gap-1.5 shrink-0 transition-all shadow-xs cursor-pointer"
                    >
                        <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah</span>
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-1.5 text-[11px] text-gray-400 mt-2.5 px-0.5">
                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Kategori tersinkron otomatis ke katalog kasir dan inventaris.</span>
            </div>
        </div>

        <!-- Section Header -->
        <div class="flex items-center justify-between px-1 mb-2.5">
            <span class="text-[11px] font-bold tracking-wider text-gray-500 uppercase">DAFTAR KATEGORI</span>
            <span class="text-[11px] font-bold text-gray-400 uppercase">{{ $categories->total() }} ITEM</span>
        </div>

        <!-- Search Bar (Opsional untuk filter cepat jika item banyak) -->
        @if ($categories->total() > 5 || request('search'))
            <form action="{{ route('categories.index') }}" method="GET" class="mb-3">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari kategori..." 
                        class="w-full bg-white border border-gray-200 text-gray-800 text-xs rounded-xl pl-9 pr-8 py-2 placeholder-gray-400 focus:outline-none focus:border-[#133c30]"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('categories.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        @endif

        <!-- Daftar Kategori Cards (Hanya Teks & Aksi) -->
        <div class="space-y-2.5 mb-4">
            @forelse ($categories as $category)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-3.5 sm:p-4 flex items-center justify-between gap-3 hover:border-gray-200 transition-all">
                    <!-- Nama Kategori & Info Produk -->
                    <div class="min-w-0 pr-2">
                        <h3 class="text-sm sm:text-base font-bold text-gray-900 truncate">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">
                            {{ $category->products_count }} Produk terhubung
                        </p>
                    </div>

                    <!-- Tombol Aksi di Kanan -->
                    <div class="flex items-center gap-1.5 shrink-0">
                        <!-- Tombol Edit -->
                        <button 
                            type="button" 
                            onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}')"
                            title="Edit Kategori"
                            class="p-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>

                        <!-- Tombol Hapus -->
                        <button 
                            type="button" 
                            onclick="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')"
                            title="Hapus Kategori"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm font-semibold text-gray-700">Belum Ada Kategori</p>
                    <p class="text-xs text-gray-400 mt-0.5">Tambahkan kategori pertama melalui form di atas.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($categories->hasPages())
            <div class="mb-4">
                {{ $categories->links() }}
            </div>
        @endif

        <!-- Widget Tips Warung Rapi -->
        <div class="bg-gray-100/70 border border-gray-200/60 rounded-2xl p-4 flex items-start gap-3.5 my-4">
            <div class="w-10 h-10 rounded-xl bg-gray-200/80 flex items-center justify-center shrink-0 text-gray-700">
                <svg class="w-5 h-5 stroke-[1.8]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-900">Tips Warung Rapi</h4>
                <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">
                    Gunakan nama kategori yang ringkas agar mudah ditemukan kasir saat transaksi ramai di jam sibuk.
                </p>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <x-modal name="modal-edit-category" maxWidth="sm">
        <form id="form-edit-category" method="POST" action="" class="p-6">
            @csrf
            @method('PUT')
            <h3 class="text-xl font-bold text-gray-900 mb-4">Edit Kategori</h3>
            <div class="space-y-4">
                <div>
                    <label for="edit_category_name" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wider">
                        Nama Kategori
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="edit_category_name" 
                        required
                        maxlength="255"
                        class="w-full bg-gray-50 border border-gray-300 focus:border-[#133c30] focus:bg-white text-gray-900 text-sm rounded-xl px-3.5 py-2.5 outline-none font-medium transition-all"
                    >
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    x-on:click="$dispatch('close')"
                    class="py-2.5 px-4 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors cursor-pointer"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="py-2.5 px-5 bg-[#133c30] hover:bg-[#0e2a22] rounded-xl text-sm font-semibold text-white transition-colors cursor-pointer shadow-xs"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Konfirmasi Hapus -->
    <x-modal name="modal-delete-category" maxWidth="sm">
        <form id="form-delete-category" method="POST" action="" class="p-6">
            @csrf
            @method('DELETE')
            <div class="py-1">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Kategori?</h3>
                <p class="text-sm text-gray-500 leading-relaxed mb-6">
                    Hapus <span id="delete_category_name" class="font-medium text-gray-800">“Sembako”</span> dari kategori? Tindakan ini tidak dapat dibatalkan.
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
                        Hapus Kategori
                    </button>
                </div>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function openEditModal(id, name) {
            const form = document.getElementById('form-edit-category');
            const input = document.getElementById('edit_category_name');
            form.action = `/categories/${id}`;
            input.value = name;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modal-edit-category' }));
            setTimeout(() => input.focus(), 150);
        }

        function openDeleteModal(id, name) {
            const form = document.getElementById('form-delete-category');
            const label = document.getElementById('delete_category_name');
            form.action = `/categories/${id}`;
            label.textContent = `“${name}”`;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modal-delete-category' }));
        }
    </script>
    @endpush
</x-app-layout>
