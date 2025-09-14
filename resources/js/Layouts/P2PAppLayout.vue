<template>
    <!-- App Loading Screen -->
    <P2PLoadingScreen
        v-if="showAppLoading"
        @complete="handleLoadingComplete"
        :duration="1200"
    />

    <!-- <P2PNotificationContainer ref="notificationContainer"> -->
    <div v-show="!showAppLoading" class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-200">
        <!-- Loading Overlay -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div class="flex flex-col items-center">
                    <div class="relative">
                        <div class="h-12 w-12 rounded-full border-4 border-slate-700"></div>
                        <div class="absolute top-0 h-12 w-12 animate-spin rounded-full border-4 border-emerald-500 border-t-transparent"></div>
                    </div>
                    <p class="mt-4 text-sm font-medium text-white">加载中...</p>
                </div>
            </div>
        </Transition>

        <!-- Navigation -->
        <nav v-if="!hideNavigation" :class="[
                 'sticky top-0 z-40 w-full border-b border-gray-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl',
                 hideNav ? 'hidden md:block' : ''
             ]">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-safe">
                <div class="flex h-14 md:h-16 items-center justify-between">
                    <!-- Logo & Navigation Links -->
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            <P2PLogo size="md" :show-tagline="true" :hide-text-on-mobile="true" />
                        </div>

                        <!-- Desktop Navigation -->
                        <div class="hidden md:ml-10 md:block">
                            <div class="flex items-center space-x-1">
                                <!-- Buy/Sell Navigation -->
                                <Link
                                    href="/market?type=buy"
                                    :class="[
                                        'flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold transition-all cursor-pointer',
                                        route().current('web.market') && $page.url.includes('type=buy')
                                            ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' 
                                            : 'text-emerald-600 dark:text-emerald-500 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-emerald-500/10 dark:hover:bg-emerald-500/10'
                                    ]"
                                >
                                    买币
                                </Link>
                                <Link
                                    href="/market?type=sell"
                                    :class="[
                                        'flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold transition-all cursor-pointer',
                                        route().current('web.market') && $page.url.includes('type=sell')
                                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' 
                                            : 'text-blue-600 dark:text-blue-500 hover:text-blue-500 dark:hover:text-blue-400 hover:bg-blue-500/10 dark:hover:bg-blue-500/10'
                                    ]"
                                >
                                    卖币
                                </Link>

                                <!-- Other Nav Items -->
                                <Link
                                    href="/orders"
                                    :class="[
                                        'flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all',
                                        route().current('orders') || route().current('web.orders')
                                            ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' 
                                            : 'text-gray-600 dark:text-slate-400 hover:text-gray-800 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800/50'
                                    ]"
                                >
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    订单
                                </Link>
                                <Link
                                    href="/publish"
                                    :class="[
                                        'flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all',
                                        route().current('publish') || route().current('web.publish')
                                            ? 'bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' 
                                            : 'text-gray-600 dark:text-slate-400 hover:text-gray-800 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800/50'
                                    ]"
                                >
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    发布
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-1 sm:space-x-3">
                        <!-- APP Download Button (only show if not running as PWA) -->
                        <a
                            v-if="!isPWA"
                            @click="handleAppClick"
                            class="flex items-center gap-1 px-2.5 py-1 bg-gray-700/40 backdrop-blur-sm border border-gray-500/40 hover:bg-gray-600/50 hover:border-pink-500/30 text-gray-300 hover:text-pink-300 rounded-lg transition-all cursor-pointer"
                            title="安装 APP"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-xs font-medium">APP</span>
                        </a>

                        <!-- Dark/Light Mode Toggle -->
                        <button
                            @click="toggleDark()"
                            class="p-1.5 md:p-2 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                            :aria-label="isDark ? '切换到浅色模式' : '切换到深色模式'"
                        >
                            <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        <!-- Notifications -->
                        <Link href="/notifications" class="relative p-1.5 md:p-2 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span v-if="$page.props.auth?.user?.unread" class="absolute top-1 md:top-1.5 right-1 md:right-1.5 h-2 w-2 bg-red-500 rounded-full animate-pulse"></span>
                        </Link>

                        <!-- Wallet -->
                        <Link href="/wallet/address-verification" class="p-1.5 md:p-2 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </Link>

                        <!-- User Profile Link (Desktop & Mobile) -->
                        <div v-if="$page.props.auth?.user" class="block">
                            <Link :href="route('web.profile')" class="flex items-center gap-2 rounded-lg px-2 md:px-3 py-1.5 md:py-2 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                                <div class="relative">
                                    <!-- Use profile photo if available, otherwise show initial -->
                                    <img
                                        v-if="$page.props.auth.user.profile_photo_url"
                                        :src="$page.props.auth.user.profile_photo_url"
                                        :alt="$page.props.auth.user.name"
                                        class="h-7 w-7 md:h-8 md:w-8 rounded-full object-cover border-2 border-gray-200 dark:border-slate-700"
                                    />
                                    <div v-else class="h-7 w-7 md:h-8 md:w-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-xs md:text-sm">{{ $page.props.auth.user.name[0].toUpperCase() }}</span>
                                    </div>
                                    <div class="absolute -bottom-0.5 -right-0.5 h-2.5 md:h-3 w-2.5 md:w-3 bg-emerald-500 rounded-full border-2 border-white dark:border-slate-900"></div>
                                </div>
                                <span class="hidden sm:inline">{{ $page.props.auth.user.name }}</span>
                            </Link>
                        </div>

                        <!-- Login/Register -->
                        <div v-else class="flex items-center space-x-1 md:space-x-2">
                            <Link href="/login" class="rounded-lg px-2.5 md:px-4 py-1.5 md:py-2 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                登录
                            </Link>
                            <Link href="/register" class="hidden md:block rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 transition-all">
                                注册账号
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Desktop Breadcrumbs -->
        <div v-if="showBreadcrumbs && breadcrumbs.length > 0" class="hidden md:block bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center py-3">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li>
                                <Link href="/" class="flex items-center text-sm text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                    首页
                                </Link>
                            </li>
                            <li v-for="(crumb, index) in breadcrumbs" :key="index" class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 dark:text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <Link 
                                    v-if="crumb.url && index < breadcrumbs.length - 1"
                                    :href="crumb.url"
                                    class="ml-2 text-sm text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300 transition-colors"
                                >
                                    {{ crumb.label }}
                                </Link>
                                <span 
                                    v-else
                                    class="ml-2 text-sm"
                                    :class="index === breadcrumbs.length - 1 ? 'text-gray-900 dark:text-slate-100 font-medium' : 'text-gray-500 dark:text-slate-400'"
                                >
                                    {{ crumb.label }}
                                </span>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main :class="showMobileBottomNav ? 'pb-20 md:pb-0' : ''">
            <slot />
        </main>

        <!-- Mobile Bottom Navigation -->
        <nav v-if="showMobileBottomNav" class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-t border-gray-200 dark:border-slate-800 shadow-lg pb-safe">
            <div class="grid grid-cols-5 h-20">
                <!-- Market -->
                <Link
                    href="/market"
                    class="flex flex-col items-center justify-center gap-0.5 relative transition-all group"
                    :class="route().current('market') || route().current('web.market')
                        ? 'text-emerald-600 dark:text-emerald-400' 
                        : 'text-gray-600 dark:text-slate-400'"
                >
                    <div class="relative">
                        <div :class="route().current('market') || route().current('web.market')
                            ? 'bg-emerald-50 dark:bg-emerald-500/10 p-1.5 rounded-lg'
                            : 'p-1.5'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-[10px] font-medium">市场</span>
                </Link>

                <!-- Orders -->
                <Link
                    href="/orders"
                    class="flex flex-col items-center justify-center gap-0.5 relative transition-all group"
                    :class="route().current('orders') || route().current('web.orders')
                        ? 'text-blue-600 dark:text-blue-400' 
                        : 'text-gray-600 dark:text-slate-400'"
                >
                    <div class="relative">
                        <div :class="route().current('orders') || route().current('web.orders')
                            ? 'bg-blue-50 dark:bg-blue-500/10 p-1.5 rounded-lg'
                            : 'p-1.5'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <!-- Order notification badge -->
                        <div v-if="false" class="absolute -top-0.5 -right-0.5 h-2 w-2 bg-red-500 rounded-full animate-pulse"></div>
                    </div>
                    <span class="text-[10px] font-medium">订单</span>
                </Link>

                <!-- Publish -->
                <Link
                    href="/publish"
                    class="flex flex-col items-center justify-center gap-0.5 relative transition-all group"
                    :class="route().current('publish') || route().current('web.publish')
                        ? 'text-gray-900 dark:text-slate-100'
                        : 'text-gray-600 dark:text-slate-400'"
                >
                    <div class="relative">
                        <div :class="route().current('publish') || route().current('web.publish')
                            ? 'bg-gray-100 dark:bg-slate-800 p-1.5 rounded-lg'
                            : 'p-1.5'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-[10px] font-medium">发布</span>
                </Link>

                <!-- Wallet -->
                <Link
                    href="/wallet/address-verification"
                    class="flex flex-col items-center justify-center gap-0.5 relative transition-all group"
                    :class="route().current('wallet.address-verification') || route().current('web.wallet.address-verification')
                        ? 'text-amber-600 dark:text-amber-400' 
                        : 'text-gray-600 dark:text-slate-400'"
                >
                    <div class="relative">
                        <div :class="route().current('wallet.address-verification') || route().current('web.wallet.address-verification')
                            ? 'bg-amber-50 dark:bg-amber-500/10 p-1.5 rounded-lg'
                            : 'p-1.5'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <!-- Wallet balance indicator -->
                        <div v-if="false" class="absolute -top-0.5 -right-0.5 h-2 w-2 bg-emerald-500 rounded-full"></div>
                    </div>
                    <span class="text-[10px] font-medium">钱包</span>
                </Link>

                <!-- Profile -->
                <Link
                    :href="route('web.profile')"
                    class="flex flex-col items-center justify-center gap-0.5 relative transition-all group"
                    :class="route().current('profile') || route().current('web.profile')
                        ? 'text-purple-600 dark:text-purple-400' 
                        : 'text-gray-600 dark:text-slate-400'"
                >
                    <div class="relative">
                        <div :class="route().current('profile') || route().current('web.profile')
                            ? 'bg-purple-50 dark:bg-purple-500/10 p-1.5 rounded-lg'
                            : 'p-1.5'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <!-- Profile notification dot -->
                        <div v-if="false" class="absolute -top-0.5 -right-0.5 h-2 w-2 bg-emerald-500 rounded-full"></div>
                    </div>
                    <span class="text-[10px] font-medium">我的</span>
                </Link>
            </div>
        </nav>

        <!-- Footer - 手机端始终隐藏，桌面端根据 hideFooter 属性决定 -->
        <P2PFooter v-if="!hideFooter" class="hidden sm:block" />
        
    </div>
    <!-- </P2PNotificationContainer> -->
