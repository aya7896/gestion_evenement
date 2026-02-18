<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Evenement')</title>
    @php
        $themes = [
            'violet' => ['dark' => '#160755', 'primary' => '#482395', 'secondary' => '#925CC7', 'light' => '#BF8DDA', 'accent' => '#1F0A67', 'dark_rgb' => '22 7 85', 'primary_rgb' => '72 35 149', 'secondary_rgb' => '146 92 199', 'light_rgb' => '191 141 218'],
            'ocean' => ['dark' => '#062A3A', 'primary' => '#0D4D68', 'secondary' => '#1693A5', 'light' => '#7AD6E0', 'accent' => '#0A3B52', 'dark_rgb' => '6 42 58', 'primary_rgb' => '13 77 104', 'secondary_rgb' => '22 147 165', 'light_rgb' => '122 214 224'],
            'sunset' => ['dark' => '#3B1E14', 'primary' => '#8A3B12', 'secondary' => '#D96C2D', 'light' => '#F8B37A', 'accent' => '#5A2810', 'dark_rgb' => '59 30 20', 'primary_rgb' => '138 59 18', 'secondary_rgb' => '217 108 45', 'light_rgb' => '248 179 122'],
            'forest' => ['dark' => '#0F2E1F', 'primary' => '#1C5A35', 'secondary' => '#38A169', 'light' => '#9AE6B4', 'accent' => '#17492D', 'dark_rgb' => '15 46 31', 'primary_rgb' => '28 90 53', 'secondary_rgb' => '56 161 105', 'light_rgb' => '154 230 180'],
            'slate' => ['dark' => '#0F172A', 'primary' => '#1E293B', 'secondary' => '#475569', 'light' => '#94A3B8', 'accent' => '#334155', 'dark_rgb' => '15 23 42', 'primary_rgb' => '30 41 59', 'secondary_rgb' => '71 85 105', 'light_rgb' => '148 163 184'],
        ];

        $themeKey = $evenement->color_template ?? 'violet';
        $theme = $themes[$themeKey] ?? $themes['violet'];
    @endphp
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'conf-dark': '{{ $theme['dark'] }}',
                        'conf-primary': '{{ $theme['primary'] }}',
                        'conf-secondary': '{{ $theme['secondary'] }}',
                        'conf-light': '{{ $theme['light'] }}',
                        'conf-accent': '{{ $theme['accent'] }}',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --conf-dark: {{ $theme['dark'] }};
            --conf-primary: {{ $theme['primary'] }};
            --conf-secondary: {{ $theme['secondary'] }};
            --conf-light: {{ $theme['light'] }};
            --conf-accent: {{ $theme['accent'] }};
            --conf-dark-rgb: {{ $theme['dark_rgb'] }};
            --conf-primary-rgb: {{ $theme['primary_rgb'] }};
            --conf-secondary-rgb: {{ $theme['secondary_rgb'] }};
            --conf-light-rgb: {{ $theme['light_rgb'] }};
        }

        body {
            font-family: 'Poppins', sans-serif;
            background:
                radial-gradient(circle at 10% 10%, rgb(var(--conf-secondary-rgb) / 0.22), transparent 45%),
                radial-gradient(circle at 90% 20%, rgb(var(--conf-light-rgb) / 0.18), transparent 40%),
                linear-gradient(160deg, var(--conf-dark) 0%, var(--conf-accent) 60%, var(--conf-primary) 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, var(--conf-light) 0%, var(--conf-secondary) 50%, var(--conf-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gradient-bg {
            background: linear-gradient(135deg, var(--conf-secondary) 0%, var(--conf-primary) 100%);
        }
        .gradient-bg-dark {
            background: linear-gradient(135deg, var(--conf-dark) 0%, var(--conf-accent) 100%);
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgb(var(--conf-dark-rgb) / 0.55) 0%, rgb(var(--conf-primary-rgb) / 0.45) 50%, rgb(var(--conf-secondary-rgb) / 0.35) 100%);
        }
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgb(var(--conf-primary-rgb) / 0.4);
        }
        .btn-gradient {
            background: linear-gradient(135deg, var(--conf-secondary) 0%, var(--conf-primary) 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgb(var(--conf-secondary-rgb) / 0.4);
        }
        .btn-outline-glow {
            border: 1px solid rgb(var(--conf-light-rgb) / 0.5);
            background: rgb(var(--conf-dark-rgb) / 0.35);
            box-shadow: inset 0 0 0 1px rgb(var(--conf-light-rgb) / 0.1);
        }
        .btn-outline-glow:hover {
            background: rgb(var(--conf-light-rgb) / 0.12);
            border-color: rgb(var(--conf-light-rgb) / 0.8);
        }
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--conf-light) 0%, var(--conf-secondary) 100%);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .glass-effect {
            background: rgb(var(--conf-dark-rgb) / 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgb(var(--conf-light-rgb) / 0.2);
        }
        .glass-panel {
            background: linear-gradient(145deg, rgb(var(--conf-dark-rgb) / 0.55), rgb(var(--conf-primary-rgb) / 0.35));
            border: 1px solid rgb(var(--conf-light-rgb) / 0.24);
            backdrop-filter: blur(18px);
        }
        .section-title {
            letter-spacing: 0.08em;
            color: rgb(var(--conf-light-rgb) / 0.9);
        }
        .stat-chip {
            border: 1px solid rgb(var(--conf-light-rgb) / 0.35);
            background: rgb(var(--conf-dark-rgb) / 0.45);
            backdrop-filter: blur(14px);
        }
        .accent-ring {
            box-shadow: 0 0 0 1px rgb(var(--conf-light-rgb) / 0.2), 0 18px 45px rgb(var(--conf-primary-rgb) / 0.25);
        }
        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .scroll-reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .text-glow {
            text-shadow: 0 0 30px rgb(var(--conf-light-rgb) / 0.5);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .tab-active {
            background: linear-gradient(135deg, var(--conf-secondary) 0%, var(--conf-primary) 100%);
            color: white;
        }
        .hero-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(36px);
            opacity: 0.45;
            pointer-events: none;
        }
    </style>
    {{-- Page-specific meta (Open Graph / Twitter) --}}
    @yield('meta')
    @stack('styles')
</head>
<body class="bg-conf-dark text-white overflow-x-hidden">

    @include('landing.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('landing.layouts.footer')

    <script>
        // Scroll reveal animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.scroll-reveal').forEach((el) => observer.observe(el));

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>