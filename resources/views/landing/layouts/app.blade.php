<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inscription')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(160deg, #160755 0%, #1F0A67 60%, #482395 100%);
        }
        .gradient-bg-dark {
            background: linear-gradient(135deg, #160755 0%, #1F0A67 100%);
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgb(22 7 85 / 0.55) 0%, rgb(72 35 149 / 0.45) 50%, rgb(146 92 199 / 0.35) 100%);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #925CC7 0%, #482395 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgb(146 92 199 / 0.35);
        }
        .text-glow {
            text-shadow: 0 0 22px rgb(191 141 218 / 0.45);
        }
        .scroll-reveal {
            opacity: 1;
            transform: none;
        }
    </style>
    @yield('meta')
    @stack('styles')
</head>
<body class="text-white overflow-x-hidden">
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
