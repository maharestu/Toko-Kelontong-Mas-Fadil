@props([
    'id',
    'title' => '',
    'maxWidth' => 'sm',
])

@php
    $maxWidthClass = match($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        default => 'max-w-sm',
    };
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity duration-200 hidden opacity-0" aria-modal="true" role="dialog">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full {{ $maxWidthClass }} overflow-hidden transform transition-all scale-95 duration-200" id="{{ $id }}-content">
        @if ($title)
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900">{{ $title }}</h3>
                <button type="button" onclick="closeModal('{{ $id }}')" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <div class="p-5">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="px-5 py-3.5 bg-gray-50/80 border-t border-gray-100 flex items-center justify-end gap-2">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>

