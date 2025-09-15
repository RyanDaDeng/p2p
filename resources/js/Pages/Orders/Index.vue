<template>
    <P2PAppLayout>
        <Head title="订单" />
        
        <!-- 页面头部 -->
        <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
            <div class="mx-auto max-w-7xl px-4 py-3 sm:py-4 sm:px-6 lg:px-8">
                <!-- 标题和统计 -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-4 mb-3 sm:mb-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">我的订单</h1>
                        <p class="hidden sm:block text-sm text-gray-500 dark:text-gray-400 mt-1">管理和跟踪您的所有交易订单</p>
                    </div>
                    
                    <!-- 订单统计 - 移动端更紧凑 -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="text-center">
                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ statistics.active }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">进行中</p>
                        </div>
                        <div class="h-6 sm:h-8 w-px bg-gray-200 dark:bg-gray-700"></div>
                        <div class="text-center">
                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ statistics.completed }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">已完成</p>
                        </div>
                        <div class="h-6 sm:h-8 w-px bg-gray-200 dark:bg-gray-700"></div>
                        <div class="text-center">
                            <p class="text-xl sm:text-2xl font-bold text-gray-600 dark:text-gray-400">{{ statistics.total }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">总订单</p>
                        </div>
                    </div>
                </div>
                
                <!-- 筛选栏 - 桌面端 -->
                <div class="hidden sm:flex flex-wrap items-center gap-2">
                    <!-- 状态筛选 -->
                    <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded p-0.5">
                        <button
                            v-for="status in statuses"
                            :key="status.value"
                            @click="filters.status = status.value"
                            :class="[
                                'px-3 py-1.5 text-xs font-medium rounded transition-all whitespace-nowrap cursor-pointer',
                                filters.status === status.value
                                    ? status.value === 'trading'
                                        ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 shadow-sm'
                                        : status.value === 'completed'
                                        ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 shadow-sm'
                                        : status.value === 'cancelled'
                                        ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 shadow-sm'
                                        : status.value === 'disputed'
                                        ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 shadow-sm'
                                        : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm'
                                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'
                            ]"
                        >
                            {{ status.label }}
                        </button>
                    </div>
                    
                    <!-- 其他筛选 -->
                    <P2PSelect
                        v-model="filters.trade_type"
                        :options="orderTypes"
                        placeholder="交易类型"
                        size="sm"
                        class="w-32"
                    />
                    
                    <P2PSelect
                        v-model="filters.cryptocurrency"
                        :options="cryptocurrencies"
                        placeholder="币种"
                        size="sm"
                        class="w-32"
                    />
                    
                    <P2PSelect
                        v-model="filters.time_range"
                        :options="timeRanges"
                        placeholder="时间范围"
                        size="sm"
                        class="w-32"
                    />
                    
                    <!-- 搜索 -->
                    <div class="ml-auto">
                        <div class="relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="搜索订单号或用户名..."
                                class="pl-9 pr-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            >
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- 筛选栏 - 移动端更紧凑 -->
                <div class="sm:hidden space-y-2">
                    <!-- 状态筛选 - 横向滚动 -->
                    <div class="overflow-x-auto">
                        <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded p-0.5 w-fit">
                            <button
                                v-for="status in statuses"
                                :key="status.value"
                                @click="filters.status = status.value"
                                :class="[
                                    'px-3 py-1.5 text-xs font-medium rounded transition-all whitespace-nowrap cursor-pointer',
                                    filters.status === status.value
                                        ? status.value === 'trading'
                                            ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 shadow-sm'
                                            : status.value === 'completed'
                                            ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 shadow-sm'
                                            : status.value === 'cancelled'
                                            ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 shadow-sm'
                                            : status.value === 'disputed'
                                            ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 shadow-sm'
                                            : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm'
                                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'
                                ]"
                            >
                                {{ status.label }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- 其他筛选和搜索 - 网格布局 -->
                    <div class="grid grid-cols-2 gap-1.5">
                        <P2PSelect
                            v-model="filters.trade_type"
                            :options="orderTypes"
                            placeholder="类型"
                            size="sm"
                            class="w-full"
                        />
                        
                        <P2PSelect
                            v-model="filters.cryptocurrency"
                            :options="cryptocurrencies"
                            placeholder="币种"
                            size="sm"
                            class="w-full"
                        />
                        
                        <P2PSelect
                            v-model="filters.time_range"
                            :options="timeRanges"
                            placeholder="时间"
                            size="sm"
                            class="w-full"
                        />
                        
                        <!-- 搜索框 -->
                        <div class="relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="搜索..."
                                class="w-full pl-8 pr-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            >
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 主内容区 -->
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <!-- 桌面端表格视图 -->
            <div class="hidden sm:block bg-white dark:bg-gray-900 rounded shadow-sm overflow-hidden">
                <div v-if="loading" class="flex items-center justify-center p-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
                </div>
                <div v-else-if="orders.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">暂无订单记录</p>
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">订单信息</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">买家</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">卖家</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">金额</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">状态</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            <tr v-for="order in orders" :key="order.id" 
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <!-- 订单信息 -->
                                <td class="px-4 py-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">#{{ order.order_no }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ formatDate(order.created_at) }}
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- 买家 -->
                                <td class="px-4 py-3">
                                    <div v-if="getUserRole(order) === 'buyer'">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">我</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">当前用户</p>
                                    </div>
                                    <div v-else-if="order.buyer" class="flex items-center gap-2">
                                        <a :href="`/trader/profile/${order.buyer.id}`" target="_blank" class="cursor-pointer">
                                            <img v-if="order.buyer.profile_photo_url"
                                                 :src="order.buyer.profile_photo_url"
                                                 :alt="order.buyer.name"
                                                 class="h-8 w-8 rounded-full object-cover hover:ring-2 hover:ring-gray-500 transition-all">
                                            <div v-else class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 dark:from-gray-500 dark:to-gray-600 flex items-center justify-center hover:ring-2 hover:ring-gray-500 transition-all">
                                                <span class="text-white font-bold text-xs">
                                                    {{ (order.buyer.name || 'U')[0].toUpperCase() }}
                                                </span>
                                            </div>
                                        </a>
                                        <div>
                                            <a :href="`/trader/profile/${order.buyer.id}`" target="_blank" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300 cursor-pointer">{{ order.buyer.name }}</a>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- 卖家 -->
                                <td class="px-4 py-3">
                                    <div v-if="getUserRole(order) === 'seller'">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">我</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">当前用户</p>
                                    </div>
                                    <div v-else-if="order.seller" class="flex items-center gap-2">
                                        <a :href="`/trader/profile/${order.seller.id}`" target="_blank" class="cursor-pointer">
                                            <img v-if="order.seller.profile_photo_url"
                                                 :src="order.seller.profile_photo_url"
                                                 :alt="order.seller.name"
                                                 class="h-8 w-8 rounded-full object-cover hover:ring-2 hover:ring-gray-500 transition-all">
                                            <div v-else class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 dark:from-gray-500 dark:to-gray-600 flex items-center justify-center hover:ring-2 hover:ring-gray-500 transition-all">
                                                <span class="text-white font-bold text-xs">
                                                    {{ (order.seller.name || 'U')[0].toUpperCase() }}
                                                </span>
                                            </div>
                                        </a>
                                        <div>
                                            <a :href="`/trader/profile/${order.seller.id}`" target="_blank" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300 cursor-pointer">{{ order.seller.name }}</a>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- 金额 -->
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ order.crypto_amount }} {{ order.currency_label || order.currency_key || order.crypto_currency }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            ≈ {{ order.fiat_currency_symbol || '¥' }}{{ (order.fiat_amount || 0).toLocaleString() }} {{ order.fiat_currency }}
                                        </p>
                                    </div>
                                </td>
                                
                                <!-- 状态 -->
                                <td class="px-4 py-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span 
                                                :class="[
                                                    'inline-flex items-center px-2 py-1 rounded text-xs font-medium',
                                                    getStatusColor(order)
                                                ]"
                                            >
                                                {{ getStatusName(order) }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                                <div
                                                    class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-1.5 rounded-full transition-all"
                                                    :style="`width: ${getOrderProgress(order)}%`"
                                                ></div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ getEscrowStatusName(order.escrow_status) }}
                                        </p>
                                    </div>
                                </td>
                                
                                <!-- 操作 -->
                                <td class="px-4 py-3">
                                    <Link 
                                        :href="`/trade/${order.order_no}/chat`"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 bg-gray-100 dark:bg-gray-800 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        查看详情
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- 分页 -->
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
                    <P2PPagination
                        :current-page="pagination.current_page"
                        :total="pagination.total"
                        :per-page="pagination.per_page"
                        @update:currentPage="handlePageChange"
                    />
                </div>
            </div>
            
            <!-- 移动端卡片视图 -->
            <div class="sm:hidden">
                <div v-if="loading" class="flex items-center justify-center p-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
                </div>
                <div v-else-if="orders.length === 0" class="text-center py-12 bg-white dark:bg-gray-900 rounded">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">暂无订单记录</p>
                </div>
                <div v-else class="space-y-3">
                    <div
                        v-for="order in orders"
                        :key="order.id"
                        class="bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-800 overflow-hidden"
                    >
                        <!-- 订单头部 -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 px-3 py-2 border-b border-gray-200 dark:border-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">订单号:</span>
                                    <span class="text-xs font-bold text-gray-900 dark:text-gray-100">#{{ order.order_no }}</span>
                                </div>
                                <span
                                    :class="[
                                        'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                                        getStatusColor(order)
                                    ]"
                                >
                                    {{ getStatusName(order) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1 mt-1">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(order.created_at) }}</span>
                            </div>
                        </div>

                        <div class="p-3 space-y-3">
                            <!-- 买家和卖家信息 -->
                            <div class="grid grid-cols-2 gap-3">
                                <!-- 买家 -->
                                <div class="space-y-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">买家</p>
                                    <div v-if="getUserRole(order) === 'buyer'">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">我</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">当前用户</p>
                                    </div>
                                    <div v-else-if="order.buyer" class="flex items-center gap-1.5">
                                        <a :href="`/trader/profile/${order.buyer.id}`" target="_blank" class="cursor-pointer">
                                            <img v-if="order.buyer.profile_photo_url"
                                                 :src="order.buyer.profile_photo_url"
                                                 :alt="order.buyer.name"
                                                 class="h-6 w-6 rounded-full object-cover hover:ring-2 hover:ring-gray-500 transition-all">
                                            <div v-else class="h-6 w-6 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 dark:from-gray-500 dark:to-gray-600 flex items-center justify-center hover:ring-2 hover:ring-gray-500 transition-all">
                                                <span class="text-white font-bold text-xs">
                                                    {{ (order.buyer.name || 'U')[0].toUpperCase() }}
                                                </span>
                                            </div>
                                        </a>
                                        <a :href="`/trader/profile/${order.buyer.id}`" target="_blank" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300 truncate cursor-pointer">{{ order.buyer.name }}</a>
                                    </div>
                                </div>

                                <!-- 卖家 -->
                                <div class="space-y-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">卖家</p>
                                    <div v-if="getUserRole(order) === 'seller'">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">我</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">当前用户</p>
                                    </div>
                                    <div v-else-if="order.seller" class="flex items-center gap-1.5">
                                        <a :href="`/trader/profile/${order.seller.id}`" target="_blank" class="cursor-pointer">
                                            <img v-if="order.seller.profile_photo_url"
                                                 :src="order.seller.profile_photo_url"
                                                 :alt="order.seller.name"
                                                 class="h-6 w-6 rounded-full object-cover hover:ring-2 hover:ring-gray-500 transition-all">
                                            <div v-else class="h-6 w-6 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 dark:from-gray-500 dark:to-gray-600 flex items-center justify-center hover:ring-2 hover:ring-gray-500 transition-all">
                                                <span class="text-white font-bold text-xs">
                                                    {{ (order.seller.name || 'U')[0].toUpperCase() }}
                                                </span>
                                            </div>
                                        </a>
                                        <a :href="`/trader/profile/${order.seller.id}`" target="_blank" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300 truncate cursor-pointer">{{ order.seller.name }}</a>
                                    </div>
                                </div>
                            </div>

                            <!-- 金额信息 -->
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded p-2.5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">交易金额</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100 mt-0.5">
                                            {{ order.crypto_amount }} {{ order.currency_label || order.currency_key || order.crypto_currency }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-600 dark:text-gray-400">法币金额</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100 mt-0.5">
                                            {{ order.fiat_currency_symbol || '¥' }}{{ (order.fiat_amount || 0).toLocaleString() }} {{ order.fiat_currency }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- 托管状态进度 -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">托管状态</span>
                                    <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">{{ getOrderProgress(order) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div
                                        class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-1.5 rounded-full transition-all"
                                        :style="`width: ${getOrderProgress(order)}%`"
                                    ></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ getEscrowStatusName(order.escrow_status) }}
                                </p>
                            </div>

                            <!-- 操作按钮 -->
                            <Link
                                :href="`/trade/${order.order_no}/chat`"
                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 bg-gray-100 dark:bg-gray-800 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                查看详情
                            </Link>
                        </div>
                    </div>
                    
                    <!-- 分页 -->
                    <div class="mt-4">
                        <P2PPagination
                            :current-page="pagination.current_page"
                            :total="pagination.total"
                            :per-page="pagination.per_page"
                            @update:currentPage="handlePageChange"
                        />
                    </div>
                </div>
            </div>
        </div>
    </P2PAppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PBadge from '@/Components/UI/P2PBadge.vue';
import P2PSelect from '@/Components/UI/P2PSelect.vue';
import P2PPagination from '@/Components/UI/P2PPagination.vue';

// 订单数据
const orders = ref([]);
const loading = ref(false);
const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 20,
    total: 0
});

