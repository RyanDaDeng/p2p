<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import BitcoinIcon from '@/Components/Icons/BitcoinIcon.vue';
import EthereumIcon from '@/Components/Icons/EthereumIcon.vue';
import UsdtIcon from '@/Components/Icons/UsdtIcon.vue';
import BnbIcon from '@/Components/Icons/BnbIcon.vue';
import UsdcIcon from '@/Components/Icons/UsdcIcon.vue';
import TronIcon from '@/Components/Icons/TronIcon.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <P2PAppLayout :hideNavigation="true">
        <Head title="忘记密码 - P2PCoinSwap" />

        <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-950">
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

            <!-- Forgot Password Form Container -->
            <div class="relative z-10 w-full max-w-md px-4 sm:px-6">
                <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-slate-800 shadow-2xl p-6 sm:p-8">
                    <!-- Logo -->
                    <div class="text-center mb-6">
                        <a href="/market" class="inline-flex items-center justify-center gap-3 group">
                            <img src="/logo.png" alt="P2PCoinSwap" class="h-12 w-12 group-hover:scale-110 transition-transform duration-200" />
                            <span class="text-2xl font-bold text-white">P2PCoinSwap</span>
                        </a>
                        <h2 class="mt-4 text-xl font-semibold text-white">重置密码</h2>
                        <p class="mt-2 text-sm text-slate-400">
                            请输入您的注册邮箱，我们将发送密码重置链接到您的邮箱
                        </p>
                    </div>

                    <!-- Success Status -->
                    <div v-if="status" class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm text-emerald-400">{{ status }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Forgot Password Form -->
                    <form @submit.prevent="submit">
                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                                邮箱地址
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    class="w-full pl-10 pr-3 py-2.5 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                    placeholder="your@email.com"
                                />
                            </div>
                            <div v-if="form.errors.email" class="mt-2 text-sm text-red-400">
                                {{ form.errors.email }}
                            </div>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </template>
                            发送重置链接
                        </P2PButton>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-slate-900/80 text-slate-500">或</span>
                        </div>
                    </div>

                    <!-- Back to Login -->
                    <a
                        :href="route('login')"
                        class="w-full py-3 px-4 bg-slate-800/50 hover:bg-slate-700/50 text-slate-300 hover:text-white font-medium rounded-lg border border-slate-700 hover:border-slate-600 transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        返回登录
                    </a>

                    <!-- Security Notice -->
                    <div class="mt-6 p-4 bg-amber-500/10 border border-amber-500/20 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm text-amber-400 font-medium">安全提示</p>
                                <p class="text-xs text-slate-400 mt-1">密码重置链接将在24小时后失效，请及时查收邮件</p>
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