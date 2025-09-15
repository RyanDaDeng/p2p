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
            <div v-if="show" class="fixed inset-0 z-[80] flex items-end md:items-center justify-center">
                <!-- Background -->
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="handleBackgroundClick"></div>

                <!-- Content -->
                <Transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0"
                    enter-to-class="translate-y-0 md:scale-100 md:opacity-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="translate-y-0 md:scale-100 md:opacity-100"
                    leave-to-class="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0"
                >
                    <div v-if="show" class="relative bg-white dark:bg-gray-900 rounded-t md:rounded shadow-2xl w-full md:max-w-lg max-h-[90vh] overflow-hidden md:m-4 pt-safe">
                        <!-- Mobile drag indicator -->
                        <div class="md:hidden flex justify-center pt-2 pb-1">
                            <div class="w-10 h-1 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
                        </div>

                        <!-- Header -->
                        <div v-if="title || slots.header" class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 dark:border-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div v-if="icon" class="p-2 rounded" :class="iconBgClass">
                                        <component :is="icon" class="w-5 h-5" :class="iconClass" />
                                    </div>
                                    <div v-if="title || subtitle">
                                        <h3 class="text-lg md:text-xl font-semibold text-gray-900 dark:text-gray-100">
                                            {{ title }}
                                        </h3>
                                        <p v-if="subtitle" class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ subtitle }}
                                        </p>
                                    </div>
                                    <slot v-else name="header" />
                                </div>
                                <button
                                    @click="$emit('close')"
                                    class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                                >
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="flex flex-col" :class="hasFooter ? 'max-h-[60vh] md:max-h-[65vh]' : ''">
                            <!-- Scrollable content area -->
                            <div class="overflow-y-auto flex-1 px-4 md:px-6 py-4">
                                <!-- Merchant Info (if provided) -->
                                <div v-if="merchant" class="mb-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-bold text-sm">
                                                {{ merchant.name?.[0]?.toUpperCase() || 'M' }}
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">
                                                {{ merchant.name || '商家' }}
                                            </p>
                                            <div class="flex items-center gap-2 text-xs">
                                                <span v-if="merchant.isVerified" class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    认证商家
                                                </span>
                                                <span v-if="merchant.tradesCount" class="text-gray-500 dark:text-gray-400">
                                                    成交 {{ merchant.tradesCount }} | {{ merchant.completionRate || 100 }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms/Content -->
                                <div v-if="terms" class="bg-gray-50 dark:bg-gray-800/50 rounded p-4 mb-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">商家条款</h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ terms }}</p>
                                </div>

                                <!-- Slot content -->
                                <slot />
                            </div>

                            <!-- Agreement Section (if required) -->
                            <div v-if="requiresAgreement" class="border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 md:px-6 py-3">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        v-model="agreed"
                                        class="mt-1 w-4 h-4 text-emerald-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:ring-2 cursor-pointer"
                                    />
                                    <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">
                                        {{ agreementText }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div v-if="slots.footer || cancelText || confirmText" class="px-4 md:px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/30">
                            <slot name="footer">
                                <div class="flex gap-3">
                                    <button
                                        v-if="cancelText"
                                        @click="$emit('close')"
                                        class="flex-1 px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer font-medium"
                                    >
                                        {{ cancelText }}
                                    </button>
                                    <button
                                        v-if="confirmText"
                                        @click="handleConfirm"
                                        :disabled="requiresAgreement && !agreed"
                                        :class="[
                                            'flex-1 px-4 py-2.5 rounded font-medium transition-all relative',
                                            (!requiresAgreement || agreed)
                                                ? 'bg-emerald-600 hover:bg-emerald-700 text-white cursor-pointer shadow-sm hover:shadow-md'
                                                : 'bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-500 cursor-not-allowed opacity-60'
                                        ]"
                                    >
                                        <!-- Disabled state overlay -->
                                        <div v-if="requiresAgreement && !agreed" class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            请先勾选同意
                                        </div>
                                        <span :class="{ 'opacity-0': requiresAgreement && !agreed }">
                                            {{ confirmText }}
                                        </span>
                                    </button>
                                </div>
                            </slot>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch, computed, useSlots } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: ''
    },
    subtitle: {
        type: String,
        default: ''
    },
    icon: {
        type: [String, Object],
        default: null
    },
    iconColor: {
        type: String,
        default: 'amber',
        validator: (value) => ['amber', 'emerald', 'red', 'blue', 'purple'].includes(value)
    },
    merchant: {
        type: Object,
        default: null
    },
    terms: {
        type: String,
        default: ''
    },
    requiresAgreement: {
        type: Boolean,
        default: false
    },
    agreementText: {
        type: String,
        default: '我已了解并同意继续'
    },
    cancelText: {
        type: String,
        default: ''
    },
    confirmText: {
        type: String,
        default: ''
    },
    persistent: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'confirm']);
const slots = useSlots();

const agreed = ref(false);

const iconBgClass = computed(() => {
    const colorMap = {
        amber: 'bg-amber-100 dark:bg-amber-900/30',
        emerald: 'bg-emerald-100 dark:bg-emerald-900/30',
        red: 'bg-red-100 dark:bg-red-900/30',
        blue: 'bg-blue-100 dark:bg-blue-900/30',
        purple: 'bg-purple-100 dark:bg-purple-900/30'
    };
    return colorMap[props.iconColor] || colorMap.amber;
});

const iconClass = computed(() => {
    const colorMap = {
        amber: 'text-amber-600 dark:text-amber-400',
        emerald: 'text-emerald-600 dark:text-emerald-400',
        red: 'text-red-600 dark:text-red-400',
        blue: 'text-blue-600 dark:text-blue-400',
        purple: 'text-purple-600 dark:text-purple-400'
    };
    return colorMap[props.iconColor] || colorMap.amber;
});

const hasFooter = computed(() => {
    return props.cancelText || props.confirmText || slots.footer;
});

watch(() => props.show, (newVal) => {
    if (newVal && props.requiresAgreement) {
        agreed.value = false;
    }
});

const handleBackgroundClick = () => {
    if (!props.persistent) {
        emit('close');
    }
};

const handleConfirm = () => {
    if (!props.requiresAgreement || agreed.value) {
        emit('confirm');
    }
};

const defaultDocumentIcon = {
    template: `
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    `
};

if (!props.icon && (props.title || props.terms)) {
    props.icon = defaultDocumentIcon;
}
</script>