// 筛选和搜索
const filters = ref({
    status: 'all',
    trade_type: 'all',
    cryptocurrency: 'all',
    time_range: '7days',
});

const searchQuery = ref('');
const searchTimer = ref(null);

// 状态选项
const statuses = [
    { value: 'all', label: '全部' },
    { value: 'trading', label: '交易中', dotColor: 'bg-blue-500' },
    { value: 'completed', label: '已完成', dotColor: 'bg-emerald-500' },
    { value: 'cancelled', label: '已取消', dotColor: 'bg-gray-500' },
    { value: 'disputed', label: '争议中', dotColor: 'bg-red-500' },
];

const orderTypes = [
    { value: 'all', label: '全部类型' },
    { value: 'buy', label: '我买入' },
    { value: 'sell', label: '我卖出' },
];

const cryptocurrencies = [
    { value: 'all', label: '全部币种' },
    { value: 'BTC', label: 'BTC' },
    { value: 'ETH', label: 'ETH' },
    { value: 'USDT', label: 'USDT' },
];

const timeRanges = [
    { value: '24hours', label: '24小时' },
    { value: '7days', label: '7天' },
    { value: '30days', label: '30天' },
    { value: '3months', label: '3个月' },
    { value: 'all', label: '全部时间' },
];

// 获取订单数据
const fetchOrders = async (page = 1) => {
    loading.value = true;
    try {
        const params = {
            page: page,
            per_page: 20
        };
        
        // 添加筛选条件
        if (filters.value.status !== 'all') {
            if (filters.value.status === 'disputed') {
                params.status = 'disputed';
            } else {
                params.status = filters.value.status;
            }
        }
        
        if (filters.value.trade_type !== 'all') {
            params.trade_type = filters.value.trade_type;
        }
        
        if (filters.value.cryptocurrency !== 'all') {
            params.cryptocurrency = filters.value.cryptocurrency;
        }
        
        if (filters.value.time_range !== 'all') {
            params.time_range = filters.value.time_range;
        }
        
        if (searchQuery.value) {
            params.search = searchQuery.value;
        }
        
        const response = await axios.get('/web/api/orders', { params });
        
        if (response.data.success) {
            orders.value = response.data.data.data || [];
            pagination.value = {
                current_page: response.data.data.current_page || 1,
                last_page: response.data.data.last_page || 1,
                per_page: response.data.data.per_page || 20,
                total: response.data.data.total || 0
            };
        }
    } catch (error) {
        console.error('获取订单失败:', error);
    } finally {
        loading.value = false;
    }
};

