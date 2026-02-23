{{-- resources/views/templates/tech/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $evenement->titre ?? 'Event')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Space Grotesk', monospace; }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: #0891b2; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #06b6d4; }
    </style>
    @yield('meta')
</head>
<body class="bg-black text-white antialiased">
    @include('landing.template 3.layouts.header')
    
    <main>
        @yield('content')
    </main>

    @include('landing.template 3.layouts.footer')
</body>
</html>
