<template>
    <div :class="[
        'inline-flex items-baseline tracking-tight',
        size === 'sm' && 'text-sm',
        size === 'md' && 'text-base sm:text-lg',
        size === 'lg' && 'text-lg sm:text-xl',
        size === 'xl' && 'text-xl sm:text-2xl'
    ]">
        <!-- P2P with special styling -->
        <span class="font-bold text-gray-900 dark:text-white">
            P<span class="text-emerald-600 dark:text-emerald-500">2</span>P
        </span>

        <!-- CoinSwap as one word -->
        <span class="font-bold text-gray-700 dark:text-gray-300 ml-0.5">
            CoinSwap
        </span>

        <!-- Domain suffix -->
        <span v-if="showDomain" class="text-gray-500 dark:text-gray-400 font-normal ml-0.5">
            .com
        </span>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
    },
    showDomain: {
        type: Boolean,
        default: false
    }
});
</script>

<style scoped>
/* Clean text rendering */
span {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
</style>