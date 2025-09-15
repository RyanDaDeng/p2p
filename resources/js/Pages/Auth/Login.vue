<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PBrandText from '@/Components/UI/P2PBrandText.vue';
import BitcoinIcon from '@/Components/Icons/BitcoinIcon.vue';
import EthereumIcon from '@/Components/Icons/EthereumIcon.vue';
import UsdtIcon from '@/Components/Icons/UsdtIcon.vue';
import BnbIcon from '@/Components/Icons/BnbIcon.vue';
import UsdcIcon from '@/Components/Icons/UsdcIcon.vue';
import TronIcon from '@/Components/Icons/TronIcon.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <P2PAppLayout :hideNavigation="true" :show-mobile-bottom-nav="false">
        <Head title="登录 - P2PCoinSwap" />

        <!-- Simple Navigation Bar -->
        <div class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-200 dark:border-slate-800 pt-safe">
            <div class="px-4 py-3 flex items-center justify-between">
                <!-- Back Button & Logo -->
                <div class="flex items-center gap-3">
                    <Link
                        href="/market"
                        class="p-2 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-all cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <Link href="/" class="flex items-center gap-2 cursor-pointer">
                        <img src="/logo.png" alt="Logo" class="h-8 w-8">
                        <div class="hidden sm:inline">
                            <P2PBrandText size="lg" />
                        </div>
                    </Link>
                </div>

                <!-- Register Link -->
                <Link
                    href="/register"
                    class="text-sm text-gray-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer"
                >
                    还没有账号？立即注册
                </Link>
            </div>
        </div>

        <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-gray-50 dark:bg-slate-950 pt-[calc(3.5rem+env(safe-area-inset-top))]">
        <!-- Cryptocurrency Background Pattern -->
        <div class="absolute inset-0 crypto-bg">
            <!-- Logo Watermark -->
            <div class="absolute bottom-10 right-10 pointer-events-none">
                <img src="/logo.png" class="w-64 h-64 opacity-5" style="filter: grayscale(100%);" />
            </div>

            <!-- Static Crypto Icons -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <!-- Bitcoin -->
                <div class="absolute top-10 left-10 opacity-20" style="transform: rotate(-15deg);">
                    <BitcoinIcon class="w-16 h-16 md:w-20 md:h-20" />
                </div>

                <!-- Ethereum -->
                <div class="absolute top-32 right-20 opacity-20" style="transform: rotate(22deg);">
                    <EthereumIcon class="w-20 h-20 md:w-24 md:h-24" />
                </div>

                <!-- USDT -->
                <div class="absolute bottom-20 left-32 opacity-20" style="transform: rotate(-8deg);">
                    <UsdtIcon class="w-24 h-24 md:w-28 md:h-28" />
                </div>

                <!-- BNB -->
                <div class="absolute top-1/3 right-10 opacity-20" style="transform: rotate(35deg);">
                    <BnbIcon class="w-18 h-18 md:w-22 md:h-22" />
                </div>

                <!-- USDC -->
                <div class="absolute top-1/2 left-10 opacity-20" style="transform: rotate(-25deg);">
                    <UsdcIcon class="w-14 h-14 md:w-16 md:h-16" />
                </div>

                <!-- Tron -->
                <div class="absolute top-3/4 right-1/3 opacity-20" style="transform: rotate(12deg);">
                    <TronIcon class="w-12 h-12 md:w-14 md:h-14" />
                </div>
            </div>

            <!-- Animated Lines -->
            <div class="absolute top-0 left-0 right-0 h-px glow-line"></div>
            <div class="absolute bottom-0 left-0 right-0 h-px glow-line" style="animation-delay: 1.5s;"></div>

            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        </div>

        <!-- Login Form Container -->
        <div class="relative z-10 w-full max-w-md px-6">
            <div class="bg-white dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-gray-200 dark:border-slate-800 shadow-2xl p-8">

                <!-- Title -->
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">登录</h2>

                <!-- Status Message -->
                <div v-if="status" class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-lg">
                    <p class="text-sm text-emerald-400">{{ status }}</p>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="submit">
                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            邮箱地址
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full pl-10 pr-3 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                placeholder="your@email.com"
                            />
                        </div>
                        <div v-if="form.errors.email" class="mt-2 text-sm text-red-400">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            密码
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="current-password"
                                class="w-full pl-10 pr-10 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg v-if="!showPassword" class="h-5 w-5 text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else class="h-5 w-5 text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <div v-if="form.errors.password" class="mt-2 text-sm text-red-400">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center cursor-pointer">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="w-4 h-4 text-emerald-500 bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-600 rounded focus:ring-emerald-500 focus:ring-2 cursor-pointer"
                            />
                            <span class="ml-2 text-sm text-gray-600 dark:text-slate-400">记住我</span>
                        </label>

                        <a
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-emerald-400 hover:text-emerald-300 transition-colors"
                        >
                            忘记密码？
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <P2PButton
                        type="submit"
                        variant="primary"
                        size="lg"
                        :fullWidth="true"
                        :loading="form.processing"
                        :disabled="form.processing"
                    >
                        <template #icon>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        </template>
                        登录账户
                    </P2PButton>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-slate-900/80 text-gray-500 dark:text-slate-500">还没有账户？</span>
                    </div>
                </div>

                <!-- Register Link -->
                <a
                    :href="route('register')"
                    class="w-full py-3 px-4 bg-gray-100 dark:bg-slate-800/50 hover:bg-gray-200 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium rounded-lg border border-gray-300 dark:border-slate-700 hover:border-gray-400 dark:hover:border-slate-600 transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    创建新账户
                </a>

            </div>
        </div>
    </div>
    </P2PAppLayout>
</template>

<style scoped>
@keyframes pulse-glow {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.8; }
}

.crypto-bg {
    background-image:
        radial-gradient(ellipse at top left, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
        radial-gradient(ellipse at bottom right, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
        radial-gradient(ellipse at center, rgba(168, 85, 247, 0.1) 0%, transparent 70%);
}

.glow-line {
    background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.5), transparent);
    animation: pulse-glow 3s ease-in-out infinite;
}

.bg-grid-pattern {
    background-image:
        linear-gradient(rgba(16, 185, 129, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(16, 185, 129, 0.1) 1px, transparent 1px);
    background-size: 50px 50px;
}
</style>
