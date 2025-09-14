<template>
    <div class="md:hidden sticky top-0 z-50 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
        <div class="flex items-center justify-between h-14 px-4">
            <!-- Left: Back Button -->
            <div class="flex items-center gap-3 flex-1">
                <button 
                    @click="handleBack"
                    class="p-2 -ml-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                >
                    <svg class="w-5 h-5 text-gray-700 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <!-- Page Title or Custom Content -->
                <slot name="custom">
                    <div>
                        <h1 class="text-base font-semibold text-gray-900 dark:text-slate-100">{{ title }}</h1>
                        <p v-if="subtitle" class="text-xs text-gray-500 dark:text-slate-400">{{ subtitle }}</p>
                    </div>
                </slot>
            </div>
            
            <!-- Right: Actions -->
            <div class="flex items-center gap-1">
                <slot name="actions">
                    <!-- Default actions can go here -->
                </slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        default: ''
    },
    backUrl: {
        type: String,
        default: null
    }
});

const handleBack = () => {
    if (props.backUrl) {
        router.visit(props.backUrl);
    } else {
        // 默认返回上一页
        window.history.back();
    }
};
</script>