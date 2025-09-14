<template>
    <P2PAppLayout title="仲裁管理">
        <div class="min-h-screen bg-gray-50 dark:bg-slate-950 py-4 md:py-6">
            <div class="max-w-5xl mx-auto px-4">
                <!-- 页面标题 - 紧凑设计 -->
                <div class="mb-4">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-slate-100">仲裁管理</h1>
                    <p class="text-sm text-gray-600 dark:text-slate-400">处理争议订单</p>
                </div>

                <!-- 争议订单列表 -->
                <div class="space-y-3">
                    <!-- 空状态 -->
                    <div v-if="orders.data.length === 0" class="bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-800 p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-base font-medium text-gray-900 dark:text-slate-100">暂无争议订单</h3>
                        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">当前没有需要仲裁的订单</p>
                    </div>

                    <!-- 订单卡片 - 紧凑设计 -->
                    <div v-for="order in orders.data" :key="order.id"
                         class="bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-800 p-4 hover:shadow-md transition-shadow">
                        
                        <!-- 头部信息 -->
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                            <div class="flex-1">
                                <!-- 订单号和状态 -->
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-base font-bold text-gray-900 dark:text-slate-100">
                                        #{{ order.order_no }}
                                    </h3>
                                    <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded">
                                        争议中
                                    </span>
                                </div>
                                
                                <!-- 时间信息 -->
                                <div class="text-xs text-gray-500 dark:text-slate-400 space-y-0.5">
                                    <p>创建：{{ formatTime(order.created_at) }}</p>
                                    <p v-if="order.disputed_at" class="text-red-600 dark:text-red-400">
                                        争议：{{ formatTime(order.disputed_at) }}
                                    </p>
                                </div>
                            </div>

                            <!-- 操作按钮 - 移动端全宽 -->
                            <div class="w-full sm:w-auto">
                                <a :href="`/trade/${order.order_no}/chat`" class="block">
                                    <P2PButton variant="primary" size="sm" fullWidth class="sm:w-auto">
                                        进入订单
                                    </P2PButton>
                                </a>
                            </div>
                        </div>

                        <!-- 交易信息 - 网格布局 -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <!-- 交易双方 -->
                            <div class="bg-gray-50 dark:bg-slate-800/50 rounded p-2">
                                <p class="text-xs text-gray-500 dark:text-slate-400">卖家</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100 truncate">
                                    {{ order.vendor.name }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-slate-800/50 rounded p-2">
                                <p class="text-xs text-gray-500 dark:text-slate-400">买家</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100 truncate">
                                    {{ order.client.name }}
                                </p>
                            </div>
                        </div>

                        <!-- 交易金额 - 一行显示 -->
                        <div class="flex items-center justify-between p-2 bg-emerald-50 dark:bg-emerald-900/10 rounded">
                            <div class="flex items-center gap-3 text-sm">
                                <span class="text-gray-600 dark:text-slate-400">
                                    {{ order.crypto_amount }} {{ order.currency_key || order.crypto_currency }}
                                </span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">
                                    ¥{{ parseFloat(order.fiat_amount).toFixed(2) }}
                                </span>
                            </div>
                        </div>

                        <!-- 争议原因 - 可展开 -->
                        <div v-if="order.dispute_reason" class="mt-3 p-2 bg-red-50 dark:bg-red-900/20 rounded border border-red-200 dark:border-red-800">
                            <p class="text-xs font-medium text-red-800 dark:text-red-200 mb-1">争议原因</p>
                            <p class="text-xs text-red-700 dark:text-red-300 line-clamp-2">
                                {{ order.dispute_reason }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 分页 - 紧凑设计 -->
                <div v-if="orders.data.length > 0" class="mt-4">
                    <P2PPagination 
                        :current-page="orders.current_page || 1"
                        :total="orders.total || 0"
                        :per-page="orders.per_page || 20"
                        @update:current-page="loadPage"
                    />
                </div>
            </div>
        </div>
    </P2PAppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PPagination from '@/Components/UI/P2PPagination.vue';

const props = defineProps({
    orders: Object,
    filters: Object,
});

// 加载分页
const loadPage = (page) => {
    router.get('/arbitration', { page }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// 格式化时间
const formatTime = (time) => {
    if (!time) return '未知';
    const date = new Date(time);
    const now = new Date();
    const diff = now - date;
    
    // 小于1小时显示分钟
    if (diff < 3600000) {
        const minutes = Math.floor(diff / 60000);
        return minutes <= 1 ? '刚刚' : `${minutes}分钟前`;
    }
    
    // 小于24小时显示小时
    if (diff < 86400000) {
        const hours = Math.floor(diff / 3600000);
        return `${hours}小时前`;
    }
    
    // 同年显示月日
    if (date.getFullYear() === now.getFullYear()) {
        return `${date.getMonth() + 1}月${date.getDate()}日`;
    }
    
    // 不同年显示完整日期
    return `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`;
};
</script>

<style scoped>
/* 限制文本行数 */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>