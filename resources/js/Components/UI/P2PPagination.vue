<template>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-gray-200 dark:border-slate-800">
        <!-- Results info -->
        <div class="text-sm text-gray-600 dark:text-slate-400 order-2 sm:order-1">
            显示 <span class="text-emerald-600 dark:text-emerald-500 font-semibold">{{ from }}-{{ to }}</span> 
            共 <span class="text-gray-900 dark:text-slate-200 font-semibold">{{ total }}</span> 条交易
        </div>
        
        <!-- Pagination controls -->
        <div class="flex items-center gap-1 order-1 sm:order-2 bg-white dark:bg-slate-800/50 p-1 rounded-lg border border-gray-200 dark:border-transparent">
            <!-- First page -->
            <button
                @click="goToPage(1)"
                :disabled="currentPage === 1"
                class="p-2 rounded-md transition-all disabled:opacity-30 disabled:cursor-not-allowed text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700/50"
                aria-label="第一页"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
            
            <!-- Previous page -->
            <button
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="p-2 rounded-md transition-all disabled:opacity-30 disabled:cursor-not-allowed text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700/50"
                aria-label="上一页"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            
            <!-- Page numbers -->
            <div class="flex items-center gap-1 px-1">
                <template v-for="page in visiblePages" :key="page">
                    <button
                        v-if="page !== '...'"
                        @click="goToPage(page)"
                        :class="[
                            'min-w-[2rem] h-8 px-2 rounded-md font-medium text-sm transition-all',
                            page === currentPage
                                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 dark:shadow-emerald-600/10'
                                : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700/50'
                        ]"
                    >
                        {{ page }}
                    </button>
                    <span
                        v-else
                        class="px-2 text-gray-400 dark:text-slate-500"
                    >
                        ···
                    </span>
                </template>
            </div>
            
            <!-- Next page -->
            <button
                @click="goToPage(currentPage + 1)"
                :disabled="currentPage === lastPage"
                class="p-2 rounded-md transition-all disabled:opacity-30 disabled:cursor-not-allowed text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700/50"
                aria-label="下一页"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            
            <!-- Last page -->
            <button
                @click="goToPage(lastPage)"
                :disabled="currentPage === lastPage"
                class="p-2 rounded-md transition-all disabled:opacity-30 disabled:cursor-not-allowed text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700/50"
                aria-label="最后页"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    total: {
        type: Number,
        required: true
    },
    perPage: {
        type: Number,
        default: 10
    }
});

const emit = defineEmits(['update:currentPage']);

const lastPage = computed(() => Math.ceil(props.total / props.perPage));

const from = computed(() => {
    if (props.total === 0) return 0;
    return (props.currentPage - 1) * props.perPage + 1;
});

const to = computed(() => {
    const end = props.currentPage * props.perPage;
    return end > props.total ? props.total : end;
});

const visiblePages = computed(() => {
    const pages = [];
    const total = lastPage.value;
    const current = props.currentPage;
    const delta = 2;
    
    if (total <= 7) {
        for (let i = 1; i <= total; i++) {
            pages.push(i);
        }
    } else {
        if (current > delta + 1) {
            pages.push(1);
            if (current > delta + 2) {
                pages.push('...');
            }
        }
        
        const rangeStart = Math.max(1, current - delta);
        const rangeEnd = Math.min(total, current + delta);
        
        for (let i = rangeStart; i <= rangeEnd; i++) {
            if (!pages.includes(i)) {
                pages.push(i);
            }
        }
        
        if (current < total - delta) {
            if (current < total - delta - 1) {
                pages.push('...');
            }
            if (!pages.includes(total)) {
                pages.push(total);
            }
        }
    }
    
    return pages;
});

const goToPage = (page) => {
    if (page !== '...' && page >= 1 && page <= lastPage.value) {
        emit('update:currentPage', page);
    }
};
</script>