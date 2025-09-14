<script setup>
import { computed, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value),
    },
    title: {
        type: String,
        default: '',
    },
    message: {
        type: String,
        required: true,
    },
    duration: {
        type: Number,
        default: 5000,
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

let timeout;

const iconClass = computed(() => {
    const classes = {
        success: 'text-emerald-500 dark:text-emerald-400',
        error: 'text-red-500 dark:text-red-400',
        warning: 'text-amber-500 dark:text-amber-400',
        info: 'text-blue-500 dark:text-blue-400',
    };
    return classes[props.type];
});

const bgClass = computed(() => {
    const classes = {
        success: 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800',
        error: 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
        warning: 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
        info: 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
    };
    return classes[props.type];
});

const handleClose = () => {
    emit('close', props.id);
};

onMounted(() => {
    if (props.duration > 0) {
        timeout = setTimeout(() => {
            handleClose();
        }, props.duration);
    }
});

onBeforeUnmount(() => {
    if (timeout) {
        clearTimeout(timeout);
    }
});
</script>

<template>
    <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:-translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg border"
            :class="bgClass">
            <div class="p-4">
                <div class="flex items-start">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <svg v-if="type === 'success'" class="h-5 w-5" :class="iconClass" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <svg v-else-if="type === 'error'" class="h-5 w-5" :class="iconClass" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <svg v-else-if="type === 'warning'" class="h-5 w-5" :class="iconClass" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <svg v-else class="h-5 w-5" :class="iconClass" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="ml-3 w-0 flex-1">
                        <p v-if="title" class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                            {{ title }}
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400" :class="{ 'mt-1': title }">
                            {{ message }}
                        </p>
                    </div>

                    <!-- Close button -->
                    <div v-if="closeable" class="ml-4 flex flex-shrink-0">
                        <button @click="handleClose"
                            class="inline-flex rounded-md text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400 focus:outline-none transition">
                            <span class="sr-only">关闭</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>