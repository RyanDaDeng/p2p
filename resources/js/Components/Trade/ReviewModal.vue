<template>
    <P2PDialog
        :show="show"
        title="评价交易"
        :subtitle="`评价${vendorName}`"
        icon-color="amber"
        confirm-text="提交评价"
        cancel-text="取消"
        :persistent="loading"
        @confirm="submitReview"
        @close="close"
    >
        <div class="space-y-4">
            <!-- 交易信息 -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded p-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs md:text-sm text-gray-500 dark:text-gray-400">订单编号</span>
                    <span class="text-xs md:text-sm font-medium text-gray-900 dark:text-gray-100">{{ orderNo }}</span>
                </div>
            </div>

            <!-- 评分 -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 md:mb-3">
                    您的评分 <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-1 md:gap-2">
                    <button
                        v-for="star in 5"
                        :key="star"
                        @click="rating = star"
                        @mouseenter="hoverRating = star"
                        @mouseleave="hoverRating = 0"
                        class="focus:outline-none transition-transform hover:scale-110 cursor-pointer"
                    >
                        <svg
                            class="w-8 h-8 md:w-10 md:h-10 transition-colors"
                            :class="(hoverRating || rating) >= star ? 'text-amber-500' : 'text-gray-300 dark:text-gray-600'"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </button>
                    <span class="ml-1 md:ml-2 text-sm md:text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ ratingText }}
                    </span>
                </div>
            </div>

            <!-- 评价内容 -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    评价内容
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1 md:ml-2">
                        ({{ comment.length }}/500)
                    </span>
                </label>
                <textarea
                    v-model="comment"
                    rows="3"
                    maxlength="500"
                    placeholder="分享您的交易体验..."
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none"
                />
                <div class="mt-2 flex flex-wrap gap-1.5 md:gap-2">
                    <button
                        v-for="tag in quickTags"
                        :key="tag"
                        @click="addQuickTag(tag)"
                        class="px-2 md:px-3 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors cursor-pointer"
                    >
                        {{ tag }}
                    </button>
                </div>
            </div>

            <!-- 匿名选项 -->
            <div class="flex items-start md:items-center">
                <input
                    id="anonymous"
                    v-model="isAnonymous"
                    type="checkbox"
                    class="w-4 h-4 mt-0.5 md:mt-0 text-emerald-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-emerald-500 focus:ring-2 cursor-pointer"
                />
                <label for="anonymous" class="ml-2 text-xs md:text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                    匿名评价
                    <span class="text-xs text-gray-500 dark:text-gray-400 block md:inline">（其他用户将看不到您的身份信息）</span>
                </label>
            </div>

            <!-- 提示 -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded p-2 md:p-3">
                <div class="flex items-start gap-1.5 md:gap-2">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="text-xs text-amber-800 dark:text-amber-200">
                        <p>评价提交后将无法修改或删除，请慎重评价。</p>
                        <p class="mt-0.5 md:mt-1">您的评价将帮助其他用户更好地了解商家。</p>
                    </div>
                </div>
            </div>
        </div>
    </P2PDialog>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import P2PDialog from '@/Components/UI/P2PDialog.vue';
import axios from 'axios';
import MessageDialog from '@/Components/MessageDialog';

const props = defineProps({
    modelValue: Boolean,
    orderId: {
        type: Number,
        required: true
    },
    orderNo: {
        type: String,
        required: true
    },
    vendorName: {
        type: String,
        required: true
    },
    fiatAmount: {
        type: [Number, String],
        required: true
    },
    fiatCurrency: {
        type: String,
        required: true
    },
    cryptoAmount: {
        type: [Number, String],
        required: true
    },
    cryptoCurrency: {
        type: String,
        required: true
    }
});

const emit = defineEmits(['update:modelValue', 'success']);

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const loading = ref(false);
const rating = ref(5);
const hoverRating = ref(0);
const comment = ref('');
const isAnonymous = ref(false);

const quickTags = [
    '交易迅速',
    '信誉良好',
    '沟通顺畅',
    '价格合理',
    '服务专业',
    '态度友好',
    '值得信赖'
];

const ratingText = computed(() => {
    const texts = ['', '非常差', '较差', '一般', '满意', '非常满意'];
    return texts[rating.value] || '';
});

const canSubmit = computed(() => {
    return rating.value > 0;
});

const close = () => {
    show.value = false;
};

const addQuickTag = (tag) => {
    if (!comment.value.includes(tag)) {
        if (comment.value.length > 0) {
            comment.value += '，' + tag;
        } else {
            comment.value = tag;
        }
    }
};

const submitReview = async () => {
    if (!canSubmit.value) {
        MessageDialog.error('请选择评分');
        return;
    }

    loading.value = true;

    try {
        const response = await axios.post(`/web/api/orders/${props.orderId}/review`, {
            rating: rating.value,
            comment: comment.value,
            is_anonymous: isAnonymous.value
        });

        if (response.data.success) {
            emit('success', response.data.review);
            close();
        }
    } catch (error) {
        console.error('提交评价失败:', error);
        MessageDialog.error(error.response?.data?.message || '提交评价失败，请稍后重试');
    } finally {
        loading.value = false;
    }
};

// 重置表单
watch(show, (newVal) => {
    if (newVal) {
        rating.value = 5;
        comment.value = '';
        isAnonymous.value = false;
        hoverRating.value = 0;
    }
});
</script>