// 监听筛选条件变化
watch([filters], () => {
    fetchOrders(1);
}, { deep: true });

// 监听搜索框变化（防抖）
watch(searchQuery, (newValue) => {
    clearTimeout(searchTimer.value);
    searchTimer.value = setTimeout(() => {
        fetchOrders(1);
    }, 500);
});

// 页面加载时获取数据
onMounted(() => {
    fetchOrders();
});

// 统计数据
const statistics = computed(() => {
    const active = orders.value.filter(o => o.status === 'trading').length;
    const completed = orders.value.filter(o => o.status === 'completed').length;
    return {
        active: active,
        completed: completed,
        total: pagination.value.total || orders.value.length,
    };
});

// 计算当前用户角色
const getUserRole = (order) => {
    const currentUserId = window.Laravel?.user?.id;
    if (!currentUserId) return null;
    
    return order.buyer_id === currentUserId ? 'buyer' : 'seller';
};

// 获取交易对方信息
const getTrader = (order) => {
    const currentUserId = window.Laravel?.user?.id;
    if (!currentUserId) return null;
    
    if (order.vendor_id === currentUserId) {
        return order.client;
    } else {
        return order.vendor;
    }
};

// 获取订单进度百分比
const getOrderProgress = (order) => {
    const progressMap = {
        'order_initiated': 10,
        'vendor_confirmed': 20,
        'seller_paid': 30,
        'escrow_received': 50,
        'buyer_confirmed_escrow': 60,
        'buyer_paid': 80,
        'seller_received': 90,
        'escrow_released': 100,
        'cancelled': 0
    };
    return progressMap[order.escrow_status] || 0;
};

