<template>
    <P2PAppLayout
        :hide-nav="true"
        :hide-footer="true"
        :show-mobile-bottom-nav="false"
        :show-breadcrumbs="true"
        :breadcrumbs="[
            { label: '订单', url: '/orders' },
            { label: `订单 #${order.order_no}`, url: null },
            { label: '交易聊天' }
        ]"
    >
        <!-- 移动端返回导航 -->
        <P2PMobileHeader :title="`订单 #${order.order_no}`" back-url="/orders">
            <template #custom>
                <div class="flex items-center gap-2 flex-1">
                    <!-- 显示所有参与者头像 -->
                    <div class="flex -space-x-2">
                        <template v-for="(participant, index) in participants" :key="participant.id">
                            <div class="relative" :style="`z-index: ${participants.length - index}`">
                                <!-- 使用profile photo if available -->
                                <img
                                    v-if="participant.profile_photo_url"
                                    :src="participant.profile_photo_url"
                                    :alt="participant.name"
                                    :class="[
                                        'h-8 w-8 rounded-full object-cover ring-2',
                                        participant.is_online ? 'ring-emerald-500' : 'ring-gray-400 dark:ring-gray-600'
                                    ]"
                                />
                                <!-- Fallback to initial -->
                                <div v-else :class="[
                                    'h-8 w-8 rounded-full flex items-center justify-center ring-2 ring-white dark:ring-gray-950',
                                    participant.is_online ? 'bg-gradient-to-br from-emerald-500 to-emerald-600' : 'bg-gray-400 dark:bg-gray-600'
                                ]">
                                    <span class="text-white text-xs font-bold">{{ participant.name?.[0]?.toUpperCase() || 'U' }}</span>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                            订单 #{{ order.order_no }}
                        </p>
                          <p class="text-xs text-gray-400 dark:text-gray-600">
                            {{ participants.filter(p => p.is_online).length }}/{{ participants.length }} 在房间
                        </p>
                    </div>
                </div>
            </template>
            <template #actions>
                <button @click="showTradeTerms = !showTradeTerms" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </button>
                <button @click="showMobileInfo = !showMobileInfo" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </template>
        </P2PMobileHeader>

        <!-- 移动端固定高度，防止整页滚动 -->
        <div class="lg:min-h-screen lg:bg-gray-50 lg:dark:bg-gray-950 h-screen lg:h-auto overflow-hidden lg:overflow-visible fixed lg:relative inset-0">
            <!-- 已取消订单提示 -->
            <div v-if="order.status === 'cancelled'" class="relative z-10 bg-red-50 dark:bg-red-900/20 border-b border-red-200 dark:border-red-800">
                <div class="max-w-7xl mx-auto px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">此订单已取消，无法进行任何操作</p>
                    </div>
                </div>
            </div>

            <!-- 争议中订单提示 -->
            <div v-if="order.is_disputed && order.status !== 'cancelled'" class="relative z-10 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800">
                <div class="max-w-7xl mx-auto px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="flex items-start gap-3 flex-1">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-amber-800 dark:text-amber-200">该订单处于争议中</p>
                                <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                                    请等待仲裁管理员加入聊天，管理员将在 <span class="font-semibold">24小时内</span> 加入处理。
                                    在此期间，您可以与对方自行协商解决。
                                </p>
                                <p v-if="order.dispute_reason" class="text-xs text-amber-700 dark:text-amber-300 mt-2">
                                    <span class="font-medium">争议原因：</span>{{ order.dispute_reason }}
                                </p>
                            </div>
                        </div>
                        <!-- 解除争议按钮（如果有权限） -->
                        <div v-if="availableActions.includes('resolve_dispute')" class="sm:ml-auto">
                            <P2PButton
                                @click="handleEscrowAction('resolve_dispute')"
                                variant="outline"
                                size="sm"
                            >
                                解除争议
                            </P2PButton>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Telegram 未连接提醒 (仅桌面端显示) -->
            <div v-if="!$page.props.auth.user?.telegram_chat_id && order.status !== 'cancelled' && order.status !== 'completed'"
                 class="hidden md:block relative z-10 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800">
                <div class="max-w-7xl mx-auto px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="flex items-start gap-3 flex-1">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">建议设置 Telegram 接收实时通知</p>
                                <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                                    连接 Telegram 后，您可以实时收到交易状态更新、对方消息提醒等重要通知，确保不错过任何关键信息。
                                </p>
                            </div>
                        </div>
                        <div class="sm:ml-auto">
                            <Link href="/notifications/settings"
                                  class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                                </svg>
                                立即设置
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 移动端：底部弹出式信息面板 -->
            <P2PMobileDrawer
                v-model="showMobileInfo"
                title="交易详情"
                :subtitle="`订单 #${order.order_no}`"
                position="bottom"
                size="default"
                :icon-color="'blue'"
            >
                <template #header-icon>
                    <div class="p-2 rounded bg-blue-100 dark:bg-blue-900/30">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </template>

                <div class="p-4 space-y-4">
                    <div class="space-y-4">
                        <!-- 托管状态 -->
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-300">{{ getStatusBadgeText(order.escrow_status) }}</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400">当前进度: {{ getProgressPercentage(order.escrow_status) }}%</p>
                                </div>
                            </div>
                        </div>

                        <!-- 交易流程步骤 -->
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">交易流程</p>
                            <div class="space-y-3">
                                <!-- 根据订单状态动态显示步骤 -->
                                <!-- 步骤 1: 订单创建 -->
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">订单创建</p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">{{ order.created_at ? formatTime(order.created_at) : '已完成' }}</p>
                                        <!-- 商家确认按钮 -->
                                        <div v-if="order.escrow_status === 'order_initiated' && availableActions.includes('vendor_confirm')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('vendor_confirm')"
                                                variant="primary"
                                                size="sm"
                                            >
                                                确认接单
                                            </P2PButton>
                                        </div>
                                    </div>
                                </div>

                                <!-- 步骤 2: 商家确认 -->
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'order_initiated' ? 'bg-amber-500 animate-pulse' :
                                            ['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-600' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">2</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'">
                                            商家确认
                                        </p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">
                                            {{ order.vendor_confirmed_at ? formatTime(order.vendor_confirmed_at) : '等待商家确认订单' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- 步骤 3: 卖家转币 -->
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'vendor_confirmed' ? 'bg-amber-500 animate-pulse' :
                                            ['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-600' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">3</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'">
                                            卖家转币到托管
                                        </p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">
                                            {{ order.seller_paid_at ? formatTime(order.seller_paid_at) : '等待卖家转入' }}
                                        </p>
                                        <!-- 转币说明 -->
                                        <div class="mt-2 p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded text-xs space-y-1">
                                            <p class="text-gray-700 dark:text-gray-300 font-medium">请卖家转币：</p>
                                            <div class="space-y-0.5">
                                                <p class="text-gray-600 dark:text-gray-400">
                                                    <span class="inline-block w-12">从:</span>
                                                    <span class="font-mono text-blue-600 dark:text-blue-400 break-all">{{ order.seller_address }}</span>
                                                </p>
                                                <p class="text-gray-600 dark:text-gray-400">
                                                    <span class="inline-block w-12">到:</span>
                                                    <span class="font-mono text-blue-600 dark:text-blue-400 break-all">{{ order.escrow_address }}</span>
                                                </p>
                                                <p class="text-gray-600 dark:text-gray-400">
                                                    <span class="inline-block w-12">金额:</span>
                                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ parseFloat(order.crypto_amount).toFixed(4) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}</span>
                                                </p>
                                                <p v-if="order.escrow_tx_hash" class="text-xs text-blue-600 dark:text-blue-400 font-mono break-all mt-1">
                                                    交易哈希: {{ order.escrow_tx_hash }}
                                                </p>
                                            </div>
                                        </div>
                                        <!-- 显示交易哈希 -->

                                        <!-- 卖家转币按钮 -->
                                        <div v-if="order.escrow_status === 'vendor_confirmed' && availableActions.includes('mark_seller_paid')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('mark_seller_paid')"
                                                variant="primary"
                                                size="sm"
                                            >
                                                我已转币
                                            </P2PButton>
                                        </div>
                                    </div>
                                </div>

                                <!-- 步骤 4: 托管确认 -->
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'seller_paid' ? 'bg-amber-500 animate-pulse' :
                                            ['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-600' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">4</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'">
                                            托管确认到账
                                        </p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">
                                            {{ order.escrow_received_at ? formatTime(order.escrow_received_at) : '系统确认中，注意：如果链上已到账，但是系统长时间还未确认，请联系官方Telegram。' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- 步骤 5: 买家确认托管到账 -->
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'escrow_received' ? 'bg-amber-500 animate-pulse' :
                                            ['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-600' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">5</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'">
                                            买家确认托管到账
                                        </p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">
                                            {{ order.buyer_confirmed_escrow_at ? formatTime(order.buyer_confirmed_escrow_at) : '等待买家确认' }}
                                        </p>
                                        <!-- 买家确认托管按钮 -->
                                        <div v-if="order.escrow_status === 'escrow_received' && availableActions.includes('confirm_escrow')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('confirm_escrow')"
                                                variant="primary"
                                                size="sm"
                                            >
                                                确认托管到账
                                            </P2PButton>
                                        </div>
                                    </div>
                                </div>

                                <!-- 步骤 6: 买家付款 -->
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'buyer_confirmed_escrow' ? 'bg-amber-500 animate-pulse' :
                                            ['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-600' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">6</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'">
                                            买家付款
                                        </p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">
                                            {{ order.buyer_paid_at ? formatTime(order.buyer_paid_at) : '等待买家付款' }}
                                        </p>
                                        <!-- 买家付款按钮 -->
                                        <div v-if="order.escrow_status === 'buyer_confirmed_escrow' && availableActions.includes('mark_buyer_paid')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('mark_buyer_paid')"
                                                variant="primary"
                                                size="sm"
                                            >
                                                我已付款
                                            </P2PButton>
                                        </div>
                                    </div>
                                </div>

                                <!-- 步骤 7: 卖家确认收款 -->
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'buyer_paid' ? 'bg-amber-500 animate-pulse' :
                                            ['seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-600' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">7</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-gray-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'">
                                            卖家确认收款
                                        </p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">
                                            {{ order.seller_received_at ? formatTime(order.seller_received_at) : '等待卖家确认' }}
                                        </p>
                                        <!-- 卖家确认收款按钮 -->
                                        <div v-if="order.escrow_status === 'buyer_paid' && availableActions.includes('mark_seller_received')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('mark_seller_received')"
                                                variant="success"
                                                size="sm"
                                            >
                                                确认收款
                                            </P2PButton>
                                        </div>
                                    </div>
                                </div>

                                <!-- 步骤 8: 释放托管 -->
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'seller_received' ? 'bg-amber-500 animate-pulse' :
                                            order.escrow_status === 'escrow_released' ? 'bg-emerald-600' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="order.escrow_status === 'escrow_released'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">8</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium" :class="['seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'">
                                            托管释放
                                        </p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">
                                            {{ order.escrow_released_at ? formatTime(order.escrow_released_at) : (order.escrow_status === 'seller_received' ? '处理中...如果处理时间超过一小时无反应，请联系官方Telegram。' : '待完成') }}
                                        </p>
                                        <!-- 显示释放交易哈希 -->
                                        <p v-if="order.release_tx_hash" class="text-xs text-blue-600 dark:text-blue-400 font-mono break-all mt-1">
                                            交易哈希: {{ order.release_tx_hash }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 交易信息 -->
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">交易信息</p>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">订单编号</span>
                                    <p class="font-mono text-gray-900 dark:text-gray-200">#{{ order.order_no }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">商家</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-200">{{ order.vendor?.name || '加载中...' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">客户</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-200">{{ order.client?.name || '加载中...' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">买家</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-200">{{ order.buyer?.name || '加载中...' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">卖家</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-200">{{ order.seller?.name || '加载中...' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">币种</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-200">{{ order.currency_label || order.currency_key || order.crypto_currency }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">数量（含交易费）</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-200">{{ parseFloat(order.crypto_amount).toFixed(4) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}</p>
                                    <p v-if="order.fee && order.fee > 0" class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">
                                        交易费（商家）: {{ parseFloat(order.fee).toFixed(8) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">单价</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-200">¥{{ parseFloat(order.price).toFixed(2) }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">总金额</span>
                                    <p class="font-bold text-emerald-600 dark:text-emerald-400">¥{{ parseFloat(order.fiat_amount).toFixed(2) }}</p>
                                </div>
                            </div>
                            <!-- 卖家地址 -->
                            <div v-if="order.seller_address" class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">卖家地址</span>
                                <p class="font-mono text-sm text-gray-900 dark:text-gray-200 break-all">{{ order.seller_address }}</p>
                            </div>
                            <!-- 托管地址 -->
                            <div v-if="order.escrow_address" class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">托管地址</span>
                                <p class="font-mono text-sm text-blue-600 dark:text-blue-400 break-all">{{ order.escrow_address }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </P2PMobileDrawer>


            <!-- 桌面端原有布局 -->
            <div class="mx-auto max-w-7xl">
                <div class="hidden md:grid md:grid-cols-3 gap-4 p-4">
                    <!-- 左侧：交易信息面板（桌面端显示） -->
                    <div class="md:col-span-1 space-y-4">
                        <!-- 交易状态卡片 -->
                        <div class="bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-800 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">交易详情</h3>
                                <span :class="getStatusBadgeClass(order.escrow_status)" class="px-3 py-1 text-xs font-semibold rounded-full">
                                    {{ getStatusBadgeText(order.escrow_status) }}
                                </span>
                            </div>


                            <!-- 交易进度 -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                                    <span>交易进度</span>
                                    <span>{{ getProgressPercentage(order.escrow_status) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full" :style="`width: ${getProgressPercentage(order.escrow_status)}%`"></div>
                                </div>
                            </div>

                            <!-- 步骤指示器 - 7步完整流程 -->
                            <div class="space-y-4 mb-6">
                                <!-- 步骤1：订单创建 -->
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">订单创建</p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">{{ order.created_at ? formatTime(order.created_at) : '已完成' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 步骤2：商家确认 -->
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'order_initiated' ? 'bg-amber-500 animate-pulse' :
                                            ['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-500' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">2</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'">
                                                商家确认
                                            </p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">
                                                {{ order.vendor_confirmed_at ? formatTime(order.vendor_confirmed_at) : (order.escrow_status === 'order_initiated' ? '等待中...' : '待完成') }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- 商家确认按钮 -->
                                    <div v-if="order.escrow_status === 'order_initiated' && availableActions.includes('vendor_confirm')" class="ml-11 mt-2">
                                        <P2PButton
                                            @click="handleEscrowAction('vendor_confirm')"
                                            variant="primary"
                                            size="sm"
                                            fullWidth
                                        >
                                            确认接单
                                        </P2PButton>
                                    </div>
                                </div>

                                <!-- 步骤3：卖家转币 -->
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'vendor_confirmed' ? 'bg-amber-500 animate-pulse' :
                                            ['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-500' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">3</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'">
                                                卖家转币到托管
                                            </p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">
                                                {{ order.seller_paid_at ? formatTime(order.seller_paid_at) : (order.escrow_status === 'vendor_confirmed' ? '等待卖家转入' : '待完成') }}
                                            </p>
                                            <!-- 转币说明 -->
                                            <div v-if="order.escrow_address" class="mt-2 p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded text-xs space-y-1">
                                                <p class="text-gray-700 dark:text-gray-300 font-medium">请卖家转币：</p>
                                                <div class="space-y-0.5">
                                                    <p class="text-gray-600 dark:text-gray-400">
                                                        <span class="inline-block w-12">从:</span>
                                                        <span class="font-mono text-blue-600 dark:text-blue-400 break-all">{{ order.seller_address }}</span>
                                                    </p>
                                                    <p class="text-gray-600 dark:text-gray-400">
                                                        <span class="inline-block w-12">到:</span>
                                                        <span class="font-mono text-blue-600 dark:text-blue-400 break-all">{{ order.escrow_address }}</span>
                                                    </p>
                                                    <p class="text-gray-600 dark:text-gray-400">
                                                        <span class="inline-block w-12">金额:</span>
                                                        <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ parseFloat(order.crypto_amount).toFixed(4) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}</span>
                                                    </p>
                                                    <p v-if="order.escrow_tx_hash" class="text-xs text-blue-600 dark:text-blue-400 font-mono break-all mt-1">
                                                        交易哈希: {{ order.escrow_tx_hash }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 卖家转币按钮 -->
                                    <div v-if="order.escrow_status === 'vendor_confirmed' && availableActions.includes('mark_seller_paid')" class="ml-11 mt-2">
                                        <P2PButton
                                            @click="handleEscrowAction('mark_seller_paid')"
                                            variant="primary"
                                            size="sm"
                                        >
                                            我已转币到托管
                                        </P2PButton>
                                    </div>
                                </div>

                                <!-- 步骤4：托管确认 -->
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'seller_paid' ? 'bg-amber-500 animate-pulse' :
                                            ['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-500' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">4</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'">
                                                托管确认到账
                                            </p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">
                                                {{ order.escrow_received_at ? formatTime(order.escrow_received_at) : (order.escrow_status === 'seller_paid' ? '系统确认中...' : '待完成') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 步骤5：买家确认托管到账 -->
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'escrow_received' ? 'bg-amber-500 animate-pulse' :
                                            ['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-500' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">5</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'">
                                                买家确认托管到账
                                            </p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">
                                                {{ order.buyer_confirmed_escrow_at ? formatTime(order.buyer_confirmed_escrow_at) : (order.escrow_status === 'escrow_received' ? '等待确认...' : '待完成') }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- 买家确认托管按钮 -->
                                    <div v-if="order.escrow_status === 'escrow_received' && availableActions.includes('confirm_escrow')" class="ml-11 mt-2">
                                        <P2PButton
                                            @click="handleEscrowAction('confirm_escrow')"
                                            variant="primary"
                                            size="sm"
                                            fullWidth
                                        >
                                            确认托管到账
                                        </P2PButton>
                                    </div>
                                </div>

                                <!-- 步骤6：买家付款 -->
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'buyer_confirmed_escrow' ? 'bg-amber-500 animate-pulse' :
                                            ['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-500' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">6</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'">
                                                买家付款
                                            </p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">
                                                {{ order.buyer_paid_at ? formatTime(order.buyer_paid_at) : (order.escrow_status === 'buyer_confirmed_escrow' ? '等待中...' : '待完成') }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- 买家付款按钮 -->
                                    <div v-if="order.escrow_status === 'buyer_confirmed_escrow' && availableActions.includes('mark_buyer_paid')" class="ml-11 mt-2">
                                        <P2PButton
                                            @click="handleEscrowAction('mark_buyer_paid')"
                                            variant="primary"
                                            size="sm"
                                            fullWidth
                                        >
                                            我已付款
                                        </P2PButton>
                                    </div>
                                </div>

                                <!-- 步骤7：卖家确认收款 -->
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'buyer_paid' ? 'bg-amber-500 animate-pulse' :
                                            ['seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-500' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="['seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">7</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'">
                                                卖家确认收款
                                            </p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">
                                                {{ order.seller_received_at ? formatTime(order.seller_received_at) : (order.escrow_status === 'buyer_paid' ? '等待中...' : '待完成') }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- 卖家确认收款按钮 -->
                                    <div v-if="order.escrow_status === 'buyer_paid' && availableActions.includes('mark_seller_received')" class="ml-11 mt-2">
                                        <P2PButton
                                            @click="handleEscrowAction('mark_seller_received')"
                                            variant="success"
                                            size="sm"
                                            fullWidth
                                        >
                                            确认收款
                                        </P2PButton>
                                    </div>
                                </div>

                                <!-- 步骤8：释放托管 -->
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'seller_received' ? 'bg-amber-500 animate-pulse' :
                                            order.escrow_status === 'escrow_released' ? 'bg-emerald-500' :
                                            'bg-gray-300 dark:bg-gray-700'
                                        ]">
                                            <svg v-if="order.escrow_status === 'escrow_released'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">8</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'">
                                                托管释放
                                            </p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">
                                                {{ order.escrow_released_at ? formatTime(order.escrow_released_at) : (order.escrow_status === 'seller_received' ? '处理中...' : '待完成') }}
                                            </p>
                                            <!-- 显示释放交易哈希 -->
                                            <p v-if="order.release_tx_hash" class="text-xs text-blue-600 dark:text-blue-400 font-mono break-all mt-1">
                                                交易哈希: {{ order.release_tx_hash }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 交易信息 -->
                            <div class="space-y-3 border-t border-gray-200 dark:border-gray-800 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">订单编号</span>
                                    <span class="text-sm font-mono text-gray-900 dark:text-gray-200">#{{ order.order_no }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">商家</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ order.vendor?.name || '加载中...' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">客户</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ order.client?.name || '加载中...' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">买家</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ order.buyer?.name || '加载中...' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">卖家</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ order.seller?.name || '加载中...' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">数量（含交易费）</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ parseFloat(order.crypto_amount).toFixed(4) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}</span>
                                </div>
                                <div v-if="order.fee && order.fee > 0" class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">交易费（商家）</span>
                                    <span class="text-sm text-amber-600 dark:text-amber-400">{{ parseFloat(order.fee).toFixed(8) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">单价</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-200">¥{{ parseFloat(order.price).toFixed(2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">总金额</span>
                                    <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">¥{{ parseFloat(order.fiat_amount).toFixed(2) }}</span>
                                </div>
                                <!-- 卖家地址 -->
                                <div v-if="order.seller_address" class="flex justify-between items-start pt-3 border-t border-gray-100 dark:border-gray-800">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">卖家地址</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-200 text-right break-all max-w-[180px]">
                                        {{ order.seller_address }}
                                    </span>
                                </div>
                                <!-- 托管地址 -->
                                <div v-if="order.escrow_address" class="flex justify-between items-start pt-3 border-t border-gray-100 dark:border-gray-800">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">托管地址</span>
                                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400 text-right break-all max-w-[180px]">
                                        {{ order.escrow_address }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 右侧区域 -->
                    <div class="md:col-span-2">
                        <!-- 聊天区域（桌面端） -->
                        <div class="bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-800 flex flex-col" style="height: 650px; min-height: 600px;">
                            <!-- 聊天头部 -->
                            <div class="border-b border-gray-200 dark:border-gray-800">
                                <div class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <!-- 参与者头像组 -->
                                            <div class="flex -space-x-2">
                                                <template v-for="(participant, index) in participants" :key="participant.id">
                                                    <a :href="`/trader/profile/${participant.id}`"
                                                       target="_blank"
                                                       class="relative hover:opacity-80 transition-opacity cursor-pointer"
                                                       :style="`z-index: ${participants.length - index}`">
                                                        <!-- 使用profile photo if available -->
                                                        <img
                                                            v-if="participant.profile_photo_url"
                                                            :src="participant.profile_photo_url"
                                                            :alt="participant.name"
                                                            class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-900"
                                                        />
                                                        <!-- Fallback to initial -->
                                                        <div v-else class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-2 ring-white dark:ring-gray-900">
                                                            <span class="text-white font-bold">{{ participant.name?.[0]?.toUpperCase() || 'U' }}</span>
                                                        </div>
                                                        <div v-if="participant.is_online" class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 bg-emerald-500 rounded-full ring-2 ring-white dark:ring-gray-900"></div>
                                                    </a>
                                                </template>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                                        订单 #{{ order.order_no }} 聊天室
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">({{ participants.length }}人)</span>
                                                    </p>
                                                </div>
                                                  <p class="text-xs text-gray-400 dark:text-gray-600">
                                                <span v-for="(participant, index) in participants" :key="participant.id">
                                                    <span :class="[
                                                        participant.is_online ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500',
                                                        participant.is_admin ? 'font-medium' : ''
                                                    ]">
                                                        {{ participant.name }}
                                                    </span>
                                                    <span v-if="index < participants.length - 1" class="text-gray-400"> · </span>
                                                </span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 聊天消息区域 -->
                            <div ref="messageContainer" class="flex-1 overflow-y-auto p-6 space-y-4">
                                <!-- 初始系统消息 -->
                                <div class="flex flex-col items-center gap-1">
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ new Date(order.created_at).toLocaleTimeString('zh-CN', { hour: '2-digit', minute: '2-digit' }) }}
                                    </div>
                                    <div class="px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded text-xs text-gray-500 dark:text-gray-400">
                                        订单已创建，等待商家确认
                                    </div>
                                </div>

                                <!-- 安全提醒 -->
                                <div class="mb-4 mx-4">
                                    <div class="bg-amber-50 dark:bg-amber-900/20 rounded p-3 border border-amber-200 dark:border-amber-800">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <div class="text-xs">
                                                <p class="font-medium text-amber-700 dark:text-amber-300 mb-1">安全提醒</p>
                                                <p class="text-amber-600 dark:text-amber-400">请勿相信任何平台外的交易邀请，谨防诈骗。任何平台以外的交流都无法作为争议材料。</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 商家公告 -->
                                <div v-if="order.ad_snapshot?.welcome_message" class="mb-4 mx-4">
                                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded p-3 border border-blue-200 dark:border-blue-800">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-xs font-medium text-blue-700 dark:text-blue-300">商家公告</span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ order.ad_snapshot.welcome_message }}</p>
                                        <div v-if="order.vendor?.response_time" class="mt-2 pt-2 border-t border-blue-200 dark:border-blue-700">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    该商家的响应时间是 <span class="font-medium text-blue-600 dark:text-blue-400">{{ order.vendor.response_time }} 分钟</span>，请在该时间内等待商家加入聊天室。
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 独立响应时间提醒（没有欢迎消息时） -->
                                <div v-if="order.vendor?.response_time && !order.ad_snapshot?.welcome_message" class="mb-4 mx-4">
                                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded p-3 border border-blue-200 dark:border-blue-800">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                该商家的响应时间是 <span class="font-medium text-blue-600 dark:text-blue-400">{{ order.vendor.response_time }} 分钟</span>，请在该时间内等待商家加入聊天室。
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 动态消息列表 -->
                                <div
                                    v-for="msg in messages"
                                    :key="msg.id"
                                    :class="[
                                    'flex',
                                    msg.type === 'system' ? 'justify-center' : 'gap-3',
                                    msg.type !== 'system' && msg.user?.id === $page.props.auth.user?.id ? 'justify-end' : ''
                                ]"
                                >
                                    <!-- 系统消息 -->
                                    <template v-if="msg.type === 'system'">
                                        <div class="flex flex-col items-center gap-1">
                                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ formatTime(msg.created_at) }}
                                            </div>
                                            <div :class="[
                                            'px-3 py-1 rounded text-xs max-w-md',
                                            msg.is_temporary ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'
                                        ]">
                                                <span v-if="msg.user">{{ msg.user.name }} </span>
                                                {{ msg.message || msg.content }}
                                            </div>

                                            <!-- 评价按钮或已评价状态 - 交易完成时显示 -->
                                            <div v-if="msg.show_review_button">
                                                <!-- 未评价 - 显示评价按钮 -->
                                                <div v-if="!order.has_review"
                                                     class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded border border-amber-200 dark:border-amber-800 max-w-sm">
                                                    <div class="flex flex-col items-center gap-2">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                            <span class="text-sm font-medium text-amber-800 dark:text-amber-300">请评价本次交易</span>
                                                        </div>
                                                        <P2PButton
                                                            @click="showReviewModal = true"
                                                            variant="warning"
                                                            size="sm"
                                                            fullWidth
                                                        >
                                                            <template #icon>
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            </template>
                                                            立即评价
                                                        </P2PButton>
                                                    </div>
                                                </div>
                                                <!-- 已评价 - 显示状态 -->
                                                <div v-else
                                                     class="mt-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded border border-emerald-200 dark:border-emerald-800 max-w-sm">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-sm font-medium text-emerald-800 dark:text-emerald-300">您已评价此交易</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- 对方消息 -->
                                    <template v-else-if="msg.user?.id !== $page.props.auth.user?.id">
                                        <a :href="`/trader/profile/${msg.user.id}`" target="_blank" class="h-8 w-8 rounded-full flex-shrink-0 hover:opacity-80 transition-opacity cursor-pointer">
                                            <!-- 使用profile photo if available -->
                                            <img
                                                v-if="msg.user.profile_photo_url"
                                                :src="msg.user.profile_photo_url"
                                                :alt="msg.user.name"
                                                class="h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                                            />
                                            <!-- Fallback to initial -->
                                            <div v-else class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">{{ msg.user.name.charAt(0) }}</span>
                                            </div>
                                        </a>
                                        <div class="max-w-lg">
                                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1 px-1">
                                                {{ msg.user.name }}
                                                <span v-if="msg.type === 'admin'" class="text-purple-600 dark:text-purple-400 font-bold">(Admin)</span>
                                            </p>
                                            <div class="inline-block bg-gray-100 dark:bg-gray-800 rounded rounded-tl-none px-4 py-3">
                                                <!-- 附件显示 -->
                                                <div v-if="msg.attachment" class="mb-2">
                                                    <P2PAttachment
                                                        :attachment="msg.attachment"
                                                        variant="default"
                                                        thumbnail-size="md"
                                                    />
                                                </div>
                                                <p v-if="msg.message" class="text-sm text-gray-900 dark:text-gray-200 break-words">{{ msg.message }}</p>
                                            </div>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-1">{{ formatTime(msg.created_at) }}</p>
                                        </div>
                                    </template>

                                    <!-- 自己的消息 -->
                                    <template v-else>
                                        <div class="max-w-lg">
                                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1 px-1 text-right">
                                                {{ msg.user.name }}
                                                <span v-if="msg.type === 'admin'" class="text-purple-600 dark:text-purple-400 font-bold">(Admin)</span>
                                            </p>
                                            <div class="inline-block bg-emerald-600 text-white rounded rounded-tr-none px-4 py-3">
                                                <!-- 附件显示 -->
                                                <div v-if="msg.attachment" class="mb-2">
                                                    <P2PAttachment
                                                        :attachment="msg.attachment"
                                                        variant="primary"
                                                        thumbnail-size="md"
                                                    />
                                                </div>
                                                <p v-if="msg.message" class="text-sm break-words">{{ msg.message }}</p>
                                            </div>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-1 text-right">{{ formatTime(msg.created_at) }}</p>
                                        </div>
                                        <!-- 当前用户头像 -->
                                        <img
                                            v-if="$page.props.auth.user?.profile_photo_url"
                                            :src="$page.props.auth.user.profile_photo_url"
                                            :alt="$page.props.auth.user.name"
                                            class="h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-gray-700 flex-shrink-0"
                                        />
                                        <!-- Fallback to initial -->
                                        <div v-else class="h-8 w-8 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-sm font-bold">{{ $page.props.auth.user?.name?.charAt(0).toUpperCase() || 'U' }}</span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- 输入区域 -->
                            <div class="border-t border-gray-200 dark:border-gray-800 p-4">
                                <!-- 文件预览 -->
                                <div v-if="selectedFile || isCompressing" class="mb-3 p-3 bg-gray-50 dark:bg-gray-800 rounded">
                                    <!-- 压缩中状态 -->
                                    <div v-if="isCompressing" class="flex items-center gap-3">
                                        <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200">正在压缩图片...</p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">优化图片大小以快速上传</p>
                                        </div>
                                    </div>
                                    <!-- 文件预览 -->
                                    <div v-else-if="selectedFile" class="flex items-start gap-3">
                                        <!-- 图片预览 -->
                                        <img v-if="selectedFile.type.startsWith('image/')"
                                             :src="filePreviewUrl"
                                             :alt="selectedFile.name"
                                             class="max-h-32 rounded"
                                        />
                                        <!-- PDF图标 -->
                                        <div v-else-if="selectedFile.type === 'application/pdf'"
                                             class="w-16 h-20 bg-red-100 dark:bg-red-900/30 rounded flex items-center justify-center">
                                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <!-- 文件信息 -->
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ selectedFile.name }}</p>
                                              <p class="text-xs text-gray-400 dark:text-gray-600">{{ formatFileSize(selectedFile.size) }}</p>
                                        </div>
                                        <!-- 移除按钮 -->
                                        <button @click="removeFile"
                                                type="button"
                                                class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <form @submit.prevent="sendMessage" class="flex gap-2">
                                    <!-- 隐藏的文件输入 -->
                                    <input
                                        ref="fileInput"
                                        type="file"
                                        @change="handleFileSelect"
                                        accept=".pdf,image/*"
                                        data-max-size="2097152"
                                        class="hidden"
                                    />
                                    <button type="button"
                                            @click="!isTradeCompleted && !isCompressing && $refs.fileInput.click()"
                                            :disabled="isTradeCompleted || isCompressing"
                                            class="p-2 rounded text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                    </button>

                                    <P2PEmojiPicker
                                        @select="(emoji) => newMessage += emoji"
                                        :disabled="isTradeCompleted"
                                        placement="top"
                                    />

                                    <input
                                        v-model="newMessage"
                                        type="text"
                                        :placeholder="isTradeCompleted ? '交易已结束，无法发送消息' : '输入消息...'"
                                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-800 border-0 rounded text-base md:text-sm text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 focus:bg-white dark:focus:bg-gray-700 focus:ring-2 focus:ring-emerald-500 focus:outline-none disabled:opacity-50 transition-colors"
                                        :disabled="!isConnected || isTradeCompleted"
                                    />

                                    <button
                                        type="submit"
                                        :disabled="(!newMessage.trim() && !selectedFile) || !isConnected || isUploading || isTradeCompleted"
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded font-medium text-sm transition-colors flex items-center gap-2"
                                    >
                                        <svg v-if="isUploading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span v-else>发送</span>
                                    </button>
                                </form>

                                <!-- 操作按钮区域 -->
                                <div v-if="(availableActions.length > 0 && order.status !== 'completed' && order.status !== 'cancelled' && order.escrow_status !== 'escrow_released') || order.status !== 'cancelled' && order.status !== 'completed'" class="flex mt-3 items-center justify-between">
                                    <div class="flex gap-2 items-center">
                                        <template v-for="action in availableActions" :key="action">
                                            <button
                                                v-if="action === 'vendor_confirm'"
                                                @click="handleEscrowAction('vendor_confirm')"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded transition-colors cursor-pointer"
                                            >
                                                确认订单
                                            </button>

                                            <button
                                                v-if="action === 'mark_seller_paid'"
                                                @click="handleEscrowAction('mark_seller_paid')"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded transition-colors cursor-pointer"
                                            >
                                                我已转币
                                            </button>

                                            <button
                                                v-if="action === 'confirm_escrow'"
                                                @click="handleEscrowAction('confirm_escrow')"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded transition-colors cursor-pointer"
                                            >
                                                确认托管
                                            </button>

                                            <button
                                                v-if="action === 'mark_buyer_paid'"
                                                @click="handleEscrowAction('mark_buyer_paid')"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded transition-colors cursor-pointer"
                                            >
                                                我已付款
                                            </button>

                                            <button
                                                v-if="action === 'mark_seller_received'"
                                                @click="handleEscrowAction('mark_seller_received')"
                                                class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors cursor-pointer"
                                            >
                                                确认收款
                                            </button>

                                            <button
                                                v-if="action === 'cancel'"
                                                @click="handleEscrowAction('cancel')"
                                                class="px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded transition-colors cursor-pointer"
                                            >
                                                取消订单
                                            </button>
                                        </template>

                                        <!-- 争议按钮 -->
                                        <button
                                            v-if="availableActions.includes('create_dispute')"
                                            @click="handleEscrowAction('create_dispute')"
                                            class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded transition-colors cursor-pointer"
                                        >
                                            发起争议
                                        </button>

                                        <button
                                            v-if="availableActions.includes('resolve_dispute')"
                                            @click="handleEscrowAction('resolve_dispute')"
                                            class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors cursor-pointer"
                                        >
                                            解除争议
                                        </button>
                                    </div>

                                    <!-- Telegram 通知提醒 (桌面版) -->
                                    <a v-if="order.status !== 'cancelled' && order.status !== 'completed'"
                                       href="/notifications/settings"
                                       target="_blank"
                                       :class="[
                                       'inline-flex items-center gap-1 px-3 py-1 text-xs rounded transition-colors cursor-pointer',
                                       $page.props.auth.user?.telegram_chat_id
                                           ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-900/50'
                                           : 'bg-blue-500 hover:bg-blue-600 text-white'
                                   ]">
                                        <svg v-if="$page.props.auth.user?.telegram_chat_id" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <svg v-else class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                                        </svg>
                                        {{ $page.props.auth.user?.telegram_chat_id ? '已开启通知' : '设置实时通知' }}
                                    </a>
                                </div>

                                <!-- 交易状态栏 (桌面端) -->
                                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/50 rounded border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div :class="getStatusColor(order.escrow_status)" class="w-2 h-2 rounded-full animate-pulse"></div>
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                            {{ getStatusText(order.escrow_status) }}
                                        </span>
                                        </div>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span>订单 #{{ order.trade_id }}</span>
                                            <span>{{ order.crypto_amount }} {{ order.currency_label || order.currency_key }}</span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">¥{{ order.fiat_amount }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- 支付信息和交易信息区域 -->
                        <div class="space-y-4 mt-3">
                            <!-- 交易条款（全宽） -->
                            <div v-if="order.ad_snapshot?.vendor_toc" class="bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-800 p-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">交易条款</h3>
                                </div>

                                <!-- 条款内容 -->
                                <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded max-h-40 overflow-y-auto">
                                    <p class="text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ order.ad_snapshot?.vendor_toc || '无交易条款' }}</p>
                                </div>

                                <!-- 提醒 -->
                                <div class="mt-3 p-2 bg-amber-50 dark:bg-amber-900/20 rounded border border-amber-200 dark:border-amber-800">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <p class="text-xs text-amber-700 dark:text-amber-300">
                                            请仔细阅读并遵守以上交易条款，违反条款可能导致交易取消
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 移动端：聊天区域（全屏） -->
                <!-- 使用CSS计算高度，考虑safe area -->
                <div class="md:hidden fixed inset-x-0 bottom-0 flex flex-col bg-white dark:bg-gray-950"
                     :style="`top: calc(3.5rem + env(safe-area-inset-top))`">
                    <!-- 移动端聊天内容 -->
                    <div ref="mobileMessageContainer" class="flex-1 overflow-y-auto overscroll-contain bg-white dark:bg-gray-950">
                        <!-- 连接状态指示器 -->
                        <div v-if="!isConnected && isPageVisible"
                             class="sticky top-0 z-10 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800 p-2">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-xs text-amber-700 dark:text-amber-300">重新连接中...</span>
                            </div>
                        </div>

                        <div class="p-4 space-y-4">
                            <!-- 初始系统消息 -->
                            <div class="flex flex-col items-center gap-1">
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ new Date(order.created_at).toLocaleTimeString('zh-CN', { hour: '2-digit', minute: '2-digit' }) }}
                                </div>
                                <div class="px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded text-xs text-gray-500 dark:text-gray-400">
                                    订单已创建，等待商家确认
                                </div>
                            </div>

                            <!-- 安全提醒 -->
                            <div class="mb-4 mx-4">
                                <div class="bg-amber-50 dark:bg-amber-900/20 rounded p-3 border border-amber-200 dark:border-amber-800">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <div class="text-xs">
                                            <p class="font-medium text-amber-700 dark:text-amber-300 mb-1">安全提醒</p>
                                            <p class="text-amber-600 dark:text-amber-400">请勿相信任何平台外的交易邀请，谨防诈骗。任何平台以外的交流都无法作为争议材料。</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 商家公告 -->
                            <div v-if="order.ad_snapshot?.welcome_message" class="mb-4 mx-4">
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded p-3 border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-xs font-medium text-blue-700 dark:text-blue-300">商家公告</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ order.ad_snapshot.welcome_message }}</p>
                                    <div v-if="order.vendor?.response_time" class="mt-2 pt-2 border-t border-blue-200 dark:border-blue-700">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                该商家的响应时间是 <span class="font-medium text-blue-600 dark:text-blue-400">{{ order.vendor.response_time }} 分钟</span>，请在该时间内等待商家加入聊天室。
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 独立响应时间提醒（没有欢迎消息时） -->
                            <div v-if="order.vendor?.response_time && !order.ad_snapshot?.welcome_message" class="mb-4 mx-4">
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded p-3 border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            该商家的响应时间是 <span class="font-medium text-blue-600 dark:text-blue-400">{{ order.vendor.response_time }} 分钟</span>，请在该时间内等待商家加入聊天室。
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- 动态消息列表 -->
                            <div
                                v-for="msg in messages"
                                :key="msg.id"
                                :class="[
                                    'flex',
                                    msg.type === 'system' ? 'justify-center' : 'gap-2',
                                    msg.type !== 'system' && msg.user?.id === $page.props.auth.user?.id ? 'justify-end' : ''
                                ]"
                            >
                                <!-- 系统消息 -->
                                <template v-if="msg.type === 'system'">
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ formatTime(msg.created_at) }}
                                        </div>
                                        <div :class="[
                                            'px-3 py-1 rounded text-xs',
                                            msg.is_temporary ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'
                                        ]">
                                            <span v-if="msg.user">{{ msg.user.name }} </span>
                                            {{ msg.message || msg.content }}
                                        </div>

                                        <!-- 移动端评价按钮或已评价状态 - 交易完成时显示 -->
                                        <div v-if="msg.show_review_button">
                                            <!-- 未评价 - 显示评价按钮 -->
                                            <div v-if="!order.has_review"
                                                 class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded border border-amber-200 dark:border-amber-800 max-w-sm">
                                                <div class="flex flex-col items-center gap-2">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                        <span class="text-sm font-medium text-amber-800 dark:text-amber-300">请评价本次交易</span>
                                                    </div>
                                                    <P2PButton
                                                        @click="showReviewModal = true"
                                                        variant="warning"
                                                        size="sm"
                                                        fullWidth
                                                    >
                                                        <template #icon>
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        </template>
                                                        立即评价
                                                    </P2PButton>
                                                </div>
                                            </div>
                                            <!-- 已评价 - 显示状态 -->
                                            <div v-else
                                                 class="mt-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded border border-emerald-200 dark:border-emerald-800 max-w-sm">
                                                <div class="flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm font-medium text-emerald-800 dark:text-emerald-300">您已评价此交易</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- 对方消息 -->
                                <template v-else-if="msg.user?.id !== $page.props.auth.user?.id">
                                    <a :href="`/trader/profile/${msg.user.id}`" target="_blank" class="h-7 w-7 rounded-full flex-shrink-0 hover:opacity-80 transition-opacity cursor-pointer">
                                        <!-- 使用profile photo if available -->
                                        <img
                                            v-if="msg.user.profile_photo_url"
                                            :src="msg.user.profile_photo_url"
                                            :alt="msg.user.name"
                                            class="h-7 w-7 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                                        />
                                        <!-- Fallback to initial -->
                                        <div v-else class="h-7 w-7 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ msg.user.name.charAt(0) }}</span>
                                        </div>
                                    </a>
                                    <div class="max-w-[85%]">
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-0.5 px-1">
                                            {{ msg.user.name }}
                                            <span v-if="msg.type === 'admin'" class="text-purple-600 dark:text-purple-400 font-bold">(Admin)</span>
                                        </p>
                                        <div class="inline-block bg-gray-100 dark:bg-gray-800 rounded rounded-tl-none px-3 py-2">
                                            <!-- 附件显示 -->
                                            <div v-if="msg.attachment" class="mb-2">
                                                <P2PAttachment
                                                    :attachment="msg.attachment"
                                                    variant="default"
                                                    thumbnail-size="sm"
                                                />
                                            </div>
                                            <p v-if="msg.message" class="text-sm text-gray-900 dark:text-gray-200 break-words">{{ msg.message }}</p>
                                        </div>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-1">{{ formatTime(msg.created_at) }}</p>
                                    </div>
                                </template>

                                <!-- 自己的消息 -->
                                <template v-else>
                                    <div class="max-w-[85%]">
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-0.5 px-1 text-right">
                                            {{ msg.user.name }}
                                            <span v-if="msg.type === 'admin'" class="text-purple-600 dark:text-purple-400 font-bold">(Admin)</span>
                                        </p>
                                        <div class="inline-block bg-emerald-600 text-white rounded rounded-tr-none px-3 py-2">
                                            <!-- 附件显示 -->
                                            <div v-if="msg.attachment" class="mb-2">
                                                <P2PAttachment
                                                    :attachment="msg.attachment"
                                                    variant="primary"
                                                    thumbnail-size="sm"
                                                />
                                            </div>
                                            <p v-if="msg.message" class="text-sm break-words">{{ msg.message }}</p>
                                        </div>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-1 text-right">{{ formatTime(msg.created_at) }}</p>
                                    </div>
                                    <!-- 当前用户头像 -->
                                    <img
                                        v-if="$page.props.auth.user?.profile_photo_url"
                                        :src="$page.props.auth.user.profile_photo_url"
                                        :alt="$page.props.auth.user.name"
                                        class="h-7 w-7 rounded-full object-cover border border-gray-200 dark:border-gray-700 flex-shrink-0"
                                    />
                                    <!-- Fallback to initial -->
                                    <div v-else class="h-7 w-7 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">{{ $page.props.auth.user?.name?.charAt(0).toUpperCase() || 'U' }}</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- 移动端底部操作区 - 固定在底部 -->
                    <div class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 pb-safe">
                        <!-- 输入区域 -->
                        <div class="p-3">
                            <!-- 文件预览 -->
                            <div v-if="selectedFile || isCompressing" class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded">
                                <!-- 压缩中状态 -->
                                <div v-if="isCompressing" class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="animate-spin h-12 w-12 border-4 border-blue-600 border-t-transparent rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-200">正在压缩图片...</p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">优化中</p>
                                    </div>
                                </div>
                                <!-- 文件预览 -->
                                <div v-else-if="selectedFile" class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <!-- 图片预览 -->
                                        <img v-if="filePreviewUrl && selectedFile.type.startsWith('image/')"
                                             :src="filePreviewUrl"
                                             class="w-12 h-12 object-cover rounded border"
                                             alt="预览">
                                        <!-- 文件图标 -->
                                        <div v-else class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-200 truncate">{{ selectedFile.name }}</p>
                                          <p class="text-xs text-gray-400 dark:text-gray-600">{{ formatFileSize(selectedFile.size) }}</p>
                                    </div>
                                    <button @click="removeFile"
                                            type="button"
                                            class="flex-shrink-0 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <form @submit.prevent="sendMessage" class="flex items-center gap-1">
                                <!-- 隐藏的文件输入 -->
                                <input
                                    ref="mobileFileInput"
                                    type="file"
                                    @change="handleFileSelect"
                                    accept=".pdf,image/*"
                                    data-max-size="2097152"
                                    class="hidden"
                                />

                                <!-- 左侧按钮组 -->
                                <div class="flex items-center">
                                    <button type="button"
                                            @click="!isTradeCompleted && !isCompressing && $refs.mobileFileInput.click()"
                                            :disabled="isTradeCompleted || isCompressing"
                                            class="p-2 rounded text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                    </button>

                                    <P2PEmojiPicker
                                        @select="(emoji) => newMessage += emoji"
                                        :disabled="isTradeCompleted"
                                        placement="top"
                                    />
                                </div>

                                <!-- 输入框 -->
                                <input
                                    v-model="newMessage"
                                    type="text"
                                    :placeholder="isTradeCompleted ? '交易已结束' : '输入消息...'"
                                    class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-800 border-0 rounded text-sm text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 focus:bg-white dark:focus:bg-gray-700 focus:ring-2 focus:ring-emerald-500 focus:outline-none disabled:opacity-50 transition-colors"
                                    :disabled="!isConnected || isTradeCompleted"
                                />

                                <!-- 发送按钮 -->
                                <button
                                    type="submit"
                                    :disabled="(!newMessage.trim() && !selectedFile) || !isConnected || isUploading || isTradeCompleted"
                                    class="px-3 py-2 bg-emerald-600 disabled:bg-gray-400 text-white rounded font-medium text-sm transition-colors"
                                >
                                    <svg v-if="isUploading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span v-else>发送</span>
                                </button>
                            </form>

                            <!-- 操作按钮区域 (移动端) -->
                            <div v-if="(availableActions.length > 0 && order.status !== 'completed' && order.status !== 'cancelled' && order.escrow_status !== 'escrow_released') || order.status !== 'cancelled' && order.status !== 'completed'" class="flex mt-2 justify-between items-center">
                                <div class="flex gap-2 overflow-x-auto pb-1">
                                    <template v-for="action in availableActions" :key="action">
                                        <button
                                            v-if="action === 'vendor_confirm'"
                                            @click="handleEscrowAction('vendor_confirm')"
                                            class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded whitespace-nowrap cursor-pointer"
                                        >
                                            确认订单
                                        </button>

                                        <button
                                            v-if="action === 'mark_seller_paid'"
                                            @click="handleEscrowAction('mark_seller_paid')"
                                            class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded whitespace-nowrap cursor-pointer"
                                        >
                                            我已转币
                                        </button>

                                        <button
                                            v-if="action === 'confirm_escrow'"
                                            @click="handleEscrowAction('confirm_escrow')"
                                            class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded whitespace-nowrap cursor-pointer"
                                        >
                                            确认托管
                                        </button>

                                        <button
                                            v-if="action === 'mark_buyer_paid'"
                                            @click="handleEscrowAction('mark_buyer_paid')"
                                            class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded whitespace-nowrap cursor-pointer"
                                        >
                                            我已付款
                                        </button>

                                        <button
                                            v-if="action === 'mark_seller_received'"
                                            @click="handleEscrowAction('mark_seller_received')"
                                            class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded whitespace-nowrap cursor-pointer"
                                        >
                                            确认收款
                                        </button>

                                        <button
                                            v-if="action === 'cancel'"
                                            @click="handleEscrowAction('cancel')"
                                            class="px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded whitespace-nowrap cursor-pointer"
                                        >
                                            取消订单
                                        </button>
                                    </template>

                                    <!-- 争议按钮 -->
                                    <button
                                        v-if="availableActions.includes('create_dispute')"
                                        @click="handleEscrowAction('create_dispute')"
                                        class="px-3 py-1.5 bg-amber-600 text-white text-xs font-medium rounded whitespace-nowrap cursor-pointer"
                                    >
                                        发起争议
                                    </button>

                                    <button
                                        v-if="availableActions.includes('resolve_dispute')"
                                        @click="handleEscrowAction('resolve_dispute')"
                                        class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded whitespace-nowrap cursor-pointer"
                                    >
                                        解除争议
                                    </button>
                                </div>

                                <!-- 实时通知按钮 (移动端) -->
                                <a v-if="order.status !== 'cancelled' && order.status !== 'completed'"
                                   href="/notifications/settings"
                                   target="_blank"
                                   :class="[
                                       'px-3 py-1.5 rounded inline-flex items-center justify-center cursor-pointer',
                                       $page.props.auth.user?.telegram_chat_id
                                           ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                                           : 'bg-blue-500 text-white'
                                   ]"
                                   :title="$page.props.auth.user?.telegram_chat_id ? 'Telegram 通知已开启' : '设置 Telegram 通知'">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                                    </svg>
                                </a>
                            </div>

                            <!-- 交易状态栏 (移动端 - 可点击) -->
                            <div @click="showMobileInfo = true" class="mt-2 p-2 bg-gray-50 dark:bg-gray-800/50 rounded border border-gray-200 dark:border-gray-700 cursor-pointer active:bg-gray-100 dark:active:bg-gray-800 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div :class="getStatusColor(order.escrow_status)" class="w-2 h-2 rounded-full animate-pulse"></div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                            {{ getStatusText(order.escrow_status) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ order.crypto_amount }} {{ order.currency_label || order.currency_key }}</span>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 确认对话框 -->
        <P2PDialog
            :show="dialogState.show && dialogState.pendingAction !== 'confirm_escrow' && dialogState.pendingAction !== 'mark_buyer_paid' && dialogState.pendingAction !== 'mark_seller_received' && dialogState.pendingAction !== 'mark_seller_paid'"
            :title="dialogState.title"
            :confirm-text="dialogState.confirmText"
            :cancel-text="dialogState.cancelText"
            :loading="dialogState.loading"
            @confirm="() => handleDialogConfirm(dialogState.inputValue)"
            @close="handleDialogCancel"
        >
            <!-- Dialog content with message -->
            <div v-if="dialogState.message" class="text-gray-700 dark:text-gray-300">
                {{ dialogState.message }}
            </div>

            <!-- Input field if needed -->
            <div v-if="dialogState.inputMode" class="mt-4">
                <label v-if="dialogState.inputLabel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ dialogState.inputLabel }}
                </label>
                <input
                    v-model="dialogState.inputValue"
                    type="text"
                    :placeholder="dialogState.inputPlaceholder"
                    :required="dialogState.inputRequired"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                />
            </div>
        </P2PDialog>

        <!-- 买家确认托管专用对话框 -->
        <P2PDialog
            :show="dialogState.pendingAction === 'confirm_escrow' && dialogState.show"
            title="确认托管到账"
            subtitle="请仔细核对托管交易信息"
            icon-color="amber"
            :requires-agreement="true"
            :agreement-disabled="!allEscrowChecksCompleted"
            :agreement-text="allEscrowChecksCompleted ? '我已核对所有信息无误，确认托管已到账' : '请先勾选上方所有确认项'"
            confirm-text="确认托管到账"
            cancel-text="返回检查"
            :loading="dialogState.loading"
            :persistent="dialogState.loading"
            @confirm="handleEscrowConfirm"
            @close="handleDialogCancel"
        >
            <div class="space-y-3">
                <!-- 交易信息显示 -->
                <div class="p-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">
                    <div class="space-y-2">
                        <div v-if="order.seller_address">
                            <label class="text-xs text-gray-500 dark:text-gray-400">来源地址（卖家）</label>
                            <p class="font-mono text-xs text-gray-900 dark:text-gray-100 break-all mt-1">
                                {{ order.seller_address }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400">托管地址</label>
                            <p class="font-mono text-xs text-gray-900 dark:text-gray-100 break-all mt-1">
                                {{ order.escrow_address }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400">交易金额</label>
                            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                {{ parseFloat(order.crypto_amount).toFixed(4) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}
                            </p>
                        </div>
                        <div v-if="order.escrow_tx_hash">
                            <label class="text-xs text-gray-500 dark:text-gray-400">交易哈希</label>
                            <p class="font-mono text-xs text-blue-600 dark:text-blue-400 break-all mt-1">
                                {{ order.escrow_tx_hash }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 重要提醒 -->
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-xs">
                            <p class="font-medium text-amber-800 dark:text-amber-300">请打开区块链浏览器仔细核对</p>
                            <p class="text-amber-700 dark:text-amber-400 mt-1">
                                确认前必须逐项检查以下所有信息
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 确认清单 -->
                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">请确认以下所有项目：</p>

                    <div class="space-y-2">
                        <div class="flex items-start">
                            <input
                                v-model="escrowChecks.txExists"
                                type="checkbox"
                                class="mt-0.5 h-4 w-4 text-emerald-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-emerald-500 focus:ring-2"
                                :disabled="dialogState.loading"
                            />
                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                我已打开区块链浏览器，确认交易记录存在
                            </label>
                        </div>

                        <div class="flex items-start">
                            <input
                                v-model="escrowChecks.confirmations"
                                type="checkbox"
                                class="mt-0.5 h-4 w-4 text-emerald-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-emerald-500 focus:ring-2"
                                :disabled="dialogState.loading"
                            />
                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                交易已有足够的确认数（建议至少6个确认）
                            </label>
                        </div>

                        <div class="flex items-start">
                            <input
                                v-model="escrowChecks.fromAddress"
                                type="checkbox"
                                class="mt-0.5 h-4 w-4 text-emerald-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-emerald-500 focus:ring-2"
                                :disabled="dialogState.loading"
                            />
                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                来源地址与卖家地址完全一致
                                <span class="block font-mono text-xs text-blue-600 dark:text-blue-400 break-all mt-1">{{ order.seller_address || '无卖家地址' }}</span>
                            </label>
                        </div>

                        <div class="flex items-start">
                            <input
                                v-model="escrowChecks.toAddress"
                                type="checkbox"
                                class="mt-0.5 h-4 w-4 text-emerald-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-emerald-500 focus:ring-2"
                                :disabled="dialogState.loading"
                            />
                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                目标地址与托管地址完全一致
                                <span class="block font-mono text-xs text-blue-600 dark:text-blue-400 break-all mt-1">{{ order.escrow_address }}</span>
                            </label>
                        </div>

                        <div class="flex items-start">
                            <input
                                v-model="escrowChecks.amount"
                                type="checkbox"
                                class="mt-0.5 h-4 w-4 text-emerald-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-emerald-500 focus:ring-2"
                                :disabled="dialogState.loading"
                            />
                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                交易金额为 <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ parseFloat(order.crypto_amount).toFixed(4) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}</span>，完全正确
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 最终警告 -->
                <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-xs">
                            <p class="font-bold text-red-800 dark:text-red-300">最终确认</p>
                            <p class="text-red-700 dark:text-red-400 mt-1">
                                确认后您将进入付款阶段，必须按时完成付款。如信息有误导致的损失，责任自负！
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </P2PDialog>

        <!-- 卖家转币专用对话框 -->
        <P2PDialog
            :show="dialogState.pendingAction === 'mark_seller_paid' && dialogState.show"
            title="标记已转币到托管"
            subtitle="请确认您已将币转入托管地址"
            icon-color="blue"
            :requires-agreement="true"
            agreement-text="我已仔细核对金额和地址，确认转币信息无误"
            confirm-text="确认已转币"
            cancel-text="返回"
            :loading="dialogState.loading"
            :persistent="dialogState.loading"
            @confirm="handleSellerPaidConfirm"
            @close="handleDialogCancel"
        >
            <div class="space-y-3">
                <!-- 交易哈希输入 - 移到顶部 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        交易哈希 (TX ID) <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="sellerTxHash"
                        type="text"
                        placeholder="请输入区块链交易哈希..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        :disabled="dialogState.loading"
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        转币后请提供交易哈希作为凭证
                    </p>
                </div>

                <!-- 您的地址显示 -->
                <div v-if="order.seller_address" class="p-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">
                    <div class="space-y-2">
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400">您的地址（来源地址）</label>
                            <p class="font-mono text-xs text-gray-900 dark:text-gray-100 break-all mt-1">
                                {{ order.seller_address }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400">托管地址（转入地址）</label>
                            <p class="font-mono text-xs text-blue-600 dark:text-blue-400 break-all mt-1">
                                {{ order.escrow_address }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400">转币金额</label>
                            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                {{ parseFloat(order.crypto_amount).toFixed(4) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 重要警告 -->
                <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-xs">
                            <p class="font-bold text-red-800 dark:text-red-300 text-sm">极其重要！请仔细核对！</p>
                            <ul class="mt-1 space-y-1 text-red-700 dark:text-red-400">
                                <li>• 请确保转币地址完全正确</li>
                                <li>• <span class="font-bold">请一定要确保金额是一次性从您的地址转入，否则系统无法确认</span></li>
                                <li>• 请确保转币金额准确无误</li>
                                <li>• <span class="font-bold">如果地址或金额错误，托管将无法确认！</span></li>
                                <li>• <span class="font-bold text-red-800 dark:text-red-200">任何损失概不负责！</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </P2PDialog>

        <!-- 买家付款专用对话框（含2FA验证） -->
        <P2PDialog
            :show="dialogState.pendingAction === 'mark_buyer_paid' && dialogState.show"
            title="标记已付款"
            :subtitle="`请确认您已向卖家付款 ¥${parseFloat(order.fiat_amount).toFixed(2)}`"
            icon-color="emerald"
            :requires-agreement="userHas2FA"
            :agreement-disabled="userHas2FA && (!dialogState.twoFactorCode || dialogState.twoFactorCode.length !== 6)"
            :agreement-text="userHas2FA ? (dialogState.twoFactorCode && dialogState.twoFactorCode.length === 6 ? '我确认已完成付款，理解虚假付款将导致账户封禁' : '请先输入6位验证码') : ''"
            :confirm-text="userHas2FA ? '确认已付款' : '前往启用双重验证'"
            cancel-text="返回"
            :loading="dialogState.loading"
            :persistent="dialogState.loading"
            @confirm="userHas2FA ? handleBuyerPaymentConfirm() : router.visit('/user/profile#tab-2fa')"
            @close="handleDialogCancel"
        >
            <div class="space-y-3">
                <!-- 付款说明输入 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        付款说明（可选）
                    </label>
                    <textarea
                        v-model="buyerPaymentProof"
                        rows="3"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="请输入付款凭证或说明，如转账单号..."
                        :disabled="dialogState.loading"
                    ></textarea>
                </div>

                <!-- 2FA验证码输入（仅当用户启用2FA时显示） -->
                <div v-if="userHas2FA">
                    <P2PTwoFactorInput
                        v-model="dialogState.twoFactorCode"
                        :error="dialogState.twoFactorError"
                        :disabled="dialogState.loading"
                        @complete="handleTwoFactorComplete"
                    />
                </div>

                <!-- 未启用2FA的提示 -->
                <div v-else class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-xs">
                            <p class="font-medium text-amber-800 dark:text-amber-300">需要双重验证</p>
                            <p class="text-amber-700 dark:text-amber-400 mt-1">
                                为保护您的资金安全，买家付款必须启用双重验证
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 警告提示 -->
                <div v-if="userHas2FA" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-xs">
                            <p class="font-medium text-red-800 dark:text-red-300">注意事项</p>
                            <p class="text-red-700 dark:text-red-400 mt-1">
                                请确保已完成付款再点击确认，虚假付款将导致账户封禁
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </P2PDialog>

        <!-- 卖家确认收款专用对话框（含2FA验证） -->
        <P2PDialog
            :show="dialogState.pendingAction === 'mark_seller_received' && dialogState.show"
            title="确认收款"
            :subtitle="`请确认您已收到买家付款 ¥${parseFloat(order.fiat_amount).toFixed(2)}`"
            icon-color="emerald"
            :requires-agreement="userHas2FA"
            :agreement-disabled="userHas2FA && (!dialogState.twoFactorCode || dialogState.twoFactorCode.length !== 6)"
            :agreement-text="userHas2FA ? (dialogState.twoFactorCode && dialogState.twoFactorCode.length === 6 ? '我确认已收到款项，同意释放托管的加密货币给买家' : '请先输入6位验证码') : ''"
            :confirm-text="userHas2FA ? '确认收款' : '前往启用双重验证'"
            cancel-text="返回"
            :loading="dialogState.loading"
            :persistent="dialogState.loading"
            @confirm="userHas2FA ? handleSellerReceivedConfirm() : router.visit('/user/profile#tab-2fa')"
            @close="handleDialogCancel"
        >
            <div class="space-y-3">
                <!-- 2FA验证码输入（仅当用户启用2FA时显示） -->
                <div v-if="userHas2FA">
                    <P2PTwoFactorInput
                        v-model="dialogState.twoFactorCode"
                        :error="dialogState.twoFactorError"
                        :disabled="dialogState.loading"
                        @complete="handleTwoFactorComplete"
                    />
                </div>

                <!-- 未启用2FA的提示 -->
                <div v-else class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-xs">
                            <p class="font-medium text-amber-800 dark:text-amber-300">需要双重验证</p>
                            <p class="text-amber-700 dark:text-amber-400 mt-1">
                                为保护您的资金安全，卖家确认收款必须启用双重验证
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 警告提示 -->
                <div v-if="userHas2FA" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-xs">
                            <p class="font-medium text-red-800 dark:text-red-300">重要提醒</p>
                            <p class="text-red-700 dark:text-red-400 mt-1">
                                确认收款后，托管的加密货币将立即释放给买家，此操作不可撤销
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </P2PDialog>
        <!-- 移动端：交易条款弹出面板 -->
        <P2PMobileDrawer
            v-model="showTradeTerms"
            title="商家交易条款"
            :subtitle="`订单 #${order.order_no}`"
            position="bottom"
            size="default"
            :icon-color="'purple'"
        >
            <template #header-icon>
                <div class="p-2 rounded bg-purple-100 dark:bg-purple-900/30">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </template>
            <div class="p-4">
                <!-- 条款内容 -->
                <div v-if="order.ad_snapshot?.vendor_toc" class="bg-gray-50 dark:bg-gray-800/50 rounded p-4 max-h-80 overflow-y-auto">
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ order.ad_snapshot?.vendor_toc }}</p>
                </div>
                <div v-else class="bg-gray-50 dark:bg-gray-800/50 rounded p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">商家未设置交易条款</p>
                </div>

                <!-- 提醒 -->
                <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 rounded border border-amber-200 dark:border-amber-800">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-xs text-amber-700 dark:text-amber-300">
                            请仔细阅读并遵守以上交易条款，违反条款可能导致交易取消
                        </p>
                    </div>
                </div>
            </div>
        </P2PMobileDrawer>
    </P2PAppLayout>

    <!-- 评价弹窗 -->
    <ReviewModal
        v-model="showReviewModal"
        :order-id="order.id"
        :order-no="order.order_no"
        :vendor-name="order.vendor?.name || '商家'"
        :fiat-amount="order.fiat_amount"
        :fiat-currency="order.fiat_currency"
        :crypto-amount="order.crypto_amount"
        :crypto-currency="order.currency_key || order.crypto_currency"
        @success="handleReviewSuccess"
    />
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick, computed } from 'vue';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PMobileDrawer from '@/Components/UI/P2PMobileDrawer.vue';
import P2PMobileHeader from '@/Components/UI/P2PMobileHeader.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PDialog from '@/Components/UI/P2PDialog.vue';
import P2PTwoFactorInput from '@/Components/UI/P2PTwoFactorInput.vue';
import P2PAttachment from '@/Components/UI/P2PAttachment.vue';
import P2PEmojiPicker from '@/Components/UI/P2PEmojiPicker.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import Modal from '@/Components/Modal.vue';
import ReviewModal from '@/Components/Trade/ReviewModal.vue';
import axios from 'axios';
import { router, Link, usePage } from '@inertiajs/vue3';
import MessageDialog from '@/Components/MessageDialog';
import { useConfig } from '@/Composables/useConfig';
import imageCompressor from '@/Services/ImageCompressor';

// Page data
const page = usePage();

// Props
const props = defineProps({
    userHas2FA: {
        type: Boolean,
        default: false
    },
    order: {
        type: Object,
        required: true
    },
    otherUser: {
        type: Object,
        default: null
    },
    messages: {
        type: Array,
        default: () => []
    },
    currentUserId: {
        type: Number,
        required: true
    },
    userRole: {
        type: String,
        required: true
    },
    availableActions: {
        type: Array,
        default: () => []
    }
});

// 移动端状态
const showMobileInfo = ref(false);
const showReviewModal = ref(false);
const showTradeTerms = ref(false);

// 响应式订单状态（用于实时更新）
const currentOrder = ref({ ...props.order });


// 聊天相关
const messages = ref(props.messages);
const newMessage = ref('');
const messageContainer = ref(null);
const selectedFile = ref(null);
const filePreviewUrl = ref(null);
const fileInput = ref(null);
const mobileFileInput = ref(null);
const isUploading = ref(false);
const isCompressing = ref(false);
const mobileMessageContainer = ref(null);
const isConnected = ref(false);
const isPageVisible = ref(true); // 追踪页面可见性
let echo = null;
let presenceChannel = null;
let disconnectTimer = null;
let disconnectedAt = null;

// 在线状态管理
const isOtherUserOnline = ref(false);
const onlineUsers = ref([]);

// 参与者列表
const participants = ref([]);

// 初始化参与者列表
const initParticipants = () => {
    const list = [];

    // 添加商家
    if (currentOrder.value.vendor) {
        list.push({
            id: currentOrder.value.vendor.id,
            name: currentOrder.value.vendor.name,
            role: '商家',
            avatar: currentOrder.value.vendor.avatar,
            profile_photo_url: currentOrder.value.vendor.avatar, // avatar field contains profile_photo_url
            is_self: currentOrder.value.vendor.id === props.currentUserId,
            is_online: currentOrder.value.vendor.id === props.currentUserId, // 自己肯定在线
            is_admin: false
        });
    }

    // 添加客户
    if (currentOrder.value.client) {
        list.push({
            id: currentOrder.value.client.id,
            name: currentOrder.value.client.name,
            role: '客户',
            avatar: currentOrder.value.client.avatar,
            profile_photo_url: currentOrder.value.client.avatar, // avatar field contains profile_photo_url
            is_self: currentOrder.value.client.id === props.currentUserId,
            is_online: currentOrder.value.client.id === props.currentUserId, // 自己肯定在线
            is_admin: false
        });
    }

    participants.value = list;
};

// 添加新参与者（管理员加入时）
const addParticipant = (user) => {
    // 检查是否已存在
    const exists = participants.value.find(p => p.id === user.id);
    if (!exists) {
        participants.value.push({
            id: user.id,
            name: user.name,
            role: user.is_admin ? '仲裁员' : '参与者',
            avatar: user.avatar,
            is_self: user.id === props.currentUserId,
            is_online: true,
            is_admin: user.is_admin || false
        });
    }
};

// 对话框状态
const dialogState = ref({
    show: false,
    title: '',
    message: '',
    inputMode: false,
    inputValue: '',
    inputLabel: '',
    inputPlaceholder: '',
    inputRequired: false,
    danger: false,
    confirmVariant: 'primary',
    confirmText: '确认',
    cancelText: '取消',
    loading: false,
    pendingAction: null,
    pendingData: {},
    require2FA: false,
    twoFactorCode: '',
    twoFactorError: ''
});

// 买家付款相关
const buyerPaymentProof = ref('');

// 卖家转币相关
const sellerTxHash = ref('');

// 买家确认托管相关
const escrowChecks = ref({
    txExists: false,
    confirmations: false,
    fromAddress: false,
    toAddress: false,
    amount: false
});

// 计算是否所有托管检查都完成
const allEscrowChecksCompleted = computed(() => {
    return escrowChecks.value.txExists &&
        escrowChecks.value.confirmations &&
        escrowChecks.value.fromAddress &&
        escrowChecks.value.toAddress &&
        escrowChecks.value.amount;
});

// 计算交易是否已结束
const isTradeCompleted = computed(() => {
    return currentOrder.value.status === 'completed' ||
        currentOrder.value.status === 'cancelled' ||
        currentOrder.value.escrow_status === 'escrow_released';
});

// 使用真实订单数据
const tradeInfo = ref({
    orderId: currentOrder.value.order_no,
    type: props.userRole === 'buyer' ? 'buy' : 'sell',
    cryptocurrency: currentOrder.value.currency_key || currentOrder.value.crypto_currency,
    amount: currentOrder.value.crypto_amount,
    price: currentOrder.value.price,
    total: currentOrder.value.fiat_amount,
    status: currentOrder.value.escrow_status,
    progress: getProgressPercentage(currentOrder.value.escrow_status),
    timeRemaining: 765 // 秒
});

// 计算进度百分比
function getProgressPercentage(status) {
    // 8个步骤，每步12.5%
    const statusMap = {
        'order_initiated': 12.5,        // 步骤1: 订单创建完成
        'vendor_confirmed': 25,          // 步骤2: 商家确认完成
        'seller_paid': 37.5,            // 步骤3: 卖家转币完成
        'escrow_received': 50,           // 步骤4: 托管确认完成
        'buyer_confirmed_escrow': 62.5,  // 步骤5: 买家确认托管完成
        'buyer_paid': 75,               // 步骤6: 买家付款完成
        'seller_received': 87.5,        // 步骤7: 卖家确认收款完成
        'escrow_released': 100          // 步骤8: 释放托管完成
    };
    return statusMap[status] || 0;
}

// 获取状态徽章样式
function getStatusBadgeClass(status) {
    const classMap = {
        'order_initiated': 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
        'vendor_confirmed': 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
        'seller_paid': 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
        'escrow_received': 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
        'buyer_paid': 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
        'seller_received': 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400',
        'escrow_released': 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
    };
    return classMap[status] || 'bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400';
}

// 获取状态徽章文本
function getStatusBadgeText(status) {
    const textMap = {
        'order_initiated': '等待确认',
        'vendor_confirmed': '等待转币',
        'seller_paid': '托管确认中',
        'escrow_received': '托管保护中',
        'buyer_paid': '等待收款确认',
        'seller_received': '释放中',
        'escrow_released': '已完成'
    };
    return textMap[status] || '处理中';
}

// 获取状态文本
function getStatusText(status) {
    const statusMap = {
        'order_initiated': '等待商家确认',
        'vendor_confirmed': '等待卖家转币',
        'seller_paid': '等待托管确认',
        'escrow_received': '托管保护中',
        'buyer_confirmed_escrow': '等待买家付款',
        'buyer_paid': '等待卖家确认收款',
        'seller_received': '准备释放托管',
        'escrow_released': '交易完成'
    };
    return statusMap[status] || '处理中';
}

// 获取状态颜色
function getStatusColor(status) {
    const colorMap = {
        'order_initiated': 'bg-amber-500',
        'vendor_confirmed': 'bg-amber-500',
        'seller_paid': 'bg-blue-500',
        'escrow_received': 'bg-blue-500',
        'buyer_confirmed_escrow': 'bg-blue-500',
        'buyer_paid': 'bg-blue-500',
        'seller_received': 'bg-emerald-500',
        'escrow_released': 'bg-emerald-500'
    };
    return colorMap[status] || 'bg-gray-500';
}

// 获取状态样式
function getStatusStyles(status) {
    const styles = {
        'order_initiated': {
            container: 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
            icon: 'text-amber-600 dark:text-amber-400',
            title: 'text-amber-700 dark:text-amber-300',
            subtitle: 'text-amber-600 dark:text-amber-400'
        },
        'vendor_confirmed': {
            container: 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
            icon: 'text-amber-600 dark:text-amber-400',
            title: 'text-amber-700 dark:text-amber-300',
            subtitle: 'text-amber-600 dark:text-amber-400'
        },
        'seller_paid': {
            container: 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
            icon: 'text-blue-600 dark:text-blue-400',
            title: 'text-blue-700 dark:text-blue-300',
            subtitle: 'text-blue-600 dark:text-blue-400'
        },
        'escrow_received': {
            container: 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
            icon: 'text-blue-600 dark:text-blue-400',
            title: 'text-blue-700 dark:text-blue-300',
            subtitle: 'text-blue-600 dark:text-blue-400'
        },
        'buyer_paid': {
            container: 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
            icon: 'text-blue-600 dark:text-blue-400',
            title: 'text-blue-700 dark:text-blue-300',
            subtitle: 'text-blue-600 dark:text-blue-400'
        },
        'seller_received': {
            container: 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800',
            icon: 'text-emerald-600 dark:text-emerald-400',
            title: 'text-emerald-700 dark:text-emerald-300',
            subtitle: 'text-emerald-600 dark:text-emerald-400'
        },
        'escrow_released': {
            container: 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800',
            icon: 'text-emerald-600 dark:text-emerald-400',
            title: 'text-emerald-700 dark:text-emerald-300',
            subtitle: 'text-emerald-600 dark:text-emerald-400'
        }
    };
    return styles[status] || styles['order_initiated'];
}

// 倒计时
const countdown = ref('12:45');
let timer = null;

const startCountdown = () => {
    timer = setInterval(() => {
        if (tradeInfo.value.timeRemaining > 0) {
            tradeInfo.value.timeRemaining--;
            const minutes = Math.floor(tradeInfo.value.timeRemaining / 60);
            const seconds = tradeInfo.value.timeRemaining % 60;
            countdown.value = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        } else {
            clearInterval(timer);
        }
    }, 1000);
};

// 滚动到底部
const scrollToBottom = () => {
    nextTick(() => {
        // 等待所有图片加载完成
        const scrollNow = () => {
            // 桌面端容器
            if (messageContainer.value) {
                messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
            }
            // 移动端容器
            if (mobileMessageContainer.value) {
                mobileMessageContainer.value.scrollTop = mobileMessageContainer.value.scrollHeight;
            }
        };

        // 立即滚动一次
        scrollNow();

        // 检查是否有图片正在加载
        const container = messageContainer.value || mobileMessageContainer.value;
        if (container) {
            const images = container.querySelectorAll('img');
            if (images.length > 0) {
                // 为每个图片添加加载完成事件
                let loadedCount = 0;
                images.forEach(img => {
                    if (img.complete) {
                        loadedCount++;
                    } else {
                        img.addEventListener('load', () => {
                            scrollNow();
                        }, { once: true });
                        img.addEventListener('error', () => {
                            scrollNow();
                        }, { once: true });
                    }
                });

                // 如果所有图片都已加载，再滚动一次
                if (loadedCount === images.length) {
                    setTimeout(scrollNow, 100);
                }
            }
        }
    });
};

// 格式化时间
const formatTime = (timestamp) => {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('zh-CN', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
};

// 格式化最后在线时间
const formatLastSeen = (timestamp) => {
    if (!timestamp) return '未知';

    const date = new Date(timestamp);
    const now = new Date();
    const diffMs = now - date;
    const diffSeconds = Math.floor(diffMs / 1000);
    const diffMinutes = Math.floor(diffSeconds / 60);
    const diffHours = Math.floor(diffMinutes / 60);
    const diffDays = Math.floor(diffHours / 24);

    if (diffSeconds < 60) {
        return '刚刚';
    } else if (diffMinutes < 60) {
        return `${diffMinutes}分钟前`;
    } else if (diffHours < 24) {
        return `${diffHours}小时前`;
    } else if (diffDays === 1) {
        return '昨天';
    } else if (diffDays < 7) {
        return `${diffDays}天前`;
    } else {
        return date.toLocaleDateString('zh-CN', {
            month: 'numeric',
            day: 'numeric'
        });
    }
};

// 格式化文件大小
const handleReviewSuccess = (review) => {
    // 更新订单状态
    currentOrder.value.has_review = true;
    currentOrder.value.review = review;
    currentOrder.value.status = 'completed';

    // 关闭弹窗
    showReviewModal.value = false;
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

// 格式化法币金额
const formatFiatAmount = (amount) => {
    if (!amount) return '0.00';
    return Number(amount).toLocaleString('zh-CN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

// 处理文件选择
const handleFileSelect = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // 最大文件大小限制 2MB
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

    let processedFile = file;

    // 如果是图片，进行压缩
    if (file.type.startsWith('image/')) {
        isCompressing.value = true;
        try {
            // 显示原始大小
            const originalSizeMB = (file.size / 1024 / 1024).toFixed(2);
            console.log(`原始图片大小: ${originalSizeMB} MB`);

            // 压缩图片，目标大小不超过2MB
            processedFile = await imageCompressor.compressImage(file, {
                maxWidth: 1920,
                maxHeight: 1920,
                maxSizeMB: 2,
                quality: 0.85
            });

            // 显示压缩后大小
            const compressedSizeMB = (processedFile.size / 1024 / 1024).toFixed(2);
            console.log(`压缩后图片大小: ${compressedSizeMB} MB`);

            // 如果压缩效果显著，可以显示提示
            if (processedFile.size < file.size * 0.8) {
                const savedPercent = Math.round((1 - processedFile.size / file.size) * 100);
                console.log(`图片已压缩 ${savedPercent}%`);
            }
        } catch (error) {
            console.error('图片压缩失败:', error);
            // 压缩失败时使用原始文件
            processedFile = file;
        } finally {
            isCompressing.value = false;
        }
    }

    // 检查最终文件大小（压缩后或原始文件）
    if (processedFile.size > MAX_FILE_SIZE) {
        alert(`文件大小不能超过 ${MAX_FILE_SIZE / 1024 / 1024}MB，当前文件大小为 ${(processedFile.size / 1024 / 1024).toFixed(2)}MB`);
        event.target.value = '';
        return;
    }

    selectedFile.value = processedFile;

    // 创建预览URL
    if (processedFile.type.startsWith('image/')) {
        filePreviewUrl.value = URL.createObjectURL(processedFile);
    }
};

// 移除文件
const removeFile = () => {
    selectedFile.value = null;
    if (filePreviewUrl.value) {
        URL.revokeObjectURL(filePreviewUrl.value);
        filePreviewUrl.value = null;
    }
    if (fileInput.value) {
        fileInput.value.value = '';
    }
    if (mobileFileInput.value) {
        mobileFileInput.value.value = '';
    }
};

// 发送消息
const sendMessage = async () => {
    if ((!newMessage.value.trim() && !selectedFile.value) || !isConnected.value || isUploading.value) return;

    const message = newMessage.value;
    const file = selectedFile.value;
    newMessage.value = '';
    isUploading.value = true;

    try {
        let attachment = null;

        // 如果有文件，先上传文件（此时文件已经被压缩过）
        if (file) {
            const formData = new FormData();
            formData.append('file', file);

            const uploadResponse = await axios.post(
                `/web/api/orders/${currentOrder.value.order_no}/chat/upload`,
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            );

            if (uploadResponse.data.success) {
                attachment = uploadResponse.data.file;
            }
        }

        // 检查对方是否在线
        const recipientId = currentOrder.value.vendor_id === props.currentUserId
            ? currentOrder.value.client_id
            : currentOrder.value.vendor_id;
        const recipient = participants.value.find(p => p.id === recipientId);
        const recipientOnline = recipient ? recipient.is_online : false;

        // 发送消息
        const response = await axios.post(`/web/api/orders/${currentOrder.value.order_no}/chat/send`, {
            message: message,
            attachment: attachment,
            recipient_online: recipientOnline
        });

        messages.value.push(response.data);

        // 清理文件选择
        removeFile();

        // 延迟滚动，确保DOM更新和图片开始加载
        nextTick(() => {
            scrollToBottom();
        });
    } catch (error) {
        console.error('发送消息失败:', error);
        newMessage.value = message;
        alert('发送失败，请重试');
    } finally {
        isUploading.value = false;
    }
};

// 快捷回复
const quickReply = (text) => {
    newMessage.value = text;
    sendMessage();
};

// 处理托管操作
const handleEscrowAction = (action) => {
    console.log('handleEscrowAction called with:', action);
    const actionConfigs = {
        vendor_confirm: {
            title: '确认接单',
            message: '确认接受此订单吗？接单后需要等待卖家转币到托管。',
            confirmVariant: 'primary',
            confirmText: '确认接单'
        },
        mark_seller_paid: {
            title: '标记已转币',
            message: '请确认您已将币转入托管地址，并提供交易哈希作为凭证',
            inputMode: true,
            inputLabel: '交易哈希 (TX ID)',
            inputPlaceholder: '请输入区块链交易哈希...',
            inputRequired: true,
            confirmVariant: 'primary',
            confirmText: '确认已转币'
        },
        confirm_escrow: {
            // 简化配置，实际内容由专用对话框处理
        },
        mark_buyer_paid: {
            title: '标记已付款',
            message: '请确认您已向卖家付款',
            inputMode: true,
            inputLabel: '付款说明（可选）',
            inputPlaceholder: '请输入付款凭证或说明...',
            inputRequired: false,
            confirmVariant: 'primary',
            confirmText: '确认已付款',
            require2FA: true  // 启用2FA验证
        },
        mark_seller_received: {
            title: '确认收款',
            message: '请确认您已收到买家的付款。确认后，托管的币将释放给买家。',
            confirmVariant: 'success',
            confirmText: '确认已收款',
            require2FA: true  // 启用2FA验证
        },
        cancel: {
            // 取消配置将在handleEscrowAction中动态设置
        },
        create_dispute: {
            title: '发起争议',
            message: '发起争议后，订单将被冻结，暂停所有交易操作，等待平台客服介入处理。请详细说明问题。',
            inputMode: true,
            inputLabel: '争议原因',
            inputPlaceholder: '请详细描述您遇到的问题...',
            inputRequired: true,
            confirmVariant: 'warning',
            confirmText: '确认发起争议'
        },
        resolve_dispute: {
            title: '解除争议',
            message: '确定要解除争议吗？解除后订单将恢复正常，可以继续进行交易。',
            inputMode: true,
            inputLabel: '解除原因（可选）',
            inputPlaceholder: '请输入解除争议的原因...',
            inputRequired: false,
            confirmVariant: 'success',
            confirmText: '确认解除'
        }
    };

    // 处理取消订单的特殊逻辑
    if (action === 'cancel') {
        const escrowStatus = currentOrder.value.escrow_status;
        let cancelConfig = {
            title: '取消订单',
            inputMode: true,
            inputLabel: '取消原因',
            inputPlaceholder: '请输入取消原因...',
            inputRequired: true,
            danger: true,
            confirmVariant: 'danger',
            confirmText: '确认取消'
        };

        // 根据状态设置不同的提示信息
        if (['seller_paid', 'escrow_received', 'buyer_confirmed_escrow'].includes(escrowStatus)) {
            // 第3-5步，买家取消会扣信誉
            cancelConfig.message = '⚠️ 警告：当前已进入托管阶段，取消订单将扣除您的信誉分 -1\n\n确定要取消此订单吗？';
        } else {
            cancelConfig.message = '确定要取消此订单吗？';
        }

        actionConfigs[action] = cancelConfig;
    }

    const config = actionConfigs[action];
    if (!config && action !== 'confirm_escrow') return;

    // 设置对话框状态 - 保留默认值并合并新配置
    Object.assign(dialogState.value, {
        show: true,
        ...(config || {}),
        cancelText: '返回',
        pendingAction: action,
        pendingData: {},
        loading: false
    });
    console.log('dialogState after setting:', dialogState.value);
};

// 处理对话框确认
const handleDialogConfirm = async (inputValue) => {
    const action = dialogState.value.pendingAction;
    if (!action) return;

    try {
        let endpoint = '';
        let data = {};

        switch(action) {
            case 'vendor_confirm':
                endpoint = `/web/api/orders/${currentOrder.value.id}/vendor-confirm`;
                break;
            case 'mark_seller_paid':
                endpoint = `/web/api/orders/${currentOrder.value.id}/seller-paid`;
                // 交易哈希是必填的
                if (!inputValue || !inputValue.trim()) {
                    dialogState.value.loading = false;
                    dialogState.value.show = false;
                    return;
                }
                data.tx_hash = inputValue.trim();
                break;
            case 'confirm_escrow':
                endpoint = `/web/api/orders/${currentOrder.value.id}/buyer-confirm-escrow`;
                break;
            case 'mark_buyer_paid':
                endpoint = `/web/api/orders/${currentOrder.value.id}/buyer-paid`;
                if (inputValue) data.payment_proof = inputValue;
                // 添加2FA验证码
                if (dialogState.value.require2FA && dialogState.value.twoFactorCode) {
                    data.two_factor_code = dialogState.value.twoFactorCode;
                }
                break;
            case 'mark_seller_received':
                endpoint = `/web/api/orders/${currentOrder.value.id}/seller-received`;
                // 添加2FA验证码
                if (dialogState.value.require2FA && dialogState.value.twoFactorCode) {
                    data.two_factor_code = dialogState.value.twoFactorCode;
                }
                break;
            case 'cancel':
                endpoint = `/web/api/orders/${currentOrder.value.id}/cancel`;
                data.reason = inputValue;
                break;
            case 'create_dispute':
                endpoint = `/web/api/orders/${currentOrder.value.id}/dispute`;
                data.reason = inputValue;
                break;
            case 'resolve_dispute':
                endpoint = `/web/api/orders/${currentOrder.value.id}/resolve-dispute`;
                if (inputValue) data.reason = inputValue;
                break;
        }

        dialogState.value.loading = true;
        const response = await axios.post(endpoint, data);

        if (response.data.success) {
            dialogState.value.show = false;
            // 刷新页面以获取最新状态
            router.reload();
        }
    } catch (error) {
        console.error('操作失败:', error);
        // 显示错误消息
        dialogState.value.loading = false;
        dialogState.value.show = false;
        // 错误已由全局axios拦截器处理
    }
};

// 处理对话框取消
const handleDialogCancel = () => {
    // 重置对话框状态到初始值
    dialogState.value = {
        show: false,
        title: '',
        message: '',
        inputMode: false,
        inputValue: '',
        inputLabel: '',
        inputPlaceholder: '',
        inputRequired: false,
        danger: false,
        confirmVariant: 'primary',
        confirmText: '确认',
        cancelText: '取消',
        loading: false,
        pendingAction: null,
        pendingData: {},
        require2FA: false,
        twoFactorCode: '',
        twoFactorError: ''
    };
    // 重置买家付款相关状态
    buyerPaymentProof.value = '';
    // 重置卖家转币相关状态
    sellerTxHash.value = '';
    // 重置托管确认相关状态
    escrowChecks.value = {
        txExists: false,
        confirmations: false,
        fromAddress: false,
        toAddress: false,
        amount: false
    };
};

// 处理2FA输入完成
const handleTwoFactorComplete = (code) => {
    dialogState.value.twoFactorCode = code;
    dialogState.value.twoFactorError = '';
};

// 处理买家付款确认（带2FA）
const handleBuyerPaymentConfirm = async () => {
    // 必须启用2FA才能继续
    if (!props.userHas2FA) {
        // 不应该到达这里，因为按钮应该被隐藏了
        alert('买家付款必须启用双重验证');
        router.visit('/user/profile#tab-2fa');
        return;
    }

    if (!dialogState.value.twoFactorCode || dialogState.value.twoFactorCode.length !== 6) {
        dialogState.value.twoFactorError = '请输入6位验证码';
        return;
    }

    dialogState.value.loading = true;
    dialogState.value.twoFactorError = '';

    try {
        const data = {
            two_factor_code: dialogState.value.twoFactorCode
        };

        if (buyerPaymentProof.value && buyerPaymentProof.value.trim()) {
            data.payment_proof = buyerPaymentProof.value.trim();
        }

        const response = await axios.post(`/web/api/orders/${currentOrder.value.id}/buyer-paid`, data);

        if (response.data.success) {
            dialogState.value.show = false;
            // 重置付款相关状态
            buyerPaymentProof.value = '';
            dialogState.value.twoFactorCode = '';
            dialogState.value.twoFactorError = '';
            // 刷新页面以获取最新状态
            router.reload();
        }
    } catch (error) {
        dialogState.value.loading = false;

        // 处理2FA相关错误
        if (error.response?.data?.errors?.two_factor_code) {
            dialogState.value.twoFactorError = error.response.data.errors.two_factor_code[0];
        } else if (error.response?.data?.message) {
            // 如果是require_2fa错误，提示用户
            if (error.response.data.require_2fa) {
                dialogState.value.show = false;
                alert('为了您的资金安全，请先在个人设置中启用双重验证');
                router.visit('/user/profile#tab-2fa');
            } else {
                dialogState.value.twoFactorError = error.response.data.message;
            }
        } else {
            dialogState.value.twoFactorError = '验证失败，请重试';
        }
    }
};

// 处理买家确认托管到账
const handleEscrowConfirm = async () => {
    if (!allEscrowChecksCompleted.value) {
        MessageDialog.error('请确认所有检查项');
        return;
    }

    dialogState.value.loading = true;

    try {
        const response = await axios.post(`/web/api/orders/${currentOrder.value.id}/buyer-confirm-escrow`);

        if (response.data.success) {
            dialogState.value.show = false;
            // 重置确认状态
            escrowChecks.value = {
                txExists: false,
                confirmations: false,
                fromAddress: false,
                toAddress: false,
                amount: false
            };
            // 刷新页面以获取最新状态
            router.reload();
        }
    } catch (error) {
        dialogState.value.loading = false;
        console.error('确认托管失败:', error);
    }
};

// 处理卖家转币确认
const handleSellerPaidConfirm = async () => {
    if (!sellerTxHash.value || !sellerTxHash.value.trim()) {
        MessageDialog.error('请输入交易哈希');
        return;
    }

    dialogState.value.loading = true;

    try {
        const response = await axios.post(`/web/api/orders/${currentOrder.value.id}/seller-paid`, {
            tx_hash: sellerTxHash.value.trim()
        });

        if (response.data.success) {
            dialogState.value.show = false;
            // 重置状态
            sellerTxHash.value = '';
            // 刷新页面以获取最新状态
            router.reload();
        }
    } catch (error) {
        dialogState.value.loading = false;
        console.error('标记转币失败:', error);
    }
};

// 处理卖家确认收款（带2FA）
const handleSellerReceivedConfirm = async () => {
    // 必须启用2FA才能继续
    if (!props.userHas2FA) {
        alert('卖家确认收款必须启用双重验证');
        router.visit('/user/profile#tab-2fa');
        return;
    }

    if (!dialogState.value.twoFactorCode || dialogState.value.twoFactorCode.length !== 6) {
        dialogState.value.twoFactorError = '请输入6位验证码';
        return;
    }

    dialogState.value.loading = true;
    dialogState.value.twoFactorError = '';

    try {
        const data = {
            two_factor_code: dialogState.value.twoFactorCode
        };

        const response = await axios.post(`/web/api/orders/${currentOrder.value.id}/seller-received`, data);

        if (response.data.success) {
            dialogState.value.show = false;
            // 重置2FA相关状态
            dialogState.value.twoFactorCode = '';
            dialogState.value.twoFactorError = '';
            // 刷新页面以获取最新状态
            router.reload();
        }
    } catch (error) {
        dialogState.value.loading = false;

        // 处理2FA相关错误
        if (error.response?.data?.errors?.two_factor_code) {
            dialogState.value.twoFactorError = error.response.data.errors.two_factor_code[0];
        } else if (error.response?.data?.message) {
            // 如果是require_2fa错误，提示用户
            if (error.response.data.require_2fa) {
                dialogState.value.show = false;
                alert('为了您的资金安全，请先在个人设置中启用双重验证');
                router.visit('/user/profile#tab-2fa');
            } else {
                dialogState.value.twoFactorError = error.response.data.message;
            }
        } else {
            dialogState.value.twoFactorError = '验证失败，请重试';
        }
    }
};

// 复制到剪贴板
const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        // 可以添加成功提示
        console.log('已复制到剪贴板');
    } catch (err) {
        console.error('复制失败:', err);
        // 降级方案
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            console.log('已复制到剪贴板');
        } catch (err) {
            console.error('复制失败:', err);
        }
        document.body.removeChild(textArea);
    }
};

// 断开WebSocket连接
const disconnectWebSocket = () => {
    console.log('[WebSocket] 断开连接');
    if (presenceChannel) {
        presenceChannel.stopListening('.MessageSent');
        presenceChannel.stopListening('.EscrowStatusUpdated');
        presenceChannel.stopListening('.OrderStatusUpdated');
        presenceChannel.stopListening('.dispute.resolved');
        echo.leave(`order.${currentOrder.value.id}`);
        presenceChannel = null;
    }
    isConnected.value = false;
    disconnectedAt = Date.now();
};

// 连接WebSocket到私有频道
const connectWebSocket = () => {
    if (typeof window.Echo !== 'undefined' && !presenceChannel) {
        echo = window.Echo;
        console.log('[WebSocket] 建立连接');

        // 使用单个 Presence Channel 处理在线状态和消息
        presenceChannel = echo.join(`order.${currentOrder.value.id}`)
            .here((users) => {
                // 初始在线用户列表
                console.log('当前在线用户:', users);
                onlineUsers.value = users;

                // 更新所有参与者的在线状态
                participants.value.forEach(participant => {
                    participant.is_online = users.some(u => u.id === participant.id);
                });

                // 如果有管理员在线但不在参与者列表中，添加他们
                users.forEach(user => {
                    const adminIds = [3]; // 管理员ID
                    if (adminIds.includes(user.id)) {
                        const exists = participants.value.find(p => p.id === user.id);
                        if (!exists) {
                            participants.value.push({
                                id: user.id,
                                name: user.name,
                                role: '仲裁员',
                                avatar: null,
                                is_self: user.id === props.currentUserId,
                                is_online: true,
                                is_admin: true
                            });
                        }
                    }
                });

                // 检查对方是否在线
                isOtherUserOnline.value = users.some(u => u.id === props.otherUser?.id);
            })
            .joining((user) => {
                // 用户加入聊天
                console.log('用户加入:', user);
                onlineUsers.value.push(user);

                // 添加加入消息到聊天
                if (user.id !== props.currentUserId) {
                    const now = new Date();
                    messages.value.push({
                        id: `join-${user.id}-${Date.now()}`,
                        type: 'system',
                        content: `${user.name} 加入了聊天`,
                        created_at: now.toISOString(),
                        is_temporary: true
                    });
                    nextTick(() => scrollToBottom());
                }

                // 更新参与者在线状态
                const participant = participants.value.find(p => p.id === user.id);
                if (participant) {
                    participant.is_online = true;
                } else {
                    // 如果是管理员加入，添加到参与者列表
                    const adminIds = [3];
                    if (adminIds.includes(user.id)) {
                        participants.value.push({
                            id: user.id,
                            name: user.name,
                            role: '仲裁员',
                            avatar: null,
                            is_self: user.id === props.currentUserId,
                            is_online: true,
                            is_admin: true
                        });
                    }
                }

                if (user.id === props.otherUser?.id) {
                    isOtherUserOnline.value = true;
                }
            })
            .leaving((user) => {
                // 用户离开聊天
                console.log('用户离开:', user);
                onlineUsers.value = onlineUsers.value.filter(u => u.id !== user.id);

                // 添加离开消息到聊天
                if (user.id !== props.currentUserId) {
                    const now = new Date();
                    messages.value.push({
                        id: `leave-${user.id}-${Date.now()}`,
                        type: 'system',
                        content: `${user.name} 离开了聊天`,
                        created_at: now.toISOString(),
                        is_temporary: true
                    });
                    nextTick(() => scrollToBottom());
                }

                // 更新参与者离线状态
                const participant = participants.value.find(p => p.id === user.id);
                if (participant) {
                    participant.is_online = false;
                }

                if (user.id === props.otherUser?.id) {
                    isOtherUserOnline.value = false;
                }
            })
            .error((error) => {
                console.error('Presence channel error:', error);
            })
            .listen('MessageSent', (e) => {
                // 如果是管理员消息，且管理员不在参与者列表中，添加管理员
                if (e.type === 'admin' && e.user) {
                    const exists = participants.value.find(p => p.id === e.user.id);
                    if (!exists) {
                        participants.value.push({
                            id: e.user.id,
                            name: e.user.name,
                            role: '仲裁员',
                            avatar: null,
                            is_self: e.user.id === props.currentUserId,
                            is_online: true,
                            is_admin: true
                        });
                    } else if (exists && !exists.is_admin) {
                        // 更新角色为仲裁员
                        exists.role = '仲裁员';
                        exists.is_admin = true;
                        exists.is_online = true;
                    }
                }

                // 系统消息始终显示，普通消息只显示其他人的
                if (e.type === 'system' || !e.user || e.user.id !== props.currentUserId) {
                    messages.value.push({
                        id: e.id,
                        message: e.message,
                        type: e.type || 'text',
                        attachment: e.attachment,
                        user: e.user,
                        created_at: e.created_at
                    });
                    scrollToBottom();
                }
            })
            .listen('EscrowStatusUpdated', (e) => {
                // 处理托管状态更新
                console.log('托管状态更新:', e);

                // 立即更新当前订单状态
                currentOrder.value.escrow_status = e.escrow_status || e.new_status;
                currentOrder.value.status = e.status || currentOrder.value.status;
                currentOrder.value.is_disputed = e.is_disputed;
                currentOrder.value.disputed_by = e.disputed_by;

                // 更新时间戳（如果需要）
                if (e.escrow_status === 'buyer_confirmed_escrow') {
                    currentOrder.value.buyer_confirmed_escrow_at = e.updated_at;
                }

                // 显示状态更新通知
                const notification = `托管状态更新: ${e.message}`;

                // 添加系统消息到聊天
                const systemMessage = {
                    id: Date.now(),
                    message: notification,
                    type: 'system',
                    user: null,
                    created_at: new Date().toISOString()
                };

                // 如果是托管释放且是买家，添加评价相关标记
                if ((e.escrow_status === 'escrow_released' || e.new_status === 'escrow_released') &&
                    props.currentUserId === currentOrder.value.client_id) {
                    systemMessage.show_review_button = true;
                    // 如果已评价，修改消息文本
                    if (currentOrder.value.has_review) {
                        systemMessage.message = '托管已释放，交易完成，您已评价此交易';
                    }
                }

                messages.value.push(systemMessage);

                scrollToBottom();

                // 延迟刷新页面以获取完整的最新状态
                setTimeout(() => {
                    router.reload();
                }, 2000);
            })
            .listen('OrderStatusUpdated', (e) => {
                // 处理订单状态更新（如取消）
                console.log('订单状态更新:', e);

                if (e.status === 'cancelled') {
                    // 不需要再添加消息，因为系统消息已经通过 MessageSent 事件发送了
                    // 只需要刷新页面以更新UI状态
                    setTimeout(() => {
                        router.reload();
                    }, 1000);
                }
            })
            .listen('.dispute.resolved', (e) => {
                // 处理争议解除事件
                console.log('争议已解除:', e);

                // 更新订单争议状态
                currentOrder.value.is_disputed = false;
                currentOrder.value.disputed_by = null;

                // 更新其他状态
                if (e.escrow_status) {
                    currentOrder.value.escrow_status = e.escrow_status;
                }
                if (e.status) {
                    currentOrder.value.status = e.status;
                }

                // 刷新页面以更新可用操作
                setTimeout(() => {
                    router.reload({ only: ['availableActions', 'order'] });
                }, 1000);

                // 显示通知
                showNotification('success', e.message || '争议已解除，订单恢复正常');
            });

        isConnected.value = true;

        // 总是加载最新消息，确保获取所有历史记录
        console.log('[WebSocket] 连接成功，加载最新消息');
        loadMessages();

        // 清除断开时间标记
        if (disconnectedAt) {
            disconnectedAt = null;
        }
    } else {
        console.warn('Echo not available, retrying...');
        setTimeout(connectWebSocket, 1000);
    }
};


// 重新加载所有消息
const loadMessages = async () => {
    try {
        console.log('[WebSocket] 重新加载所有消息');
        const response = await axios.get(`/web/api/orders/${currentOrder.value.order_no}/chat/messages`);

        if (response.data && response.data.length > 0) {
            console.log('[WebSocket] 加载消息成功:', response.data.length, '条');
            // 替换所有消息
            messages.value = response.data;
            scrollToBottom();
        } else {
            console.log('[WebSocket] 没有消息');
        }
    } catch (error) {
        console.error('[WebSocket] 加载消息失败:', error);
    }
};

// 页面可见性变化处理
const handleVisibilityChange = () => {
    isPageVisible.value = !document.hidden; // 更新响应式变量

    if (document.hidden) {
        // 页面隐藏
        console.log('[WebSocket] 页面隐藏，准备断开');

        // 移动端延迟10秒，桌面端延迟30秒
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        const delay = isMobile ? 10000 : 30000; // 移动端10秒，桌面端30秒

        disconnectTimer = setTimeout(() => {
            disconnectWebSocket();
        }, delay);
    } else {
        // 页面显示
        console.log('[WebSocket] 页面显示, 连接状态:', isConnected.value, '频道:', !!presenceChannel);

        // 取消断开计时
        if (disconnectTimer) {
            clearTimeout(disconnectTimer);
            disconnectTimer = null;
        }

        // 检查是否为移动设备
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

        // 如果未连接，重新连接
        if (!isConnected.value && !presenceChannel) {
            console.log('[WebSocket] 检测到断开状态，重新连接...');
            connectWebSocket();
        } else if (!isConnected.value || !presenceChannel) {
            // 如果连接状态不一致，也重新连接
            console.log('[WebSocket] 连接状态不一致，重新连接...');
            connectWebSocket();
        } else {
            // 已连接状态
            console.log('[WebSocket] 已连接');

            // 移动端总是重新加载消息，确保获取最新内容
            if (isMobile) {
                console.log('[WebSocket] 移动端恢复，重新加载消息');
                loadMessages();
            }
        }
    }
};

// 焦点处理函数（需要在外部定义以便在 unmount 时移除）
const handleFocus = () => {
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    if (isMobile && isConnected.value) {
        console.log('[WebSocket] 页面获得焦点，重新加载消息');
        loadMessages();
    }
};

onMounted(() => {
    initParticipants();
    startCountdown();

    // 如果交易已完成，添加相应的系统消息
    if (currentOrder.value.escrow_status === 'escrow_released' &&
        props.currentUserId === currentOrder.value.client_id) {

        // 检查是否已有评价相关消息
        const hasReviewMessage = messages.value.some(msg => msg.show_review_button === true);

        if (!hasReviewMessage) {
            const reviewMessage = {
                id: Date.now(),
                message: currentOrder.value.has_review
                    ? '交易已完成，您已评价此交易'
                    : '交易已完成！请对本次交易进行评价',
                type: 'system',
                user: null,
                created_at: new Date().toISOString(),
                show_review_button: true  // 添加标记以便模板判断
            };
            console.log('Adding review message:', reviewMessage);
            messages.value.push(reviewMessage);
        }
    }

    scrollToBottom();
    connectWebSocket();

    // 监听页面可见性变化
    document.addEventListener('visibilitychange', handleVisibilityChange);

    // 监听焦点（移动端更可靠）
    document.addEventListener('focusin', handleFocus);

    // PWA相关事件
    document.addEventListener('pause', () => {
        console.log('[WebSocket] PWA进入后台');
        disconnectWebSocket();
    });

    document.addEventListener('resume', () => {
        console.log('[WebSocket] PWA恢复前台');
        if (!isConnected.value) {
            connectWebSocket();
        } else {
            // 即使已连接，也重新加载消息确保同步
            console.log('[WebSocket] PWA恢复，重新加载消息');
            loadMessages();
        }
    });
});

onUnmounted(() => {
    // 清理定时器
    if (timer) {
        clearInterval(timer);
    }
    if (disconnectTimer) {
        clearTimeout(disconnectTimer);
    }

    // 断开WebSocket
    disconnectWebSocket();

    // 移除事件监听
    document.removeEventListener('visibilitychange', handleVisibilityChange);
    document.removeEventListener('focusin', handleFocus);
    document.removeEventListener('pause', handleVisibilityChange);
    document.removeEventListener('resume', handleVisibilityChange);
});
</script>
