<template>
    <button
        @click="toggle"
        :disabled="disabled"
        :class="[
            'relative inline-flex items-center h-6 transition-colors duration-200 ease-in-out rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2',
            modelValue
                ? (variant === 'primary' ? 'bg-emerald-600 dark:bg-emerald-500 focus:ring-emerald-500' : 'bg-blue-600 dark:bg-blue-500 focus:ring-blue-500')
                : 'bg-gray-200 dark:bg-gray-700 focus:ring-gray-500',
            size === 'sm' ? 'w-11' : size === 'lg' ? 'w-14' : 'w-12',
            disabled ? 'opacity-50 cursor-not-allowed' : ''
        ]"
    >
        <span
            :class="[
                'pointer-events-none inline-block bg-white dark:bg-gray-100 rounded-full shadow transform ring-0 transition-transform duration-200 ease-in-out',
                modelValue
                    ? (size === 'sm' ? 'translate-x-5' : size === 'lg' ? 'translate-x-7' : 'translate-x-6')
                    : 'translate-x-1',
                size === 'sm' ? 'h-5 w-5' : size === 'lg' ? 'h-5 w-5' : 'h-5 w-5'
            ]"
        >
            <span
                v-if="loading"
                class="absolute inset-0 flex items-center justify-center"
            >
                <svg class="animate-spin h-3 w-3 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </span>
    </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
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
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary'].includes(value)
    }
});

const emit = defineEmits(['update:modelValue', 'change']);

const toggle = () => {
    if (!props.disabled && !props.loading) {
        const newValue = !props.modelValue;
        emit('update:modelValue', newValue);
        emit('change', newValue);
    }
};
</script>