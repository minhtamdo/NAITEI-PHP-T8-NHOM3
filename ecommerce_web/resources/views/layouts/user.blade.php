<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'E-Commerce Website')</title>
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
