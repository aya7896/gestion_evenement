<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $evenement->titre ?? 'Evenement')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: system-ui, -apple-system, sans-serif; }
    </style>
    @yield('meta')
</head>
<body class="bg-black text-white antialiased">
    @include('landing.Template 2.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('landing.Template 2.layouts.footer')
</body>
</html>
