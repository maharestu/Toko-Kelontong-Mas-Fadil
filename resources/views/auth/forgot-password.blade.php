<x-guest-layout>
    <!-- Brand Header -->
    <div class="mb-8">
        <x-application-logo />
    </div>

    <!-- Screen Title -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Lupa Sandi?</h2>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Masukkan email pegawai yang terdaftar untuk menerima tautan pengaturan ulang sandi.</p>
    </div>

    <!-- Session Status Alert -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-[11px] font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                EMAIL
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </div>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="Masukkan email"
                       class="block w-full pl-10 pr-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a382b]/20 focus:border-[#1a382b] transition-all" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" 
                    class="w-full py-3 px-4 rounded-xl bg-[#173628] hover:bg-[#11291e] active:scale-[0.99] text-white font-medium text-sm flex items-center justify-center gap-2 shadow-sm transition-all duration-150">
                <span>Kirim Tautan Reset</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Back to Login Link -->
        <div class="pt-3 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center text-xs text-gray-600 hover:text-gray-900 font-medium transition-colors">
                Kembali ke Masuk
            </a>
        </div>
    </form>
</x-guest-layout>
