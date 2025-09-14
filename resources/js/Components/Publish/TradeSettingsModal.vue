<template>
    <P2PPublishDialog
        v-model="show"
        title="交易设置"
        subtitle="设置您的默认交易条款和注意事项"
        confirm-text="保存设置"
        :loading="loading"
        @confirm="saveSettings"
        @cancel="close"
    >
        <template #content>
            <div class="space-y-6">
                <!-- 说明 -->
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-blue-800 dark:text-blue-200 font-medium">关于交易设置</p>
                            <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                                这里设置的交易条款将作为您创建新广告时的默认内容。每个广告会保存一份独立副本，修改此处设置不会影响已发布的广告。
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 交易须知 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        交易须知
                    </label>
                    <textarea
                        v-model="form.vendor_toc"
                        rows="6"
                        placeholder="设置您的交易须知和注意事项，例如：&#10;• 请使用本人实名账户付款&#10;• 付款后请主动提供付款截图&#10;• 付款备注请勿包含敏感词汇&#10;• 确认付款后请耐心等待放币&#10;• 交易时间为每日 9:00-22:00&#10;• 大额交易可能需要额外验证"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                        向交易对方展示的交易条款和注意事项
                    </p>
                </div>

                <!-- 欢迎消息 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        自动欢迎消息
                    </label>
                    <textarea
                        v-model="form.welcome_message"
                        rows="3"
                        placeholder="当用户开始交易时自动发送的消息，例如：&#10;您好！感谢选择与我交易，请仔细阅读交易条款。有任何问题请随时联系我。"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                        交易开始时自动发送给对方的欢迎消息
                    </p>
                </div>

                <!-- 预览 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        预览效果
                    </label>
                    <div class="p-4 bg-gray-50 dark:bg-slate-800/50 rounded-lg space-y-3">
                        <div v-if="form.vendor_toc">
                            <p class="text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">交易须知</p>
                            <p class="text-sm text-gray-700 dark:text-slate-300 whitespace-pre-wrap">{{ form.vendor_toc }}</p>
                        </div>
                        <div v-if="form.welcome_message">
                            <p class="text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">自动欢迎消息</p>
                            <p class="text-sm text-gray-700 dark:text-slate-300 whitespace-pre-wrap">{{ form.welcome_message }}</p>
                        </div>
                        <p v-if="!form.vendor_toc && !form.welcome_message" class="text-sm text-gray-500 dark:text-slate-400 italic">
                            暂无内容
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </P2PPublishDialog>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import P2PPublishDialog from '@/Components/UI/P2PPublishDialog.vue';
import axios from 'axios';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue', 'saved']);

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const loading = ref(false);

const form = ref({
    vendor_toc: '',
    welcome_message: ''
});

// Load saved settings from API
const loadSettings = async () => {
    try {
        const response = await axios.get('/web/api/trade-settings');
        if (response.data.success && response.data.data.settings) {
            const settings = response.data.data.settings;
            form.value = {
                vendor_toc: settings.vendor_toc || '',
                welcome_message: settings.welcome_message || ''
            };
        }
    } catch (error) {
        console.error('获取交易设置失败:', error);
    }
};

const saveSettings = async () => {
    loading.value = true;

    try {
        const response = await axios.post('/web/api/trade-settings', form.value);
        if (response.data.success) {
            emit('saved', form.value);
            close();
        }
    } catch (error) {
        console.error('保存交易设置失败:', error);
    } finally {
        loading.value = false;
    }
};

const close = () => {
    emit('update:modelValue', false);
};

onMounted(() => {
    loadSettings();
});
</script>
