<x-app-layout>
    <div class="p-4 sm:p-5 space-y-4 pb-10">

        {{-- 1. Back Navigation --}}
        <div>
            <a href="{{ route('restocks.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 hover:text-black mb-3 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                <span>Riwayat Restock</span>
            </a>

            {{-- 2. Restock Code --}}
            <h1 class="text-2xl font-black text-gray-900 tracking-tight font-mono leading-tight">
                {{ $code }}
            </h1>

            {{-- 3. Info Pills --}}
            <div class="flex flex-wrap gap-2 mt-2.5">
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-[11px] font-medium px-3 py-1.5 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-500" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Waktu Transaksi: {{ $time }}
                </span>
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-[11px] font-medium px-3 py-1.5 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-500" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Dicatat Oleh: {{ $author }}
                </span>
            </div>
        </div>

        {{-- 4. Catatan Pembelian --}}
        @if ($notes)
            <div class="bg-gray-100/80 rounded-2xl p-4 border border-gray-200/60">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1.5">
                    Catatan Pembelian
                </span>
                <p class="text-xs text-gray-700 leading-relaxed">{{ $notes }}</p>
            </div>
        @endif

        {{-- 5. Produk Masuk --}}
        <div class="space-y-2.5">
            {{-- Section Header --}}
            <div class="flex items-center justify-between">
                <span class="text-sm font-extrabold text-gray-900">Produk Masuk</span>
                <span class="bg-[#0a2316] text-white text-[10px] font-bold px-2.5 py-1 rounded-full">
                    {{ count($details) }} Jenis ({{ $total_qty }} Unit)
                </span>
            </div>

            {{-- Product Cards --}}
            @foreach ($details as $detail)
                @php
                    $subtotal = $detail['subtotal'] ?? ($detail['qty'] * $detail['buy']);
                @endphp
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.04)] space-y-2">
                    {{-- Name + Qty Badge --}}
                    <div class="flex items-start justify-between gap-2">
                        <span class="font-bold text-sm text-gray-900 leading-snug flex-1">{{ $detail['name'] }}</span>
                        <span class="flex-shrink-0 bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                            +{{ $detail['qty'] }} {{ $detail['unit'] }}
                        </span>
                    </div>

                    {{-- Price Arrow Row --}}
                    <div class="flex items-center gap-1 text-[11px] text-gray-500">
                        <span>Beli:</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($detail['buy'], 0, ',', '.') }}/{{ $detail['unit'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400 mx-0.5" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                        <span>Jual:</span>
                        <span class="font-semibold text-[#123b28]">Rp {{ number_format($detail['sell'], 0, ',', '.') }}/{{ $detail['unit'] }}</span>
                    </div>

                    {{-- Subtotal --}}
                    <div class="flex items-center justify-between pt-1.5 border-t border-gray-50">
                        <span class="text-[11px] text-gray-400">Subtotal Modal</span>
                        <span class="text-xs font-black text-gray-900 font-mono">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 6. Ringkasan Harga --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.04)] overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <span class="text-xs font-extrabold text-gray-900">Ringkasan Harga</span>
            </div>
            <div class="divide-y divide-gray-50">
                <div class="flex items-center justify-between px-4 py-3">
                    <span class="text-xs text-gray-500">Total Jenis Produk</span>
                    <span class="text-xs font-bold text-gray-900">{{ count($details) }} Jenis</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3">
                    <span class="text-xs text-gray-500">Total Kuantitas Masuk</span>
                    <span class="text-xs font-bold text-gray-900">{{ $total_qty }} Unit</span>
                </div>
            </div>
        </div>

        {{-- 7. Total Harga (Green Banner) --}}
        <div class="bg-[#123b28] rounded-2xl px-5 py-4 flex items-center justify-between shadow-[0_2px_10px_rgba(18,59,40,0.2)]">
            <div>
                <span class="text-[10px] font-bold text-emerald-300 uppercase tracking-wider block mb-0.5">
                    Total Harga
                </span>
                <span class="text-2xl font-black text-white font-mono tracking-tight">
                    Rp {{ number_format($total_cost, 0, ',', '.') }}
                </span>
            </div>
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-300" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                    <line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
            </div>
        </div>

        {{-- 8. Bottom Back Button --}}
        <a href="{{ route('restocks.index') }}"
           class="block w-full text-center bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 py-3.5 rounded-2xl font-bold text-sm shadow-sm transition-all active:scale-[0.99]">
            Kembali ke Riwayat Restock
        </a>

    </div>
</x-app-layout>
