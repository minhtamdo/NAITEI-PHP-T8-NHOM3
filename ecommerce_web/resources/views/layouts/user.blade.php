<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'E-Commerce Website')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/user.css')
</head>
<body>
    @include('partials.user.header')

    <main class="container">
        @yield('content')
    </main>

    @include('partials.user.footer')
</body>
</html>
