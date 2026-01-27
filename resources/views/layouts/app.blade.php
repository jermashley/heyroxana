<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Invite')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0 opacity-30 [background-image:radial-gradient(circle,rgba(43,27,21,0.08)_2px,transparent_2px)] [background-size:26px_26px]"></div>
        <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-ember/20 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 h-80 w-80 -translate-x-1/3 translate-y-1/3 rounded-full bg-copper/30 blur-3xl"></div>

        <div class="relative z-10 px-4 py-10 sm:py-14">
            @yield('content')
        </div>
    </div>
</body>
</html>
