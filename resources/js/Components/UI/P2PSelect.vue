<template>
    <div class="relative">
        <button
            type="button"
            @click="toggle"
            :class="buttonClasses"
            :disabled="disabled"
        >
            <span class="flex items-center min-w-0 flex-1">
                <!-- Slot for custom selected option display -->
                <slot name="selected" :option="selectedOption" v-if="selectedOption && $slots.selected">
                    <!-- Custom content will be inserted here -->
                </slot>
                <!-- Default display -->
                <template v-else>
                    <span v-if="selectedOption?.icon" class="mr-2 text-gray-500 dark:text-slate-400 flex-shrink-0">{{ selectedOption.icon }}</span>
                    <span v-if="selectedOption?.flag" class="mr-2 text-lg flex-shrink-0">{{ selectedOption.flag }}</span>
                    <span class="truncate block" :class="selectedOption ? 'text-gray-900 dark:text-slate-100' : 'text-gray-500 dark:text-slate-400'">
                        {{ selectedOption?.label || placeholder }}
                    </span>
                </template>
            </span>
            <svg class="ml-2 h-4 w-4 text-gray-500 dark:text-slate-500 transition-transform duration-200 flex-shrink-0" 
                 :class="{ 'rotate-180': open }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
        >
            <div
                v-show="open"
                ref="dropdownRef"
                class="absolute z-50 mt-2 w-full min-w-max rounded-xl bg-white dark:bg-slate-800 shadow-xl ring-1 ring-gray-200 dark:ring-slate-700/50 backdrop-blur-xl"
                :style="dropdownStyle"
            >
                <!-- Dropdown Header -->
                <div class="px-3 py-2 border-b border-gray-200 dark:border-slate-700/50">
                    <p class="text-xs font-medium text-gray-500 dark:text-slate-400">{{ placeholder }}</p>
                </div>
                
                <!-- Clear Button -->
                <div v-if="modelValue && modelValue !== defaultValue" class="border-b border-gray-200 dark:border-slate-700/50">
                    <button
                        type="button"
                        @click="clearSelection"
                        class="w-full flex items-center px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors cursor-pointer"
                    >
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>清除选择</span>
                    </button>
                </div>
                
                <!-- Options List -->
                <div class="max-h-60 overflow-auto py-1 custom-scrollbar">
                    <button
                        v-for="option in options"
                        :key="option.value"
                        type="button"
                        @click="!option.disabled && selectOption(option)"
                        :class="optionClasses(option)"
                        :disabled="option.disabled"
                    >
                        <span class="flex items-center">
                            <!-- Slot for custom option display -->
                            <slot name="option" :option="option" v-if="$slots.option">
                                <!-- Custom content will be inserted here -->
                            </slot>
                            <!-- Default display -->
                            <template v-else>
                                <span v-if="option.icon" class="mr-2 text-gray-500 dark:text-slate-400">{{ option.icon }}</span>
                                <span v-if="option.flag" class="mr-2 text-lg">{{ option.flag }}</span>
                                <span class="truncate">{{ option.label }}</span>
                            </template>
                        </span>
                        
                        <!-- Selected Indicator -->
                        <Transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 scale-0"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-0"
                        >
                            <div v-if="option.value === modelValue" class="flex items-center">
                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2"></div>
                                <svg class="h-4 w-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </Transition>
                    </button>
                </div>
                
                <!-- Trust Footer -->
                <div class="px-3 py-2 bg-gray-50 dark:bg-slate-900/50 border-t border-gray-200 dark:border-slate-700/50 rounded-b-xl">
                    <div class="flex items-center justify-center gap-1.5">
                        <img src="/logo.png" alt="Logo" class="h-3.5 w-auto" />
                        <span class="text-xs font-medium text-gray-600 dark:text-slate-400">P2PCoinSwap</span>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        default: null
    },
    options: {
        type: Array,
        required: true
    },
    placeholder: {
        type: String,
        default: '请选择'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    defaultValue: {
        type: [String, Number, null],
        default: null
    }
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);
const dropdownRef = ref(null);
const dropdownStyle = ref({});

// Global state for managing dropdowns
let activeDropdown = null;

const selectedOption = computed(() => {
    return props.options.find(option => option.value === props.modelValue);
});