// 辅助函数
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    const hours = Math.floor(diff / (1000 * 60 * 60));
    
    if (hours < 1) {
        const minutes = Math.floor(diff / (1000 * 60));
        return `${minutes}分钟前`;
    } else if (hours < 24) {
        return `${hours}小时前`;
    } else {
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        const hour = date.getHours().toString().padStart(2, '0');
        const minute = date.getMinutes().toString().padStart(2, '0');
        return `${month}/${day} ${hour}:${minute}`;
    }
};

const getStatusName = (order) => {
    if (order.is_disputed) {
        return '争议中';
    }
    
    const statusMap = {
        'trading': '交易中',
        'completed': '已完成',
        'cancelled': '已取消',
    };
    return statusMap[order.status] || order.status;
};

// 获取托管状态的具体描述
const getEscrowStatusName = (escrowStatus) => {
    const statusMap = {
        'order_initiated': '订单创建',
        'vendor_confirmed': '商家已确认',
        'seller_paid': '卖家已转币',
        'escrow_received': '托管已到账',
        'buyer_confirmed_escrow': '买家已确认托管',
        'buyer_paid': '买家已付款',
        'seller_received': '卖家已收款',
        'escrow_released': '托管已释放',
        'cancelled': '已取消'
    };
    return statusMap[escrowStatus] || escrowStatus;
};

const getStatusColor = (order) => {
    if (order.is_disputed) {
        return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
    }

    const colorMap = {
        'trading': 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
        'completed': 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
        'cancelled': 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400',
    };
    return colorMap[order.status] || 'bg-gray-100 text-gray-700';
};

const getPaymentMethodName = (methods) => {
    if (!methods || methods.length === 0) return '未设置';
    
    const methodMap = {
        'alipay': '支付宝',
        'wechat': '微信',
        'bank_transfer': '银行转账',
        'paypal': 'PayPal'
    };
    
    if (Array.isArray(methods)) {
        return methodMap[methods[0]] || methods[0];
    }
    
    return methodMap[methods] || methods;
};

// 页面切换
const handlePageChange = (page) => {
    fetchOrders(page);
};
</script>