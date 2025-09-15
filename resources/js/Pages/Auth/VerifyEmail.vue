<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import BitcoinIcon from '@/Components/Icons/BitcoinIcon.vue';
import EthereumIcon from '@/Components/Icons/EthereumIcon.vue';
import UsdtIcon from '@/Components/Icons/UsdtIcon.vue';
import BnbIcon from '@/Components/Icons/BnbIcon.vue';
import UsdcIcon from '@/Components/Icons/UsdcIcon.vue';
import TronIcon from '@/Components/Icons/TronIcon.vue';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <P2PAppLayout :hideNavigation="true" :show-mobile-bottom-nav="false">
        <Head title="邮箱验证 - P2PCoinSwap" />

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
                        <span class="hidden sm:inline text-gray-900 dark:text-white font-bold text-lg">P2PCoinSwap</span>
                    </Link>
                </div>

                <!-- Help Link -->
                <a
                    href="/help"
                    class="text-sm text-gray-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer"
                >
                    需要帮助？
                </a>
            </div>
        </div>

        <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-gray-50 dark:bg-slate-950 pt-[calc(3.5rem+env(safe-area-inset-top))]">
            <!-- Cryptocurrency Background Pattern -->
            <div class="absolute inset-0 crypto-bg">
                <!-- Logo Watermark -->
                <div class="absolute bottom-10 right-10 pointer-events-none">
                    <img src="/logo.png" class="w-64 h-64 opacity-5" style="filter: grayscale(100%);" />
                </div>
            </div>

            <!-- Static Crypto Icons -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <!-- Bitcoin -->
                <div class="absolute top-20 left-20 opacity-20" style="transform: rotate(-20deg);">
                    <BitcoinIcon class="w-16 h-16 md:w-20 md:h-20" />
                </div>

                <!-- Ethereum -->
                <div class="absolute top-1/3 right-32 opacity-20" style="transform: rotate(15deg);">
                    <EthereumIcon class="w-20 h-20 md:w-24 md:h-24" />
                </div>

                <!-- USDT -->
                <div class="absolute bottom-32 left-40 opacity-20" style="transform: rotate(-10deg);">
                    <UsdtIcon class="w-24 h-24 md:w-28 md:h-28" />
                </div>

                <!-- BNB -->
                <div class="absolute top-1/2 right-20 opacity-20" style="transform: rotate(25deg);">
                    <BnbIcon class="w-18 h-18 md:w-22 md:h-22" />
                </div>

                <!-- USDC -->
                <div class="absolute bottom-1/3 left-10 opacity-20" style="transform: rotate(-30deg);">
                    <UsdcIcon class="w-14 h-14 md:w-16 md:h-16" />
                </div>

                <!-- Tron -->
                <div class="absolute top-20 right-1/4 opacity-20" style="transform: rotate(20deg);">
                    <TronIcon class="w-12 h-12 md:w-14 md:h-14" />
                </div>
            </div>

            <!-- Animated Lines -->
            <div class="absolute top-0 left-0 right-0 h-px glow-line"></div>
            <div class="absolute bottom-0 left-0 right-0 h-px glow-line" style="animation-delay: 1.5s;"></div>

            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

            <!-- Email Verification Form Container -->
            <div class="relative z-10 w-full max-w-md px-4 sm:px-6">
                <div class="bg-white dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-gray-200 dark:border-slate-800 shadow-2xl p-6 sm:p-8">
                    <!-- Logo -->
                    <div class="text-center mb-6">
                        <a href="/market" class="inline-flex items-center justify-center gap-3 group">
                            <img src="/logo.png" alt="P2PCoinSwap" class="h-12 w-12 group-hover:scale-110 transition-transform duration-200" />
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">P2PCoinSwap</span>
                        </a>
                        <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">验证您的邮箱</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">
                            我们已向您的邮箱发送了验证链接，请查收邮件并点击验证链接以完成注册
                        </p>
                    </div>

                    <!-- Success Status -->
                    <div v-if="verificationLinkSent" class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm text-emerald-400 font-medium">验证邮件已发送</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">新的验证链接已发送到您在个人资料中提供的邮箱地址</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Verification Form -->
                    <form @submit.prevent="submit">
                        <!-- Resend Button -->
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </template>
                            重新发送验证邮件
                        </P2PButton>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-slate-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white dark:bg-slate-900/80 text-gray-500 dark:text-slate-500">或</span>
                        </div>
                    </div>

                    <!-- Action Links -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <Link
                            :href="route('profile.show')"
                            class="flex-1 py-3 px-4 bg-gray-100 dark:bg-slate-800/50 hover:bg-gray-200 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium rounded-lg border border-gray-300 dark:border-slate-700 hover:border-gray-400 dark:hover:border-slate-600 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            编辑资料
                        </Link>

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="flex-1 py-3 px-4 bg-red-100 dark:bg-red-900/20 hover:bg-red-200 dark:hover:bg-red-900/30 text-red-700 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium rounded-lg border border-red-300 dark:border-red-800 hover:border-red-400 dark:hover:border-red-700 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            退出登录
                        </Link>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm text-blue-400 font-medium">验证说明</p>
                                <ul class="text-xs text-gray-500 dark:text-slate-400 mt-1 space-y-1">
                                    <li>• 请检查您的邮箱（包括垃圾邮件文件夹）</li>
                                    <li>• 验证链接24小时内有效</li>
                                    <li>• 如果没有收到邮件，请点击"重新发送"</li>
                                </ul>
                            </div>
                        </div>
                    </div>
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