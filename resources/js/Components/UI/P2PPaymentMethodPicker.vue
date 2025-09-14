<template>
    <div>
        <!-- Trigger Button -->
        <button 
            @click="showPicker = true"
            type="button"
            class="relative inline-flex items-center justify-between w-full px-4 h-10 text-sm rounded-lg border shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900/50 text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:border-gray-400 dark:hover:border-slate-600 cursor-pointer"
        >
            <span class="flex items-center flex-1 min-w-0">
                <span v-if="selectedMethod" class="flex items-center gap-1.5">
                    <span class="text-base flex-shrink-0">{{ selectedMethodDetails.categoryIcon }}</span>
                    <span class="text-gray-900 dark:text-slate-100 whitespace-nowrap">{{ selectedMethodDetails.label }}</span>
                </span>
                <span v-else class="text-gray-500 dark:text-slate-400 whitespace-nowrap">{{ placeholder }}</span>
            </span>
            <svg class="ml-2 h-4 w-4 text-gray-500 dark:text-slate-500 flex-shrink-0" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Payment Method Picker Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showPicker" class="fixed inset-0 z-50 overflow-y-auto">
                    <!-- Overlay -->
                    <div class="fixed inset-0 bg-black/50 dark:bg-black/70" @click="closePicker"></div>
                    
                    <!-- Modal Content -->
                    <div class="flex items-center justify-center min-h-full p-4">
                        <div class="relative bg-white dark:bg-slate-900 rounded-xl shadow-xl max-w-2xl w-full max-h-[80vh] flex flex-col">
                            <!-- Header -->
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-800">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-slate-100">
                                        选择支付
                                    </h3>
                                    <button 
                                        @click="closePicker"
                                        class="text-gray-400 hover:text-gray-500 dark:text-slate-500 dark:hover:text-slate-400 cursor-pointer"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Search Bar -->
                                <div class="mt-4 relative">
                                    <input 
                                        v-model="searchQuery"
                                        type="text"
                                        placeholder="搜索支付..."
                                        class="w-full px-10 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    />
                                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>

                            </div>

                            <!-- Content -->
                            <div class="flex-1 overflow-y-auto px-6 py-4">
                                <!-- All Methods (categorized) -->
                                <div class="space-y-6">
                                    <div v-for="category in filteredCategories" :key="category.id" v-show="category.filteredMethods.length > 0">
                                        <!-- Category Header -->
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="text-xl">{{ category.icon }}</span>
                                            <h4 class="font-medium text-gray-900 dark:text-slate-100">{{ category.name }}</h4>
                                            <span class="text-xs text-gray-500 dark:text-slate-400">({{ category.filteredMethods.length }})</span>
                                        </div>

                                        <!-- Methods Grid -->
                                        <div class="grid grid-cols-2 gap-2">
                                            <div
                                                v-for="method in category.filteredMethods"
                                                :key="method.value"
                                                @click="selectMethod(method.value)"
                                                :class="[
                                                    'p-3 rounded-lg border cursor-pointer transition-all hover:shadow-md',
                                                    tempSelected === method.value
                                                        ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20'
                                                        : 'border-gray-200 dark:border-slate-700 hover:border-gray-300 dark:hover:border-slate-600'
                                                ]"
                                            >
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-medium text-sm text-gray-900 dark:text-slate-100">
                                                            {{ method.label }}
                                                        </span>
                                                    </div>
                                                    <svg v-if="tempSelected === method.value" class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- No Results -->
                                <div v-if="!hasResults" class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">没有找到支付</p>
                                    <p class="text-xs text-gray-400 dark:text-slate-500">请尝试调整搜索关键词</p>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-slate-800">
                                <!-- Desktop: side by side layout -->
                                <div class="hidden sm:flex items-center justify-between">
                                    <p class="text-xs text-gray-500 dark:text-slate-400">
                                        选择您想要在此广告中使用的支付
                                    </p>
                                    <div class="flex gap-2">
                                        <button
                                            @click="resetSelection"
                                            class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors cursor-pointer"
                                        >
                                            清除
                                        </button>
                                        <button
                                            @click="closePicker"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                                        >
                                            取消
                                        </button>
                                        <button
                                            @click="confirmSelection"
                                            :disabled="!tempSelected"
                                            class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 disabled:bg-gray-300 dark:disabled:bg-slate-700 disabled:cursor-not-allowed transition-colors cursor-pointer"
                                        >
                                            确认
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Mobile: stacked layout -->
                                <div class="sm:hidden space-y-3">
                                    <p class="text-xs text-gray-500 dark:text-slate-400 text-center">
                                        选择您想要在此广告中使用的支付
                                    </p>
                                    <div class="grid grid-cols-3 gap-2">
                                        <button
                                            @click="resetSelection"
                                            class="px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/50 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors cursor-pointer"
                                        >
                                            清除
                                        </button>
                                        <button
                                            @click="closePicker"
                                            class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                                        >
                                            取消
                                        </button>
                                        <button
                                            @click="confirmSelection"
                                            :disabled="!tempSelected"
                                            class="px-3 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 disabled:bg-gray-300 dark:disabled:bg-slate-700 disabled:cursor-not-allowed transition-colors cursor-pointer"
                                        >
                                            确认
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: '选择支付'
    },
    categories: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:modelValue']);

const showPicker = ref(false);
const searchQuery = ref('');
const tempSelected = ref(props.modelValue);

// Use categories from props
const paymentCategories = computed(() => props.categories);

const selectedMethod = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const selectedMethodDetails = computed(() => {
    if (!selectedMethod.value) return null;
    // Find method details from categories
    for (const category of paymentCategories.value) {
        const method = category.methods?.find(m => m.value === selectedMethod.value);
        if (method) {
            return {
                ...method,
                category: category.name,
                categoryId: category.id,
                categoryIcon: category.icon,
                categoryColor: category.color
            };
        }
    }
    return null;
});

const filteredCategories = computed(() => {
    return paymentCategories.value.map(category => ({
        ...category,
        filteredMethods: category.methods.filter(method => {
            if (!searchQuery.value) return true;
            const query = searchQuery.value.toLowerCase();
            return method.label.toLowerCase().includes(query) ||
                   method.value.toLowerCase().includes(query) ||
                   category.name.toLowerCase().includes(query);
        })
    }));
});

const hasResults = computed(() => {
    return filteredCategories.value.some(cat => cat.filteredMethods.length > 0);
});

const getCategoryIcon = (categoryId) => {
    const category = paymentCategories.value.find(c => c.id === categoryId);
    return category ? category.icon : '📱';
};

const selectMethod = (value) => {
    tempSelected.value = value;
};

const confirmSelection = () => {
    if (tempSelected.value) {
        selectedMethod.value = tempSelected.value;
        closePicker();
    }
};

const resetSelection = () => {
    tempSelected.value = null;
    selectedMethod.value = null;
    closePicker();
};

const closePicker = () => {
    showPicker.value = false;
    searchQuery.value = '';
};

// Reset temp selection when picker opens
watch(showPicker, (isOpen) => {
    if (isOpen) {
        tempSelected.value = props.modelValue;
    }
});
</script>