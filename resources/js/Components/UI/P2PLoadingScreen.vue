<template>
    <Transition
        enter-active-class="transition-opacity duration-200"
        leave-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        leave-to-class="opacity-0"
    >
        <div v-if="isVisible" class="fixed inset-0 z-[9999] bg-white dark:bg-slate-950">
            <!-- 简洁的网格背景 -->
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-emerald-50/5 to-transparent dark:via-emerald-950/10"></div>

            <!-- 中心内容 -->
            <div class="h-full flex flex-col items-center justify-center px-4">
                <!-- Logo -->
                <div class="mb-12 text-center">
                    <div class="flex items-center justify-center gap-4 mb-4">
                        <img src="/logo.png" alt="P2PCoinSwap" class="w-16 h-16" />
                        <div class="text-left">
                            <P2PBrandText size="xl" />
                            <div class="text-sm text-gray-600 dark:text-slate-400">Trade Crypto P2P</div>
                        </div>
                    </div>
                </div>

                <!-- 简单的加载指示器 -->
                <div class="flex gap-1.5">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
            </div>

            <!-- 底部版权信息 -->
            <div class="absolute bottom-8 pb-safe left-0 right-0 text-center">
                <p class="text-xs text-gray-400 dark:text-slate-600 flex items-center justify-center gap-1">© 2025 <P2PBrandText size="sm" />. All rights reserved.</p>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import P2PLogo from './P2PLogo.vue'
import P2PBrandText from './P2PBrandText.vue'

const props = defineProps({
    duration: {
        type: Number,
        default: 1500 // 默认显示1.5秒
    }
})

const emit = defineEmits(['complete'])

const isVisible = ref(true)

onMounted(() => {
    // 简单的定时器
    setTimeout(() => {
        isVisible.value = false
        setTimeout(() => {
            emit('complete')
        }, 300)
    }, props.duration)
})
</script>

