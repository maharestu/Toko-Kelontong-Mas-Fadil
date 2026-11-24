<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Toko Mas Adil - Portal Navigasi</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 bg-[#f8f9fa] antialiased min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-sm border border-gray-100 p-7 sm:p-8 space-y-6">
            <!-- Header Brand -->
            <x-application-logo />

            <div class="border-t border-gray-100 pt-4">
                <h2 class="text-base font-bold text-gray-900">Portal Akses Halaman</h2>
                <p class="text-xs text-gray-500 mt-0.5">Pilih tampilan yang ingin Anda buka atau uji coba:</p>
            </div>

            <!-- List Menu Navigasi Langsung -->
            <div class="space-y-2.5">
                <!-- 1. Login -->
                <a href="{{ route('login') }}" 
                   class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-[#14281f] hover:text-white group border border-gray-100 transition-all duration-150">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white group-hover:bg-white/10 flex items-center justify-center text-gray-700 group-hover:text-white shadow-xs transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold leading-tight">1. Tampilan Login</p>
                            <p class="text-[10px] text-gray-400 group-hover:text-gray-200 mt-0.5">/login</p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 group-hover:text-white transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>

                <!-- 2. Lupa Sandi -->
                <a href="{{ url('/lupa-password') }}" 
                   class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-[#14281f] hover:text-white group border border-gray-100 transition-all duration-150">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white group-hover:bg-white/10 flex items-center justify-center text-gray-700 group-hover:text-white shadow-xs transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold leading-tight">2. Tampilan Lupa Sandi</p>
                            <p class="text-[10px] text-gray-400 group-hover:text-gray-200 mt-0.5">/lupa-password atau /forgot-password</p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 group-hover:text-white transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>

                <!-- 3. Buat Sandi Baru -->
                <a href="{{ url('/buat-password') }}" 
                   class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 hover:bg-[#14281f] hover:text-white group border border-gray-100 transition-all duration-150">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white group-hover:bg-white/10 flex items-center justify-center text-gray-700 group-hover:text-white shadow-xs transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold leading-tight">3. Tampilan Buat Sandi Baru</p>
                            <p class="text-[10px] text-gray-400 group-hover:text-gray-200 mt-0.5">/buat-password atau /reset-password</p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 group-hover:text-white transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>

                <!-- 4. Dashboard Admin Analitik -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center justify-between p-3.5 rounded-2xl bg-emerald-50 hover:bg-[#14281f] hover:text-white group border border-emerald-100 transition-all duration-150">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white group-hover:bg-white/10 flex items-center justify-center text-emerald-800 group-hover:text-white shadow-xs transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold leading-tight text-emerald-950 group-hover:text-white">4. Dashboard Admin Analitik</p>
                            <p class="text-[10px] text-emerald-600 group-hover:text-gray-200 mt-0.5">/dashboard (Langsung Terbuka)</p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-700 group-hover:text-white transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>

            <div class="pt-2 text-center text-[11px] text-gray-400">
                Akun Uji Coba: <code class="text-gray-700 font-mono">admin@tokomasadil.com</code> / <code class="text-gray-700 font-mono">password</code>
            </div>
        </div>
    </body>
</html>
