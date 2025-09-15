<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'P2PCoinSwap - Peer-to-Peer Cryptocurrency Trading Platform' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="shortcut icon" type="image/png" href="/logo.png">
    <link rel="apple-touch-icon" href="/logo.png">

    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $description ?? 'Trade cryptocurrencies directly with other users on our secure P2P platform. Buy and sell Bitcoin, Ethereum, USDT and more with escrow protection.' }}">
    <meta name="keywords" content="p2p crypto, cryptocurrency trading, bitcoin exchange, peer to peer, escrow, USDT, BTC, ETH">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $title ?? 'P2PCoinSwap' }}">
    <meta property="og:description" content="{{ $description ?? 'Secure P2P cryptocurrency trading platform' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Loading animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Custom animations */
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }


        .slide-in {
            animation: slideIn 0.6s ease-out;
        }
    </style>

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="bg-slate-950 text-slate-300 min-h-screen flex flex-col">
    <!-- Navigation -->
    @include('components.navigation')

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Scripts -->
    @stack('scripts')

    <!-- Global Scripts -->
    <script>
        // Dark mode is always on for this theme
        document.documentElement.classList.add('dark');
    </script>
</body>
</html>
