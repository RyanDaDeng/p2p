<template>
    <a :href="href" class="flex items-center space-x-2 hover:opacity-90 transition-opacity">
        <img src="/logo.png" alt="Logo" :class="logoClasses" />
        <div :class="[textContainerClasses, hideTextOnMobile ? 'hidden md:flex' : '']">
            <span :class="titleClasses">{{ title }}</span>
            <p :class="taglineClasses" v-if="showTagline">{{ tagline }}</p>
        </div>
    </a>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    href: {
        type: String,
        default: '/market'
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    showTagline: {
        type: Boolean,
        default: true
    },
    hideTextOnMobile: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'P2PCoinSwap'
    },
    tagline: {
        type: String,
        default: '安全 · 可信 · 快捷'
    }
});

const logoClasses = computed(() => {
    const sizes = {
        sm: 'h-8 w-auto',
        md: 'h-8 md:h-10 w-auto',
        lg: 'h-10 md:h-12 w-auto'
    };
    return sizes[props.size] || sizes.md;
});

const textContainerClasses = computed(() => {
    return props.showTagline ? 'flex flex-col justify-center' : '';
});

const titleClasses = computed(() => {
    const sizes = {
        sm: 'text-sm font-bold text-gray-900 dark:text-slate-100',
        md: 'text-base sm:text-lg font-bold text-gray-900 dark:text-slate-100',
        lg: 'text-lg sm:text-xl font-bold text-gray-900 dark:text-slate-100'
    };
    return sizes[props.size] || sizes.md;
});

const taglineClasses = computed(() => {
    const sizes = {
        sm: 'text-[10px] text-emerald-600 dark:text-emerald-500',
        md: 'text-[10px] sm:text-xs text-emerald-600 dark:text-emerald-500',
        lg: 'text-xs sm:text-sm text-emerald-600 dark:text-emerald-500'
    };
    return sizes[props.size] || sizes.md;
});
</script>
