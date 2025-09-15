<template>
    <component
        :is="as"
        :type="type"
        :class="classes"
        :disabled="disabled || loading"
        class="cursor-pointer"
    >
        <!-- Loading spinner -->
        <svg 
            v-if="loading" 
            class="animate-spin" 
            :class="iconSizeClass"
            fill="none" 
            viewBox="0 0 24 24"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        
        <!-- Icon (if provided via slot) -->
        <span v-if="$slots.icon && !loading" :class="iconSizeClass">
            <slot name="icon" />
        </span>
        
        <!-- Button text -->
        <span v-if="!iconOnly">
            <slot />
        </span>
    </component>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => [
            'primary',
            'secondary', 
            'danger',
            'warning',
            'success',
            'ghost',
            'outline',
            'blue'
        ].includes(value)
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value)
    },
    fullWidth: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    },
    as: {
        type: String,
        default: 'button'
    },
    type: {
        type: String,
        default: 'button'
    },
    iconOnly: {
        type: Boolean,
        default: false
    },
    rounded: {
        type: String,
        default: 'sm',
        validator: (value) => ['sm', 'md', 'lg', 'xl', '2xl', 'full'].includes(value)
    }
});

// 基础样式
const baseClasses = 'inline-flex items-center justify-center gap-1.5 font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed';

// 按钮变体样式（使用我们的品牌色）
const variants = {
    primary: 'bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-emerald-50 shadow-sm hover:shadow-md focus:ring-emerald-500 dark:focus:ring-emerald-400',
    secondary: 'bg-gray-600 dark:bg-gray-600 hover:bg-gray-700 dark:hover:bg-gray-500 active:bg-gray-800 dark:active:bg-gray-700 text-white focus:ring-gray-500 dark:focus:ring-gray-400',
    blue: 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white shadow-sm hover:shadow-md focus:ring-indigo-500 dark:focus:ring-indigo-400',
    danger: 'bg-red-600 hover:bg-red-700 active:bg-red-800 text-white focus:ring-red-500 dark:focus:ring-red-400',
    warning: 'bg-amber-600 hover:bg-amber-700 active:bg-amber-800 text-white focus:ring-amber-500 dark:focus:ring-amber-400',
    success: 'bg-green-600 hover:bg-green-700 active:bg-green-800 text-white focus:ring-green-500 dark:focus:ring-green-400',
    ghost: 'bg-transparent hover:bg-gray-100 dark:hover:bg-gray-800 active:bg-gray-200 dark:active:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-gray-400 dark:focus:ring-gray-500',
    outline: 'bg-transparent border-2 border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800/50 focus:ring-gray-400 dark:focus:ring-gray-500'
};

// 按钮尺寸样式
const sizes = {
    xs: 'px-2 py-1 text-xs',
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-4 py-2 text-sm',
    lg: 'px-5 py-2.5 text-base',
    xl: 'px-6 py-3 text-lg'
};

// 图标尺寸
const iconSizes = {
    xs: 'w-3 h-3',
    sm: 'w-3.5 h-3.5',
    md: 'w-4 h-4',
    lg: 'w-5 h-5',
    xl: 'w-6 h-6'
};

// 圆角样式
const roundedStyles = {
    sm: 'rounded-sm',
    md: 'rounded-md',
    lg: 'rounded-lg',
    xl: 'rounded-xl',
    '2xl': 'rounded-2xl',
    full: 'rounded-full'
};

// 计算按钮类
const classes = computed(() => {
    const width = props.fullWidth ? 'w-full' : '';
    const iconOnlyPadding = props.iconOnly ? 
        `p-${props.size === 'xs' ? '1' : props.size === 'sm' ? '1.5' : props.size === 'md' ? '2' : props.size === 'lg' ? '2.5' : '3'} aspect-square` : 
        sizes[props.size];
    
    return `${baseClasses} ${variants[props.variant]} ${iconOnlyPadding} ${roundedStyles[props.rounded]} ${width}`;
});

// 计算图标尺寸
const iconSizeClass = computed(() => iconSizes[props.size]);
</script>