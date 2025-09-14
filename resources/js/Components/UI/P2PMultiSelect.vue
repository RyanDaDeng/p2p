<template>
    <div class="relative" ref="selectRef">
        <!-- Input Field -->
        <div 
            @click="toggleDropdown"
            class="relative w-full min-h-[2.5rem] px-3 py-2 border rounded-lg bg-white dark:bg-slate-800 transition-all cursor-pointer"
            :class="[
                isOpen 
                    ? 'border-emerald-500 ring-2 ring-emerald-500/20' 
                    : 'border-gray-300 dark:border-slate-600 hover:border-gray-400 dark:hover:border-slate-500',
                disabled ? 'opacity-50 cursor-not-allowed' : ''
            ]"
        >
            <!-- Selected Items Display -->
            <div class="flex items-center gap-2 min-h-[1.5rem]">
                <div class="flex-1 flex flex-wrap items-center gap-1.5">
                    <template v-if="selectedItems.length > 0">
                        <!-- Show tags for multi-select -->
                        <template v-if="multiple">
                            <span 
                                v-for="(item, index) in displayedItems" 
                                :key="getItemValue(item)"
                                class="inline-flex items-center gap-1 px-2 py-0.5 text-xs bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-full"
                            >
                                <span>{{ getItemLabel(item) }}</span>
                                <button 
                                    @click.stop="removeItem(item)"
                                    class="hover:text-emerald-900 dark:hover:text-emerald-100 transition-colors"
                                    type="button"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                            <span 
                                v-if="hiddenCount > 0"
                                class="inline-flex items-center px-2 py-0.5 text-xs bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400 rounded-full"
                            >
                                +{{ hiddenCount }}
                            </span>
                        </template>
                        <!-- Show single value for single-select -->
                        <template v-else>
                            <span class="text-sm text-gray-900 dark:text-slate-100 leading-6">
                                {{ getItemLabel(selectedItems[0]) }}
                            </span>
                        </template>
                    </template>
                    <template v-else>
                        <span class="text-sm text-gray-500 dark:text-slate-400 leading-6">{{ placeholder }}</span>
                    </template>
                </div>

                <!-- Dropdown Arrow -->
                <svg 
                    class="w-4 h-4 text-gray-400 dark:text-slate-500 transition-transform flex-shrink-0"
                    :class="isOpen ? 'rotate-180' : ''"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div 
                v-if="isOpen && !disabled"
                class="absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-gray-200 dark:border-slate-700 max-h-60 overflow-hidden"
            >
                <!-- Search Input -->
                <div v-if="searchable" class="p-2 border-b border-gray-200 dark:border-slate-700">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="搜索..."
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                        @click.stop
                    />
                </div>

                <!-- Options List -->
                <div class="overflow-y-auto max-h-48">
                    <div v-if="filteredOptions.length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-slate-400 text-center">
                        无匹配项
                    </div>
                    <button
                        v-for="option in filteredOptions"
                        :key="getItemValue(option)"
                        @click.stop="selectItem(option)"
                        type="button"
                        class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors flex items-center justify-between group"
                        :class="isSelected(option) ? 'bg-emerald-50 dark:bg-emerald-900/20' : ''"
                    >
                        <div class="flex items-center gap-2">
                            <!-- Checkbox/Radio for visual indication -->
                            <div v-if="multiple" class="flex-shrink-0">
                                <div 
                                    class="w-4 h-4 rounded border-2 transition-colors"
                                    :class="isSelected(option) 
                                        ? 'bg-emerald-600 border-emerald-600' 
                                        : 'border-gray-300 dark:border-slate-600'"
                                >
                                    <svg v-if="isSelected(option)" class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    </svg>
                                </div>
                            </div>
                            <div v-else class="flex-shrink-0">
                                <div 
                                    class="w-4 h-4 rounded-full border-2 transition-colors"
                                    :class="isSelected(option) 
                                        ? 'bg-emerald-600 border-emerald-600' 
                                        : 'border-gray-300 dark:border-slate-600'"
                                >
                                    <div v-if="isSelected(option)" class="w-full h-full flex items-center justify-center">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Option Label -->
                            <span 
                                class="text-gray-700 dark:text-slate-300"
                                :class="isSelected(option) ? 'font-medium text-emerald-700 dark:text-emerald-300' : ''"
                            >
                                {{ getItemLabel(option) }}
                            </span>
                        </div>

                        <!-- Optional Icon/Badge -->
                        <span v-if="option.badge" class="px-2 py-0.5 text-xs bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400 rounded">
                            {{ option.badge }}
                        </span>
                    </button>
                </div>

                <!-- Actions (for multi-select) -->
                <div v-if="multiple && showActions" class="p-2 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between">
                    <button 
                        @click.stop="selectAll"
                        type="button"
                        class="text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300"
                    >
                        全选
                    </button>
                    <button 
                        @click.stop="clearSelection"
                        type="button"
                        class="text-xs text-gray-600 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300"
                    >
                        清空
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue: {
        type: [Array, String, Number, Object],
        default: () => []
    },
    options: {
        type: Array,
        required: true
    },
    multiple: {
        type: Boolean,
        default: true
    },
    searchable: {
        type: Boolean,
        default: true
    },
    placeholder: {
        type: String,
        default: '请选择'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    labelKey: {
        type: String,
        default: 'label'
    },
    valueKey: {
        type: String,
        default: 'value'
    },
    maxDisplay: {
        type: Number,
        default: 3
    },
    showActions: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['update:modelValue', 'change']);

