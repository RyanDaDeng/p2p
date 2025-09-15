<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="modelValue" class="fixed inset-0 z-50 overflow-hidden">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="handleClose"></div>
                
                <div class="flex items-center justify-center min-h-screen p-4">
                    <Transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div v-if="modelValue" 
                            class="relative bg-white dark:bg-gray-900 rounded shadow-2xl w-full max-w-2xl max-h-[90vh] md:max-h-[85vh] flex flex-col pt-safe"
                        >
                            <div class="flex-shrink-0 px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ title }}
                                        </h2>
                                        <p v-if="subtitle" class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            {{ subtitle }}
                                        </p>
                                    </div>
                                    <button
                                        @click="handleClose"
                                        class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                                    >
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex-1 overflow-y-auto px-6 py-4 scrollbar-thin">
                                <slot name="content" />
                            </div>

                            <div class="flex-shrink-0 px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 rounded-b">
                                <slot name="footer">
                                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                        <button
                                            v-if="showCancel"
                                            @click="handleCancel"
                                            class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                                        >
                                            {{ cancelText }}
                                        </button>
                                        <button
                                            v-if="showConfirm"
                                            @click="handleConfirm"
                                            :disabled="confirmDisabled || loading"
                                            class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer flex items-center justify-center"
                                        >
                                            <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            {{ confirmText }}
                                        </button>
                                    </div>
                                </slot>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        default: ''
    },
    showCancel: {
        type: Boolean,
        default: true
    },
    showConfirm: {
        type: Boolean,
        default: true
    },
    cancelText: {
        type: String,
        default: '取消'
    },
    confirmText: {
        type: String,
        default: '确认'
    },
    confirmDisabled: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    },
    persistent: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue', 'confirm', 'cancel', 'close']);

const handleClose = () => {
    if (!props.persistent && !props.loading) {
        emit('update:modelValue', false);
        emit('close');
    }
};

const handleCancel = () => {
    if (!props.loading) {
        emit('cancel');
        emit('update:modelValue', false);
    }
};

const handleConfirm = () => {
    if (!props.confirmDisabled && !props.loading) {
        emit('confirm');
    }
};

const handleEscape = (e) => {
    if (e.key === 'Escape' && props.modelValue) {
        handleClose();
    }
};

watch(() => props.modelValue, (newVal) => {
    if (newVal) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

onMounted(() => {
    document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleEscape);
    document.body.style.overflow = '';
});
</script>

<style scoped>
.scrollbar-thin {
    scrollbar-width: thin;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: rgb(209 213 219);
    border-radius: 9999px;
}

.dark .scrollbar-thin::-webkit-scrollbar-thumb {
    background: rgb(51 65 85);
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: rgb(156 163 175);
}

.dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: rgb(71 85 105);
}

@media (max-width: 640px) {
    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }
}
</style>