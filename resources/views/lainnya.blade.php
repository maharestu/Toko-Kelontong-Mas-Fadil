<x-app-layout>
    <!-- Top Header -->
    <div class="py-4 px-4 bg-white border-b border-gray-100 text-center sticky top-0 z-20">
        <h1 class="text-base font-bold text-gray-900 tracking-tight">Lainnya</h1>
    </div>

    <div class="p-4 sm:p-5 space-y-6">
        <!-- 1. Grid 2x2 Menu Fitur Utama -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Restock -->
            <a href="{{ route('restocks.index') }}" class="bg-white rounded-2xl p-4 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] flex flex-col justify-between min-h-[148px] hover:shadow-md hover:border-gray-200 transition-all group active:scale-[0.98]">
                <div class="w-10 h-10 rounded-xl bg-[#eef5f0] text-[#1b4332] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <!-- 3D Box / Package Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.12 6.4-6-3.4a2 2 0 0 0-2 0l-6 3.4a2 2 0 0 0-1 1.7v6.8a2 2 0 0 0 1 1.7l6 3.4a2 2 0 0 0 2 0l6-3.4a2 2 0 0 0 1-1.7V8.1a2 2 0 0 0-1-1.7z"/>
                        <path d="m3.3 7 8.7 5 8.7-5"/>
                        <path d="M12 22V12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-[#1b4332] transition-colors">Restock</h3>
                    <p class="text-[11px] text-gray-400 mt-1 leading-snug">Catat barang masuk &amp; kulakan</p>
                </div>
            </a>

            <!-- Laporan -->
            <a href="#" class="bg-white rounded-2xl p-4 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] flex flex-col justify-between min-h-[148px] hover:shadow-md hover:border-gray-200 transition-all group active:scale-[0.98]">
                <div class="w-10 h-10 rounded-xl bg-[#eef5f0] text-[#1b4332] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <!-- Bar Chart Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 20V10"/>
                        <path d="M12 20V4"/>
                        <path d="M6 20v-6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-[#1b4332] transition-colors">Laporan</h3>
                    <p class="text-[11px] text-gray-400 mt-1 leading-snug">Ringkasan omzet &amp; laba</p>
                </div>
            </a>

            <!-- Promosi -->
            <a href="#" class="bg-white rounded-2xl p-4 border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] flex flex-col justify-between min-h-[148px] hover:shadow-md hover:border-gray-200 transition-all group active:scale-[0.98]">
                <div class="w-10 h-10 rounded-xl bg-[#eef5f0] text-[#1b4332] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <!-- Price Tag Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/>
                        <circle cx="7" cy="7" r="1.5" fill="currentColor"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-[#1b4332] transition-colors">Promosi</h3>
                    <p class="text-[11px] text-gray-400 mt-1 leading-snug">Atur diskon &amp; promo toko</p>
                </div>
            </a>

            <!-- Tambah Fitur (Dashed Border) -->
            <div class="rounded-2xl p-4 border border-dashed border-gray-300 bg-white/40 flex flex-col justify-between min-h-[148px] hover:bg-white/70 transition-colors cursor-pointer group">
                <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center flex-shrink-0 group-hover:bg-gray-200 transition-colors">
                    <!-- Plus Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="M12 5v14"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900">Tambah Fitur</h3>
                    <p class="text-[11px] text-gray-400 mt-1 leading-snug">Simulasi fitur baru</p>
                </div>
            </div>
        </div>

        <!-- 2. Pengaturan & Umum -->
        <div>
            <h2 class="text-xs sm:text-sm font-bold text-gray-900 mb-2.5 px-0.5">Pengaturan &amp; Umum</h2>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_1px_4px_rgba(0,0,0,0.03)] overflow-hidden divide-y divide-gray-100">
                <!-- Profil Toko -->
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-3.5 hover:bg-gray-50/80 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#eef5f0] text-[#1b4332] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <!-- Shop / Storefront Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                                <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                                <path d="M2 7h20"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 group-hover:text-[#1b4332] transition-colors">Profil Toko</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">Nama warung, alamat &amp; info struk</p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>

                <!-- Printer & Perangkat -->
                <a href="#" class="flex items-center justify-between p-3.5 hover:bg-gray-50/80 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#eef5f0] text-[#1b4332] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <!-- Printer Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9V2h12v7"/>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                <rect width="12" height="8" x="6" y="14"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 group-hover:text-[#1b4332] transition-colors">Printer &amp; Perangkat</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">Atur printer struk Bluetooth</p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>

                <!-- Bantuan -->
                <a href="#" class="flex items-center justify-between p-3.5 hover:bg-gray-50/80 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#eef5f0] text-[#1b4332] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <!-- Question Mark Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                <path d="M12 17h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 group-hover:text-[#1b4332] transition-colors">Bantuan</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">Panduan penggunaan aplikasi</p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- 3. Versi Aplikasi -->
        <div class="text-center pt-2 pb-4">
            <p class="text-xs text-gray-400 font-normal tracking-wide">WarungPOS v1.4.0</p>
        </div>
    </div>
</x-app-layout>

