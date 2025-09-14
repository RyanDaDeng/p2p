<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="/logo.png">
        <link rel="shortcut icon" type="image/png" href="/logo.png">

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#10b981">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="P2PCoinSwap">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="application-name" content="P2PCoinSwap">
        <meta name="msapplication-TileColor" content="#10b981">
        <meta name="msapplication-starturl" content="/market">
        <link rel="apple-touch-icon" href="/logo.png">
        <link rel="manifest" href="/build/manifest.webmanifest">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
