<template>
    <P2PAppLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-6">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- 页面标题 -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">消息通知</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">查看您的通知消息</p>
                </div>

                <!-- 通知列表 -->
                <div v-if="notifications.length > 0" class="bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-800 divide-y divide-gray-200 dark:divide-gray-800 overflow-hidden">
                    <div v-for="notification in notifications" :key="notification.id"
                         class="p-4 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-start gap-3">
                            <!-- 图标 -->
                            <div class="flex-shrink-0">
                                <div v-if="notification.type === 'order'"
                                     class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <div v-else
                                     class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- 内容 -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ notification.message }}
                                            <span v-if="notification.count > 1"
                                                  class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                {{ notification.count }} 条
                                            </span>
                                        </p>
                                        <p v-if="notification.order" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            订单号: #{{ notification.order.order_no }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            {{ formatTime(notification.created_at) }}
                                        </p>
                                    </div>

                                    <!-- 状态标记 -->
                                    <div class="flex items-center gap-2 ml-4">
                                        <span v-if="!notification.is_read"
                                              class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                            未读
                                        </span>

                                        <!-- 操作按钮 -->
                                        <Link v-if="notification.order_id"
                                              :href="`/trade/${notification.order.order_no}/chat`"
                                              class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                                            查看订单
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 空状态 -->
                <div v-else class="bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-800 p-12">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">暂无通知</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">您目前没有任何通知消息</p>
                    </div>
                </div>

                <!-- 分页 -->
                <div v-if="notifications.length > 0" class="mt-6 flex justify-center">
                    <nav class="flex items-center gap-2">
                        <button @click="changePage(currentPage - 1)"
                                :disabled="currentPage === 1"
                                class="px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            上一页
                        </button>

                        <span class="px-4 py-1.5 text-sm text-gray-700 dark:text-gray-300">
                            第 {{ currentPage }} / {{ totalPages }} 页
                        </span>

                        <button @click="changePage(currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                class="px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            下一页
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </P2PAppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import axios from 'axios';

const notifications = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);

const formatTime = (timestamp) => {
    if (!timestamp) return '';
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;

    // 小于1分钟
    if (diff < 60000) {
        return '刚刚';
    }

    // 小于1小时
    if (diff < 3600000) {
        const minutes = Math.floor(diff / 60000);
        return `${minutes}分钟前`;
    }

    // 小于24小时
    if (diff < 86400000) {
        const hours = Math.floor(diff / 3600000);
        return `${hours}小时前`;
    }

    // 小于7天
    if (diff < 604800000) {
        const days = Math.floor(diff / 86400000);
        return `${days}天前`;
    }

    // 超过7天显示日期
    return date.toLocaleDateString('zh-CN');
};

const loadNotifications = async (page = 1) => {
    try {
        const response = await axios.get('/web/api/notifications', {
            params: { page }
        });

        notifications.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
    } catch (error) {
        console.error('Failed to load notifications:', error);
    }
};

const changePage = async (page) => {
    if (page < 1 || page > totalPages.value) return;
    await loadNotifications(page);
};

onMounted(() => {
    // 页面加载时获取通知列表
    loadNotifications();
});
</script>
