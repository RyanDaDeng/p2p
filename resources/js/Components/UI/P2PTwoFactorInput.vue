<template>
    <div class="space-y-3">
        <!-- 2FA 标题 -->
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <label class="text-sm font-medium text-gray-700 dark:text-slate-300">
                {{ label || '双重验证码' }}
            </label>
        </div>
        
        <!-- 2FA 输入框组 -->
        <div class="flex gap-2 justify-center">
            <input
                v-for="(digit, index) in digits"
                :key="index"
                :ref="el => inputs[index] = el"
                v-model="digits[index]"
                type="text"
                inputmode="numeric"
                maxlength="1"
                class="w-12 h-12 text-center text-lg font-mono font-bold rounded-lg border-2 transition-all duration-200 focus:outline-none"
                :class="[
                    error
                        ? 'border-red-500 bg-red-50 dark:bg-red-900/20 text-red-900 dark:text-red-200'
                        : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20'
                ]"
                @input="handleInput(index, $event)"
                @keydown="handleKeydown(index, $event)"
                @paste="handlePaste($event)"
                :disabled="disabled"
            />
        </div>
        
        <!-- 错误提示 -->
        <p v-if="error" class="text-xs text-red-600 dark:text-red-400 text-center">
            {{ error }}
        </p>
        
        <!-- 帮助提示 -->
        <p v-if="helpText && !error" class="text-xs text-gray-500 dark:text-slate-400 text-center">
            {{ helpText }}
        </p>
        
        <!-- 使用恢复代码 -->
        <div v-if="showRecoveryOption" class="text-center">
            <button
                type="button"
                @click="$emit('use-recovery')"
                class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors"
            >
                使用恢复代码
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, nextTick } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    label: {
        type: String,
        default: '双重验证码'
    },
    error: {
        type: String,
        default: ''
    },
    helpText: {
        type: String,
        default: '请输入您的验证器应用中显示的6位数字'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    showRecoveryOption: {
        type: Boolean,
        default: false
    },
    autoFocus: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['update:modelValue', 'complete', 'use-recovery']);

// 6位数字数组
const digits = ref(['', '', '', '', '', '']);
const inputs = ref([]);

// 监听输入变化，更新modelValue
watch(digits, (newDigits) => {
    const code = newDigits.join('');
    emit('update:modelValue', code);
    
    // 如果输入完整的6位数字，触发完成事件
    if (code.length === 6 && /^\d{6}$/.test(code)) {
        emit('complete', code);
    }
}, { deep: true });

// 监听外部值变化
watch(() => props.modelValue, (newValue) => {
    if (newValue === '') {
        digits.value = ['', '', '', '', '', ''];
    } else if (newValue.length <= 6) {
        const newDigits = newValue.split('');
        for (let i = 0; i < 6; i++) {
            digits.value[i] = newDigits[i] || '';
        }
    }
});

// 处理输入
const handleInput = (index, event) => {
    const value = event.target.value;
    
    // 只允许数字
    if (!/^\d*$/.test(value)) {
        event.target.value = digits.value[index];
        return;
    }
    
    // 如果输入了值，自动跳到下一个输入框
    if (value && index < 5) {
        nextTick(() => {
            inputs.value[index + 1]?.focus();
            inputs.value[index + 1]?.select();
        });
    }
};

// 处理键盘事件
const handleKeydown = (index, event) => {
    // 删除键处理
    if (event.key === 'Backspace') {
        if (!digits.value[index] && index > 0) {
            // 如果当前输入框为空，删除前一个输入框的值并聚焦
            event.preventDefault();
            digits.value[index - 1] = '';
            inputs.value[index - 1]?.focus();
        }
    }
    // 左箭头
    else if (event.key === 'ArrowLeft' && index > 0) {
        event.preventDefault();
        inputs.value[index - 1]?.focus();
    }
    // 右箭头
    else if (event.key === 'ArrowRight' && index < 5) {
        event.preventDefault();
        inputs.value[index + 1]?.focus();
    }
};

// 处理粘贴
const handlePaste = (event) => {
    event.preventDefault();
    const pastedData = event.clipboardData.getData('text');
    const numbers = pastedData.replace(/\D/g, '').slice(0, 6);
    
    if (numbers) {
        const numberArray = numbers.split('');
        for (let i = 0; i < 6; i++) {
            digits.value[i] = numberArray[i] || '';
        }
        // 聚焦到最后一个有值的输入框的下一个
        const lastIndex = Math.min(numberArray.length, 5);
        inputs.value[lastIndex]?.focus();
    }
};

// 清空输入
const clear = () => {
    digits.value = ['', '', '', '', '', ''];
    inputs.value[0]?.focus();
};

// 聚焦第一个输入框
const focus = () => {
    inputs.value[0]?.focus();
};

// 组件挂载时自动聚焦
onMounted(() => {
    if (props.autoFocus) {
        nextTick(() => {
            inputs.value[0]?.focus();
        });
    }
});

// 暴露方法
defineExpose({
    clear,
    focus
});
</script>