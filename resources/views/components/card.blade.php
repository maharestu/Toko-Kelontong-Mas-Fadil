@props([
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-xs border border-gray-100 p-4 ' . $class]) }}>
    {{ $slot }}
</div>

