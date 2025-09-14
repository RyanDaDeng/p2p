<template>
    <div>
        <!-- Display Selected or Placeholder -->
        <button
            type="button"
            @click="open = true"
            class="w-full px-4 py-3 bg-white dark:bg-slate-900 border-2 border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer hover:border-emerald-500 dark:hover:border-emerald-400 focus:border-emerald-500 dark:focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:focus:ring-emerald-400/20 transition-all duration-200 text-left group"
        >
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <div v-if="selected">
                        <div class="flex items-center gap-2 mb-1">
                            <component
                                :is="getIcon(selected.currency)"
                                :size="20"
                                class="flex-shrink-0"
                            />
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                {{ selected.currency }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-slate-400">
                                {{ selected.network }}
                            </span>
                        </div>
                        <div class="text-sm font-mono text-gray-900 dark:text-slate-100 break-all">
                            {{ selected.address }}
                        </div>
                    </div>
                    <div v-else class="flex items-center gap-2 text-gray-400 dark:text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>选择地址</span>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 dark:text-slate-500 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                </svg>
            </div>
        </button>

        <!-- Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="open" class="fixed inset-0 z-50 flex items-end md:items-center justify-center">
                    <!-- Background -->
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

                    <!-- Content -->
                    <Transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0"
                        enter-to-class="translate-y-0 md:scale-100 md:opacity-100"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="translate-y-0 md:scale-100 md:opacity-100"
                        leave-to-class="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0"
                    >
                        <div v-if="open" class="relative bg-white dark:bg-slate-900 rounded-t-2xl md:rounded-2xl shadow-2xl w-full md:max-w-2xl max-h-[85vh] md:max-h-[80vh] overflow-hidden md:m-4">
                            <!-- Mobile drag indicator -->
                            <div class="md:hidden flex justify-center pt-2 pb-1">
                                <div class="w-10 h-1 bg-gray-300 dark:bg-slate-700 rounded-full"></div>
                            </div>

                            <!-- Header -->
                            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 dark:border-slate-800">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg md:text-xl font-semibold text-gray-900 dark:text-slate-100">选择收款地址</h3>
                                    <button
                                        @click="open = false"
                                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                                    >
                                        <svg class="w-5 h-5 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">请选择一个地址用于接收款项</p>
                            </div>

                            <!-- Empty state -->
                            <div v-if="list.length === 0" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-gray-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-gray-500 dark:text-slate-400 mb-4">暂无可用地址</p>
                                <a
                                    href="/wallet/address-verification"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span>添加地址</span>
                                </a>
                            </div>

                            <!-- List -->
                            <div v-else class="flex flex-col max-h-[70vh] md:max-h-[75vh]">
                                <div class="overflow-y-auto flex-1">
                                    <div class="p-2 md:p-3 space-y-1">
                                        <div
                                            v-for="item in list"
                                            :key="item.address"
                                            @click="pick(item)"
                                            :class="[
                                                'relative px-4 py-3 rounded-xl cursor-pointer transition-all duration-200 group',
                                                (item.id === value || item.address === value)
                                                    ? 'bg-gradient-to-r from-emerald-50 to-emerald-100/50 dark:from-emerald-900/30 dark:to-emerald-800/20 ring-2 ring-emerald-500 dark:ring-emerald-400'
                                                    : 'hover:bg-gray-50 dark:hover:bg-slate-800 hover:ring-1 hover:ring-gray-200 dark:hover:ring-slate-700',
                                                item.disabled ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''
                                            ]"
                                        >
                                            <div class="flex items-start gap-3">
                                                <!-- Crypto Icon -->
                                                <div :class="[
                                                    'p-2 rounded-lg flex-shrink-0 transition-colors flex items-center justify-center',
                                                    (item.id === value || item.address === value)
                                                        ? 'bg-emerald-100 dark:bg-emerald-900/30'
                                                        : 'bg-gray-100 dark:bg-slate-800 group-hover:bg-emerald-50 dark:group-hover:bg-emerald-900/20'
                                                ]">
                                                    <component
                                                        :is="getIcon(item.currency)"
                                                        :size="20"
                                                        class="flex-shrink-0"
                                                    />
                                                </div>

                                                <!-- Content -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span :class="[
                                                            'inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium',
                                                            (item.id === value || item.address === value)
                                                                ? 'bg-emerald-500 text-white'
                                                                : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400'
                                                        ]">
                                                            {{ item.currency }}
                                                        </span>
                                                        <span class="text-xs font-medium" :class="[
                                                            (item.id === value || item.address === value)
                                                                ? 'text-emerald-600 dark:text-emerald-400'
                                                                : 'text-gray-500 dark:text-slate-500'
                                                        ]">
                                                            {{ item.network }}
                                                        </span>
                                                    </div>
                                                    <div class="font-mono text-sm break-all leading-relaxed" :class="[
                                                        (item.id === value || item.address === value)
                                                            ? 'text-gray-900 dark:text-slate-100 font-medium'
                                                            : 'text-gray-700 dark:text-slate-300'
                                                    ]">
                                                        {{ item.address }}
                                                    </div>
                                                </div>

                                                <!-- Check mark -->
                                                <Transition
                                                    enter-active-class="transition ease-out duration-200"
                                                    enter-from-class="opacity-0 scale-0"
                                                    enter-to-class="opacity-100 scale-100"
                                                    leave-active-class="transition ease-in duration-150"
                                                    leave-from-class="opacity-100 scale-100"
                                                    leave-to-class="opacity-0 scale-0"
                                                >
                                                    <div v-if="item.id === value || item.address === value" class="flex-shrink-0">
                                                        <div class="p-1 bg-emerald-500 rounded-full">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </Transition>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer with add address link -->
                                <div class="border-t border-gray-200 dark:border-slate-800 px-4 py-3 text-center">
                                    <a
                                        href="/wallet/address-verification"
                                        class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium cursor-pointer"
                                    >
                                        需要更多地址？前往添加 →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { getCryptoIcon } from '@/Utils/crypto';

const props = defineProps({
    modelValue: String,
    addresses: Array
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);

// Current value
const value = computed(() => props.modelValue);

// List of addresses
const list = computed(() => props.addresses || []);

// Selected address object
const selected = computed(() => {
    // Support both ID and address as value for backward compatibility
    return list.value.find(item =>
        item.id === value.value ||
        item.address === value.value
    );
});

// Pick an address
const pick = (item) => {
    if (item.disabled) return;
    // 发送ID
    emit('update:modelValue', item.id);
    open.value = false;
};

// Get icon component for currency
const getIcon = (currency) => {
    return getCryptoIcon(currency);
};
</script>