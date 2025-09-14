<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import BitcoinIcon from '@/Components/Icons/BitcoinIcon.vue';
import EthereumIcon from '@/Components/Icons/EthereumIcon.vue';
import UsdtIcon from '@/Components/Icons/UsdtIcon.vue';
import BnbIcon from '@/Components/Icons/BnbIcon.vue';
import UsdcIcon from '@/Components/Icons/UsdcIcon.vue';
import TronIcon from '@/Components/Icons/TronIcon.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <P2PAppLayout :hideNavigation="true">
        <Head title="注册 - P2PCoinSwap" />

        <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-950">
        <!-- Cryptocurrency Background Pattern -->
        <div class="absolute inset-0 crypto-bg">
            <!-- Logo Watermark -->
            <div class="absolute bottom-10 right-10 pointer-events-none">
                <img src="/logo.png" class="w-64 h-64 opacity-5" style="filter: grayscale(100%);" />
            </div>

            <!-- Static Crypto Icons -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <!-- Bitcoin -->
                <div class="absolute top-20 right-10 opacity-20" style="transform: rotate(18deg);">
                    <BitcoinIcon class="w-16 h-16 md:w-20 md:h-20" />
                </div>

                <!-- Ethereum -->
                <div class="absolute bottom-32 left-20 opacity-20" style="transform: rotate(-30deg);">
                    <EthereumIcon class="w-20 h-20 md:w-24 md:h-24" />
                </div>

                <!-- USDT -->
                <div class="absolute top-40 left-32 opacity-20" style="transform: rotate(10deg);">
                    <UsdtIcon class="w-24 h-24 md:w-28 md:h-28" />
                </div>

                <!-- BNB -->
                <div class="absolute top-60 right-40 opacity-20" style="transform: rotate(-20deg);">
                    <BnbIcon class="w-18 h-18 md:w-22 md:h-22" />
                </div>

                <!-- USDC -->
                <div class="absolute bottom-1/2 right-10 opacity-20" style="transform: rotate(28deg);">
                    <UsdcIcon class="w-14 h-14 md:w-16 md:h-16" />
                </div>

                <!-- Tron -->
                <div class="absolute bottom-1/4 left-1/3 opacity-20" style="transform: rotate(-12deg);">
                    <TronIcon class="w-12 h-12 md:w-14 md:h-14" />
                </div>
            </div>

            <!-- Animated Lines -->
            <div class="absolute top-0 left-0 right-0 h-px glow-line"></div>
            <div class="absolute bottom-0 left-0 right-0 h-px glow-line" style="animation-delay: 1.5s;"></div>

            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        </div>

        <!-- Register Form Container -->
        <div class="relative z-10 w-full max-w-md px-4">
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-slate-800 shadow-2xl p-4 sm:p-6">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <a href="/market" class="inline-flex items-center justify-center gap-2 group">
                        <img src="/logo.png" alt="P2PCoinSwap" class="h-10 w-10 sm:h-12 sm:w-12 group-hover:scale-110 transition-transform duration-200" />
                        <span class="text-xl sm:text-2xl font-bold text-white">P2PCoinSwap</span>
                    </a>
                    <p class="mt-1 text-xs sm:text-sm text-slate-400">创建账户开始安全交易</p>
                </div>

                <!-- Register Form -->
                <form @submit.prevent="submit">
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="block text-xs sm:text-sm font-medium text-slate-300 mb-1.5">
                            用户名
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                autofocus
                                autocomplete="name"
                                class="w-full pl-9 pr-3 py-2 sm:py-2.5 text-sm bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                placeholder="输入用户名"
                            />
                        </div>
                        <div v-if="form.errors.name" class="mt-2 text-sm text-red-400">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="block text-xs sm:text-sm font-medium text-slate-300 mb-1.5">
                            邮箱地址
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autocomplete="username"
                                class="w-full pl-9 pr-3 py-2 sm:py-2.5 text-sm bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                placeholder="your@email.com"
                            />
                        </div>
                        <div v-if="form.errors.email" class="mt-2 text-sm text-red-400">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="block text-xs sm:text-sm font-medium text-slate-300 mb-1.5">
                            密码
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="new-password"
                                class="w-full pl-9 pr-9 py-2 sm:py-2.5 text-sm bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                placeholder="至少8位字符"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg v-if="!showPassword" class="h-5 w-5 text-slate-500 hover:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else class="h-5 w-5 text-slate-500 hover:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <div v-if="form.errors.password" class="mt-2 text-sm text-red-400">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="block text-xs sm:text-sm font-medium text-slate-300 mb-1.5">
                            确认密码
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                :type="showPasswordConfirmation ? 'text' : 'password'"
                                required
                                autocomplete="new-password"
                                class="w-full pl-9 pr-9 py-2 sm:py-2.5 text-sm bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                placeholder="再次输入密码"
                            />
                            <button
                                type="button"
                                @click="showPasswordConfirmation = !showPasswordConfirmation"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg v-if="!showPasswordConfirmation" class="h-5 w-5 text-slate-500 hover:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else class="h-5 w-5 text-slate-500 hover:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <div v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-400">
                            {{ form.errors.password_confirmation }}
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="mb-4">
                        <label class="flex items-start cursor-pointer">
                            <input
                                v-model="form.terms"
                                type="checkbox"
                                required
                                class="w-3.5 h-3.5 sm:w-4 sm:h-4 mt-0.5 text-emerald-500 bg-slate-800 border-slate-600 rounded focus:ring-emerald-500 focus:ring-2 cursor-pointer"
                            />
                            <span class="ml-2 text-xs sm:text-sm text-slate-400">
                                我同意
                                <a href="/terms" class="text-emerald-400 hover:text-emerald-300">服务条款</a>
                                和
                                <a href="/privacy" class="text-emerald-400 hover:text-emerald-300">隐私政策</a>
                            </span>
                        </label>
                        <div v-if="form.errors.terms" class="mt-2 text-sm text-red-400">
                            {{ form.errors.terms }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <P2PButton
                        type="submit"
                        variant="primary"
                        size="md"
                        :fullWidth="true"
                        :loading="form.processing"
                        :disabled="form.processing"
                    >
                        <template #icon>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </template>
                        创建账户
                    </P2PButton>
                </form>

                <!-- Divider -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center text-xs sm:text-sm">
                        <span class="px-2 bg-slate-900/80 text-slate-500">已有账户？</span>
                    </div>
                </div>

                <!-- Login Link -->
                <a
                    :href="route('login')"
                    class="w-full py-2.5 px-4 bg-slate-800/50 hover:bg-slate-700/50 text-slate-300 hover:text-white text-sm font-medium rounded-lg border border-slate-700 hover:border-slate-600 transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    立即登录
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