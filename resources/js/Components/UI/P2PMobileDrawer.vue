<template>
    <Teleport to="body">
        <!-- 背景遮罩 -->
        <Transition
            enter-active-class="transition-opacity ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-if="modelValue"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] lg:hidden"
                @click="close"
            ></div>
        </Transition>

        <!-- Drawer面板 -->
        <Transition
            :enter-active-class="`transition-transform ease-out duration-300`"
            :enter-from-class="transformClass.from"
            :enter-to-class="transformClass.to"
            :leave-active-class="`transition-transform ease-in duration-200`"
            :leave-from-class="transformClass.to"
            :leave-to-class="transformClass.from"
        >
            <div
                v-if="modelValue"
                :class="[
                    'fixed z-[70] bg-white dark:bg-slate-900 shadow-2xl lg:hidden flex flex-col',
                    positionClasses,
                    sizeClasses
                ]"
            >
                <!-- Drawer头部 -->
                <div class="flex-shrink-0 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <!-- 拖动指示器（底部drawer） -->
                        <div v-if="position === 'bottom'" class="absolute top-2 left-1/2 -translate-x-1/2 w-12 h-1 bg-gray-300 dark:bg-slate-600 rounded-full"></div>
                        
                        <div class="flex items-center gap-3">
                            <slot name="header-icon">
                                <div v-if="icon" class="p-2 rounded-lg" :class="iconColorClass">
                                    <component :is="icon" class="w-5 h-5" />
                                </div>
                            </slot>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">{{ title }}</h3>
                                <p v-if="subtitle" class="text-xs text-gray-500 dark:text-slate-400">{{ subtitle }}</p>
                            </div>
                        </div>
                        
                        <button 
                            @click="close"
                            class="p-2 rounded-lg text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Drawer内容 -->
                <div :class="contentClasses">
                    <slot />
                </div>

                <!-- Drawer底部（可选） -->
                <div v-if="$slots.footer" class="flex-shrink-0 bg-white dark:bg-slate-900 border-t border-gray-200 dark:border-slate-800 p-4">
                    <slot name="footer" />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: '详情'
    },
    subtitle: {
        type: String,
        default: ''
    },
    position: {
        type: String,
        default: 'bottom',
        validator: (value) => ['bottom', 'right', 'left'].includes(value)
    },
    size: {
        type: String,
        default: 'default',
        validator: (value) => ['small', 'default', 'large', 'full'].includes(value)
    },
    icon: {
        type: [Object, String],
        default: null
    },
    iconColor: {
        type: String,
        default: 'blue',
        validator: (value) => ['blue', 'emerald', 'amber', 'red', 'purple'].includes(value)
    },
    persistent: {
        type: Boolean,
        default: false
    },
    scrollable: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['update:modelValue', 'open', 'close']);

// 位置样式类
const positionClasses = computed(() => {
    const positions = {
        bottom: 'bottom-0 left-0 right-0 rounded-t-2xl',
        right: 'top-0 right-0 bottom-0 rounded-l-2xl',
        left: 'top-0 left-0 bottom-0 rounded-r-2xl'
    };
    return positions[props.position];
});

// 尺寸样式类
const sizeClasses = computed(() => {
    if (props.position === 'bottom') {
        const sizes = {
            small: 'max-h-[40vh]',
            default: 'max-h-[70vh]',
            large: 'max-h-[85vh]',
            full: 'h-full'
        };
        return sizes[props.size];
    } else {
        const sizes = {
            small: 'w-64',
            default: 'w-80',
            large: 'w-96',
            full: 'w-full'
        };
        return sizes[props.size];
    }
});

// 内容区样式
const contentClasses = computed(() => {
    let classes = ['flex-1', 'min-h-0']; // min-h-0 允许flex子元素收缩
    if (props.scrollable) {
        classes.push('overflow-y-auto');
    }
    return classes.join(' ');
});

// 动画类
const transformClass = computed(() => {
    const transforms = {
        bottom: {
            from: 'translate-y-full',
            to: 'translate-y-0'
        },
        right: {
            from: 'translate-x-full',
            to: 'translate-x-0'
        },
        left: {
            from: '-translate-x-full',
            to: 'translate-x-0'
        }
    };
    return transforms[props.position];
});

// 图标颜色类
const iconColorClass = computed(() => {
    const colors = {
        blue: 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
        emerald: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400',
        amber: 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400',
        red: 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
        purple: 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400'
    };
    return colors[props.iconColor];
});

// 关闭drawer
const close = () => {
    if (!props.persistent) {
        emit('update:modelValue', false);
        emit('close');
    }
};

// 处理ESC键
const handleEscape = (event) => {
    if (event.key === 'Escape' && props.modelValue && !props.persistent) {
        close();
    }
};

// 阻止背景滚动
const preventBodyScroll = (prevent) => {
    if (prevent) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};

// 监听打开/关闭
watch(() => props.modelValue, (newValue) => {
    preventBodyScroll(newValue);
    if (newValue) {
        emit('open');
    }
});

// 生命周期
onMounted(() => {
    document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleEscape);
    preventBodyScroll(false);
});
</script>