<template>
    <footer class="relative bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-950 border-t border-gray-200 dark:border-gray-800 pb-16 md:pb-0">
        <!-- Logo Watermark -->
        <div class="absolute right-4 md:right-10 bottom-10 opacity-[0.03] dark:opacity-[0.08] pointer-events-none">
            <img src="/logo.png" class="w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 lg:w-48 lg:h-48" style="filter: grayscale(100%);" />
        </div>
        <!-- Main Footer Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 md:gap-8">
                <!-- Brand Section - hidden on mobile -->
                <div class="hidden sm:block col-span-2 sm:col-span-2 md:col-span-3 lg:col-span-1 mb-4 sm:mb-0">
                    <div class="mb-3">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="/logo.png" alt="P2PCoinSwap" class="w-8 h-8" />
                            <div>
                                <P2PBrandText size="md" />
                                <div class="text-[10px] text-gray-500 dark:text-gray-400">Trade Crypto P2P</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        The leading peer-to-peer cryptocurrency trading platform, providing secure and convenient digital asset trading services globally.
                    </p>
                </div>

                <!-- P2P Services -->
                <div class="col-span-1">
                    <h4 class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-white mb-2 sm:mb-3 flex items-center gap-1">
                        <div class="w-0.5 h-3 bg-emerald-500 rounded"></div>
                        P2P Services
                    </h4>
                    <ul class="space-y-1">
                        <li>
                            <a href="/market?type=buy" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-emerald-500 text-[10px]">›</span> Buy Crypto
                            </a>
                        </li>
                        <li>
                            <a href="/market?type=sell" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-emerald-500 text-[10px]">›</span> Sell Crypto
                            </a>
                        </li>
                        <li>
                            <a href="/vendor/application" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-emerald-500 text-[10px]">›</span> Become Vendor
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Fees & Info -->
                <div class="col-span-1">
                    <h4 class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-white mb-2 sm:mb-3 flex items-center gap-1">
                        <div class="w-0.5 h-3 bg-gray-500 rounded"></div>
                        Fees & Info
                    </h4>
                    <ul class="space-y-1">
                        <li>
                            <a href="/trading-fees" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-gray-400 text-[10px]">›</span> Trading Fees
                            </a>
                        </li>
                        <li>
                            <a href="/kyc-verification" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-gray-400 text-[10px]">›</span> KYC
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Help Center -->
                <div class="col-span-1">
                    <h4 class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-white mb-2 sm:mb-3 flex items-center gap-1">
                        <div class="w-0.5 h-3 bg-amber-500 rounded"></div>
                        Help Center
                    </h4>
                    <ul class="space-y-1">
                        <li>
                            <a href="/contact-support" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-amber-400 text-[10px]">›</span> Support
                            </a>
                        </li>
                        <li>
                            <a href="/trade-flow-guide" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-amber-400 text-[10px]">›</span> Trade Guide
                            </a>
                        </li>
                        <li>
                            <a href="/dispute-resolution" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-amber-400 text-[10px]">›</span> Disputes
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Legal -->
                <div class="col-span-1">
                    <h4 class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-white mb-2 sm:mb-3 flex items-center gap-1">
                        <div class="w-0.5 h-3 bg-gray-500 rounded"></div>
                        Legal
                    </h4>
                    <ul class="space-y-1">
                        <li>
                            <a href="/terms" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-gray-400 text-[10px]">›</span> Terms
                            </a>
                        </li>
                        <li>
                            <a href="/privacy" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-0.5">
                                <span class="text-gray-400 text-[10px]">›</span> Privacy
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                    <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-500 flex items-center gap-1">
                        © 2025 <P2PBrandText size="sm" />. All rights reserved.
                    </div>

                    <!-- Social Links -->
                    <div class="flex items-center gap-2">
                        <a href="https://t.me/+C56TwooPC5A5ZDU1" target="_blank" rel="noopener noreferrer" class="p-1.5 rounded-lg bg-gray-100 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700/50 transition-all">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import P2PBrandText from './P2PBrandText.vue';
</script>