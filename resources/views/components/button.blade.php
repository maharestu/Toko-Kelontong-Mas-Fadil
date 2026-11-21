@props([
    'variant' => 'primary',
    'type' => 'button',
    'size' => 'md',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs rounded-lg',
        'lg' => 'px-6 py-3 text-base rounded-xl',
        default => 'px-4 py-2.5 text-sm rounded-xl',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-[#133c30] hover:bg-[#0d2820] text-white focus:ring-[#133c30]',
        'secondary' => 'bg-gray-100 hover:bg-gray-200 text-gray-700 focus:ring-gray-300',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'outline' => 'border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 focus:ring-[#133c30]',
        default => 'bg-[#133c30] hover:bg-[#0d2820] text-white',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $variantClasses"]) }}>
    {{ $slot }}
</button>

