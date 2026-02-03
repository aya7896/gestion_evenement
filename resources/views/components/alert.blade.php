@props(['type' => 'info', 'message' => null])

@php
    $colors = [
        'success' => 'bg-green-50 border-green-400 text-green-800',
        'error'   => 'bg-red-50 border-red-400 text-red-800',
        'info'    => 'bg-blue-50 border-blue-400 text-blue-800',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
    ];
    $classes = $colors[$type] ?? $colors['info'];
@endphp

<div {{ $attributes->merge(['class' => "mb-6 border-l-4 p-4 rounded shadow-sm " . $classes]) }} role="alert">
    <div class="flex items-start">
        <div class="text-sm text-current">
            @if($message)
                <p>{{ $message }}</p>
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
</div>