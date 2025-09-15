<template>
    <P2PAppLayout>
        <Head :title="user.name" />
        
        <!-- Compact Profile Header -->
        <div class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-900 dark:to-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
                <!-- User Info Row -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <!-- Avatar -->
                    <img
                        v-if="user.profile_photo_url"
                        :src="user.profile_photo_url"
                        :alt="user.name"
                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover shadow-lg border-2 border-white dark:border-gray-800"
                    />
                    <div v-else class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg">
                        <span class="text-white text-xl sm:text-2xl font-bold">
                            {{ user.name[0].toUpperCase() }}
                        </span>
                    </div>
                    
                    <!-- Name and Status -->
                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex flex-col sm:flex-row items-center sm:items-baseline gap-2 sm:gap-3">
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ user.name }}</h1>
                            <div class="flex items-center gap-2">
                                <div v-for="badge in verificationBadges.filter(b => b.verified)" :key="badge.type"
                                     class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-300 dark:border-emerald-500/30 rounded-full">
                                    <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-xs text-emerald-700 dark:text-emerald-400">{{ badge.label }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-2 text-sm">
                            <span v-if="user.online" class="text-green-600 dark:text-green-400 flex items-center gap-1">
                                <div class="w-2 h-2 bg-green-600 dark:bg-green-400 rounded-full animate-pulse"></div>
                                在线
                            </span>
                            <span v-else class="text-gray-600 dark:text-gray-400">
                                离线 · {{ user.lastOnline }}
                            </span>
                            <span class="text-gray-600 dark:text-gray-400">注册于 {{ user.memberSince }}</span>
                        </div>
                    </div>
                    
                    <!-- Quick Stats (Desktop) -->
                    <div class="hidden sm:flex items-center gap-6 text-center">
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ user.completedTrades }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">成功交易</div>
                        </div>
                        <div>
                            <div class="flex items-center gap-1">
                                <span class="text-2xl font-bold text-amber-400">{{ user.rating }}</span>
                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">用户评分</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ user.usersTradedWith }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">交易伙伴</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ user.responseTime ? '< ' + user.responseTime + '分钟' : '暂无' }}
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">响应时间</div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Stats -->
                <div class="sm:hidden grid grid-cols-4 gap-2 mt-4 pt-4 border-t border-gray-300 dark:border-gray-700">
                    <div class="text-center">
                        <div class="text-base font-bold text-gray-900 dark:text-white">{{ user.completedTrades }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">交易</div>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-1">
                            <span class="text-base font-bold text-amber-400">{{ user.rating }}</span>
                            <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">评分</div>
                    </div>
                    <div class="text-center">
                        <div class="text-base font-bold text-gray-900 dark:text-white">{{ user.usersTradedWith }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">伙伴</div>
                    </div>
                    <div class="text-center">
                        <div class="text-base font-bold text-gray-900 dark:text-white">
                            {{ user.responseTime ? '< ' + user.responseTime + '分' : '暂无' }}
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">响应</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content - Compact Layout -->
        <div class="bg-gray-50 dark:bg-gray-950 min-h-screen">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <!-- Reviews Section -->
                <div class="bg-white dark:bg-gray-900 rounded shadow-sm">
                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">用户评价</h2>
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-sm text-gray-600 dark:text-gray-400">
                                    {{ user.totalReviews }} 条评价
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex">
                                    <svg v-for="i in 5" :key="i" class="w-4 h-4" 
                                         :class="i <= Math.floor(user.rating) ? 'text-amber-400' : 'text-gray-300 dark:text-gray-700'"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ user.rating }}</span>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Tabs -->
                    <div class="flex gap-1 px-4 py-2 border-b border-gray-200 dark:border-gray-800 overflow-x-auto">
                        <button v-for="tab in feedbackTabs" :key="tab.value"
                                @click="handleTabChange(tab.value)"
                                :disabled="loadingReviews"
                                :class="[
                                    'px-3 py-1.5 text-sm font-medium rounded-md transition-colors whitespace-nowrap cursor-pointer',
                                    activeFeedbackTab === tab.value
                                        ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300'
                                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800',
                                    loadingReviews && 'opacity-50 cursor-wait'
                                ]">
                            {{ tab.label }}
                        </button>
                    </div>

                    <!-- Reviews List -->
                    <div v-if="loadingReviews" class="flex items-center justify-center py-8">
                        <svg class="animate-spin h-8 w-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div v-else class="divide-y divide-gray-200 dark:divide-gray-800">
                        <div v-if="feedbacks.length === 0" class="p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">暂无评价</p>
                        </div>
                        
                        <div v-for="feedback in feedbacks" :key="feedback.id" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-start gap-3">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">
                                            {{ feedback.userName[0].toUpperCase() }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ feedback.userName }}</p>
                                                <span class="text-xs text-gray-500 dark:text-gray-500">{{ feedback.date }}</span>
                                            </div>
                                            <div class="flex items-center gap-1 mt-1">
                                                <svg v-for="i in 5" :key="i" class="w-3.5 h-3.5" 
                                                     :class="i <= feedback.rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-700'"
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <span class="text-xs text-gray-600 dark:text-gray-400 ml-1">
                                                    {{ feedback.tradeType }} {{ feedback.cryptoCurrency }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ feedback.comment }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
                        <P2PPagination 
                            :current-page="currentPage"
                            :total-pages="totalPages"
                            @page-changed="handlePageChange"
                        />
                    </div>
                </div>
            </div>
        </div>
    </P2PAppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PPagination from '@/Components/UI/P2PPagination.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    ratingStats: {
        type: Object,
        default: () => ({})
    },
    isOwnProfile: {
        type: Boolean,
        default: false
    }
});