const toggle = () => {
    if (!props.disabled) {
        if (open.value) {
            close();
        } else {
            // Close any other open dropdown
            if (activeDropdown && activeDropdown !== close) {
                activeDropdown();
            }
            open.value = true;
            activeDropdown = close;
            nextTick(() => {
                updateDropdownPosition();
            });
        }
    }
};

const close = () => {
    open.value = false;
    if (activeDropdown === close) {
        activeDropdown = null;
    }
};

const selectOption = (option) => {
    emit('update:modelValue', option.value);
    close();
};

const clearSelection = () => {
    // If defaultValue is provided, reset to that, otherwise null
    emit('update:modelValue', props.defaultValue);
    close();
};

const updateDropdownPosition = () => {
    if (!dropdownRef.value) return;
    
    const rect = dropdownRef.value.getBoundingClientRect();
    const windowHeight = window.innerHeight;
    
    // Check if dropdown would go off bottom of screen
    if (rect.bottom > windowHeight) {
        // Position above the button instead
        dropdownStyle.value = {
            bottom: '100%',
            top: 'auto',
            marginBottom: '0.5rem',
            marginTop: '0'
        };
    } else {
        // Normal position below
        dropdownStyle.value = {};
    }
};

const buttonClasses = computed(() => {
    const base = 'relative w-full cursor-pointer rounded-lg border backdrop-blur text-left shadow-sm transition-all focus:border-emerald-500/50 focus:outline-none focus:ring-2 focus:ring-emerald-500/20';
    
    const sizes = {
        sm: 'px-3 h-8 text-xs',
        md: 'px-4 h-10 text-sm',
        lg: 'px-4 h-12 text-base'
    };
    
    const state = props.disabled
        ? 'cursor-not-allowed border-gray-300 dark:border-slate-800 bg-gray-100 dark:bg-slate-900/30 text-gray-400 dark:text-slate-600'
        : open.value
        ? 'border-emerald-500/50 text-gray-900 dark:text-slate-100 bg-white dark:bg-slate-900/70'
        : 'border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900/50 text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:border-gray-400 dark:hover:border-slate-600';
    
    return `${base} ${sizes[props.size]} ${state} flex items-center justify-between`;
});

const optionClasses = (option) => {
    const base = 'relative flex w-full select-none items-center justify-between px-3 py-2 text-sm transition-all';

    // Handle disabled state
    if (option.disabled) {
        return `${base} cursor-not-allowed opacity-50 text-gray-400 dark:text-slate-500 bg-gray-50 dark:bg-slate-800/50`;
    }

    // Normal state
    const state = option.value === props.modelValue
        ? 'cursor-pointer bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-medium'
        : 'cursor-pointer text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-slate-100';

    return `${base} ${state}`;
};

const handleClickOutside = (event) => {
    // Get the parent element that contains both button and dropdown
    const componentEl = dropdownRef.value?.closest('.relative');
    
    // If click is outside this component, close the dropdown
    if (componentEl && !componentEl.contains(event.target)) {
        close();
    }
};

const handleEscape = (event) => {
    if (event.key === 'Escape' && open.value) {
        close();
    }
};

watch(open, (newValue) => {
    if (newValue) {
        // Use setTimeout to avoid catching the opening click
        setTimeout(() => {
            document.addEventListener('click', handleClickOutside);
            document.addEventListener('keydown', handleEscape);
            window.addEventListener('resize', updateDropdownPosition);
            window.addEventListener('scroll', updateDropdownPosition, true);
        }, 0);
    } else {
        document.removeEventListener('click', handleClickOutside);
        document.removeEventListener('keydown', handleEscape);
        window.removeEventListener('resize', updateDropdownPosition);
        window.removeEventListener('scroll', updateDropdownPosition, true);
    }
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleEscape);
    window.removeEventListener('resize', updateDropdownPosition);
    window.removeEventListener('scroll', updateDropdownPosition, true);
    
    if (activeDropdown === close) {
        activeDropdown = null;
    }
});
</script>

<style scoped>
/* Light mode scrollbar */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Dark mode scrollbar */
.dark .custom-scrollbar {
    scrollbar-color: #475569 #1e293b;
}

.dark .custom-scrollbar::-webkit-scrollbar-track {
    background: #1e293b;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #475569;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>