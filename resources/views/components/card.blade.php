<div {{ $attributes->merge(['class' => 'bg-white dark:bg-neutral-800 rounded-lg shadow-card p-5']) }}>
    @if(isset($title))
        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ $title }}</h3>
    @endif

    <div class="mt-3">
        {{ $slot }}
    </div>
</div>