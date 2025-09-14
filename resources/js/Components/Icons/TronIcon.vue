<template>
    <svg :width="size" :height="size" :class="className" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
        <g fill="none">
            <circle cx="16" cy="16" r="16" fill="#EF0027"/>
            <path fill="#FFF" d="M21.932 9.913L7.5 7.257l7.595 19.112 10.583-12.373-3.746-4.083zm-.232 1.17l2.208 2.408-1.035.181-3.993-2.19 2.82-.398zm-1.436 2.635l3.227-.565L14.72 24.05l4.644-11.214zm-5.96 10.234L8.79 8.898l9.514 1.556-3.999 13.498z"/>
        </g>
    </svg>
</template>

<script setup>
const props = defineProps({
    size: {
        type: [String, Number],
        default: 32
    },
    className: {
        type: String,
        default: ''
    }
});
</script>