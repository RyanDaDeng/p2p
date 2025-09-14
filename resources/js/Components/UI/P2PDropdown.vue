<template>
    <div class="relative">
        <div @click="toggle" class="cursor-pointer">
            <slot name="trigger" />
        </div>
        
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="open"
                ref="dropdownRef"
                :class="[
                    'absolute z-50 mt-2 rounded-xl bg-white dark:bg-slate-800 shadow-xl ring-1 ring-gray-200 dark:ring-slate-700/50 backdrop-blur-xl',
                    alignmentClasses,
                    widthClasses
                ]"
                :style="dropdownStyle"
            >
                <!-- Dropdown Content -->
                <div class="py-[0.25rem]" role="menu" aria-orientation="vertical">
                    <slot name="content" />
                </div>
                
                <!-- Trust Footer (Optional) -->
                <div v-if="showTrustFooter" class="px-[0.75rem] py-[0.5rem] bg-gray-50 dark:bg-slate-900/50 border-t border-gray-200 dark:border-slate-700/50 rounded-b-xl">
                    <div class="flex items-center justify-center gap-[0.5rem]">
                        <svg class="w-[0.75rem] h-[0.75rem] text-emerald-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="text-xs text-gray-600 dark:text-slate-500">安全操作</span>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, watch, onUnmounted, nextTick } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'left',
        validator: (value) => ['left', 'right', 'center'].includes(value)
    },
    width: {
        type: String,
        default: '48',
        validator: (value) => ['48', '56', '64', 'full'].includes(value)
    },
    showTrustFooter: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['open', 'close']);

const open = ref(false);
const dropdownRef = ref(null);
const dropdownStyle = ref({});

// Global state for managing dropdowns
let activeDropdown = null;

const toggle = () => {
    if (open.value) {
        close();
    } else {
        // Close any other open dropdown
        if (activeDropdown && activeDropdown !== close) {
            activeDropdown();
        }
        open.value = true;
        activeDropdown = close;
        emit('open');
        nextTick(() => {
            updateDropdownPosition();
        });
    }
};

const close = () => {
    open.value = false;
    if (activeDropdown === close) {
        activeDropdown = null;
    }
    emit('close');
};

const updateDropdownPosition = () => {
    if (!dropdownRef.value) return;
    
    const rect = dropdownRef.value.getBoundingClientRect();
    const windowHeight = window.innerHeight;
    const windowWidth = window.innerWidth;
    
    let styles = {};
    
    // Vertical positioning
    if (rect.bottom > windowHeight - 20) {
        // Position above if not enough space below
        styles.bottom = '100%';
        styles.top = 'auto';
        styles.marginBottom = '0.5rem';
        styles.marginTop = '0';
    }
    
    // Horizontal positioning for edge cases
    if (props.align === 'right' && rect.left < 20) {
        styles.right = 'auto';
        styles.left = '0';
    } else if (props.align === 'left' && rect.right > windowWidth - 20) {
        styles.left = 'auto';
        styles.right = '0';
    }
    
    dropdownStyle.value = styles;
};

const alignmentClasses = computed(() => {
    const alignments = {
        left: 'left-0 origin-top-left',
        right: 'right-0 origin-top-right',
        center: 'left-1/2 -translate-x-1/2 origin-top'
    };
    return alignments[props.align];
});

const widthClasses = computed(() => {
    const widths = {
        '48': 'w-48',
        '56': 'w-56',
        '64': 'w-64',
        'full': 'w-full'
    };
    return widths[props.width];
});

const handleClickOutside = (event) => {
    const target = event.target;
    const dropdown = target.closest('.relative');
    
    // If click is outside this dropdown component
    if (!dropdown || !dropdown.contains(event.target)) {
        close();
    }
};

const handleEscape = (event) => {
    if (event.key === 'Escape' && open.value) {
        close();
    }
};

// Expose close method for parent components
defineExpose({
    close,
    open
});

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