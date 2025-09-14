<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-black/60 dark:bg-black/80" @click="$emit('close')"></div>
                
                <!-- Dialog -->
                <div class="flex items-center justify-center min-h-full p-4">
                    <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full">
                        <!-- Header with warning icon -->
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-slate-100">
                                        交易安全提醒
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-slate-400">
                                        请仔细阅读以下重要提示
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="px-6 pb-4">
                            <div class="space-y-4 text-sm">
                                <!-- Warning 1 -->
                                <div class="flex gap-2">
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-slate-100 mb-1">
                                            资金安全警告
                                        </p>
                                        <p class="text-gray-600 dark:text-slate-300">
                                            <span class="font-bold text-red-600 dark:text-red-400">切勿在资金到达托管前进行任何转账！</span>
                                            请等待系统确认后再进行支付。
                                        </p>
                                    </div>
                                </div>

                                <!-- Warning 2 -->
                                <div class="flex gap-2">
                                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-slate-100 mb-1">
                                            沟通凭证提醒
                                        </p>
                                        <p class="text-gray-600 dark:text-slate-300">
                                            只有在本平台内的聊天记录才能作为交易凭证。站外沟通内容无法作为争议处理依据。
                                        </p>
                                    </div>
                                </div>

                                <!-- Terms -->
                                <div class="flex gap-2">
                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-slate-100 mb-1">
                                            交易条款确认
                                        </p>
                                        <p class="text-gray-600 dark:text-slate-300">
                                            点击确认即表示您同意：
                                        </p>
                                        <ul class="mt-1 space-y-1 text-gray-600 dark:text-slate-300">
                                            <li class="flex items-start gap-1">
                                                <span class="text-gray-400">•</span>
                                                <span>遵守商家的交易条款</span>
                                            </li>
                                            <li class="flex items-start gap-1">
                                                <span class="text-gray-400">•</span>
                                                <span>在规定时间内完成交易</span>
                                            </li>
                                            <li class="flex items-start gap-1">
                                                <span class="text-gray-400">•</span>
                                                <span>使用实名账户进行支付</span>
                                            </li>
                                            <li class="flex items-start gap-1">
                                                <span class="text-gray-400">•</span>
                                                <span>诚信交易，不进行欺诈行为</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 pb-6">
                            <div class="flex gap-3">
                                <button
                                    @click="$emit('close')"
                                    class="flex-1 px-4 py-2.5 text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-700 font-medium transition-colors cursor-pointer"
                                >
                                    取消
                                </button>
                                <button
                                    @click="handleConfirm"
                                    class="flex-1 px-4 py-2.5 text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 font-medium transition-colors cursor-pointer"
                                >
                                    我已了解，继续交易
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    tradeType: {
        type: String,
        required: true,
        validator: (value) => ['buy', 'sell'].includes(value)
    }
});

const emit = defineEmits(['close', 'confirm']);

const handleConfirm = () => {
    emit('confirm');
};
</script>