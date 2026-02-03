<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') • Gestion Events Stage</title>

    <!-- Fonts - Using distinctive typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js + persist -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'DM Sans', system-ui, sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Sora', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen antialiased transition-colors duration-300">

    <div x-data="{ 
        darkMode: Alpine.$persist(true).as('darkMode'),
        sidebarOpen: Alpine.$persist(true).as('sidebarOpen')
    }" x-cloak>

        <!-- Header fixe -->
        @include('layouts.header')

        <!-- Contenu avec sidebar -->
        <div class="flex pt-16 min-h-screen">

            <!-- Sidebar toggle -->
            @include('layouts.sidebar')

            <!-- Zone principale avec transition -->
            <main class="flex-1 overflow-y-auto bg-slate-50/70 dark:bg-slate-950/70 backdrop-blur-sm p-6 lg:p-8 transition-all duration-300"
                  :class="sidebarOpen ? 'lg:ml-72' : 'lg:ml-20'">
                @yield('content')
            </main>
        </div>

        <!-- Footer -->
        @include('layouts.footer')

    </div>

</body>
</html>