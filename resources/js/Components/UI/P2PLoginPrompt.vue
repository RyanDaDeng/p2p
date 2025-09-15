<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="modelValue"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @click.self="close"
            >
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close"></div>

                <Transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="modelValue"
                        class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden"
                    >
                        <button
                            @click="close"
                            class="absolute top-4 right-4 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                        >
                            <svg class="w-5 h-5 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="p-8 text-center">
                            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 dark:text-slate-100 mb-3">
                                登录后继续
                            </h3>

                            <p class="text-gray-600 dark:text-slate-400 mb-8">
                                {{ message || '请先登录您的账户以继续此操作' }}
                            </p>

                            <div class="space-y-3">
                                <button
                                    @click="handleLogin"
                                    class="w-full py-3 px-6 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl cursor-pointer"
                                >
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                        <span>立即登录</span>
                                    </div>
                                </button>

                                <button
                                    @click="handleRegister"
                                    class="w-full py-3 px-6 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-xl border-2 border-gray-200 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 transition-all duration-200 cursor-pointer"
                                >
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                        </svg>
                                        <span>立即注册</span>
                                    </div>
                                </button>
                            </div>

                            <div class="mt-6 flex items-center justify-center gap-2">
                                <img src="/logo.png" alt="Logo" class="h-5 w-auto" />
                                <P2PBrandText />
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import P2PBrandText from '@/Components/UI/P2PBrandText.vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    },
    message: {
        type: String,
        default: null
    },
    returnUrl: {
        type: String,
        default: null
    }
});

const emit = defineEmits(['update:modelValue']);

const close = () => {
    emit('update:modelValue', false);
};

const handleLogin = () => {
    close();
    if (props.returnUrl) {
        router.visit(`/login?return=${encodeURIComponent(props.returnUrl)}`);
    } else {
        router.visit('/login');
    }
};

const handleRegister = () => {
    close();
    if (props.returnUrl) {
        router.visit(`/register?return=${encodeURIComponent(props.returnUrl)}`);
    } else {
        router.visit('/register');
    }
};
</script>