const selectRef = ref(null);
const isOpen = ref(false);
const searchQuery = ref('');

// Computed
const selectedItems = computed(() => {
    if (!props.modelValue) return [];
    
    if (props.multiple) {
        const values = Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue];
        return values.map(val => {
            if (typeof val === 'object') return val;
            return props.options.find(opt => getItemValue(opt) === val) || val;
        });
    } else {
        const value = props.modelValue;
        if (typeof value === 'object') return [value];
        const found = props.options.find(opt => getItemValue(opt) === value);
        return found ? [found] : [];
    }
});

const displayedItems = computed(() => {
    return selectedItems.value.slice(0, props.maxDisplay);
});

const hiddenCount = computed(() => {
    return Math.max(0, selectedItems.value.length - props.maxDisplay);
});

const filteredOptions = computed(() => {
    if (!searchQuery.value) return props.options;
    
    const query = searchQuery.value.toLowerCase();
    return props.options.filter(option => {
        const label = getItemLabel(option).toLowerCase();
        return label.includes(query);
    });
});

// Methods
const getItemLabel = (item) => {
    if (typeof item === 'string') return item;
    if (typeof item === 'object' && item !== null) {
        return item[props.labelKey] || item.label || item.name || '';
    }
    return String(item);
};

const getItemValue = (item) => {
    if (typeof item === 'string' || typeof item === 'number') return item;
    if (typeof item === 'object' && item !== null) {
        return item[props.valueKey] || item.value || item.id || item;
    }
    return item;
};

const isSelected = (option) => {
    const value = getItemValue(option);
    if (props.multiple) {
        const values = Array.isArray(props.modelValue) ? props.modelValue : [];
        return values.some(v => {
            const compareValue = typeof v === 'object' ? getItemValue(v) : v;
            return compareValue === value;
        });
    } else {
        const compareValue = typeof props.modelValue === 'object' 
            ? getItemValue(props.modelValue) 
            : props.modelValue;
        return compareValue === value;
    }
};

const toggleDropdown = () => {
    if (props.disabled) return;
    isOpen.value = !isOpen.value;
};

const selectItem = (option) => {
    const value = getItemValue(option);
    
    if (props.multiple) {
        let newValue = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
        const index = newValue.findIndex(v => {
            const compareValue = typeof v === 'object' ? getItemValue(v) : v;
            return compareValue === value;
        });
        
        if (index > -1) {
            newValue.splice(index, 1);
        } else {
            newValue.push(value);
        }
        
        emit('update:modelValue', newValue);
        emit('change', newValue);
    } else {
        emit('update:modelValue', value);
        emit('change', value);
        isOpen.value = false;
    }
};

const removeItem = (item) => {
    if (!props.multiple || props.disabled) return;
    
    const value = getItemValue(item);
    let newValue = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
    const index = newValue.findIndex(v => {
        const compareValue = typeof v === 'object' ? getItemValue(v) : v;
        return compareValue === value;
    });
    
    if (index > -1) {
        newValue.splice(index, 1);
        emit('update:modelValue', newValue);
        emit('change', newValue);
    }
};

const selectAll = () => {
    if (!props.multiple) return;
    
    const allValues = filteredOptions.value.map(opt => getItemValue(opt));
    emit('update:modelValue', allValues);
    emit('change', allValues);
};

const clearSelection = () => {
    emit('update:modelValue', props.multiple ? [] : null);
    emit('change', props.multiple ? [] : null);
};

// Click outside handler
const handleClickOutside = (event) => {
    if (!selectRef.value || selectRef.value.contains(event.target)) return;
    isOpen.value = false;
};

// Lifecycle
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

// Reset search when dropdown closes
watch(isOpen, (newVal) => {
    if (!newVal) {
        searchQuery.value = '';
    }
});
</script>