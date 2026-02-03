<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EventPro') - Gestion d'Événements</title>

    @php
        $manifest = public_path('build/manifest.json');
        $hasManifest = file_exists($manifest);
    @endphp

    @if(app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @elseif($hasManifest)
        @php $manifestContents = json_decode(file_get_contents($manifest), true); @endphp
        @if(isset($manifestContents['resources/css/app.css']['file']))
            <link rel="stylesheet" href="{{ asset('build/' . $manifestContents['resources/css/app.css']['file']) }}">
            <script type="module" src="{{ asset('build/' . $manifestContents['resources/js/app.js']['file']) }}"></script>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        html, body { height: 100%; }
        body { display: flex; flex-direction: column; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-neutral-50 to-neutral-100 dark:from-neutral-900 dark:to-neutral-800 min-h-screen flex items-center justify-center py-10">

    <div class="w-full max-w-md px-4 animate-fade-in">
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-2xl overflow-hidden hover:shadow-hover transition-all duration-300">
            <div class="p-8">
                <div class="flex justify-center mb-8">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-600 to-accent-500 flex items-center justify-center text-white animate-pulse-slow shadow-lg">
                        <i class="fas fa-calendar-alt text-3xl"></i>
                    </div>
                </div>

                {{ $slot }}

            </div>
        </div>

        <p class="mt-6 text-center text-sm text-neutral-500 dark:text-neutral-400 flex items-center justify-center gap-2">
            <i class="fas fa-heart text-red-500 animate-bounce-slow"></i>
            © {{ date('Y') }} EventPro - Gestion d'événements
            <i class="fas fa-heart text-red-500 animate-bounce-slow"></i>
        </p>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>