@props(['type' => 'primary'])

@php
    $styles = [
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700',
        'outline' => 'bg-transparent border border-neutral-300 text-neutral-700 hover:bg-neutral-50',
        'danger'  => 'bg-red-600 text-white hover:bg-red-700',
    ];
    $class = $styles[$type] ?? $styles['primary'];
@endphp

<button {{ $attributes->merge(['class' => "px-4 py-2 rounded-md font-medium text-sm " . $class]) }}>
    {{ $slot }}
</button>