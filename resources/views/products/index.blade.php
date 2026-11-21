<x-layouts.app title="Kelola Produk - Toko Kelontong Mas Fadil" active="produk">
    <div class="px-4 pt-5 pb-6">
        <header class="mb-4">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Katalog Produk</h1>
            <p class="text-xs text-gray-500 font-medium mt-0.5">
                Total {{ $products->total() }} Produk &bull; <span class="text-emerald-700 font-semibold">Inventaris Toko</span>
            </p>
        </header>

        <!-- Daftar Produk Sementara -->
        <div class="space-y-3">
            @forelse ($products as $product)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-3.5 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</h3>
                            <span class="text-[10px] font-semibold bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                {{ $product->category->name ?? 'Tanpa Kategori' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Stok: <span class="font-bold text-gray-800">{{ $product->stock }} {{ $product->unit }}</span> &bull; 
                            Rp {{ number_format($product->current_selling_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-500">
                    <p class="text-sm font-semibold text-gray-700">Belum ada produk</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>

