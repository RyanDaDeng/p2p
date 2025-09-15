@extends('layouts.app')

@section('title', 'P2PCoinSwap - Secure · Trusted · Fast')

@push('styles')
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
        }

        /* Gradient text animation */
        @keyframes gradient-x {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .gradient-text {
            background: linear-gradient(90deg, #10b981, #3b82f6, #10b981);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient-x 3s linear infinite;
        }

        /* Code-rain animation for tech section */
        @keyframes code-rain {
            0% { transform: translateY(-100%); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(100vh); opacity: 0; }
        }

        .code-rain {
            position: absolute;
            color: #10b981;
            font-family: monospace;
            font-size: 10px;
            animation: code-rain 8s linear infinite;
            opacity: 0.1;
        }

        /* Security shield pulse */
        @keyframes shield-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        .shield-pulse {
            animation: shield-pulse 2s ease-in-out infinite;
        }

        /* Smooth scroll indicator */
        @keyframes bounce-arrow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(10px); }
        }

        .bounce-arrow {
            animation: bounce-arrow 2s ease-in-out infinite;
        }

        /* Feature card hover effect */
        .feature-card {
            transition: all 0.3s ease;
            transform-style: preserve-3d;
        }

        .feature-card:hover {
            transform: translateY(-5px) rotateX(5deg);
        }

        /* Payment method logos hover */
        .payment-logo {
            transition: all 0.2s ease;
            filter: grayscale(0.5);
        }

        .payment-logo:hover {
            filter: grayscale(0);
            transform: scale(1.1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
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
        @keyframes loading-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .loading-pulse {
            animation: loading-pulse 1.5s ease-in-out infinite;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 overflow-hidden">
        <!-- 3D Floating Coins Background -->
        <div class="absolute inset-0 hero-3d-container pointer-events-none">
            <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/btc.svg"
                 class="floating-coin w-16 h-16 top-20 left-10" style="animation-delay: 0s;">
            <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/eth.svg"
                 class="floating-coin floating-coin-reverse w-12 h-12 top-40 right-20" style="animation-delay: 1s;">
            <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/usdt.svg"
                 class="floating-coin w-14 h-14 bottom-40 left-1/4" style="animation-delay: 2s;">
            <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/bnb.svg"
                 class="floating-coin floating-coin-reverse w-10 h-10 top-60 left-1/3" style="animation-delay: 3s;">
            <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/sol.svg"
                 class="floating-coin w-12 h-12 bottom-20 right-1/4" style="animation-delay: 4s;">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-5xl mx-auto text-center">
                <!-- Trust Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/10 border border-emerald-500/30 rounded-full mb-8">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm text-emerald-400 font-medium">Secured with Escrow Protection</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-5xl md:text-7xl font-bold mb-6">
                    <span class="text-white">Trade Crypto</span>
                    <br>
                    <span class="gradient-text">Peer-to-Peer</span>
                </h1>

                <p class="text-xl text-slate-400 mb-8 max-w-2xl mx-auto">
                    The most trusted P2P crypto trading platform. Buy and sell Bitcoin, USDT, ETH directly with users worldwide using 300+ payment methods.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="/market"
                       class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Start Trading Now
                    </a>
                    <a href="/trade-flow-guide"
                       class="px-8 py-4 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-semibold rounded-xl transition-all duration-200">
                        How It Works
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">$2.5B+</div>
                        <div class="text-sm text-slate-500">Trading Volume</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">500K+</div>
                        <div class="text-sm text-slate-500">Active Traders</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">50+</div>
                        <div class="text-sm text-slate-500">Cryptocurrencies</div>
                    </div>
                </div>

                <!-- Scroll Indicator -->
                <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 bounce-arrow">
                    <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Features Section -->
    <section class="py-20 bg-slate-900/50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Why Traders Trust Us</h2>
                <p class="text-lg text-slate-400">Industry-leading security and user protection</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Escrow Protection -->
                <div class="feature-card bg-slate-900/80 border border-slate-800 rounded-2xl p-8 hover:border-emerald-500/30 transition-all">
                    <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 shield-pulse">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Escrow Protection</h3>
                    <p class="text-slate-400">Your crypto is secured in escrow until both parties confirm the trade completion</p>
                </div>

                <!-- Dispute Resolution -->
                <div class="feature-card bg-slate-900/80 border border-slate-800 rounded-2xl p-8 hover:border-blue-500/30 transition-all">
                    <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">24/7 Support</h3>
                    <p class="text-slate-400">Professional dispute resolution team available round the clock</p>
                </div>

                <!-- KYC Verified -->
                <div class="feature-card bg-slate-900/80 border border-slate-800 rounded-2xl p-8 hover:border-purple-500/30 transition-all">
                    <div class="w-16 h-16 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Verified Traders</h3>
                    <p class="text-slate-400">Trade with confidence knowing all users are KYC verified</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 relative overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">How P2P Trading Works</h2>
                <p class="text-lg text-slate-400">Simple, secure, and fast</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <!-- Step 1 -->
                <div class="flex gap-8 mb-12">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                            1
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-xl font-semibold text-white mb-2">Choose Your Trade</h3>
                        <p class="text-slate-400">Browse offers from verified traders. Filter by payment method, price, and limits.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-8 mb-12">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            2
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-xl font-semibold text-white mb-2">Initiate Trade</h3>
                        <p class="text-slate-400">Enter amount and start the trade. Crypto is automatically locked in escrow.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-8 mb-12">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                            3
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-xl font-semibold text-white mb-2">Make Payment</h3>
                        <p class="text-slate-400">Follow seller's payment instructions and mark as paid when complete.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex gap-8">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold">
                            4
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-xl font-semibold text-white mb-2">Receive Crypto</h3>
                        <p class="text-slate-400">Seller confirms payment and releases crypto from escrow to your wallet.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Supported Cryptos Section -->
    <section class="py-20 bg-slate-900/50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Trade 50+ Cryptocurrencies</h2>
                <p class="text-lg text-slate-400">Popular coins with deep liquidity</p>
            </div>

            <div class="flex flex-wrap justify-center gap-6 max-w-4xl mx-auto">
                <div class="flex items-center gap-3 px-6 py-3 bg-slate-900/80 border border-slate-800 rounded-xl hover:border-emerald-500/30 transition-all">
                    <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/btc.svg" class="w-8 h-8">
                    <div>
                        <div class="text-white font-medium">Bitcoin</div>
                        <div class="text-xs text-slate-500">BTC</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-3 bg-slate-900/80 border border-slate-800 rounded-xl hover:border-emerald-500/30 transition-all">
                    <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/usdt.svg" class="w-8 h-8">
                    <div>
                        <div class="text-white font-medium">Tether</div>
                        <div class="text-xs text-slate-500">USDT</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-3 bg-slate-900/80 border border-slate-800 rounded-xl hover:border-emerald-500/30 transition-all">
                    <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/eth.svg" class="w-8 h-8">
                    <div>
                        <div class="text-white font-medium">Ethereum</div>
                        <div class="text-xs text-slate-500">ETH</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-3 bg-slate-900/80 border border-slate-800 rounded-xl hover:border-emerald-500/30 transition-all">
                    <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/bnb.svg" class="w-8 h-8">
                    <div>
                        <div class="text-white font-medium">BNB</div>
                        <div class="text-xs text-slate-500">BNB</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-3 bg-slate-900/80 border border-slate-800 rounded-xl hover:border-emerald-500/30 transition-all">
                    <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/sol.svg" class="w-8 h-8">
                    <div>
                        <div class="text-white font-medium">Solana</div>
                        <div class="text-xs text-slate-500">SOL</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-3 bg-slate-900/80 border border-slate-800 rounded-xl hover:border-emerald-500/30 transition-all">
                    <img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1279a46566019c4051b7d8def8bce02b937b9ee0/svg/color/usdc.svg" class="w-8 h-8">
                    <div>
                        <div class="text-white font-medium">USD Coin</div>
                        <div class="text-xs text-slate-500">USDC</div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="/market" class="text-emerald-400 hover:text-emerald-300 font-medium">
                    View all cryptocurrencies →
                </a>
            </div>
        </div>
    </section>

    <!-- Payment Methods Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">300+ Payment Methods</h2>
                <p class="text-lg text-slate-400">Trade with your preferred payment option</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 max-w-5xl mx-auto">
                <div class="payment-logo bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex items-center justify-center h-20 hover:border-emerald-500/30 transition-all">
                    <span class="text-white font-medium">Bank Transfer</span>
                </div>
                <div class="payment-logo bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex items-center justify-center h-20 hover:border-emerald-500/30 transition-all">
                    <span class="text-white font-medium">WeChat</span>
                </div>
                <div class="payment-logo bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex items-center justify-center h-20 hover:border-emerald-500/30 transition-all">
                    <span class="text-white font-medium">Alipay</span>
                </div>
                <div class="payment-logo bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex items-center justify-center h-20 hover:border-emerald-500/30 transition-all">
                    <span class="text-white font-medium">PayPal</span>
                </div>
                <div class="payment-logo bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex items-center justify-center h-20 hover:border-emerald-500/30 transition-all">
                    <span class="text-white font-medium">Wise</span>
                </div>
                <div class="payment-logo bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex items-center justify-center h-20 hover:border-emerald-500/30 transition-all">
                    <span class="text-white font-medium">Cash</span>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="/market" class="text-emerald-400 hover:text-emerald-300 font-medium">
                    Explore all payment methods →
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-emerald-900/20 to-blue-900/20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Start Trading?</h2>
            <p class="text-xl text-slate-400 mb-8 max-w-2xl mx-auto">
                Join millions of traders worldwide on the most trusted P2P platform
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/market"
                   class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40">
                    Start Trading Now
                </a>
                <a href="/contact-support"
                   class="px-8 py-4 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-semibold rounded-xl transition-all duration-200">
                    Contact Support
                </a>
            </div>
        </div>
    </section>

    <!-- Wave Divider -->
    <div class="relative h-32 overflow-hidden">
        <svg class="absolute bottom-0 w-full" viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path d="M0,50 Q360,30 720,50 T1440,50 L1440,100 L0,100 Z"
                  fill="rgba(15,23,42,0.5)"/>
            <path d="M0,50 Q360,30 720,50 T1440,50"
                  fill="none" stroke="rgba(16,185,129,0.1)" stroke-width="1"/>
        </svg>
    </div>
@endsection

@push('scripts')
    <script>
        // Enhanced smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add parallax effect to floating coins
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const coins = document.querySelectorAll('.floating-coin');
            coins.forEach((coin, index) => {
                const speed = 0.5 + (index * 0.1);
                coin.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Add intersection observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card').forEach(el => {
            observer.observe(el);
        });
    </script>
@endpush