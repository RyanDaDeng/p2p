<template>
    <P2PAppLayout
        :hide-nav="true"
        :hide-footer="true"
        :show-breadcrumbs="true"
        :breadcrumbs="[
            { label: '订单', url: '/orders' },
            { label: `订单 #${order.order_no}`, url: null },
            { label: '交易聊天' }
        ]"
    >
        <!-- 移动端返回导航 -->
        <P2PMobileHeader back-url="/orders">
            <template #custom>
                <div class="flex items-center gap-2 flex-1">
                    <!-- 显示所有参与者头像 -->
                    <div class="flex -space-x-2">
                        <template v-for="(participant, index) in participants" :key="participant.id">
                            <div class="relative" :style="`z-index: ${participants.length - index}`">
                                <div :class="[
                                    'h-8 w-8 rounded-full flex items-center justify-center ring-2 ring-white dark:ring-slate-950',
                                    participant.is_online ? 'bg-gradient-to-br from-emerald-500 to-emerald-600' : 'bg-gray-400 dark:bg-slate-600'
                                ]">
                                    <span class="text-white text-xs font-bold">{{ participant.name?.[0]?.toUpperCase() || 'U' }}</span>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-slate-100 truncate">
                            订单 #{{ order.order_no }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">
                            {{ participants.filter(p => p.is_online).length }}/{{ participants.length }} 在房间
                        </p>
                    </div>
                </div>
            </template>
            <template #actions>
                <button @click="showMobileInfo = !showMobileInfo" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 text-gray-700 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </template>
        </P2PMobileHeader>

        <!-- 移动端固定高度，防止整页滚动 -->
        <div class="lg:min-h-screen lg:bg-gray-50 lg:dark:bg-slate-950 h-screen lg:h-auto overflow-hidden lg:overflow-visible">
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
                 class="hidden lg:block relative z-10 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800">
                <div class="max-w-7xl mx-auto px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="flex items-start gap-3 flex-1">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">建议连接 Telegram 接收实时通知</p>
                                <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                                    连接 Telegram 后，您可以实时收到交易状态更新、对方消息提醒等重要通知，确保不错过任何关键信息。
                                </p>
                            </div>
                        </div>
                        <div class="sm:ml-auto">
                            <Link href="/notifications/settings" 
                                  class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
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
                    <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </template>

                <div class="p-4 space-y-4">
                    <div class="space-y-4">
                        <!-- 托管状态 -->
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
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
                            <p class="text-sm font-medium text-gray-900 dark:text-slate-100 mb-3">交易流程</p>
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
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-slate-100">订单创建</p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">{{ order.created_at ? formatTime(order.created_at) : '已完成' }}</p>
                                        <!-- 商家确认按钮 -->
                                        <div v-if="order.escrow_status === 'order_initiated' && availableActions.includes('vendor_confirm')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('vendor_confirm')"
                                                variant="primary"
                                                size="md"
                                                fullWidth
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">2</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-600 dark:text-slate-300'">
                                            商家确认
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">3</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-600 dark:text-slate-300'">
                                            卖家转币到托管
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">
                                            {{ order.seller_paid_at ? formatTime(order.seller_paid_at) : '等待卖家转币' }}
                                        </p>
                                        <!-- 卖家转币按钮 -->
                                        <div v-if="order.escrow_status === 'vendor_confirmed' && availableActions.includes('mark_seller_paid')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('mark_seller_paid')"
                                                variant="primary"
                                                size="md"
                                                fullWidth
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">4</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-600 dark:text-slate-300'">
                                            托管确认到账
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">
                                            {{ order.escrow_received_at ? formatTime(order.escrow_received_at) : '系统确认中' }}
                                        </p>
                                        <!-- 模拟托管确认按钮（仅测试环境） -->
                                        <div v-if="order.escrow_status === 'seller_paid'" class="mt-2 space-y-2">
                                            <div class="flex gap-2">
                                                <P2PButton
                                                    @click="mockEscrowStatus('received')"
                                                    variant="success"
                                                    size="xs"
                                                    fullWidth
                                                >
                                                    模拟已收到
                                                </P2PButton>
                                                <P2PButton
                                                    @click="mockEscrowStatus('not_received')"
                                                    variant="danger"
                                                    size="xs"
                                                    fullWidth
                                                >
                                                    模拟未收到
                                                </P2PButton>
                                            </div>
                                            <p class="text-xs text-amber-600 dark:text-amber-400 text-center">
                                                🧪 测试模式
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 步骤 5: 买家确认托管到账 -->
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'escrow_received' ? 'bg-amber-500 animate-pulse' :
                                            ['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-600' :
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">5</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-600 dark:text-slate-300'">
                                            买家确认托管到账
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">
                                            {{ order.buyer_confirmed_escrow_at ? formatTime(order.buyer_confirmed_escrow_at) : '等待买家确认' }}
                                        </p>
                                        <!-- 买家确认托管按钮 -->
                                        <div v-if="order.escrow_status === 'escrow_received' && availableActions.includes('confirm_escrow')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('confirm_escrow')"
                                                variant="primary"
                                                size="md"
                                                fullWidth
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">6</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-600 dark:text-slate-300'">
                                            买家付款
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">
                                            {{ order.buyer_paid_at ? formatTime(order.buyer_paid_at) : '等待买家付款' }}
                                        </p>
                                        <!-- 买家付款按钮 -->
                                        <div v-if="order.escrow_status === 'buyer_confirmed_escrow' && availableActions.includes('mark_buyer_paid')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('mark_buyer_paid')"
                                                variant="primary"
                                                size="md"
                                                fullWidth
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">7</span>
                                        </div>
                                        <div class="w-0.5 h-16 bg-gray-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-sm font-medium" :class="['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-600 dark:text-slate-300'">
                                            卖家确认收款
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">
                                            {{ order.seller_received_at ? formatTime(order.seller_received_at) : '等待卖家确认' }}
                                        </p>
                                        <!-- 卖家确认收款按钮 -->
                                        <div v-if="order.escrow_status === 'buyer_paid' && availableActions.includes('mark_seller_received')" class="mt-2">
                                            <P2PButton
                                                @click="handleEscrowAction('mark_seller_received')"
                                                variant="success"
                                                size="md"
                                                fullWidth
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="order.escrow_status === 'escrow_released'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white text-xs font-bold">8</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium" :class="['seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-600 dark:text-slate-300'">
                                            托管释放
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">
                                            {{ order.escrow_released_at ? formatTime(order.escrow_released_at) : '平台释放币给买家' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 交易信息 -->
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-slate-100 mb-2">交易信息</p>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-slate-400">订单编号</span>
                                    <p class="font-mono text-gray-900 dark:text-slate-200">#{{ order.order_no }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-slate-400">商家</span>
                                    <p class="font-medium text-gray-900 dark:text-slate-200">{{ order.vendor?.name || '加载中...' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-slate-400">客户</span>
                                    <p class="font-medium text-gray-900 dark:text-slate-200">{{ order.client?.name || '加载中...' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-slate-400">买家</span>
                                    <p class="font-medium text-gray-900 dark:text-slate-200">{{ order.buyer?.name || '加载中...' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-slate-400">卖家</span>
                                    <p class="font-medium text-gray-900 dark:text-slate-200">{{ order.seller?.name || '加载中...' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-slate-400">币种</span>
                                    <p class="font-medium text-gray-900 dark:text-slate-200">{{ order.currency_label || order.currency_key || order.crypto_currency }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-slate-400">数量（含交易费）</span>
                                    <p class="font-medium text-gray-900 dark:text-slate-200">{{ parseFloat(order.crypto_amount).toFixed(4) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}</p>
                                    <p v-if="order.fee && order.fee > 0" class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">
                                        交易费（商家）: {{ parseFloat(order.fee).toFixed(8) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-slate-400">单价</span>
                                    <p class="font-medium text-gray-900 dark:text-slate-200">¥{{ parseFloat(order.price).toFixed(2) }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-slate-400">总金额</span>
                                    <p class="font-bold text-emerald-600 dark:text-emerald-400">¥{{ parseFloat(order.fiat_amount).toFixed(2) }}</p>
                                </div>
                            </div>
                            <!-- 卖家地址 -->
                            <div v-if="order.seller_address" class="mt-3 pt-3 border-t border-gray-100 dark:border-slate-800">
                                <span class="text-xs text-gray-500 dark:text-slate-400 block mb-1">卖家地址</span>
                                <p class="font-mono text-sm text-gray-900 dark:text-slate-200 break-all">{{ order.seller_address }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </P2PMobileDrawer>

            <!-- 桌面端原有布局 -->
            <div class="mx-auto max-w-7xl">
                <div class="hidden lg:grid lg:grid-cols-3 gap-4 p-4">
                    <!-- 左侧：交易信息面板（桌面端显示） -->
                    <div class="lg:col-span-1 space-y-4">
                        <!-- 交易状态卡片 -->
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">交易详情</h3>
                                <span :class="getStatusBadgeClass(order.escrow_status)" class="px-3 py-1 text-xs font-semibold rounded-full">
                                    {{ getStatusBadgeText(order.escrow_status) }}
                                </span>
                            </div>
                            
                            <!-- 评价按钮 - 放在顶部显眼位置 -->
                            <div v-if="currentOrder.escrow_status === 'escrow_released' && currentUserId === currentOrder.client_id" class="mb-4">
                                <div v-if="!currentOrder.has_review" class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="text-sm font-medium text-amber-800 dark:text-amber-200">交易已完成，请评价</span>
                                        </div>
                                    </div>
                                    <P2PButton
                                        @click="showReviewModal = true"
                                        variant="primary"
                                        size="sm"
                                        fullWidth
                                        class="mt-2"
                                    >
                                        <template #icon>
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </template>
                                        立即评价商家
                                    </P2PButton>
                                </div>
                                <div v-else class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm font-medium text-emerald-800 dark:text-emerald-200">您已评价此交易</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 交易进度 -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-slate-400 mb-2">
                                    <span>交易进度</span>
                                    <span>{{ getProgressPercentage(order.escrow_status) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2">
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
                                            <p class="text-sm font-medium text-gray-900 dark:text-slate-100">订单创建</p>
                                            <p class="text-xs text-gray-500 dark:text-slate-400">{{ order.created_at ? formatTime(order.created_at) : '已完成' }}</p>
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">2</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-400 dark:text-slate-500'">
                                                商家确认
                                            </p>
                                            <p class="text-xs" :class="['vendor_confirmed', 'seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-500 dark:text-slate-400' : 'text-gray-400 dark:text-slate-600'">
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">3</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-400 dark:text-slate-500'">
                                                卖家转币到托管
                                            </p>
                                            <p class="text-xs" :class="['seller_paid', 'escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-500 dark:text-slate-400' : 'text-gray-400 dark:text-slate-600'">
                                                {{ order.seller_paid_at ? formatTime(order.seller_paid_at) : (order.escrow_status === 'vendor_confirmed' ? '等待中...' : '待完成') }}
                                            </p>
                                            <!-- 显示交易哈希 -->
                                            <p v-if="order.escrow_tx_hash" class="text-xs text-blue-600 dark:text-blue-400 font-mono break-all mt-1">
                                                TX: {{ order.escrow_tx_hash }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- 卖家转币按钮 -->
                                    <div v-if="order.escrow_status === 'vendor_confirmed' && availableActions.includes('mark_seller_paid')" class="ml-11 mt-2">
                                        <P2PButton
                                            @click="handleEscrowAction('mark_seller_paid')"
                                            variant="primary"
                                            size="sm"
                                            fullWidth
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">4</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-400 dark:text-slate-500'">
                                                托管确认到账
                                            </p>
                                            <p class="text-xs" :class="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-500 dark:text-slate-400' : 'text-gray-400 dark:text-slate-600'">
                                                {{ order.escrow_received_at ? formatTime(order.escrow_received_at) : (order.escrow_status === 'seller_paid' ? '系统确认中...' : '待完成') }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- 模拟托管确认按钮（仅测试环境） -->
                                    <div v-if="order.escrow_status === 'seller_paid'" class="ml-11 mt-2 space-y-2">
                                        <div class="flex gap-2">
                                            <P2PButton
                                                @click="mockEscrowStatus('received')"
                                                variant="success"
                                                size="sm"
                                                fullWidth
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                模拟已收到
                                            </P2PButton>
                                            <P2PButton
                                                @click="mockEscrowStatus('not_received')"
                                                variant="danger"
                                                size="sm"
                                                fullWidth
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                模拟未收到
                                            </P2PButton>
                                        </div>
                                        <p class="text-xs text-amber-600 dark:text-amber-400 text-center">
                                            🧪 测试模式：模拟区块链确认
                                        </p>
                                    </div>
                                </div>

                                <!-- 步骤5：买家确认托管到账 -->
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div :class="[
                                            'w-8 h-8 rounded-full flex items-center justify-center',
                                            order.escrow_status === 'escrow_received' ? 'bg-amber-500 animate-pulse' :
                                            ['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'bg-emerald-500' :
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">5</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-400 dark:text-slate-500'">
                                                买家确认托管到账
                                            </p>
                                            <p class="text-xs" :class="['escrow_received', 'buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-500 dark:text-slate-400' : 'text-gray-400 dark:text-slate-600'">
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">6</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['buyer_confirmed_escrow', 'buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-400 dark:text-slate-500'">
                                                买家付款
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-slate-400">
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="['seller_received', 'escrow_released'].includes(order.escrow_status)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">7</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-400 dark:text-slate-500'">
                                                卖家确认收款
                                            </p>
                                            <p class="text-xs" :class="['buyer_paid', 'seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-500 dark:text-slate-400' : 'text-gray-400 dark:text-slate-600'">
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
                                            'bg-gray-300 dark:bg-slate-700'
                                        ]">
                                            <svg v-if="order.escrow_status === 'escrow_released'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span v-else class="text-white font-bold text-xs">8</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium" :class="['seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-400 dark:text-slate-500'">
                                                托管释放
                                            </p>
                                            <p class="text-xs" :class="['seller_received', 'escrow_released'].includes(order.escrow_status) ? 'text-gray-500 dark:text-slate-400' : 'text-gray-400 dark:text-slate-600'">
                                                {{ order.escrow_released_at ? formatTime(order.escrow_released_at) : (order.escrow_status === 'seller_received' ? '处理中...' : '待完成') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 交易信息 -->
                            <div class="space-y-3 border-t border-gray-200 dark:border-slate-800 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">订单编号</span>
                                    <span class="text-sm font-mono text-gray-900 dark:text-slate-200">#{{ order.order_no }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">商家</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-slate-200">{{ order.vendor?.name || '加载中...' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">客户</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-slate-200">{{ order.client?.name || '加载中...' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">买家</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-slate-200">{{ order.buyer?.name || '加载中...' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">卖家</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-slate-200">{{ order.seller?.name || '加载中...' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">数量（含交易费）</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-slate-200">{{ parseFloat(order.crypto_amount).toFixed(4) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}</span>
                                </div>
                                <div v-if="order.fee && order.fee > 0" class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">交易费（商家）</span>
                                    <span class="text-sm text-amber-600 dark:text-amber-400">{{ parseFloat(order.fee).toFixed(8) }} {{ order.currency_label || order.currency_key || order.crypto_currency }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">单价</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-slate-200">¥{{ parseFloat(order.price).toFixed(2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">总金额</span>
                                    <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">¥{{ parseFloat(order.fiat_amount).toFixed(2) }}</span>
                                </div>
                                <!-- 卖家地址 -->
                                <div v-if="order.seller_address" class="flex justify-between items-start pt-3 border-t border-gray-100 dark:border-slate-800">
                                    <span class="text-sm text-gray-500 dark:text-slate-400">卖家地址</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-slate-200 text-right break-all max-w-[180px]">
                                        {{ order.seller_address }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 右侧区域 -->
                    <div class="lg:col-span-2">
                        <!-- 聊天区域（桌面端） -->
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 flex flex-col" style="height: 650px; min-height: 600px;">
                        <!-- 聊天头部 -->
                        <div class="border-b border-gray-200 dark:border-slate-800">
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
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-2 ring-white dark:ring-slate-900">
                                                        <span class="text-white font-bold">{{ participant.name?.[0]?.toUpperCase() || 'U' }}</span>
                                                    </div>
                                                    <div v-if="participant.is_online" class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 bg-emerald-500 rounded-full ring-2 ring-white dark:ring-slate-900"></div>
                                                </a>
                                            </template>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-gray-900 dark:text-slate-100">
                                                    订单 #{{ order.order_no }} 聊天室
                                                    <span class="text-xs text-gray-500 dark:text-slate-400 ml-2">({{ participants.length }}人)</span>
                                                </p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-slate-400">
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

                            <!-- 安全提醒栏 -->
                            <div class="px-6 pb-3">
                                <div class="flex items-center gap-2 p-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-xs text-amber-800 dark:text-amber-300">
                                        <span class="font-semibold">安全提醒：</span>请勿相信任何平台外的交易邀请，谨防诈骗
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 聊天消息区域 -->
                        <div ref="messageContainer" class="flex-1 overflow-y-auto p-6 space-y-4">
                            <!-- 初始系统消息 -->
                            <div class="flex justify-center">
                                <div class="px-3 py-1 bg-gray-100 dark:bg-slate-800 rounded-full text-xs text-gray-500 dark:text-slate-400">
                                    订单已创建，等待商家确认
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
                                    <div class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 rounded-full text-xs text-blue-600 dark:text-blue-400 max-w-md">
                                        <span v-if="msg.user">{{ msg.user.name }} </span>
                                        {{ msg.message }}
                                    </div>
                                </template>

                                <!-- 对方消息 -->
                                <template v-else-if="msg.user?.id !== $page.props.auth.user?.id">
                                    <a :href="`/trader/profile/${msg.user.id}`" target="_blank" class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0 hover:opacity-80 transition-opacity cursor-pointer">
                                        <span class="text-white text-sm font-bold">{{ msg.user.name.charAt(0) }}</span>
                                    </a>
                                    <div class="max-w-md">
                                        <p class="text-xs font-medium text-gray-600 dark:text-slate-400 mb-1 px-1">
                                            {{ msg.user.name }}
                                            <span v-if="msg.type === 'admin'" class="text-purple-600 dark:text-purple-400 font-bold">(Admin)</span>
                                        </p>
                                        <div class="bg-gray-100 dark:bg-slate-800 rounded-2xl rounded-tl-none px-4 py-3">
                                            <!-- 附件显示 -->
                                            <div v-if="msg.attachment" class="mb-2">
                                                <!-- 图片显示 -->
                                                <img v-if="msg.attachment.type && msg.attachment.type.startsWith('image/')"
                                                     :src="msg.attachment.url"
                                                     :alt="msg.attachment.name"
                                                     class="max-w-full rounded-lg cursor-pointer"
                                                     @click="window.open(msg.attachment.url, '_blank')"
                                                />
                                                <!-- PDF显示 -->
                                                <a v-else-if="msg.attachment.type === 'application/pdf'"
                                                   :href="msg.attachment.url"
                                                   target="_blank"
                                                   class="flex items-center gap-2 p-2 bg-white dark:bg-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors">
                                                    <div class="w-10 h-12 bg-red-100 dark:bg-red-900/30 rounded flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-medium text-gray-900 dark:text-slate-200">{{ msg.attachment.name }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-slate-400">{{ formatFileSize(msg.attachment.size) }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <p v-if="msg.message" class="text-sm text-gray-900 dark:text-slate-200">{{ msg.message }}</p>
                                        </div>
                                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1 px-1">{{ formatTime(msg.created_at) }}</p>
                                    </div>
                                </template>

                                <!-- 自己的消息 -->
                                <template v-else>
                                    <div class="max-w-md">
                                        <p class="text-xs font-medium text-gray-600 dark:text-slate-400 mb-1 px-1 text-right">
                                            {{ msg.user.name }}
                                            <span v-if="msg.type === 'admin'" class="text-purple-600 dark:text-purple-400 font-bold">(Admin)</span>
                                        </p>
                                        <div class="bg-emerald-600 text-white rounded-2xl rounded-tr-none px-4 py-3">
                                            <!-- 附件显示 -->
                                            <div v-if="msg.attachment" class="mb-2">
                                                <!-- 图片显示 -->
                                                <img v-if="msg.attachment.type && msg.attachment.type.startsWith('image/')"
                                                     :src="msg.attachment.url"
                                                     :alt="msg.attachment.name"
                                                     class="max-w-full rounded-lg cursor-pointer"
                                                     @click="window.open(msg.attachment.url, '_blank')"
                                                />
                                                <!-- PDF显示 -->
                                                <a v-else-if="msg.attachment.type === 'application/pdf'"
                                                   :href="msg.attachment.url"
                                                   target="_blank"
                                                   class="flex items-center gap-2 p-2 bg-emerald-500 rounded-lg hover:bg-emerald-400 transition-colors">
                                                    <div class="w-10 h-12 bg-white/20 rounded flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-medium text-white">{{ msg.attachment.name }}</p>
                                                        <p class="text-xs text-emerald-100">{{ formatFileSize(msg.attachment.size) }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <p v-if="msg.message" class="text-sm">{{ msg.message }}</p>
                                        </div>
                                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1 px-1 text-right">{{ formatTime(msg.created_at) }}</p>
                                    </div>
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-sm font-bold">我</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- 输入区域 -->
                        <div class="border-t border-gray-200 dark:border-slate-800 p-4">
                            <!-- 文件预览 -->
                            <div v-if="selectedFile" class="mb-3 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <!-- 图片预览 -->
                                    <img v-if="selectedFile.type.startsWith('image/')"
                                         :src="filePreviewUrl"
                                         :alt="selectedFile.name"
                                         class="max-h-32 rounded-lg"
                                    />
                                    <!-- PDF图标 -->
                                    <div v-else-if="selectedFile.type === 'application/pdf'"
                                         class="w-16 h-20 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <!-- 文件信息 -->
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-slate-200">{{ selectedFile.name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">{{ formatFileSize(selectedFile.size) }}</p>
                                    </div>
                                    <!-- 移除按钮 -->
                                    <button @click="removeFile"
                                            type="button"
                                            class="p-1 rounded hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    class="hidden"
                                />
                                <button type="button"
                                        @click="!isTradeCompleted && $refs.fileInput.click()"
                                        :disabled="isTradeCompleted"
                                        class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                </button>

                                <input
                                    v-model="newMessage"
                                    type="text"
                                    :placeholder="isTradeCompleted ? '交易已结束，无法发送消息' : '输入消息...'"
                                    class="flex-1 px-4 py-2 bg-gray-100 dark:bg-slate-800 border-0 rounded-lg text-sm text-gray-900 dark:text-slate-200 placeholder-gray-500 dark:placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 disabled:opacity-50"
                                    :disabled="!isConnected || isTradeCompleted"
                                />

                                <button
                                    type="submit"
                                    :disabled="(!newMessage.trim() && !selectedFile) || !isConnected || isUploading || isTradeCompleted"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-lg font-medium text-sm transition-colors flex items-center gap-2"
                                >
                                    <span v-if="isUploading" class="animate-spin">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    <span>{{ isUploading ? '发送中...' : '发送' }}</span>
                                </button>
                            </form>

                            <!-- 快捷回复 -->
                            <div class="flex gap-2 mt-3 items-center">
                                <button @click="quickReply('已付款')" class="px-3 py-1 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-xs text-gray-700 dark:text-slate-300 rounded-full transition-colors">
                                    已付款
                                </button>
                                <button @click="quickReply('请确认收款')" class="px-3 py-1 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-xs text-gray-700 dark:text-slate-300 rounded-full transition-colors">
                                    请确认收款
                                </button>
                                <button @click="quickReply('谢谢')" class="px-3 py-1 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-xs text-gray-700 dark:text-slate-300 rounded-full transition-colors">
                                    谢谢
                                </button>
                                
                                <!-- Telegram 通知提醒 (桌面版) -->
                                <a v-if="!$page.props.auth.user?.telegram_chat_id && order.status !== 'cancelled' && order.status !== 'completed'"
                                   href="/notifications/settings" 
                                   target="_blank"
                                   class="ml-auto inline-flex items-center gap-1 px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-full transition-colors">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                                    </svg>
                                    实时通知
                                </a>
                            </div>
                        </div>

                        
                        <!-- 底部操作栏 -->
                        <div v-if="order.status !== 'completed' && order.status !== 'cancelled' && order.escrow_status !== 'escrow_released'" class="border-t border-gray-200 dark:border-slate-800 p-3 bg-gray-50 dark:bg-slate-900/50 rounded-b-xl">
                            <!-- 动态托管操作按钮 -->
                            <div class="flex gap-2">
                                <template v-for="action in availableActions" :key="action">
                                    <P2PButton
                                        v-if="action === 'vendor_confirm'"
                                        @click="handleEscrowAction('vendor_confirm')"
                                        variant="primary"
                                        size="md"
                                        fullWidth
                                    >
                                        确认订单
                                    </P2PButton>

                                    <P2PButton
                                        v-if="action === 'mark_seller_paid'"
                                        @click="handleEscrowAction('mark_seller_paid')"
                                        variant="primary"
                                        size="md"
                                        fullWidth
                                    >
                                        我已转币到托管
                                    </P2PButton>

                                    <P2PButton
                                        v-if="action === 'confirm_escrow'"
                                        @click="handleEscrowAction('confirm_escrow')"
                                        variant="primary"
                                        size="md"
                                        fullWidth
                                    >
                                        确认托管到账
                                    </P2PButton>

                                    <P2PButton
                                        v-if="action === 'mark_buyer_paid'"
                                        @click="handleEscrowAction('mark_buyer_paid')"
                                        variant="primary"
                                        size="md"
                                        fullWidth
                                    >
                                        我已付款
                                    </P2PButton>

                                    <P2PButton
                                        v-if="action === 'mark_seller_received'"
                                        @click="handleEscrowAction('mark_seller_received')"
                                        variant="success"
                                        size="md"
                                        fullWidth
                                    >
                                        确认收款
                                    </P2PButton>

                                    <P2PButton
                                        v-if="action === 'cancel'"
                                        @click="handleEscrowAction('cancel')"
                                        variant="secondary"
                                        size="md"
                                        fullWidth
                                    >
                                        取消订单
                                    </P2PButton>
                                </template>

                                <!-- 争议按钮 -->
                                <P2PButton
                                    v-if="availableActions.includes('create_dispute')"
                                    @click="handleEscrowAction('create_dispute')"
                                    variant="warning"
                                    size="md"
                                    fullWidth
                                >
                                    发起争议
                                </P2PButton>

                                <P2PButton
                                    v-if="availableActions.includes('resolve_dispute')"
                                    @click="handleEscrowAction('resolve_dispute')"
                                    variant="success"
                                    size="md"
                                    fullWidth
                                >
                                    解除争议
                                </P2PButton>
                            </div>
                        </div>
                    </div>

                    <!-- 支付信息和交易信息区域 -->
                    <div class="space-y-4 mt-6">
                    <!-- 交易条款（全宽） -->
                    <div v-if="order.ad_snapshot?.vendor_toc" class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-slate-100">交易条款</h3>
                        </div>

                        <!-- 条款内容 -->
                        <div class="p-3 bg-gray-50 dark:bg-slate-800/50 rounded-lg max-h-40 overflow-y-auto">
                            <p class="text-xs text-gray-700 dark:text-slate-300 whitespace-pre-wrap">{{ order.ad_snapshot?.vendor_toc || '无交易条款' }}</p>
                        </div>

                        <!-- 提醒 -->
                        <div class="mt-3 p-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
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
            <!-- 计算高度：100vh - 顶部导航(3.5rem) - 底部导航(4rem) = calc(100vh-7.5rem) -->
            <div class="lg:hidden fixed inset-x-0 top-14 bottom-16 flex flex-col bg-white dark:bg-slate-950">
                    <!-- 移动端评价提示 -->
                    <div v-if="currentOrder.escrow_status === 'escrow_released' && currentUserId === currentOrder.client_id && !currentOrder.has_review" 
                         class="bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800 p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 flex-1">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-amber-800 dark:text-amber-200">交易已完成</p>
                                    <p class="text-xs text-amber-700 dark:text-amber-300">请对商家进行评价</p>
                                </div>
                            </div>
                            <P2PButton
                                @click="showReviewModal = true"
                                variant="primary"
                                size="sm"
                            >
                                立即评价
                            </P2PButton>
                        </div>
                    </div>
                    
                    <!-- 移动端聊天内容 -->
                    <div ref="mobileMessageContainer" class="flex-1 overflow-y-auto bg-white dark:bg-slate-950">
                        <div class="p-4 space-y-4">
                            <!-- 初始系统消息 -->
                            <div class="flex justify-center">
                                <div class="px-3 py-1 bg-gray-100 dark:bg-slate-800 rounded-full text-xs text-gray-500 dark:text-slate-400">
                                    订单已创建，等待商家确认
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
                                    <div class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 rounded-full text-xs text-blue-600 dark:text-blue-400">
                                        <span v-if="msg.user">{{ msg.user.name }} </span>
                                        {{ msg.message }}
                                    </div>
                                </template>

                                <!-- 对方消息 -->
                                <template v-else-if="msg.user?.id !== $page.props.auth.user?.id">
                                    <a :href="`/trader/profile/${msg.user.id}`" target="_blank" class="h-7 w-7 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0 hover:opacity-80 transition-opacity cursor-pointer">
                                        <span class="text-white text-xs font-bold">{{ msg.user.name.charAt(0) }}</span>
                                    </a>
                                    <div class="max-w-[70%]">
                                        <p class="text-xs font-medium text-gray-600 dark:text-slate-400 mb-0.5 px-1">
                                            {{ msg.user.name }}
                                            <span v-if="msg.type === 'admin'" class="text-purple-600 dark:text-purple-400 font-bold">(Admin)</span>
                                        </p>
                                        <div class="bg-gray-100 dark:bg-slate-800 rounded-2xl rounded-tl-none px-3 py-2">
                                            <!-- 附件显示 -->
                                            <div v-if="msg.attachment" class="mb-2">
                                                <!-- 图片显示 -->
                                                <img v-if="msg.attachment.type && msg.attachment.type.startsWith('image/')"
                                                     :src="msg.attachment.url"
                                                     :alt="msg.attachment.name"
                                                     class="max-w-full rounded-lg cursor-pointer"
                                                     @click="window.open(msg.attachment.url, '_blank')"
                                                />
                                                <!-- PDF显示 -->
                                                <a v-else-if="msg.attachment.type === 'application/pdf'"
                                                   :href="msg.attachment.url"
                                                   target="_blank"
                                                   class="flex items-center gap-2 p-2 bg-white dark:bg-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors">
                                                    <div class="w-8 h-10 bg-red-100 dark:bg-red-900/30 rounded flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-medium text-gray-900 dark:text-slate-200">{{ msg.attachment.name }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-slate-400">{{ formatFileSize(msg.attachment.size) }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <p v-if="msg.message" class="text-sm text-gray-900 dark:text-slate-200">{{ msg.message }}</p>
                                        </div>
                                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1 px-1">{{ formatTime(msg.created_at) }}</p>
                                    </div>
                                </template>

                                <!-- 自己的消息 -->
                                <template v-else>
                                    <div class="max-w-[70%]">
                                        <p class="text-xs font-medium text-gray-600 dark:text-slate-400 mb-0.5 px-1 text-right">
                                            {{ msg.user.name }}
                                            <span v-if="msg.type === 'admin'" class="text-purple-600 dark:text-purple-400 font-bold">(Admin)</span>
                                        </p>
                                        <div class="bg-emerald-600 text-white rounded-2xl rounded-tr-none px-3 py-2">
                                            <!-- 附件显示 -->
                                            <div v-if="msg.attachment" class="mb-2">
                                                <!-- 图片显示 -->
                                                <img v-if="msg.attachment.type && msg.attachment.type.startsWith('image/')"
                                                     :src="msg.attachment.url"
                                                     :alt="msg.attachment.name"
                                                     class="max-w-full rounded-lg cursor-pointer"
                                                     @click="window.open(msg.attachment.url, '_blank')"
                                                />
                                                <!-- PDF显示 -->
                                                <a v-else-if="msg.attachment.type === 'application/pdf'"
                                                   :href="msg.attachment.url"
                                                   target="_blank"
                                                   class="flex items-center gap-2 p-2 bg-emerald-500 rounded-lg hover:bg-emerald-400 transition-colors">
                                                    <div class="w-8 h-10 bg-white/20 rounded flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-medium text-white">{{ msg.attachment.name }}</p>
                                                        <p class="text-xs text-emerald-100">{{ formatFileSize(msg.attachment.size) }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <p v-if="msg.message" class="text-sm">{{ msg.message }}</p>
                                        </div>
                                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1 px-1 text-right">{{ formatTime(msg.created_at) }}</p>
                                    </div>
                                    <div class="h-7 w-7 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">我</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                <!-- 操作按钮 -->
                <div class="px-3 pb-3">
                    <div class="flex gap-2">
                        <template v-for="action in potentialActions" :key="action">
                            <P2PButton
                                v-if="action === 'vendor_confirm'"
                                @click="handleEscrowAction('vendor_confirm')"
                                variant="primary"
                                size="sm"
                                fullWidth
                            >
                                确认订单
                            </P2PButton>

                            <P2PButton
                                v-if="action === 'mark_seller_paid'"
                                @click="handleEscrowAction('mark_seller_paid')"
                                variant="primary"
                                size="sm"
                                fullWidth
                            >
                                我已转币
                            </P2PButton>

                            <P2PButton
                                v-if="action === 'confirm_escrow'"
                                @click="handleEscrowAction('confirm_escrow')"
                                variant="primary"
                                size="sm"
                                fullWidth
                            >
                                确认托管到账
                            </P2PButton>

                            <P2PButton
                                v-if="action === 'mark_buyer_paid'"
                                @click="handleEscrowAction('mark_buyer_paid')"
                                variant="primary"
                                size="sm"
                                fullWidth
                            >
                                我已付款
                            </P2PButton>

                            <P2PButton
                                v-if="action === 'mark_seller_received'"
                                @click="handleEscrowAction('mark_seller_received')"
                                variant="success"
                                size="sm"
                                fullWidth
                            >
                                确认收款
                            </P2PButton>

                            <P2PButton
                                v-if="action === 'cancel'"
                                @click="handleEscrowAction('cancel')"
                                variant="secondary"
                                size="sm"
                                fullWidth
                            >
                                取消订单
                            </P2PButton>
                        </template>

                        <P2PButton
                            v-if="potentialActions.includes('create_dispute')"
                            @click="handleEscrowAction('create_dispute')"
                            variant="warning"
                            size="sm"
                            fullWidth
                        >
                            发起争议
                        </P2PButton>

                        <P2PButton
                            v-if="potentialActions.includes('resolve_dispute')"
                            @click="handleEscrowAction('resolve_dispute')"
                            variant="success"
                            size="sm"
                            fullWidth
                        >
                            解除争议
                        </P2PButton>
                    </div>
                </div>
                    <!-- 移动端底部操作区 - 固定在底部 -->
                    <div class="bg-white dark:bg-slate-900 border-t border-gray-200 dark:border-slate-800">
                        <!-- 输入区域 -->
                        <div class="p-3">
                            <!-- 文件预览 -->
                            <div v-if="selectedFile" class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <!-- 图片预览 -->
                                        <img v-if="filePreviewUrl && selectedFile.type.startsWith('image/')"
                                             :src="filePreviewUrl"
                                             class="w-12 h-12 object-cover rounded-lg border"
                                             alt="预览">
                                        <!-- 文件图标 -->
                                        <div v-else class="w-12 h-12 bg-gray-200 dark:bg-slate-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-500 dark:text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-slate-200 truncate">{{ selectedFile.name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400">{{ formatFileSize(selectedFile.size) }}</p>
                                    </div>
                                    <button @click="clearFileSelection"
                                            class="flex-shrink-0 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <form @submit.prevent="sendMessage" class="flex gap-2">
                                <!-- 隐藏的文件输入 -->
                                <input
                                    ref="mobileFileInput"
                                    type="file"
                                    @change="handleFileSelect"
                                    accept=".pdf,image/*"
                                    class="hidden"
                                />
                                <button type="button"
                                        @click="!isTradeCompleted && $refs.mobileFileInput.click()"
                                        :disabled="isTradeCompleted"
                                        class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                </button>
                                <input
                                    v-model="newMessage"
                                    type="text"
                                    :placeholder="isTradeCompleted ? '交易已结束，无法发送消息' : '输入消息...'"
                                    class="flex-1 px-3 py-2 bg-gray-100 dark:bg-slate-800 border-0 rounded-lg text-sm text-gray-900 dark:text-slate-200 placeholder-gray-500 dark:placeholder-slate-400 disabled:opacity-50"
                                    :disabled="!isConnected || isTradeCompleted"
                                />
                                <button
                                    type="submit"
                                    :disabled="(!newMessage.trim() && !selectedFile) || !isConnected || isUploading || isTradeCompleted"
                                    class="px-4 py-2 bg-emerald-600 disabled:bg-gray-400 text-white rounded-lg font-medium text-sm transition-colors flex items-center gap-2"
                                >
                                    <span v-if="isUploading" class="animate-spin">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    <span>{{ isUploading ? '发送中...' : '发送' }}</span>
                                </button>
                            </form>

                            <!-- 快捷回复 -->
                            <div class="flex gap-2 mt-2 overflow-x-auto">
                                <button @click="quickReply('已付款')" class="px-3 py-1 bg-gray-100 dark:bg-slate-800 text-xs text-gray-700 dark:text-slate-300 rounded-full whitespace-nowrap">已付款</button>
                                <button @click="quickReply('请确认')" class="px-3 py-1 bg-gray-100 dark:bg-slate-800 text-xs text-gray-700 dark:text-slate-300 rounded-full whitespace-nowrap">请确认</button>
                                <button @click="quickReply('谢谢')" class="px-3 py-1 bg-gray-100 dark:bg-slate-800 text-xs text-gray-700 dark:text-slate-300 rounded-full whitespace-nowrap">谢谢</button>
                                <button @click="quickReply('稍等')" class="px-3 py-1 bg-gray-100 dark:bg-slate-800 text-xs text-gray-700 dark:text-slate-300 rounded-full whitespace-nowrap">稍等</button>
                                
                                <!-- 实时通知按钮 (移动端) -->
                                <a v-if="!$page.props.auth.user?.telegram_chat_id && order.status !== 'cancelled' && order.status !== 'completed'"
                                   href="/notifications/settings" 
                                   target="_blank"
                                   class="px-3 py-1 bg-blue-500 text-white text-xs rounded-full whitespace-nowrap inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                                    </svg>
                                    实时通知
                                </a>
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
            :message="dialogState.message"
            :input-mode="dialogState.inputMode"
            :input-label="dialogState.inputLabel"
            :input-placeholder="dialogState.inputPlaceholder"
            :input-required="dialogState.inputRequired"
            :danger="dialogState.danger"
            :confirm-variant="dialogState.confirmVariant"
            :confirm-text="dialogState.confirmText"
            :cancel-text="dialogState.cancelText"
            :loading="dialogState.loading"
            @confirm="handleDialogConfirm"
            @cancel="handleDialogCancel"
            @close="handleDialogCancel"
        />

        <!-- 买家确认托管专用对话框 -->
        <Modal
            :show="dialogState.pendingAction === 'confirm_escrow' && dialogState.show"
            max-width="lg"
            :closeable="true"
            @close="handleDialogCancel"
        >
            <div class="bg-white dark:bg-slate-900 px-4 pt-4 pb-4 sm:p-5 sm:pb-4 max-h-[80vh] overflow-y-auto">
                <!-- 图标居中 -->
                <div class="mx-auto flex items-center justify-center size-10 rounded-full bg-amber-100 dark:bg-amber-900/30">
                    <svg class="size-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                
                <!-- 标题和内容 -->
                <div class="mt-2 text-center">
                    <h3 class="text-base font-medium text-gray-900 dark:text-slate-100">
                        确认托管到账
                    </h3>
                    <div class="mt-3 space-y-2.5 text-left">
                        <!-- 交易信息显示 -->
                        <div class="p-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg">
                            <div class="space-y-2">
                                <div v-if="currentOrder.seller_address">
                                    <label class="text-xs text-gray-500 dark:text-slate-400">来源地址（卖家）</label>
                                    <p class="font-mono text-xs text-gray-900 dark:text-slate-100 break-all mt-0.5">
                                        {{ currentOrder.seller_address }}
                                    </p>
                                </div>
                                <div v-if="currentOrder.escrow_address">
                                    <label class="text-xs text-gray-500 dark:text-slate-400">托管地址</label>
                                    <p class="font-mono text-xs text-gray-900 dark:text-slate-100 break-all mt-0.5">
                                        {{ currentOrder.escrow_address }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-slate-400">交易金额</label>
                                    <p class="text-base font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ parseFloat(currentOrder.crypto_amount).toFixed(4) }} {{ currentOrder.currency_label || currentOrder.currency_key || currentOrder.crypto_currency }}
                                    </p>
                                </div>
                                <div v-if="currentOrder.escrow_tx_hash">
                                    <label class="text-xs text-gray-500 dark:text-slate-400">交易哈希</label>
                                    <p class="font-mono text-xs text-blue-600 dark:text-blue-400 break-all mt-0.5">
                                        {{ currentOrder.escrow_tx_hash }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 重要提醒 -->
                        <div class="p-2.5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div class="text-xs">
                                    <p class="font-medium text-amber-800 dark:text-amber-300">请打开区块链浏览器仔细核对</p>
                                    <p class="text-amber-700 dark:text-amber-400 mt-0.5">
                                        确认前必须逐项检查以下所有信息
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 确认清单 -->
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-700 dark:text-slate-300">请确认以下所有项目：</p>
                            
                            <div class="space-y-1.5">
                                <div class="flex items-start">
                                    <input
                                        v-model="escrowChecks.txExists"
                                        type="checkbox"
                                        class="mt-0.5 h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                                        :disabled="dialogState.loading"
                                    />
                                    <label class="ml-2 text-xs text-gray-700 dark:text-slate-300">
                                        我已打开区块链浏览器，确认交易记录存在
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input
                                        v-model="escrowChecks.confirmations"
                                        type="checkbox"
                                        class="mt-0.5 h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                                        :disabled="dialogState.loading"
                                    />
                                    <label class="ml-2 text-xs text-gray-700 dark:text-slate-300">
                                        交易已有足够的确认数（建议至少6个确认）
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input
                                        v-model="escrowChecks.fromAddress"
                                        type="checkbox"
                                        class="mt-0.5 h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                                        :disabled="dialogState.loading"
                                    />
                                    <label class="ml-2 text-xs text-gray-700 dark:text-slate-300">
                                        来源地址与卖家地址完全一致
                                        <span class="block font-mono text-blue-600 dark:text-blue-400 break-all mt-0.5">{{ currentOrder.seller_address || '无卖家地址' }}</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input
                                        v-model="escrowChecks.toAddress"
                                        type="checkbox"
                                        class="mt-0.5 h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                                        :disabled="dialogState.loading"
                                    />
                                    <label class="ml-2 text-xs text-gray-700 dark:text-slate-300">
                                        目标地址与托管地址完全一致
                                        <span class="block font-mono text-blue-600 dark:text-blue-400 break-all mt-0.5">{{ currentOrder.escrow_address || '托管地址待分配' }}</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input
                                        v-model="escrowChecks.amount"
                                        type="checkbox"
                                        class="mt-0.5 h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                                        :disabled="dialogState.loading"
                                    />
                                    <label class="ml-2 text-xs text-gray-700 dark:text-slate-300">
                                        交易金额为 <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ parseFloat(currentOrder.crypto_amount).toFixed(4) }} {{ currentOrder.currency_label || currentOrder.currency_key || currentOrder.crypto_currency }}</span>，完全正确
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 最终警告 -->
                        <div class="p-2.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div class="text-xs">
                                    <p class="font-bold text-red-800 dark:text-red-300">最终确认</p>
                                    <p class="text-red-700 dark:text-red-400 mt-0.5">
                                        确认后您将进入付款阶段，必须按时完成付款。如信息有误导致的损失，责任自负！
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 按钮区域 -->
            <div class="flex flex-row justify-end px-4 py-3 sm:px-5 sm:py-3 bg-gray-100 dark:bg-slate-800 text-end gap-2">
                <P2PButton
                    variant="ghost"
                    @click="handleDialogCancel"
                    :disabled="dialogState.loading"
                >
                    返回检查
                </P2PButton>
                <P2PButton
                    variant="warning"
                    :loading="dialogState.loading"
                    :disabled="!allEscrowChecksCompleted"
                    @click="handleEscrowConfirm"
                >
                    确认托管到账
                </P2PButton>
            </div>
        </Modal>
        
        <!-- 卖家转币专用对话框 -->
        <Modal
            :show="dialogState.pendingAction === 'mark_seller_paid' && dialogState.show"
            max-width="lg"
            :closeable="true"
            @close="handleDialogCancel"
        >
            <div class="bg-white dark:bg-slate-900 px-4 pt-4 pb-4 sm:p-5 sm:pb-4 max-h-[80vh] overflow-y-auto">
                <!-- 图标居中 -->
                <div class="mx-auto flex items-center justify-center size-10 rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                
                <!-- 标题和内容 -->
                <div class="mt-2 text-center">
                    <h3 class="text-base font-medium text-gray-900 dark:text-slate-100">
                        标记已转币到托管
                    </h3>
                    <div class="mt-3 space-y-2.5 text-left">
                        <!-- 您的地址显示 -->
                        <div v-if="currentOrder.seller_address" class="p-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg">
                            <div class="space-y-1.5">
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-slate-400">您的地址</label>
                                    <p class="font-mono text-xs text-gray-900 dark:text-slate-100 break-all mt-0.5">
                                        {{ currentOrder.seller_address }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-slate-400">转币金额</label>
                                    <p class="text-base font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ parseFloat(currentOrder.crypto_amount).toFixed(4) }} {{ currentOrder.currency_label || currentOrder.currency_key || currentOrder.crypto_currency }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 托管地址说明 -->
                        <div class="p-2.5 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <div class="text-xs">
                                    <p class="font-medium text-blue-800 dark:text-blue-300">托管地址提示</p>
                                    <p class="text-blue-700 dark:text-blue-400 mt-0.5">
                                        托管地址功能正在开发中，请先将币转到您自己的地址保管
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 重要警告 -->
                        <div class="p-2.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div class="text-xs">
                                    <p class="font-bold text-red-800 dark:text-red-300 text-sm">极其重要！请仔细核对！</p>
                                    <ul class="mt-1 space-y-0.5 text-red-700 dark:text-red-400">
                                        <li class="text-xs">• 请确保转币地址完全正确</li>
                                        <li class="text-xs">• <span class="font-bold">请一定要确保金额是一次性从您的地址转入，否则系统无法确认</span></li>
                                        <li class="text-xs">• 请确保转币金额准确无误</li>
                                        <li class="text-xs">• <span class="font-bold">如果地址或金额错误，托管将无法确认！</span></li>
                                        <li class="text-xs">• <span class="font-bold text-red-800 dark:text-red-200">任何损失概不负责！</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 交易哈希输入 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                交易哈希 (TX ID) <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="sellerTxHash"
                                type="text"
                                placeholder="请输入区块链交易哈希..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :disabled="dialogState.loading"
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                                转币后请提供交易哈希作为凭证
                            </p>
                        </div>
                        
                        <!-- 确认复选框 -->
                        <div class="flex items-start">
                            <input
                                v-model="sellerConfirmChecked"
                                type="checkbox"
                                class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                :disabled="dialogState.loading"
                            />
                            <label class="ml-2 text-sm text-gray-700 dark:text-slate-300">
                                我已仔细核对金额，确认已准备好 <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ parseFloat(currentOrder.crypto_amount).toFixed(4) }} {{ currentOrder.currency_label || currentOrder.currency_key || currentOrder.crypto_currency }}</span> 用于交易
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 按钮区域 -->
            <div class="flex flex-row justify-end px-4 py-3 sm:px-5 sm:py-3 bg-gray-100 dark:bg-slate-800 text-end gap-2">
                <P2PButton
                    variant="ghost"
                    @click="handleDialogCancel"
                    :disabled="dialogState.loading"
                >
                    返回
                </P2PButton>
                <P2PButton
                    variant="primary"
                    :loading="dialogState.loading"
                    :disabled="!sellerTxHash || !sellerTxHash.trim() || !sellerConfirmChecked"
                    @click="handleSellerPaidConfirm"
                >
                    确认已转币
                </P2PButton>
            </div>
        </Modal>

        <!-- 买家付款专用对话框（含2FA验证） -->
        <Modal
            :show="dialogState.pendingAction === 'mark_buyer_paid' && dialogState.show"
            max-width="md"
            :closeable="true"
            @close="handleDialogCancel"
        >
            <div class="bg-white dark:bg-slate-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- 图标居中 -->
                <div class="mx-auto flex items-center justify-center size-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                    <svg class="size-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- 标题和内容 -->
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-slate-100">
                        标记已付款
                    </h3>

                    <div class="mt-4 space-y-3 text-left">
                            <!-- 主要提示信息 -->
                            <p class="text-sm text-gray-600 dark:text-slate-400">
                                请确认您已向卖家付款 <span class="font-semibold text-emerald-600 dark:text-emerald-400">¥{{ parseFloat(order.fiat_amount).toFixed(2) }}</span>
                            </p>

                            <!-- 付款说明输入 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                    付款说明（可选）
                                </label>
                                <textarea
                                    v-model="buyerPaymentProof"
                                    rows="3"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="请输入付款凭证或说明，如转账单号..."
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
                            <div v-else class="p-2.5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-md">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="text-sm">
                                        <p class="font-medium text-amber-800 dark:text-amber-300">需要双重验证</p>
                                        <p class="text-amber-700 dark:text-amber-400 mt-0.5">为保护您的资金安全，买家付款必须启用双重验证</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 警告提示 -->
                            <div v-if="userHas2FA" class="p-2.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="text-sm">
                                        <p class="font-medium text-red-800 dark:text-red-300">注意事项</p>
                                        <p class="text-red-700 dark:text-red-400 mt-0.5">请确保已完成付款再点击确认，虚假付款将导致账户封禁</p>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 dark:bg-slate-800 text-end gap-3">
                <P2PButton
                    variant="ghost"
                    @click="handleDialogCancel"
                    :disabled="dialogState.loading"
                >
                    返回
                </P2PButton>
                <P2PButton
                    v-if="userHas2FA"
                    variant="primary"
                    :loading="dialogState.loading"
                    :disabled="!dialogState.twoFactorCode || dialogState.twoFactorCode.length !== 6"
                    @click="handleBuyerPaymentConfirm"
                >
                    确认已付款
                </P2PButton>
                <P2PButton
                    v-else
                    variant="warning"
                    @click="() => router.visit('/user/profile')"
                >
                    前往启用双重验证
                </P2PButton>
            </div>
        </Modal>

        <!-- 卖家确认收款专用对话框（含2FA验证） -->
        <Modal
            :show="dialogState.pendingAction === 'mark_seller_received' && dialogState.show"
            max-width="md"
            :closeable="true"
            @close="handleDialogCancel"
        >
            <div class="bg-white dark:bg-slate-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- 图标居中 -->
                <div class="mx-auto flex items-center justify-center size-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                    <svg class="size-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- 标题和内容 -->
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-slate-100">
                        确认收款
                    </h3>

                    <div class="mt-4 space-y-3 text-left">
                        <!-- 主要提示信息 -->
                        <p class="text-sm text-gray-600 dark:text-slate-400">
                            请确认您已收到买家付款 <span class="font-semibold text-emerald-600 dark:text-emerald-400">¥{{ parseFloat(order.fiat_amount).toFixed(2) }}</span>
                        </p>

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
                        <div v-else class="p-2.5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-md">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div class="text-sm">
                                    <p class="font-medium text-amber-800 dark:text-amber-300">需要双重验证</p>
                                    <p class="text-amber-700 dark:text-amber-400 mt-0.5">为保护您的资金安全，卖家确认收款必须启用双重验证</p>
                                </div>
                            </div>
                        </div>

                        <!-- 警告提示 -->
                        <div v-if="userHas2FA" class="p-2.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <div class="text-sm">
                                    <p class="font-medium text-red-800 dark:text-red-300">重要提醒</p>
                                    <p class="text-red-700 dark:text-red-400 mt-0.5">确认收款后，托管的加密货币将立即释放给买家，此操作不可撤销</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 dark:bg-slate-800 text-end gap-3">
                <P2PButton
                    variant="ghost"
                    @click="handleDialogCancel"
                    :disabled="dialogState.loading"
                >
                    返回
                </P2PButton>
                <P2PButton
                    v-if="userHas2FA"
                    variant="success"
                    :loading="dialogState.loading"
                    :disabled="!dialogState.twoFactorCode || dialogState.twoFactorCode.length !== 6"
                    @click="handleSellerReceivedConfirm"
                >
                    确认收款
                </P2PButton>
                <P2PButton
                    v-else
                    variant="warning"
                    @click="() => router.visit('/user/profile')"
                >
                    前往启用双重验证
                </P2PButton>
            </div>
        </Modal>
    </P2PAppLayout>
    
    <!-- 评价弹窗 -->
    <ReviewModal
        v-model="showReviewModal"
        :order-id="currentOrder.id"
        :order-no="currentOrder.order_no"
        :vendor-name="currentOrder.vendor?.name || '商家'"
        :fiat-amount="currentOrder.fiat_amount"
        :fiat-currency="currentOrder.fiat_currency"
        :crypto-amount="currentOrder.crypto_amount"
        :crypto-currency="currentOrder.currency_key || currentOrder.crypto_currency"
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
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import Modal from '@/Components/Modal.vue';
import ReviewModal from '@/Components/Trade/ReviewModal.vue';
import axios from 'axios';
import { router, Link, usePage } from '@inertiajs/vue3';
import MessageDialog from '@/Components/MessageDialog';
import { useConfig } from '@/Composables/useConfig';

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

// 响应式订单状态（用于实时更新）
const currentOrder = ref({ ...props.order });


// 聊天相关
const messages = ref(props.messages);
const newMessage = ref('');
const messageContainer = ref(null);
const selectedFile = ref(null);
const filePreviewUrl = ref(null);
const fileInput = ref(null);
const isUploading = ref(false);
const mobileMessageContainer = ref(null);
const isConnected = ref(false);
let echo = null;

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
const sellerConfirmChecked = ref(false);

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
        'order_initiated': { title: '等待确认', subtitle: '等待商家确认订单' },
        'vendor_confirmed': { title: '等待转币', subtitle: '等待卖家转币到托管' },
        'seller_paid': { title: '正在确认', subtitle: '等待托管确认到账' },
        'escrow_received': { title: '托管保护中', subtitle: '平台已锁定卖家资产' },
        'buyer_paid': { title: '等待确认', subtitle: '等待卖家确认收款' },
        'seller_received': { title: '即将释放', subtitle: '准备释放托管资产' },
        'escrow_released': { title: '交易完成', subtitle: '托管已释放给买家' }
    };
    return statusMap[status] || { title: '处理中', subtitle: '请稍候...' };
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

// 计算应该显示哪些按钮（基于状态）
const potentialActions = computed(() => {
    const actions = [];
    const userId = props.currentUserId;
    const order = currentOrder.value;

    // 根据escrow_status确定应该显示哪些按钮
    switch(order.escrow_status) {
        case 'order_initiated':
            if (order.vendor_id === userId) {
                actions.push('vendor_confirm');
            }
            actions.push('cancel');
            break;

        case 'vendor_confirmed':
            if (order.seller_id === userId) {
                actions.push('mark_seller_paid');
            }
            actions.push('cancel');
            break;

        case 'seller_paid':
            if (order.buyer_id === userId) {
                actions.push('cancel');
            }
            break;

        case 'escrow_received':
            if (order.buyer_id === userId) {
                actions.push('confirm_escrow');
                actions.push('cancel');
            }
            break;

        case 'buyer_confirmed_escrow':
            if (order.buyer_id === userId) {
                actions.push('mark_buyer_paid');
                actions.push('cancel');
            }
            break;

        case 'buyer_paid':
            if (order.seller_id === userId) {
                actions.push('mark_seller_received');
            }
            break;
    }

    // 争议按钮的逻辑 - 检查订单状态是否为 trading（活跃状态）
    if (order.status === 'trading') {
        // 使用 == 进行比较，自动处理类型转换（字符串和数字）
        if (order.is_disputed && order.disputed_by == userId) {
            actions.push('resolve_dispute');
        } else if (!order.is_disputed) {
            actions.push('create_dispute');
        }
    }

    return actions;
});

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
        minute: '2-digit'
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
    
    MessageDialog.success('感谢您的评价！');
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

// 处理文件选择
const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // 检查文件大小 (10MB)
    if (file.size > 10 * 1024 * 1024) {
        alert('文件大小不能超过10MB');
        event.target.value = '';
        return;
    }

    selectedFile.value = file;

    // 创建预览URL
    if (file.type.startsWith('image/')) {
        filePreviewUrl.value = URL.createObjectURL(file);
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

        // 如果有文件，先上传文件
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
    sellerConfirmChecked.value = false;
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
        router.visit('/user/profile');
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
                router.visit('/user/profile');
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
    
    if (!sellerConfirmChecked.value) {
        MessageDialog.error('请确认已核对地址和金额');
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
            sellerConfirmChecked.value = false;
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
        router.visit('/user/profile');
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
                router.visit('/user/profile');
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

// 模拟托管状态（测试用）
const mockEscrowStatus = async (status) => {
    try {
        if (status === 'received') {
            // 调用模拟确认托管到账的API
            const response = await axios.post(`/web/api/orders/${props.order.id}/mock-escrow-received`);

            if (response.data.success) {
                // 发送系统消息
                const msgResponse = await axios.post(`/web/api/orders/${props.order.order_no}/chat/send`, {
                    message: '✅ [测试模式] 托管已确认到账！买家现在可以付款了'
                });

                if (msgResponse.data) {
                    messages.value.push({
                        ...msgResponse.data,
                        type: 'system'
                    });
                    scrollToBottom();
                }

                // 延迟刷新页面
                setTimeout(() => {
                    router.reload();
                }, 1000);
            }
        } else if (status === 'not_received') {
            // 调用模拟托管未收到的API
            const response = await axios.post(`/web/api/orders/${props.order.id}/mock-escrow-not-received`);

            if (response.data.success) {
                // 延迟刷新页面以显示状态变化
                setTimeout(() => {
                    router.reload();
                }, 1000);
            }
        }
    } catch (error) {
        console.error('模拟托管状态失败:', error);
        // 错误已由全局axios拦截器处理
    }
};

// 连接WebSocket到私有频道
const connectWebSocket = () => {
    if (typeof window.Echo !== 'undefined') {
        echo = window.Echo;
        
        // 使用单个 Presence Channel 处理在线状态和消息
        echo.join(`order.${currentOrder.value.id}`)
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
                messages.value.push({
                    id: Date.now(),
                    message: notification,
                    type: 'system',
                    user: null,
                    created_at: new Date().toISOString()
                });

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
    } else {
        console.warn('Echo not available, retrying...');
        setTimeout(connectWebSocket, 1000);
    }
};


onMounted(() => {
    initParticipants();
    startCountdown();
    
    scrollToBottom();
    connectWebSocket();
});

onUnmounted(() => {
    if (timer) {
        clearInterval(timer);
    }
    if (echo) {
        // 离开 Presence Channel
        echo.leave(`order.${currentOrder.value.id}`);
    }
});
</script>