const verificationBadges = computed(() => [
    { type: 'email', label: '邮箱验证', verified: props.user.is_verified },
    { type: '2fa', label: '双重验证', verified: props.user.has_2fa },
    { type: 'merchant', label: '商家验证', verified: props.user.totalTrades > 10 }
]);

const activeFeedbackTab = ref('all');
const feedbackTabs = [
    { value: 'all', label: '全部' },
    { value: 'positive', label: '好评' },
    { value: 'neutral', label: '中评' },
    { value: 'negative', label: '差评' }
];

// Reviews data
const reviews = ref([]);
const reviewsPagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0
});
const loadingReviews = ref(false);

const feedbacks = computed(() => {
    return reviews.value.map(review => ({
        id: review.id,
        userName: review.is_anonymous ? '匿名用户' : (review.reviewer?.name || '用户'),
        avatar: review.is_anonymous
            ? `https://ui-avatars.com/api/?name=匿名&color=6b7280&background=e5e7eb`
            : `https://ui-avatars.com/api/?name=${review.reviewer?.name || 'User'}&color=10b981&background=d1fae5`,
        rating: review.rating,
        date: new Date(review.created_at).toLocaleDateString('zh-CN'),
        comment: review.comment || '用户未留言',
        tradeType: review.trade_type === 'buy' ? '客户购买' : '客户出售',
        cryptoCurrency: review.currency_key || review.crypto_currency
    }));
});

// Pagination state
const currentPage = computed(() => reviewsPagination.value.current_page);
const totalPages = computed(() => reviewsPagination.value.last_page);

// Fetch reviews from API
const fetchReviews = async (page = 1, rating = 'all') => {
    loadingReviews.value = true;
    try {
        const params = { page, per_page: 10 };
        if (rating !== 'all') {
            params.rating = rating;
        }

        const response = await axios.get(`/web/api/users/${props.user.id}/reviews`, { params });
        if (response.data.success) {
            reviews.value = response.data.data.reviews;
            reviewsPagination.value = response.data.data.pagination;
        }
    } catch (error) {
        console.error('Failed to fetch reviews:', error);
    } finally {
        loadingReviews.value = false;
    }
};

// Handle tab change
const handleTabChange = async (tab) => {
    activeFeedbackTab.value = tab;
    await fetchReviews(1, tab);
};

// Handle page change
const handlePageChange = async (page) => {
    await fetchReviews(page, activeFeedbackTab.value);
};

// Load initial reviews
onMounted(() => {
    fetchReviews();
});
</script>