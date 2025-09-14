<template>
    <span :class="classes">
        <span v-if="dot" :class="dotClasses"></span>
        <slot />
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'primary', 'success', 'warning', 'danger', 'info', 'emerald', 'blue', 'amber', 'gray'].includes(value)
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    dot: {
        type: Boolean,
        default: false
    },
    rounded: {
        type: Boolean,
        default: false
    }
});

const classes = computed(() => {
    const base = 'inline-flex items-center font-medium';
    
    const variants = {
        default: 'bg-gray-100 text-gray-800 dark:bg-slate-800 dark:text-slate-300',
        primary: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        success: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        warning: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        danger: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
        info: 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
        emerald: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
        blue: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
        amber: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
        gray: 'bg-gray-100 text-gray-700 dark:bg-slate-800 dark:text-slate-400'
    };
    
    const sizes = {
        sm: 'px-2 py-0.5 text-xs',
        md: 'px-2.5 py-0.5 text-sm',
        lg: 'px-3 py-1 text-base'
    };
    
    const rounded = props.rounded ? 'rounded-full' : 'rounded-md';
    
    return `${base} ${variants[props.variant]} ${sizes[props.size]} ${rounded}`;
});

const dotClasses = computed(() => {
    const base = 'mr-1.5 h-2 w-2 rounded-full';
    
    const colors = {
        default: 'bg-gray-500',
        primary: 'bg-blue-500',
        success: 'bg-green-500',
        warning: 'bg-yellow-500',
        danger: 'bg-red-500',
        info: 'bg-purple-500',
        emerald: 'bg-emerald-500',
        blue: 'bg-blue-500',
        amber: 'bg-amber-500',
        gray: 'bg-gray-500'
    };
    
    return `${base} ${colors[props.variant]}`;
});
</script>