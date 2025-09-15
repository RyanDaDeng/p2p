<nav class="fixed top-0 w-full z-50 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800/50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo Section -->
            <div class="flex items-center">
                <a href="/" class="flex items-center group">
                    <img src="/logo.png" alt="P2PCoinSwap" class="h-8 w-8 group-hover:scale-110 transition-transform duration-200" />
                    <div class="hidden md:block ml-3">
                        <x-p2p-brand-text size="lg" />
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex ml-10 space-x-1">
                    <a href="/market" class="px-4 py-2 text-sm text-slate-300 hover:text-emerald-400 hover:bg-slate-800/50 rounded-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Buy Crypto
                    </a>
                    <a href="/market?type=sell" class="px-4 py-2 text-sm text-slate-300 hover:text-blue-400 hover:bg-slate-800/50 rounded-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Sell Crypto
                    </a>
                    <a href="/trade-flow-guide" class="px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all duration-200">
                        How it Works
                    </a>
                    <a href="/contact-support" class="px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all duration-200">
                        Contact Us
                    </a>
                </nav>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-4">
                <!-- Trust Indicator -->
                <div class="hidden lg:flex items-center gap-1 text-xs text-slate-500 mr-4">
                    <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>P2P</span>
                </div>

                <!-- Download App Button -->
                <a href="/app-download" class="flex items-center gap-1 px-2.5 py-1 bg-gray-700/40 backdrop-blur-sm border border-gray-500/40 hover:bg-gray-600/50 hover:border-pink-500/30 text-gray-300 hover:text-pink-300 rounded-lg transition-all cursor-pointer" title="Install APP">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs font-medium">APP</span>
                </a>

                <!-- Start Trading Button -->
                <a href="/market" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40">
                    Start Trading
                </a>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg transition-colors">
                    <!-- Hamburger Icon -->
                    <svg id="menuIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <!-- Close Icon -->
                    <svg id="closeIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-slate-800/50">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/market" class="block px-3 py-2 text-sm text-slate-300 hover:text-emerald-400 hover:bg-slate-800/50 rounded-lg transition-all duration-200">
                    Buy Crypto
                </a>
                <a href="/market?type=sell" class="block px-3 py-2 text-sm text-slate-300 hover:text-blue-400 hover:bg-slate-800/50 rounded-lg transition-all duration-200">
                    Sell Crypto
                </a>
                <a href="/trade-flow-guide" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all duration-200">
                    How it Works
                </a>
                <a href="/contact-support" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all duration-200">
                    Contact Us
                </a>
                <div class="pt-2 mt-2 border-t border-slate-800/50">
                    <a href="/app-download" class="block px-3 py-2 text-sm text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition-all duration-200">
                        Download App
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');

    if (mobileMenu.classList.contains('hidden')) {
        // Open menu
        mobileMenu.classList.remove('hidden');
        menuIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
    } else {
        // Close menu
        mobileMenu.classList.add('hidden');
        menuIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
    }
}
</script>

<!-- Spacer for fixed nav -->
<div class="h-16"></div>