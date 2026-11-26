<x-app-layout title="Profil Pengguna - Toko Mas Adil" active="lainnya">
    <div class="p-4 sm:p-5 space-y-4">
        <!-- Header & Back Link -->
        <div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 hover:text-black mb-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                <span>Menu Lainnya</span>
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Profil Pengguna</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola informasi akun, kata sandi, dan keamanan.</p>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-[0_2px_10px_-2px_rgba(0,0,0,0.04)]">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>