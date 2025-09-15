<script setup>
import { nextTick, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import BitcoinIcon from '@/Components/Icons/BitcoinIcon.vue';
import EthereumIcon from '@/Components/Icons/EthereumIcon.vue';
import UsdtIcon from '@/Components/Icons/UsdtIcon.vue';
import BnbIcon from '@/Components/Icons/BnbIcon.vue';
import UsdcIcon from '@/Components/Icons/UsdcIcon.vue';
import TronIcon from '@/Components/Icons/TronIcon.vue';

const recovery = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
    recovery.value ^= true;

    await nextTick();

    if (recovery.value) {
        recoveryCodeInput.value.focus();
        form.code = '';
    } else {
        codeInput.value.focus();
        form.recovery_code = '';
    }
};

const submit = () => {
    form.post(route('two-factor.login'));
};
</script>

<template>
    <P2PAppLayout :hideNavigation="true" :show-mobile-bottom-nav="false">
        <Head title="双因素认证 - P2PCoinSwap" />

        <!-- Simple Navigation Bar -->
        <div class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-200 dark:border-slate-800 pt-safe">
            <div class="px-4 py-3 flex items-center justify-between">
                <!-- Back Button & Logo -->
                <div class="flex items-center gap-3">
                    <a
                        href="/login"
                        class="p-2 rounded-lg text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-all cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <a href="/" class="flex items-center gap-2 cursor-pointer">
                        <img src="/logo.png" alt="Logo" class="h-8 w-8">
                        <span class="hidden sm:inline text-gray-900 dark:text-white font-bold text-lg">P2PCoinSwap</span>
                    </a>
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

            <!-- Two Factor Form Container -->
            <div class="relative z-10 w-full max-w-md px-4 sm:px-6">
                <div class="bg-white dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-gray-200 dark:border-slate-800 shadow-2xl p-6 sm:p-8">
                    <!-- Logo -->
                    <div class="text-center mb-6">
                        <a href="/market" class="inline-flex items-center justify-center gap-3 group">
                            <img src="/logo.png" alt="P2PCoinSwap" class="h-12 w-12 group-hover:scale-110 transition-transform duration-200" />
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">P2PCoinSwap</span>
                        </a>
                        <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">双因素认证</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">
                            <template v-if="!recovery">
                                请输入您的验证器应用提供的验证码
                            </template>
                            <template v-else>
                                请输入您的紧急恢复代码之一
                            </template>
                        </p>
                    </div>

                    <!-- Two Factor Form -->
                    <form @submit.prevent="submit">
                        <!-- Authentication Code -->
                        <div v-if="!recovery" class="mb-6">
                            <label for="code" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                验证码
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="code"
                                    ref="codeInput"
                                    v-model="form.code"
                                    type="text"
                                    inputmode="numeric"
                                    autofocus
                                    autocomplete="one-time-code"
                                    class="w-full pl-10 pr-3 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                    placeholder="000000"
                                />
                            </div>
                            <div v-if="form.errors.code" class="mt-2 text-sm text-red-400">
                                {{ form.errors.code }}
                            </div>
                        </div>

                        <!-- Recovery Code -->
                        <div v-else class="mb-6">
                            <label for="recovery_code" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                恢复代码
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="recovery_code"
                                    ref="recoveryCodeInput"
                                    v-model="form.recovery_code"
                                    type="text"
                                    autocomplete="one-time-code"
                                    class="w-full pl-10 pr-3 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-300 dark:border-slate-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                    placeholder="xxxxx-xxxxx"
                                />
                            </div>
                            <div v-if="form.errors.recovery_code" class="mt-2 text-sm text-red-400">
                                {{ form.errors.recovery_code }}
                            </div>
                        </div>

                        <!-- Toggle Recovery Mode -->
                        <div class="mb-6">
                            <button
                                type="button"
                                @click.prevent="toggleRecovery"
                                class="text-sm text-emerald-400 hover:text-emerald-300 transition-colors"
                            >
                                <template v-if="!recovery">
                                    使用恢复代码
                                </template>
                                <template v-else>
                                    使用验证码
                                </template>
                            </button>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </template>
                            验证身份
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

                    <!-- Cancel Link -->
                    <a
                        href="/login"
                        class="w-full py-3 px-4 bg-gray-100 dark:bg-slate-800/50 hover:bg-gray-200 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white font-medium rounded-lg border border-gray-300 dark:border-slate-700 hover:border-gray-400 dark:hover:border-slate-600 transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        取消登录
                    </a>

                    <!-- Security Notice -->
                    <div class="mt-6 p-4 bg-amber-500/10 border border-amber-500/20 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm text-amber-400 font-medium">安全提示</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">请勿将验证码或恢复代码分享给任何人</p>
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