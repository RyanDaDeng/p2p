<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    type: {
        type: String,
        default: 'text',
    },
    placeholder: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    readonly: {
        type: Boolean,
        default: false,
    },
    required: {
        type: Boolean,
        default: false,
    },
    autofocus: {
        type: Boolean,
        default: false,
    },
    autocomplete: {
        type: String,
        default: '',
    },
    min: {
        type: [String, Number],
        default: undefined,
    },
    max: {
        type: [String, Number],
        default: undefined,
    },
    step: {
        type: [String, Number],
        default: undefined,
    },
    pattern: {
        type: String,
        default: undefined,
    },
    inputmode: {
        type: String,
        default: undefined,
    },
    name: {
        type: String,
        default: '',
    },
    id: {
        type: String,
        default: '',
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'success', 'error', 'warning'].includes(value),
    },
    icon: {
        type: Boolean,
        default: false,
    },
    suffix: {
        type: String,
        default: '',
    },
});

defineEmits(['update:modelValue', 'focus', 'blur', 'keydown', 'keyup', 'keyup.enter']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });

const sizeClasses = {
    sm: 'py-1.5 px-3 text-sm',
    md: 'py-2 px-4 text-base',
    lg: 'py-3 px-4 text-lg',
};

const variantClasses = {
    default: 'border-gray-300 dark:border-gray-700 focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-emerald-500/20 dark:focus:ring-emerald-400/20',
    success: 'border-emerald-500 dark:border-emerald-400 focus:border-emerald-600 dark:focus:border-emerald-300 focus:ring-emerald-500/20 dark:focus:ring-emerald-400/20',
    error: 'border-red-500 dark:border-red-400 focus:border-red-600 dark:focus:border-red-300 focus:ring-red-500/20 dark:focus:ring-red-400/20',
    warning: 'border-amber-500 dark:border-amber-400 focus:border-amber-600 dark:focus:border-amber-300 focus:ring-amber-500/20 dark:focus:ring-amber-400/20',
};
</script>

<template>
    <div class="relative">
        <!-- Icon Slot -->
        <div v-if="icon || $slots.icon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <slot name="icon">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </slot>
        </div>

        <!-- Input Field -->
        <input
            ref="input"
            :id="id"
            :name="name"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :readonly="readonly"
            :required="required"
            :autofocus="autofocus"
            :autocomplete="autocomplete"
            :min="min"
            :max="max"
            :step="step"
            :pattern="pattern"
            :inputmode="inputmode"
            class="w-full rounded border bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200 focus:outline-none focus:ring-2 disabled:bg-gray-50 dark:disabled:bg-gray-900 disabled:text-gray-500 dark:disabled:text-gray-400 disabled:cursor-not-allowed"
            :class="[
                sizeClasses[size],
                variantClasses[variant],
                { 
                    'pl-10': icon || $slots.icon,
                    'pr-10': suffix || $slots.suffix
                }
            ]"
            @input="$emit('update:modelValue', $event.target.value)"
            @focus="$emit('focus', $event)"
            @blur="$emit('blur', $event)"
            @keydown="$emit('keydown', $event)"
            @keyup="$emit('keyup', $event)"
            @keyup.enter="$emit('keyup.enter', $event)"
        />

        <!-- Suffix -->
        <div v-if="suffix || $slots.suffix" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <slot name="suffix">
                <span class="text-gray-500 dark:text-gray-400 text-sm">{{ suffix }}</span>
            </slot>
        </div>
    </div>
</template>