</template>

<script setup>
import { ref, onMounted, watch, provide, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import P2PFooter from '@/Components/UI/P2PFooter.vue';
import P2PLogo from '@/Components/UI/P2PLogo.vue';
import P2PLoadingScreen from '@/Components/UI/P2PLoadingScreen.vue';
import { usePWA } from '@/composables/usePWA';
// import P2PNotificationContainer from '@/Components/UI/P2PNotificationContainer.vue';

// Props
const props = defineProps({
    hideNav: {
        type: Boolean,
        default: false
    },
    hideNavigation: {
        type: Boolean,
        default: false
    },
    hideFooter: {
        type: Boolean,
        default: false
    },
    showMobileBottomNav: {
        type: Boolean,
        default: true
    },
    showBreadcrumbs: {
        type: Boolean,
        default: false
    },
    breadcrumbs: {
        type: Array,
        default: () => []
    }
});

// State
const loading = ref(false);
const $page = usePage();
const notificationContainer = ref(null);

// App Loading Screen State
const showAppLoading = ref(false);

// PWA Detection
const { isPWA, shouldShowAppButton, canInstallPWA, promptInstall } = usePWA();

// Check if should show loading screen (once per day)
const checkShouldShowLoading = () => {
    const storageKey = 'p2p_app_loading_shown';
    const lastShown = localStorage.getItem(storageKey);
    const today = new Date().toDateString();

    if (lastShown !== today) {
        localStorage.setItem(storageKey, today);
        return true;
    }
    return false;
};

// Handle loading complete
const handleLoadingComplete = () => {
    showAppLoading.value = false;
};

// 计算是否是管理员
const isAdmin = computed(() => {
    const adminIds = [3]; // 管理员ID列表
    return $page.props.auth?.user && adminIds.includes($page.props.auth.user.id);
});

// Dark mode - check initial state
const isDark = ref(document.documentElement.classList.contains('dark'));

const toggleDark = () => {
    isDark.value = !isDark.value;
    // Toggle the class
    if (isDark.value) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    // Save preference to localStorage
    localStorage.setItem('vueuse-color-scheme', isDark.value ? 'dark' : 'light');
};

// Handle APP button click
const handleAppClick = async () => {
    // If browser supports PWA installation prompt
    if (canInstallPWA.value) {
        const installed = await promptInstall();
        if (installed) {
            console.log('PWA installed successfully');
        }
    } else {
        // Fallback to app download page
        window.location.href = '/app-download';
    }
};

// Handle currency selection from onboarding

// Initialize on mount
onMounted(() => {
    // Check if should show loading screen
    showAppLoading.value = checkShouldShowLoading();

    // Initialize theme
    const savedTheme = localStorage.getItem('vueuse-color-scheme');
    if (savedTheme === 'dark') {
        isDark.value = true;
        document.documentElement.classList.add('dark');
    } else if (savedTheme === 'light') {
        isDark.value = false;
        document.documentElement.classList.remove('dark');
    }
});

// Route helper that also supports Ziggy routes
const route = (name) => {
    if (window.route) {
        return window.route(name);
    }
    // Fallback to just returning the path
    return '/' + name.replace('web.', '');
};

// Check if current route matches
route.current = (name) => {
    return $page.component === name || $page.url.startsWith('/' + name.replace('web.', ''));
};
</script>

<style scoped>
/* Custom dropdown item styles */
:deep(.dropdown-item) {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    color: rgb(203 213 225);
    transition: all 0.15s;
    cursor: pointer;
}

:deep(.dropdown-item:hover) {
    background: rgb(51 65 85 / 0.5);
    color: rgb(241 245 249);
}

:deep(.dropdown-item.active) {
    background: rgb(16 185 129 / 0.1);
    color: rgb(52 211 153);
    font-weight: 500;
}

:deep(.dropdown-divider) {
    margin: 0.25rem 0;
    border-top: 1px solid rgb(51 65 85 / 0.5);
}
</style>