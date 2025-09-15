<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, maximum-scale=1, user-scalable=no">
    <title>P2PCoinSwap - Secure · Trusted · Fast</title>
    <meta name="description" content="P2PCoinSwap is a peer-to-peer cryptocurrency trading platform. Trade Bitcoin, USDT, Ethereum directly with users worldwide using 300+ payment methods.">
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="shortcut icon" type="image/png" href="/logo.png">
    <link rel="apple-touch-icon" href="/logo.png">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="rgba(255, 255, 255, 0.95)" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="rgba(15, 23, 42, 0.95)" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="P2PCoinSwap">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="P2PCoinSwap">
    <link rel="manifest" href="/build/manifest.webmanifest">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        }
    </script>
    <style>
        /* PWA Safe Area Support */
        .pt-safe-area-inset-top {
            padding-top: env(safe-area-inset-top);
        }
        .pb-safe-area-inset-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            scroll-behavior: smooth;
        }
        .glass { backdrop-filter: blur(20px); }

        /* Section transitions */
        section {
            position: relative;
        }

        /* Smooth fade between sections */
        .section-fade::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to bottom, transparent, rgba(15, 23, 42, 0.5));
            pointer-events: none;
        }

        /* 3D Floating Coins Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
            33% { transform: translateY(-20px) rotate(120deg) scale(1.1); }
            66% { transform: translateY(10px) rotate(240deg) scale(0.95); }
        }

        @keyframes float-reverse {
            0%, 100% { transform: translateY(0) rotate(360deg) scale(1); }
            33% { transform: translateY(15px) rotate(240deg) scale(0.95); }
            66% { transform: translateY(-25px) rotate(120deg) scale(1.05); }
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.4; filter: blur(2px); }
            50% { opacity: 0.7; filter: blur(1px); }
        }

        .floating-coin {
            position: absolute;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));
            transition: all 0.3s ease;
        }

        .floating-coin-reverse {
            animation: float-reverse 7s ease-in-out infinite;
        }

        .floating-coin:hover {
            filter: drop-shadow(0 15px 40px rgba(0,0,0,0.5));
            animation-play-state: paused;
        }

        /* 3D perspective effect */
        .hero-3d-container {
            perspective: 1000px;
            transform-style: preserve-3d;
        }

        .coin-btc { animation-delay: 0s; }
        .coin-eth { animation-delay: 1.5s; }
        .coin-usdt { animation-delay: 3s; }
        .coin-usdc { animation-delay: 4.5s; }

        /* Gradient orbs background */
        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.15;
            animation: pulse-glow 8s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-300">
    <!-- Navigation -->
    @include('components.navigation')

    <!-- Hero Section -->
    <section class="relative py-20 overflow-hidden">
        <!-- 3D Floating Coins Background -->
        <div class="absolute inset-0 hero-3d-container pointer-events-none">
            <!-- Gradient Orbs -->
            <div class="gradient-orb w-96 h-96 bg-emerald-600 -top-48 -left-48"></div>
            <div class="gradient-orb w-64 h-64 bg-cyan-500 top-32 right-0"></div>
            <div class="gradient-orb w-80 h-80 bg-slate-600 bottom-0 left-1/2"></div>

            <!-- Floating Bitcoin - Top Left -->
            <div class="floating-coin coin-btc top-20 left-10 opacity-20">
                <svg width="60" height="60" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <g fill-rule="evenodd">
                        <circle cx="16" cy="16" r="16" fill="#F7931A" opacity="0.9"/>
                        <path fill="white" fill-rule="nonzero" d="M23.189 14.02c.314-2.096-1.283-3.223-3.465-3.975l.708-2.84-1.728-.43-.69 2.765c-.454-.114-.92-.22-1.385-.326l.695-2.783L15.596 6l-.708 2.839c-.376-.086-.746-.17-1.104-.26l.002-.009-2.384-.595-.46 1.846s1.283.294 1.256.312c.7.175.826.638.805 1.006l-.806 3.235c.048.012.11.03.18.057l-.183-.045-1.13 4.532c-.086.212-.303.531-.793.41.018.025-1.256-.313-1.256-.313l-.858 1.978 2.25.561c.418.105.828.215 1.231.318l-.715 2.872 1.727.43.708-2.84c.472.127.93.245 1.378.357l-.706 2.828 1.728.43.715-2.866c2.948.558 5.164.333 6.097-2.333.752-2.146-.037-3.385-1.588-4.192 1.13-.26 1.98-1.003 2.207-2.538zm-3.95 5.538c-.533 2.147-4.148.986-5.32.695l.95-3.805c1.172.293 4.929.872 4.37 3.11zm.535-5.569c-.487 1.953-3.495.96-4.47.717l.86-3.45c.975.243 4.118.696 3.61 2.733z"/>
                    </g>
                </svg>
            </div>

            <!-- Floating Ethereum - Top Right -->
            <div class="floating-coin floating-coin-reverse coin-eth top-32 right-20 opacity-15">
                <svg width="48" height="48" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" fill-rule="evenodd">
                        <circle cx="16" cy="16" r="16" fill="#627EEA" opacity="0.9"/>
                        <g fill="#FFF" fill-rule="nonzero">
                            <path fill-opacity=".602" d="M16.498 4v8.87l7.497 3.35z"/>
                            <path d="M16.498 4L9 16.22l7.498-3.35z"/>
                            <path fill-opacity=".602" d="M16.498 21.968v6.027L24 17.616z"/>
                            <path d="M16.498 27.995v-6.028L9 17.616z"/>
                            <path fill-opacity=".2" d="M16.498 20.573l7.497-4.353-7.497-3.348z"/>
                            <path fill-opacity=".602" d="M9 16.22l7.498 4.353v-7.701z"/>
                        </g>
                    </g>
                </svg>
            </div>

            <!-- Floating USDT - Middle Left -->
            <div class="floating-coin coin-usdt top-1/2 left-5 opacity-10">
                <svg width="72" height="72" viewBox="2 1 22 22" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_usdt_float)">
                        <path d="M12.9375 22.5649C18.7533 22.5649 23.4679 17.8503 23.4679 12.0345C23.4679 6.21868 18.7533 1.50405 12.9375 1.50405C7.12173 1.50405 2.4071 6.21868 2.4071 12.0345C2.4071 17.8503 7.12173 22.5649 12.9375 22.5649Z" fill="#26A17B" opacity="0.9"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.203 12.9449V12.9436C14.1306 12.9489 13.7575 12.9712 12.9249 12.9712C12.2602 12.9712 11.7922 12.9515 11.6277 12.9436V12.9456C9.06878 12.833 7.15883 12.3875 7.15883 11.8543C7.15883 11.3219 9.06878 10.8763 11.6277 10.7618V12.502C11.7948 12.5138 12.274 12.5421 12.9361 12.5421C13.7305 12.5421 14.1287 12.5092 14.203 12.5026V10.7631C16.7566 10.877 18.662 11.3226 18.662 11.8543C18.662 12.3875 16.7566 12.8317 14.203 12.9449ZM14.203 10.5821V9.02496H17.7663V6.65035H8.06444V9.02496H11.6277V10.5815C8.73181 10.7144 6.55399 11.2883 6.55399 11.9754C6.55399 12.6626 8.73181 13.2358 11.6277 13.3694V18.3595H14.203V13.3681C17.0943 13.2351 19.2668 12.6619 19.2668 11.9754C19.2668 11.289 17.0943 10.7157 14.203 10.5821Z" fill="white"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_usdt_float">
                            <rect width="21.0608" height="21.0608" fill="white" transform="translate(2.40912 1.50471)"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>

            <!-- Floating USDC - Middle Right -->
            <div class="floating-coin floating-coin-reverse coin-usdc top-60 right-10 opacity-20">
                <svg width="56" height="56" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none">
                        <circle cx="16" cy="16" r="16" fill="#2775CA" opacity="0.9"/>
                        <path fill="#FFF" d="M20.022 18.124c0-2.124-1.28-2.852-3.84-3.156-1.828-.243-2.193-.728-2.193-1.578 0-.85.61-1.396 1.828-1.396 1.097 0 1.707.364 2.011 1.275a.458.458 0 00.427.303h.975a.416.416 0 00.427-.425v-.06a3.04 3.04 0 00-2.743-2.489V9.142c0-.243-.183-.425-.487-.486h-.915c-.243 0-.426.182-.487.486v1.396c-1.829.242-2.986 1.456-2.986 2.974 0 2.002 1.218 2.791 3.778 3.095 1.707.303 2.255.668 2.255 1.639 0 .97-.853 1.638-2.011 1.638-1.585 0-2.133-.667-2.316-1.578-.06-.242-.244-.364-.427-.364h-1.036a.416.416 0 00-.426.425v.06c.243 1.518 1.219 2.61 3.023 2.914v1.457c0 .242.183.425.487.485h.915c.243 0 .426-.182.487-.485V21.34c1.829-.303 3.047-1.578 3.047-3.217z"/>
                        <path fill="#FFF" d="M12.892 24.497c-4.754-1.7-7.192-6.98-5.424-11.653.914-2.55 2.925-4.491 5.424-5.402.244-.121.365-.303.365-.607v-.85c0-.242-.121-.424-.365-.485-.061 0-.183 0-.244.06a10.895 10.895 0 00-7.13 13.717c1.096 3.4 3.717 6.01 7.13 7.102.244.121.488 0 .548-.243.061-.06.061-.122.061-.243v-.85c0-.182-.182-.424-.365-.546zm6.46-18.936c-.244-.122-.488 0-.548.242-.061.061-.061.122-.061.243v.85c0 .243.182.485.365.607 4.754 1.7 7.192 6.98 5.424 11.653-.914 2.55-2.925 4.491-5.424 5.402-.244.121-.365.303-.365.607v.85c0 .242.121.424.365.485.061 0 .183 0 .244-.06a10.895 10.895 0 007.13-13.717c-1.096-3.46-3.778-6.07-7.13-7.162z"/>
                    </g>
                </svg>
            </div>

            <!-- Additional smaller floating coins -->
            <div class="floating-coin top-16 left-1/3 opacity-10">
                <svg width="40" height="40" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="16" cy="16" r="16" fill="#F7931A" opacity="0.6"/>
                </svg>
            </div>

            <div class="floating-coin floating-coin-reverse bottom-32 right-1/4 opacity-15">
                <svg width="36" height="36" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="16" cy="16" r="16" fill="#627EEA" opacity="0.6"/>
                </svg>
            </div>

            <div class="floating-coin bottom-20 left-1/4 opacity-10">
                <svg width="44" height="44" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="16" cy="16" r="16" fill="#26A17B" opacity="0.6"/>
                </svg>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-5xl md:text-7xl font-bold mb-4 tracking-tight">
                        <span class="text-emerald-500">Buy</span>
                        <span class="text-white">&</span>
                        <span class="text-blue-500">Sell</span>
                        <span class="text-white">Crypto</span>
                    </h1>
                    <div class="flex items-center justify-center gap-3 text-slate-400">
                        <div class="h-px w-12 bg-slate-600"></div>
                        <span class="text-lg md:text-2xl font-medium tracking-wide uppercase">Escrow-based P2P</span>
                        <div class="h-px w-12 bg-slate-600"></div>
                    </div>
                </div>
                <p class="text-xl text-slate-400 mb-12 max-w-2xl mx-auto leading-relaxed">
                    Buy and sell Bitcoin, USDT, Ethereum directly with verified traders.
                    300+ payment methods. Fast, secure, private.
                </p>

                <!-- Quick Action Button -->
                <div class="flex justify-center mb-16">
                    <a href="/market" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-semibold text-lg transition transform hover:scale-105 cursor-pointer">
                        Start Trading Now
                    </a>
                </div>

                <!-- Crypto Showcase -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-2xl mx-auto">
                    <div class="text-center opacity-80 hover:opacity-100 transition cursor-pointer transform hover:scale-110">
                        <!-- Bitcoin SVG -->
                        <svg width="48" height="48" class="mx-auto mb-2" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <g fill-rule="evenodd">
                                <circle cx="16" cy="16" r="16" fill="#F7931A"/>
                                <path fill="white" fill-rule="nonzero" d="M23.189 14.02c.314-2.096-1.283-3.223-3.465-3.975l.708-2.84-1.728-.43-.69 2.765c-.454-.114-.92-.22-1.385-.326l.695-2.783L15.596 6l-.708 2.839c-.376-.086-.746-.17-1.104-.26l.002-.009-2.384-.595-.46 1.846s1.283.294 1.256.312c.7.175.826.638.805 1.006l-.806 3.235c.048.012.11.03.18.057l-.183-.045-1.13 4.532c-.086.212-.303.531-.793.41.018.025-1.256-.313-1.256-.313l-.858 1.978 2.25.561c.418.105.828.215 1.231.318l-.715 2.872 1.727.43.708-2.84c.472.127.93.245 1.378.357l-.706 2.828 1.728.43.715-2.866c2.948.558 5.164.333 6.097-2.333.752-2.146-.037-3.385-1.588-4.192 1.13-.26 1.98-1.003 2.207-2.538zm-3.95 5.538c-.533 2.147-4.148.986-5.32.695l.95-3.805c1.172.293 4.929.872 4.37 3.11zm.535-5.569c-.487 1.953-3.495.96-4.47.717l.86-3.45c.975.243 4.118.696 3.61 2.733z"/>
                            </g>
                        </svg>
                        <span class="text-xs text-slate-400 font-medium">Bitcoin</span>
                    </div>
                    <div class="text-center opacity-80 hover:opacity-100 transition cursor-pointer transform hover:scale-110">
                        <!-- Ethereum SVG -->
                        <svg width="48" height="48" class="mx-auto mb-2" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                            <g fill="none" fill-rule="evenodd">
                                <circle cx="16" cy="16" r="16" fill="#627EEA"/>
                                <g fill="#FFF" fill-rule="nonzero">
                                    <path fill-opacity=".602" d="M16.498 4v8.87l7.497 3.35z"/>
                                    <path d="M16.498 4L9 16.22l7.498-3.35z"/>
                                    <path fill-opacity=".602" d="M16.498 21.968v6.027L24 17.616z"/>
                                    <path d="M16.498 27.995v-6.028L9 17.616z"/>
                                    <path fill-opacity=".2" d="M16.498 20.573l7.497-4.353-7.497-3.348z"/>
                                    <path fill-opacity=".602" d="M9 16.22l7.498 4.353v-7.701z"/>
                                </g>
                            </g>
                        </svg>
                        <span class="text-xs text-slate-400 font-medium">Ethereum</span>
                    </div>
                    <div class="text-center opacity-80 hover:opacity-100 transition cursor-pointer transform hover:scale-110">
                        <!-- USDT SVG -->
                        <svg width="48" height="48" class="mx-auto mb-2" viewBox="2 1 22 22" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_usdt_landing)">
                                <path d="M12.9375 22.5649C18.7533 22.5649 23.4679 17.8503 23.4679 12.0345C23.4679 6.21868 18.7533 1.50405 12.9375 1.50405C7.12173 1.50405 2.4071 6.21868 2.4071 12.0345C2.4071 17.8503 7.12173 22.5649 12.9375 22.5649Z" fill="#26A17B"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.203 12.9449V12.9436C14.1306 12.9489 13.7575 12.9712 12.9249 12.9712C12.2602 12.9712 11.7922 12.9515 11.6277 12.9436V12.9456C9.06878 12.833 7.15883 12.3875 7.15883 11.8543C7.15883 11.3219 9.06878 10.8763 11.6277 10.7618V12.502C11.7948 12.5138 12.274 12.5421 12.9361 12.5421C13.7305 12.5421 14.1287 12.5092 14.203 12.5026V10.7631C16.7566 10.877 18.662 11.3226 18.662 11.8543C18.662 12.3875 16.7566 12.8317 14.203 12.9449ZM14.203 10.5821V9.02496H17.7663V6.65035H8.06444V9.02496H11.6277V10.5815C8.73181 10.7144 6.55399 11.2883 6.55399 11.9754C6.55399 12.6626 8.73181 13.2358 11.6277 13.3694V18.3595H14.203V13.3681C17.0943 13.2351 19.2668 12.6619 19.2668 11.9754C19.2668 11.289 17.0943 10.7157 14.203 10.5821Z" fill="white"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_usdt_landing">
                                    <rect width="21.0608" height="21.0608" fill="white" transform="translate(2.40912 1.50471)"/>
                                </clipPath>
                            </defs>
                        </svg>
                        <span class="text-xs text-slate-400 font-medium">USDT</span>
                    </div>
                    <div class="text-center opacity-80 hover:opacity-100 transition cursor-pointer transform hover:scale-110">
                        <!-- USDC SVG -->
                        <svg width="48" height="48" class="mx-auto mb-2" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                            <g fill="none">
                                <circle cx="16" cy="16" r="16" fill="#2775CA"/>
                                <path fill="#FFF" d="M20.022 18.124c0-2.124-1.28-2.852-3.84-3.156-1.828-.243-2.193-.728-2.193-1.578 0-.85.61-1.396 1.828-1.396 1.097 0 1.707.364 2.011 1.275a.458.458 0 00.427.303h.975a.416.416 0 00.427-.425v-.06a3.04 3.04 0 00-2.743-2.489V9.142c0-.243-.183-.425-.487-.486h-.915c-.243 0-.426.182-.487.486v1.396c-1.829.242-2.986 1.456-2.986 2.974 0 2.002 1.218 2.791 3.778 3.095 1.707.303 2.255.668 2.255 1.639 0 .97-.853 1.638-2.011 1.638-1.585 0-2.133-.667-2.316-1.578-.06-.242-.244-.364-.427-.364h-1.036a.416.416 0 00-.426.425v.06c.243 1.518 1.219 2.61 3.023 2.914v1.457c0 .242.183.425.487.485h.915c.243 0 .426-.182.487-.485V21.34c1.829-.303 3.047-1.578 3.047-3.217z"/>
                                <path fill="#FFF" d="M12.892 24.497c-4.754-1.7-7.192-6.98-5.424-11.653.914-2.55 2.925-4.491 5.424-5.402.244-.121.365-.303.365-.607v-.85c0-.242-.121-.424-.365-.485-.061 0-.183 0-.244.06a10.895 10.895 0 00-7.13 13.717c1.096 3.4 3.717 6.01 7.13 7.102.244.121.488 0 .548-.243.061-.06.061-.122.061-.243v-.85c0-.182-.182-.424-.365-.546zm6.46-18.936c-.244-.122-.488 0-.548.242-.061.061-.061.122-.061.243v.85c0 .243.182.485.365.607 4.754 1.7 7.192 6.98 5.424 11.653-.914 2.55-2.925 4.491-5.424 5.402-.244.121-.365.303-.365.607v.85c0 .242.121.424.365.485.061 0 .183 0 .244-.06a10.895 10.895 0 007.13-13.717c-1.096-3.46-3.778-6.07-7.13-7.162z"/>
                            </g>
                        </svg>
                        <span class="text-xs text-slate-400 font-medium">USDC</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 relative overflow-hidden">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(16, 185, 129, 0.15) 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        <!-- Floating Crypto Elements -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-10 left-10 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute bottom-20 right-20 w-32 h-32 bg-blue-500/10 rounded-full blur-xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-amber-500/10 rounded-full blur-xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Why Choose P2P Trading?</h2>
                <p class="text-lg text-slate-400">Direct trading with real people worldwide</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Secure Escrow Card -->
                <div class="group relative h-full">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-emerald-700/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="relative h-full bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-8 hover:border-emerald-600/30 transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 bg-emerald-600/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">Secure Escrow</h3>
                        <p class="text-slate-400 text-sm leading-relaxed flex-grow">Funds held safely in blockchain escrow addresses until both parties complete the trade successfully</p>
                        <div class="mt-6 pt-6 border-t border-slate-800/50">
                            <div class="flex items-center text-emerald-500 text-xs font-medium">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                100% Protected
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Better Rates Card -->
                <div class="group relative h-full">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-blue-700/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="relative h-full bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-8 hover:border-blue-600/30 transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 bg-blue-600/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">Better Rates</h3>
                        <p class="text-slate-400 text-sm leading-relaxed flex-grow">Get competitive rates by trading directly with other users, no middleman fees</p>
                        <div class="mt-6 pt-6 border-t border-slate-800/50">
                            <div class="flex items-center text-blue-500 text-xs font-medium">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                </svg>
                                Real-time Pricing
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flexible Payment Card -->
                <div class="group relative h-full">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-600/20 to-amber-700/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="relative h-full bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-8 hover:border-amber-600/30 transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 bg-amber-600/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2m-2-4h4m-4 0l-3-3m3 3l-3 3M9 8H7a2 2 0 00-2 2v6a2 2 0 002 2h2m2-4H5m4 0l3-3m-3 3l3 3"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">300+ Payment Methods</h3>
                        <p class="text-slate-400 text-sm leading-relaxed flex-grow">Bank transfer, PayPal, Revolut, Wise, cash, and hundreds more payment options</p>
                        <div class="mt-6 pt-6 border-t border-slate-800/50">
                            <div class="flex items-center text-amber-500 text-xs font-medium">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                            Global Coverage
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </section>

    <!-- Crypto-Themed Geometric Divider -->
    <div class="relative py-8">
        <div class="absolute inset-0 flex items-center justify-center">
            <!-- Static crypto icons decoration -->
            <div class="flex gap-16 opacity-10">
                <!-- Bitcoin -->
                <svg width="32" height="32" viewBox="0 0 32 32" class="text-amber-500">
                    <g fill="none" fill-rule="evenodd">
                        <circle cx="16" cy="16" r="16" fill="currentColor" opacity="0.2"/>
                        <path fill="currentColor" fill-rule="nonzero" d="M23.189 14.02c.314-2.096-1.283-3.223-3.465-3.975l.708-2.84-1.728-.43-.69 2.765c-.454-.114-.92-.22-1.385-.326l.695-2.783L15.596 6l-.708 2.839c-.376-.086-.746-.17-1.104-.26l.002-.009-2.384-.595-.46 1.846s1.283.294 1.256.312c.7.175.826.638.805 1.006l-.806 3.235c.048.012.11.03.18.057l-.183-.045-1.13 4.532c-.086.212-.303.531-.793.41.018.025-1.256-.313-1.256-.313l-.858 1.978 2.25.561c.418.105.828.215 1.231.318l-.715 2.872 1.727.43.708-2.84c.472.127.93.245 1.378.357l-.706 2.828 1.728.43.715-2.866c2.948.558 5.164.333 6.097-2.333.752-2.146-.037-3.385-1.588-4.192 1.13-.26 1.98-1.003 2.207-2.538zm-3.95 5.538c-.533 2.147-4.148.986-5.32.695l.95-3.805c1.172.293 4.929.872 4.37 3.11zm.535-5.569c-.487 1.953-3.495.96-4.47.717l.86-3.45c.975.243 4.118.696 3.61 2.733z"/>
                    </g>
                </svg>
                <!-- Ethereum -->
                <svg width="32" height="32" viewBox="0 0 32 32" class="text-blue-500">
                    <g fill="none" fill-rule="evenodd">
                        <circle cx="16" cy="16" r="16" fill="currentColor" opacity="0.2"/>
                        <g fill="currentColor" fill-rule="nonzero">
                            <path fill-opacity=".602" d="M16.498 4v8.87l7.497 3.35z"/>
                            <path d="M16.498 4L9 16.22l7.498-3.35z"/>
                            <path fill-opacity=".602" d="M16.498 21.968v6.027L24 17.616z"/>
                            <path d="M16.498 27.995v-6.028L9 17.616z"/>
                            <path fill-opacity=".2" d="M16.498 20.573l7.497-4.353-7.497-3.348z"/>
                            <path fill-opacity=".602" d="M9 16.22l7.498 4.353v-7.701z"/>
                        </g>
                    </g>
                </svg>
                <!-- USDT -->
                <svg width="32" height="32" viewBox="2 1 22 22" class="text-emerald-500">
                    <g clip-path="url(#clip0_divider_usdt)">
                        <circle cx="12.9375" cy="12.0345" r="10.5304" fill="currentColor" opacity="0.2"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.203 12.9449V12.9436C14.1306 12.9489 13.7575 12.9712 12.9249 12.9712C12.2602 12.9712 11.7922 12.9515 11.6277 12.9436V12.9456C9.06878 12.833 7.15883 12.3875 7.15883 11.8543C7.15883 11.3219 9.06878 10.8763 11.6277 10.7618V12.502C11.7948 12.5138 12.274 12.5421 12.9361 12.5421C13.7305 12.5421 14.1287 12.5092 14.203 12.5026V10.7631C16.7566 10.877 18.662 11.3226 18.662 11.8543C18.662 12.3875 16.7566 12.8317 14.203 12.9449ZM14.203 10.5821V9.02496H17.7663V6.65035H8.06444V9.02496H11.6277V10.5815C8.73181 10.7144 6.55399 11.2883 6.55399 11.9754C6.55399 12.6626 8.73181 13.2358 11.6277 13.3694V18.3595H14.203V13.3681C17.0943 13.2351 19.2668 12.6619 19.2668 11.9754C19.2668 11.289 17.0943 10.7157 14.203 10.5821Z" fill="currentColor"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_divider_usdt">
                            <rect width="21.0608" height="21.0608" fill="white" transform="translate(2.40912 1.50471)"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
        </div>
        <svg class="w-full h-16 relative" viewBox="0 0 1440 60" preserveAspectRatio="none">
            <defs>
                <linearGradient id="trade-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:rgb(59,130,246);stop-opacity:0" />
                    <stop offset="25%" style="stop-color:rgb(59,130,246);stop-opacity:0.2" />
                    <stop offset="50%" style="stop-color:rgb(16,185,129);stop-opacity:0.3" />
                    <stop offset="75%" style="stop-color:rgb(59,130,246);stop-opacity:0.2" />
                    <stop offset="100%" style="stop-color:rgb(59,130,246);stop-opacity:0" />
                </linearGradient>
            </defs>
            <polygon points="0,30 360,10 720,25 1080,10 1440,30 1440,60 0,60" fill="url(#trade-gradient)"></polygon>
            <polyline points="0,30 360,10 720,25 1080,10 1440,30" fill="none" stroke="rgba(16,185,129,0.3)" stroke-width="1"></polyline>

            <!-- Crypto icons at peak points -->
            <!-- Bitcoin at first peak -->
            <g transform="translate(344, -6)" opacity="0.3">
                <g transform="scale(1)">
                    <circle cx="16" cy="16" r="16" fill="#F7931A" opacity="0.3"/>
                    <path fill="#FFF" d="M23.189 14.02c.314-2.096-1.283-3.223-3.465-3.975l.708-2.84-1.728-.43-.69 2.765c-.454-.114-.92-.22-1.385-.326l.695-2.783L15.596 6l-.708 2.839c-.376-.086-.746-.17-1.104-.26l.002-.009-2.384-.595-.46 1.846s1.283.294 1.256.312c.7.175.826.638.805 1.006l-.806 3.235c.048.012.11.03.18.057l-.183-.045-1.13 4.532c-.086.212-.303.531-.793.41.018.025-1.256-.313-1.256-.313l-.858 1.978 2.25.561c.418.105.828.215 1.231.318l-.715 2.872 1.727.43.708-2.84c.472.127.93.245 1.378.357l-.706 2.828 1.728.43.715-2.866c2.948.558 5.164.333 6.097-2.333.752-2.146-.037-3.385-1.588-4.192 1.13-.26 1.98-1.003 2.207-2.538zm-3.95 5.538c-.533 2.147-4.148.986-5.32.695l.95-3.805c1.172.293 4.929.872 4.37 3.11zm.535-5.569c-.487 1.953-3.495.96-4.47.717l.86-3.45c.975.243 4.118.696 3.61 2.733z"/>
                </g>
            </g>

            <!-- Ethereum at middle peak -->
            <g transform="translate(704, 9)" opacity="0.3">
                <g transform="scale(1)">
                    <circle cx="16" cy="16" r="16" fill="#627EEA" opacity="0.3"/>
                    <g fill="#FFF">
                        <path fill-opacity=".602" d="M16.498 4v8.87l7.497 3.35z"/>
                        <path d="M16.498 4L9 16.22l7.498-3.35z"/>
                        <path fill-opacity=".602" d="M16.498 21.968v6.027L24 17.616z"/>
                        <path d="M16.498 27.995v-6.028L9 17.616z"/>
                        <path fill-opacity=".2" d="M16.498 20.573l7.497-4.353-7.497-3.348z"/>
                        <path fill-opacity=".602" d="M9 16.22l7.498 4.353v-7.701z"/>
                    </g>
                </g>
            </g>

            <!-- USDT at third peak -->
            <g transform="translate(1064, -6)" opacity="0.3">
                <g transform="scale(1.3)">
                    <circle cx="12.9375" cy="12.0345" r="10.5304" fill="#26A17B" opacity="0.3"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.203 12.9449V12.9436C14.1306 12.9489 13.7575 12.9712 12.9249 12.9712C12.2602 12.9712 11.7922 12.9515 11.6277 12.9436V12.9456C9.06878 12.833 7.15883 12.3875 7.15883 11.8543C7.15883 11.3219 9.06878 10.8763 11.6277 10.7618V12.502C11.7948 12.5138 12.274 12.5421 12.9361 12.5421C13.7305 12.5421 14.1287 12.5092 14.203 12.5026V10.7631C16.7566 10.877 18.662 11.3226 18.662 11.8543C18.662 12.3875 16.7566 12.8317 14.203 12.9449ZM14.203 10.5821V9.02496H17.7663V6.65035H8.06444V9.02496H11.6277V10.5815C8.73181 10.7144 6.55399 11.2883 6.55399 11.9754C6.55399 12.6626 8.73181 13.2358 11.6277 13.3694V18.3595H14.203V13.3681C17.0943 13.2351 19.2668 12.6619 19.2668 11.9754C19.2668 11.289 17.0943 10.7157 14.203 10.5821Z" fill="white"/>
                </g>
                <animateTransform attributeName="transform" type="scale" values="1;1.1;1" dur="3s" repeatCount="indefinite" additive="sum"/>
            </g>
        </svg>
    </div>

    <!-- How it Works - Animated Trade Flow -->
    <section id="how" class="py-16 overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Live Trade Flow</h2>
                <p class="text-lg text-slate-400">Experience a real-time trade simulation with system notifications</p>
            </div>

            <!-- Trade Simulation Container -->
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                    <!-- Left Side - Trade Steps -->
                    <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-6 flex flex-col">
                        <!-- Trade Info Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800/50">
                            <div>
                                <h3 class="text-white font-semibold">Trade Progress</h3>
                                <p class="text-xs text-slate-400 mt-1">Order #2024112801</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-emerald-500 font-medium">LIVE DEMO</span>
                            </div>
                        </div>

                    <!-- Animated Steps -->
                    <div class="space-y-4">
                        <!-- Step 1: Order Created -->
                        <div class="trade-step" style="animation-delay: 0s;">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-emerald-600/20 rounded-full flex items-center justify-center">
                                        <span class="text-emerald-500 font-bold">1</span>
                                    </div>
                                    <div class="absolute top-10 left-5 w-0.5 h-12 bg-slate-700"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-white font-medium">Order Created</h4>
                                        <span class="text-xs text-slate-500 time-stamp">18:10</span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1">Buyer initiates trade for 100 USDT</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Vendor Confirms -->
                        <div class="trade-step" style="animation-delay: 1s;">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-blue-600/20 rounded-full flex items-center justify-center">
                                        <span class="text-blue-500 font-bold">2</span>
                                    </div>
                                    <div class="absolute top-10 left-5 w-0.5 h-12 bg-slate-700"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-white font-medium">Vendor Confirms</h4>
                                        <span class="text-xs text-slate-500 time-stamp">18:11</span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1">Vendor accepts the trade request</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Seller Transfers to Escrow -->
                        <div class="trade-step" style="animation-delay: 2s;">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-amber-600/20 rounded-full flex items-center justify-center">
                                        <span class="text-amber-500 font-bold">3</span>
                                    </div>
                                    <div class="absolute top-10 left-5 w-0.5 h-12 bg-slate-700"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-white font-medium">Seller Transfers to Escrow</h4>
                                        <span class="text-xs text-slate-500 time-stamp">18:11</span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1">Seller sends crypto to escrow address</p>
                                    <div class="mt-2 p-2 bg-slate-800/50 rounded-lg">
                                        <p class="text-xs font-mono text-slate-500">TX: edcc3423...4284da</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Escrow Confirms -->
                        <div class="trade-step" style="animation-delay: 3s;">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-purple-600/20 rounded-full flex items-center justify-center">
                                        <span class="text-purple-500 font-bold">4</span>
                                    </div>
                                    <div class="absolute top-10 left-5 w-0.5 h-12 bg-slate-700"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-white font-medium">Escrow Confirmed</h4>
                                        <span class="text-xs text-slate-500 time-stamp">18:11</span>
                                        <span class="text-xs px-2 py-0.5 bg-purple-600/20 text-purple-400 rounded-full">Auto</span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1">Blockchain escrow verifies funds received</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Buyer Confirms Escrow -->
                        <div class="trade-step" style="animation-delay: 4s;">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-emerald-600/20 rounded-full flex items-center justify-center">
                                        <span class="text-emerald-500 font-bold">5</span>
                                    </div>
                                    <div class="absolute top-10 left-5 w-0.5 h-12 bg-slate-700"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-white font-medium">Buyer Confirms Escrow</h4>
                                        <span class="text-xs text-slate-500 time-stamp">18:11</span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1">Buyer verifies escrow balance</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 6: Buyer Pays -->
                        <div class="trade-step" style="animation-delay: 5s;">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-blue-600/20 rounded-full flex items-center justify-center">
                                        <span class="text-blue-500 font-bold">6</span>
                                    </div>
                                    <div class="absolute top-10 left-5 w-0.5 h-12 bg-slate-700"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-white font-medium">Buyer Sends Payment</h4>
                                        <span class="text-xs text-slate-500 time-stamp">18:12</span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1">Buyer sends payment via selected method</p>
                                    <div class="mt-2 flex gap-2">
                                        <span class="text-xs px-2 py-1 bg-blue-600/20 text-blue-400 rounded">Bank Transfer</span>
                                        <span class="text-xs px-2 py-1 bg-emerald-600/20 text-emerald-400 rounded">$100.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 7: Seller Confirms Receipt -->
                        <div class="trade-step" style="animation-delay: 6s;">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-amber-600/20 rounded-full flex items-center justify-center">
                                        <span class="text-amber-500 font-bold">7</span>
                                    </div>
                                    <div class="absolute top-10 left-5 w-0.5 h-12 bg-slate-700"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-white font-medium">Seller Confirms Receipt</h4>
                                        <span class="text-xs text-slate-500 time-stamp">18:12</span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1">Seller confirms payment received</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 8: Escrow Released -->
                        <div class="trade-step" style="animation-delay: 7s;">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center final-step">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-white font-medium">Escrow Released</h4>
                                        <span class="text-xs text-slate-500 time-stamp">18:12</span>
                                        <span class="text-xs px-2 py-0.5 bg-emerald-600/20 text-emerald-400 rounded-full">Complete</span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1">Crypto automatically sent to buyer's wallet</p>
                                    <div class="mt-3 p-3 bg-emerald-600/10 border border-emerald-600/30 rounded-lg">
                                        <p class="text-sm text-emerald-400 font-medium">✨ Trade completed successfully in 2 minutes!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trade Details -->
                    <div class="mt-8 pt-4 border-t border-slate-800">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Trade Information</h4>

                        <!-- Participants -->
                        <div class="space-y-2 pb-3 mb-3 border-b border-slate-800/50">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Buyer</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
                                        <span class="text-white text-[8px] font-bold">B</span>
                                    </div>
                                    <span class="text-xs font-medium text-white">BuyerUser</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Seller</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                        <span class="text-white text-[8px] font-bold">S</span>
                                    </div>
                                    <span class="text-xs font-medium text-white">SellerPro</span>
                                    <span class="text-[10px] px-1.5 py-0.5 bg-amber-600/20 text-amber-400 rounded">Vendor</span>
                                </div>
                            </div>
                        </div>

                        <!-- Trade Info -->
                        <div class="space-y-2 pb-3 mb-3 border-b border-slate-800/50">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Amount</span>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs font-medium text-white">100 USDT</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Rate</span>
                                <span class="text-xs font-medium text-white">$1.00/USDT</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Total</span>
                                <span class="text-sm font-bold text-amber-500">$100.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Fee (Vendor)</span>
                                <span class="text-xs text-amber-400">0.1 USDT</span>
                            </div>
                        </div>

                        <!-- Payment & Escrow -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Payment</span>
                                <span class="text-xs px-2 py-0.5 bg-blue-600/20 text-blue-400 rounded">Bank Transfer</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Escrow</span>
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                    <span class="text-xs font-mono text-emerald-400">0x3f2...8da</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Right Side - Chat Skeleton -->
                    <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl flex flex-col overflow-hidden">
                        <!-- Chat Header -->
                        <div class="px-6 py-4 border-b border-slate-800/50 bg-slate-900/80">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <!-- Two User Avatars -->
                                    <div class="flex -space-x-2">
                                        <div class="relative z-20">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center ring-2 ring-slate-900 bg-gradient-to-br from-emerald-500 to-emerald-600">
                                                <span class="text-white text-xs font-bold">B</span>
                                            </div>
                                            <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-slate-900 rounded-full"></div>
                                        </div>
                                        <div class="relative z-10">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center ring-2 ring-slate-900 bg-gradient-to-br from-blue-500 to-blue-600">
                                                <span class="text-white text-xs font-bold">S</span>
                                            </div>
                                            <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-slate-900 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-semibold text-sm">Trade Chat</h3>
                                        <p class="text-xs text-slate-400">Order #2024112801 • 2/2 online</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs px-2 py-1 bg-emerald-600/20 text-emerald-400 rounded-full">Secured</span>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages Container with scrollbar -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-800/50">
                            <!-- System Message - Order Created -->
                            <div class="chat-message flex justify-center" style="animation-delay: 0s;">
                                <div class="bg-slate-800/30 rounded-full px-3 py-1">
                                    <p class="text-xs text-slate-400">Trade started • 18:10</p>
                                </div>
                            </div>

                            <!-- Buyer Message -->
                            <div class="chat-message" style="animation-delay: 0.2s;">
                                <div class="flex items-start gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">B</span>
                                    </div>
                                    <div class="max-w-[70%]">
                                        <div class="bg-emerald-600/20 border border-emerald-600/30 rounded-2xl rounded-tl-sm px-4 py-2">
                                            <p class="text-sm text-slate-200">Hi, I want to buy 100 USDT</p>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 px-1">18:10</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Seller Message -->
                            <div class="chat-message" style="animation-delay: 0.5s;">
                                <div class="flex items-start gap-2 justify-end">
                                    <div class="max-w-[70%]">
                                        <div class="bg-blue-600/20 border border-blue-600/30 rounded-2xl rounded-tr-sm px-4 py-2">
                                            <p class="text-sm text-slate-200">Sure! I'm confirming the order now</p>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 px-1 text-right">18:11</p>
                                    </div>
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">S</span>
                                    </div>
                                </div>
                            </div>

                            <!-- System Notification -->
                            <div class="chat-message flex justify-center" style="animation-delay: 0.8s;">
                                <div class="bg-amber-600/10 border border-amber-600/30 rounded-full px-3 py-1">
                                    <p class="text-xs text-amber-400">🔒 Seller transferring to escrow...</p>
                                </div>
                            </div>

                            <!-- Seller Message with TX -->
                            <div class="chat-message" style="animation-delay: 1s;">
                                <div class="flex items-start gap-2 justify-end">
                                    <div class="max-w-[70%]">
                                        <div class="bg-blue-600/20 border border-blue-600/30 rounded-2xl rounded-tr-sm px-4 py-2">
                                            <p class="text-sm text-slate-200">Transferring USDT to escrow now</p>
                                            <div class="mt-2 p-2 bg-slate-900/50 rounded-lg">
                                                <p class="text-xs font-mono text-slate-400">TX: edcc3423...4284da</p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 px-1 text-right">18:11</p>
                                    </div>
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">S</span>
                                    </div>
                                </div>
                            </div>

                            <!-- System Success Notification -->
                            <div class="chat-message flex justify-center" style="animation-delay: 1.3s;">
                                <div class="bg-purple-600/10 border border-purple-600/30 rounded-full px-3 py-1">
                                    <p class="text-xs text-purple-400">✓ Escrow confirmed • Funds secured</p>
                                </div>
                            </div>

                            <!-- Buyer Message -->
                            <div class="chat-message" style="animation-delay: 1.5s;">
                                <div class="flex items-start gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">B</span>
                                    </div>
                                    <div class="max-w-[70%]">
                                        <div class="bg-emerald-600/20 border border-emerald-600/30 rounded-2xl rounded-tl-sm px-4 py-2">
                                            <p class="text-sm text-slate-200">Great! I can see the funds in escrow. Sending payment now via bank transfer</p>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 px-1">18:11</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Buyer Message with Attachment -->
                            <div class="chat-message" style="animation-delay: 1.8s;">
                                <div class="flex items-start gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">B</span>
                                    </div>
                                    <div class="max-w-[70%]">
                                        <div class="bg-emerald-600/20 border border-emerald-600/30 rounded-2xl rounded-tl-sm px-4 py-2">
                                            <p class="text-sm text-slate-200 mb-2">Payment sent!</p>
                                            <!-- Attachment Skeleton -->
                                            <div class="bg-slate-800/50 rounded-lg p-3 flex items-center gap-3">
                                                <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-xs text-slate-300 font-medium">payment_screenshot.jpg</p>
                                                    <p class="text-xs text-slate-500">256 KB</p>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex gap-2">
                                                <span class="text-xs px-2 py-1 bg-blue-600/20 text-blue-400 rounded">Bank Transfer</span>
                                                <span class="text-xs px-2 py-1 bg-emerald-600/20 text-emerald-400 rounded">$700.00</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 px-1">18:12</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Seller Message -->
                            <div class="chat-message" style="animation-delay: 2s;">
                                <div class="flex items-start gap-2 justify-end">
                                    <div class="max-w-[70%]">
                                        <div class="bg-blue-600/20 border border-blue-600/30 rounded-2xl rounded-tr-sm px-4 py-2">
                                            <p class="text-sm text-slate-200">Let me check my bank account...</p>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 px-1 text-right">18:12</p>
                                    </div>
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">S</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Seller Confirmation -->
                            <div class="chat-message" style="animation-delay: 2.3s;">
                                <div class="flex items-start gap-2 justify-end">
                                    <div class="max-w-[70%]">
                                        <div class="bg-blue-600/20 border border-blue-600/30 rounded-2xl rounded-tr-sm px-4 py-2">
                                            <p class="text-sm text-slate-200">Payment received! Releasing crypto now 👍</p>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 px-1 text-right">18:12</p>
                                    </div>
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">S</span>
                                    </div>
                                </div>
                            </div>

                            <!-- System Complete Notification -->
                            <div class="chat-message flex justify-center" style="animation-delay: 2.5s;">
                                <div class="bg-emerald-600/10 border border-emerald-600/30 rounded-full px-4 py-2">
                                    <p class="text-sm text-emerald-400 font-medium">🎉 Trade completed successfully!</p>
                                    <p class="text-xs text-slate-400 mt-1">100 USDT released • Total time: 2 minutes</p>
                                </div>
                            </div>

                            <!-- Buyer Thank You -->
                            <div class="chat-message" style="animation-delay: 2.8s;">
                                <div class="flex items-start gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">B</span>
                                    </div>
                                    <div class="max-w-[70%]">
                                        <div class="bg-emerald-600/20 border border-emerald-600/30 rounded-2xl rounded-tl-sm px-4 py-2">
                                            <p class="text-sm text-slate-200">Thank you! Great trade 👍</p>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 px-1">18:12</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Auto scroll to bottom spacer -->
                            <div id="chat-bottom"></div>
                        </div>

                        <!-- Chat Input Skeleton (Fixed at bottom) -->
                        <div class="px-4 py-3 border-t border-slate-800/50 bg-slate-900/80 mt-auto">
                            <div class="flex items-center gap-2">
                                <!-- Attachment Button -->
                                <button class="p-2 bg-slate-800/50 rounded-lg text-slate-400 hover:bg-slate-800 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                </button>
                                <!-- Input Field -->
                                <div class="flex-1 bg-slate-800/50 rounded-lg px-4 py-2">
                                    <p class="text-sm text-slate-400">Type a message...</p>
                                </div>
                                <!-- Send Button -->
                                <button class="p-2 bg-emerald-600/20 rounded-lg text-emerald-400 hover:bg-emerald-600/30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Animation Styles -->
        <style>
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

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.5;
                }
            }

            .trade-step {
                opacity: 0;
                animation: slideIn 0.6s ease-out forwards;
            }

            .chat-message {
                opacity: 0;
                animation: fadeInUp 0.5s ease-out forwards;
            }

            .final-step {
                box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
            }

            .time-stamp {
                opacity: 0;
                animation: slideIn 0.3s ease-out forwards;
                animation-delay: inherit;
            }
        </style>

        <!-- Auto-scroll chat to bottom -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const chatContainer = document.querySelector('.flex-1.overflow-y-auto');
                    if (chatContainer) {
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                    }
                }, 3000);
            });
        </script>
    </section>

    <!-- Curved Divider -->
    <div class="relative -mb-1">
        <svg class="w-full h-24" viewBox="0 0 1440 100" preserveAspectRatio="none">
            <defs>
                <linearGradient id="escrow-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:rgb(15,23,42);stop-opacity:0.3" />
                    <stop offset="50%" style="stop-color:rgb(16,185,129);stop-opacity:0.1" />
                    <stop offset="100%" style="stop-color:rgb(15,23,42);stop-opacity:0.3" />
                </linearGradient>
            </defs>
            <path d="M0,40 C360,80 1080,80 1440,40 L1440,100 L0,100 Z" fill="url(#escrow-gradient)"></path>
            <path d="M0,40 C360,80 1080,80 1440,40" fill="none" stroke="rgba(16,185,129,0.2)" stroke-width="2"></path>
        </svg>
    </div>

    <!-- Escrow Protection Section -->
    <section class="py-20 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-20 right-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-20 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">How Escrow Protection Works</h2>
                <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                    Your funds are protected throughout the entire trade process using blockchain escrow technology
                </p>
            </div>

            <!-- Escrow Process Flow -->
            <div class="max-w-5xl mx-auto">
                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <!-- Step 1 -->
                    <div class="relative">
                        <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-6 hover:border-emerald-600/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-medium text-emerald-500 uppercase tracking-wider">Step 1</span>
                                <div class="w-8 h-8 bg-emerald-600/20 rounded-full flex items-center justify-center">
                                    <span class="text-emerald-500 font-bold text-sm">1</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white mb-2">Trade Initiated</h3>
                            <p class="text-sm text-slate-400">Seller deposits crypto into a temporary blockchain escrow address</p>
                        </div>
                        <!-- Connecting Line -->
                        <div class="hidden md:block absolute top-1/2 -right-4 w-8 h-px bg-gradient-to-r from-slate-800 to-slate-700"></div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-6 hover:border-blue-600/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-medium text-blue-500 uppercase tracking-wider">Step 2</span>
                                <div class="w-8 h-8 bg-blue-600/20 rounded-full flex items-center justify-center">
                                    <span class="text-blue-500 font-bold text-sm">2</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white mb-2">Payment Exchange</h3>
                            <p class="text-sm text-slate-400">Buyer sends payment via chosen method, both parties confirm</p>
                        </div>
                        <!-- Connecting Line -->
                        <div class="hidden md:block absolute top-1/2 -right-4 w-8 h-px bg-gradient-to-r from-slate-800 to-slate-700"></div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-6 hover:border-amber-600/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-medium text-amber-500 uppercase tracking-wider">Step 3</span>
                                <div class="w-8 h-8 bg-amber-600/20 rounded-full flex items-center justify-center">
                                    <span class="text-amber-500 font-bold text-sm">3</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white mb-2">Automatic Release</h3>
                            <p class="text-sm text-slate-400">Escrow releases crypto to buyer's wallet instantly</p>
                        </div>
                    </div>
                </div>

                <!-- Escrow Features Grid -->
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-slate-900/40 backdrop-blur-sm rounded-2xl p-8 border border-slate-800/50">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-emerald-600/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white mb-2">Blockchain Security</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">Each trade is secured with a unique transaction hash on the blockchain, ensuring complete transparency and traceability</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900/40 backdrop-blur-sm rounded-2xl p-8 border border-slate-800/50">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-600/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white mb-2">Instant Settlement</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">Once both parties confirm, the blockchain escrow system automatically processes the release without manual intervention</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900/40 backdrop-blur-sm rounded-2xl p-8 border border-slate-800/50">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-amber-600/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white mb-2">Transaction History</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">Every escrow transaction is recorded on the blockchain, providing transparent and immutable trade records</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900/40 backdrop-blur-sm rounded-2xl p-8 border border-slate-800/50">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-purple-600/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white mb-2">Dispute Resolution</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">In case of disputes, our support team can review blockchain evidence and chat logs to ensure fair resolution</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Wave Divider -->
    <div class="relative">
        <svg class="w-full h-20 fill-current text-slate-900/50" preserveAspectRatio="none" viewBox="0 0 1440 54">
            <path d="M0,22L60,26.7C120,31,240,41,360,45.3C480,50,600,50,720,43.3C840,37,960,23,1080,18.3C1200,13,1320,17,1380,18.3L1440,20L1440,54L1380,54C1320,54,1200,54,1080,54C960,54,840,54,720,54C600,54,480,54,360,54C240,54,120,54,60,54L0,54Z"></path>
        </svg>
    </div>

    <!-- Security Section -->
    <section id="security" class="py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Multi-Layer Security Protection</h2>
                <p class="text-lg text-slate-400">Every trade is protected with multiple security measures</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- 2FA Authentication -->
                <div class="group relative h-full">
                    <div class="relative h-full bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-6 hover:border-emerald-600/30 transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 bg-emerald-600/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">2FA Authentication</h3>
                        <p class="text-sm text-slate-400 leading-relaxed flex-grow">Two-factor authentication adds an extra security layer to protect your account</p>
                    </div>
                </div>

                <!-- KYC Verification -->
                <div class="group relative h-full">
                    <div class="relative h-full bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-6 hover:border-blue-600/30 transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">KYC Verification</h3>
                        <p class="text-sm text-slate-400 leading-relaxed flex-grow">Identity verification ensures all traders are legitimate and trustworthy</p>
                    </div>
                </div>

                <!-- Dispute Resolution -->
                <div class="group relative h-full">
                    <div class="relative h-full bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-6 hover:border-amber-600/30 transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 bg-amber-600/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Dispute Resolution</h3>
                        <p class="text-sm text-slate-400 leading-relaxed flex-grow">Professional arbitration team reviews evidence to ensure fair outcomes</p>
                    </div>
                </div>

                <!-- Transaction Monitoring -->
                <div class="group relative h-full">
                    <div class="relative h-full bg-slate-900/60 backdrop-blur-sm border border-slate-800 rounded-2xl p-6 hover:border-purple-600/30 transition-all duration-300 flex flex-col">
                        <div class="w-14 h-14 bg-purple-600/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Transaction Monitoring</h3>
                        <p class="text-sm text-slate-400 leading-relaxed flex-grow">Real-time monitoring systems detect and prevent suspicious activities</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Subtle Wave Divider -->
    <div class="relative">
        <svg class="w-full h-20 fill-current text-slate-900/50" preserveAspectRatio="none" viewBox="0 0 1440 54">
            <path d="M0,22L60,26.7C120,31,240,41,360,45.3C480,50,600,50,720,43.3C840,37,960,23,1080,18.3C1200,13,1320,17,1380,18.3L1440,20L1440,54L1380,54C1320,54,1200,54,1080,54C960,54,840,54,720,54C600,54,480,54,360,54C240,54,120,54,60,54L0,54Z"></path>
        </svg>
    </div>

    <!-- CTA Section -->
    <section class="py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to Start Trading?</h2>
                <p class="text-xl text-slate-400 mb-8">
                    Join thousands of traders worldwide. Buy and sell crypto your way.
                </p>
                <div class="flex justify-center">
                    <a href="/market" class="px-10 py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 cursor-pointer">
                        Start Trading
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Divider -->
    <div class="relative">
        <svg class="w-full h-24" viewBox="0 0 1440 100" preserveAspectRatio="none">
            <defs>
                <linearGradient id="footer-gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" style="stop-color:rgb(15,23,42);stop-opacity:0" />
                    <stop offset="100%" style="stop-color:rgb(15,23,42);stop-opacity:1" />
                </linearGradient>
            </defs>
            <path d="M0,50 Q360,30 720,50 T1440,50 L1440,100 L0,100 Z"
                  fill="url(#footer-gradient)"/>
            <path d="M0,50 Q360,30 720,50 T1440,50"
                  fill="none" stroke="rgba(16,185,129,0.1)" stroke-width="1"/>
        </svg>
    </div>

    @include('components.footer')
</body>
</html>
