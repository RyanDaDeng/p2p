<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
    onColor: {
        type: String,
        default: 'emerald',
    },
    offColor: {
        type: String,
        default: 'slate',
    },
    onLabel: {
        type: String,
        default: '',
    },
    offLabel: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

const toggle = () => {
    if (!props.disabled) {
        const newValue = !props.modelValue;
        emit('update:modelValue', newValue);
        emit('change', newValue);
    }
};

const sizeClasses = computed(() => {
    const sizes = {
        sm: {
            track: 'h-5 w-9',
            thumb: 'h-4 w-4',
            translate: 'translate-x-4',
        },
        md: {
            track: 'h-6 w-11',
            thumb: 'h-5 w-5',
            translate: 'translate-x-5',
        },
        lg: {
            track: 'h-7 w-14',
            thumb: 'h-6 w-6',
            translate: 'translate-x-7',
        },
    };
    return sizes[props.size];
});

const colorClasses = computed(() => {
    if (props.modelValue) {
        return `bg-${props.onColor}-600 dark:bg-${props.onColor}-500`;
    }
    return 'bg-slate-200 dark:bg-slate-700';
});
</script>

<template>
    <div class="flex items-center space-x-3">
        <!-- Off Label -->
        <span v-if="offLabel" 
            class="text-sm font-medium transition-colors duration-200"
            :class="!modelValue ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-400'">
            {{ offLabel }}
        </span>

        <!-- Toggle Switch -->
        <button
            type="button"
            :disabled="disabled"
            @click="toggle"
            class="relative inline-flex flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 disabled:cursor-not-allowed disabled:opacity-50"
            :class="[sizeClasses.track, colorClasses]"
            role="switch"
            :aria-checked="modelValue"
        >
            <span class="sr-only">Toggle</span>
            <span
                class="pointer-events-none inline-block rounded-full bg-white shadow ring-0 transition-transform duration-200 ease-in-out"
                :class="[
                    sizeClasses.thumb,
                    modelValue ? sizeClasses.translate : 'translate-x-0'
                ]"
            />
        </button>

        <!-- On Label -->
        <span v-if="onLabel" 
            class="text-sm font-medium transition-colors duration-200"
            :class="modelValue ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-400'">
            {{ onLabel }}
        </span>
    </div>
</template>