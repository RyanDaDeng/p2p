<template>
    <P2PAppLayout>
        <Head title="我的发布" />

        <!-- 页面头部 -->
        <div class="bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
            <div class="mx-auto max-w-7xl px-4 py-3 sm:py-4 sm:px-6 lg:px-8">
                <!-- 标题和统计 -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-4 mb-3 sm:mb-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-slate-100">我的发布</h1>
                        <p class="hidden sm:block text-sm text-gray-500 dark:text-slate-400 mt-1">管理您的交易广告</p>
                    </div>

                    <!-- 发布统计 -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="text-center">
                            <p class="text-xl sm:text-2xl font-bold text-emerald-600 dark:text-emerald-500">{{ activeOffers }}</p>
                            <p class="text-xs text-gray-500 dark:text-slate-400">活跃广告</p>
                        </div>
                        <div class="h-6 sm:h-8 w-px bg-gray-200 dark:bg-slate-700"></div>
                        <div class="text-center">
                            <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-500">{{ offers.filter(o => o.status === 'paused').length }}</p>
                            <p class="text-xs text-gray-500 dark:text-slate-400">已暂停</p>
                        </div>
                        <div class="h-6 sm:h-8 w-px bg-gray-200 dark:bg-slate-700"></div>
                        <div class="text-center">
                            <p class="text-xl sm:text-2xl font-bold text-gray-600 dark:text-slate-400">{{ totalOffers }}</p>
                            <p class="text-xs text-gray-500 dark:text-slate-400">总广告</p>
                        </div>
                    </div>
                </div>

                <!-- 筛选栏 -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <!-- 筛选器 -->
                    <div class="flex items-center gap-2 order-2 sm:order-1 overflow-x-auto">
                        <!-- 状态筛选 - 类似订单页面的按钮组 -->
                        <div class="flex items-center bg-gray-100 dark:bg-slate-800 rounded-lg p-0.5">
                            <button
                                v-for="status in statusOptions"
                                :key="status.value || 'all'"
                                @click="filterStatus = status.value"
                                :class="[
                                    'px-3 py-1.5 text-xs font-medium rounded-md transition-all whitespace-nowrap cursor-pointer',
                                    filterStatus === status.value
                                        ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-sm'
                                        : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200'
                                ]"
                            >
                                {{ status.label }}
                            </button>
                        </div>

                        <!-- 类型筛选 -->
                        <div class="flex items-center bg-gray-100 dark:bg-slate-800 rounded-lg p-0.5">
                            <button
                                v-for="type in typeOptions"
                                :key="type.value || 'all'"
                                @click="filterType = type.value"
                                :class="[
                                    'px-3 py-1.5 text-xs font-medium rounded-md transition-all whitespace-nowrap cursor-pointer',
                                    filterType === type.value
                                        ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-sm'
                                        : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200'
                                ]"
                            >
                                {{ type.label }}
                            </button>
                        </div>
                    </div>

                    <!-- 操作按钮 -->
                    <div class="flex items-center gap-2 order-1 sm:order-2">
                        <P2PButton
                            @click="showCreateModal = true"
                            variant="primary"
                            size="sm"
                        >
                            <template #icon>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </template>
                            创建发布
                        </P2PButton>

                        <P2PButton
                            @click="showTradeSettings = true"
                            variant="ghost"
                            size="sm"
                            class="hidden sm:inline-flex"
                        >
                            <template #icon>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </template>
                            交易设置
                        </P2PButton>

                        <P2PButton
                            @click="$inertia.visit('/notifications/settings')"
                            variant="ghost"
                            size="sm"
                            class="hidden sm:inline-flex"
                        >
                            <template #icon>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </template>
                            通知设置
                        </P2PButton>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Loading State -->
            <div v-if="loading" class="flex items-center justify-center py-12">
                <svg class="animate-spin h-8 w-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Empty State -->
            <div v-else-if="offers.length === 0" class="text-center py-12 bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-800">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-slate-100">暂无广告</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">点击"创建发布"开始发布您的第一个交易广告</p>
            </div>

            <!-- Desktop View -->
            <div v-if="!loading && offers.length > 0" class="hidden md:block space-y-4">
                <!-- Ad Cards - 使用主页相同布局 -->
                <div v-for="offer in offers" :key="offer?.id"
                     class="bg-white dark:bg-slate-900/60 border border-gray-200 dark:border-slate-800 rounded-lg hover:border-gray-300 dark:hover:border-slate-700 transition-all group shadow-sm dark:shadow-none">
                    <div class="p-4">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- 交易类型与币种 - Col 1-3 -->
                            <div class="col-span-3">
                                <div class="flex flex-col gap-2">
                                    <!-- 币种显示 -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center shadow-sm">
                                            <component :is="getCryptoIcon(offer?.currency)" :size="20" class="dark:brightness-110" />
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-slate-100">
                                                {{ offer?.currency_label || offer?.currency || 'USDT' }}
                                                <span class="text-xs text-gray-500 dark:text-slate-500 ml-2">#{{ offer?.id }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span :class="[
                                                    'text-xs font-medium',
                                                    offer?.type === 'sell' ? 'text-blue-600 dark:text-blue-400' : 'text-emerald-600 dark:text-emerald-400'
                                                ]">
                                                    {{ offer?.type === 'sell' ? '商家收购' : '商家出售' }}
                                                </span>
                                                <span v-if="offer?.status === 'active'" class="h-1.5 w-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 支付方式与备注 - Col 4-7 -->
                            <div class="col-span-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-start gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 bg-blue-500/10 border border-blue-500/20 dark:border-blue-500/30 rounded text-xs font-medium text-blue-600 dark:text-blue-400">
                                            {{ getPaymentMethodDetails(offer?.payment_method)?.label || offer?.payment_method }}
                                        </span>
                                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded text-xs text-gray-700 dark:text-slate-400">
                                            📍 {{ getCountryName(offer?.country) }}
                                        </span>
                                    </div>
                                    <div v-if="offer?.notes" class="text-xs text-gray-600 dark:text-slate-400 italic line-clamp-2">
                                        {{ offer?.notes }}
                                    </div>
                                </div>
                            </div>

                            <!-- 资产与价格 - Col 8-9 -->
                            <div class="col-span-2 text-right">
                                <p v-if="offer?.price_model === 'fixed'" class="text-xl lg:text-2xl text-emerald-500 font-bold leading-none">
                                    ¥{{ offer?.price?.toLocaleString() || '0' }}
                                </p>
                                <p v-else class="text-xl lg:text-2xl text-emerald-500 font-bold leading-none">
                                    {{ offer?.margin > 0 ? '+' : '' }}{{ offer?.margin || 0 }}%
                                </p>
                                <div class="text-xs mt-0.5 text-gray-500 dark:text-slate-400">
                                    {{ offer?.price_model === 'fixed' ? '固定单价' : '动态单价' }} · {{ offer?.fiat_currency || 'CNY' }}
                                </div>
                            </div>

                            <!-- 操作按钮与限额 - Col 10-12 -->
                            <div class="col-span-3">
                                <div class="text-right">
                                    <div class="text-sm mb-2">
                                        <span class="text-xs text-gray-500 dark:text-slate-500">限额:</span>
                                        <span class="text-emerald-500 font-medium ml-1">
                                            ¥{{ offer?.minLimit?.toLocaleString() || '0' }} - ¥{{ offer?.maxLimit?.toLocaleString() || '0' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-end gap-1">
                                        <button
                                            v-if="offer?.status === 'active'"
                                            @click="toggleOfferStatus(offer?.id, 'pause')"
                                            class="px-3 py-1.5 text-sm font-medium text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors cursor-pointer"
                                        >
                                            暂停
                                        </button>
                                        <button
                                            v-else-if="offer?.status === 'paused'"
                                            @click="toggleOfferStatus(offer?.id, 'activate')"
                                            class="px-3 py-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors cursor-pointer"
                                        >
                                            激活
                                        </button>
                                        <button
                                            v-else-if="offer?.status === 'archived'"
                                            @click="restoreOffer(offer?.id)"
                                            class="px-3 py-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors cursor-pointer"
                                        >
                                            恢复
                                        </button>
                                        <button
                                            v-if="offer?.status !== 'archived'"
                                            @click="editOffer(offer)"
                                            class="px-3 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors cursor-pointer"
                                        >
                                            编辑
                                        </button>
                                        <button
                                            v-if="offer?.status !== 'archived'"
                                            @click="archiveOffer(offer?.id)"
                                            class="px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900/30 transition-colors cursor-pointer"
                                        >
                                            归档
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Cards -->
            <div v-if="!loading && offers.length > 0" class="md:hidden space-y-3">
                <div v-for="offer in offers" :key="offer?.id"
                     class="bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-800 overflow-hidden">
                    <div class="p-3">
                        <!-- 头部信息 -->
                        <div class="flex items-center justify-between mb-3">
                            <!-- 币种与交易类型 -->
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center shadow-sm">
                                    <component :is="getCryptoIcon(offer?.currency)" :size="18" class="dark:brightness-110" />
                                </div>
                                <div>
                                    <div class="font-medium text-sm text-gray-900 dark:text-slate-100">
                                        {{ offer?.currency_label || offer?.currency || 'USDT' }}
                                        <span class="text-xs text-gray-500 dark:text-slate-500 ml-1">#{{ offer?.id }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span :class="[
                                            'text-xs font-medium',
                                            offer?.type === 'sell' ? 'text-blue-600 dark:text-blue-400' : 'text-emerald-600 dark:text-emerald-400'
                                        ]">
                                            {{ offer?.type === 'sell' ? '商家收购' : '商家出售' }}
                                        </span>
                                        <span v-if="offer?.status === 'active'" class="h-1.5 w-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- 价格信息 -->
                            <div class="text-right">
                                <div v-if="offer?.price_model === 'fixed'" class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                    ¥{{ (offer?.price || 0).toLocaleString() }}
                                </div>
                                <div v-else class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ offer?.margin > 0 ? '+' : '' }}{{ offer?.margin || 0 }}%
                                </div>
                                <div class="text-xs text-gray-500 dark:text-slate-400">
                                    {{ offer?.price_model === 'fixed' ? '固定' : '浮动' }}
                                </div>
                            </div>
                        </div>

                        <!-- 限额和支付方式一行 -->
                        <div class="mb-2 text-xs">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-gray-600 dark:text-slate-400">
                                    限额: ¥{{ (offer?.minLimit || 0).toLocaleString() }}-{{ (offer?.maxLimit || 0).toLocaleString() }}
                                </span>
                                <div class="flex gap-1">
                                    <span class="px-1.5 py-0.5 bg-blue-500/10 border border-blue-500/20 dark:border-blue-500/30 rounded text-blue-600 dark:text-blue-400">
                                        {{ getPaymentMethodDetails(offer?.payment_method)?.label || offer?.payment_method }}
                                    </span>
                                    <span class="px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded text-gray-700 dark:text-slate-400">
                                        📍 {{ getCountryName(offer?.country) }}
                                    </span>
                                </div>
                            </div>
                            <div v-if="offer?.notes" class="text-gray-600 dark:text-slate-400 italic line-clamp-2">
                                {{ offer?.notes }}
                            </div>
                        </div>

                        <!-- 操作按钮 -->
                        <div class="flex items-center gap-2 justify-end">
                            <button
                                v-if="offer?.status === 'active'"
                                @click="toggleOfferStatus(offer?.id, 'pause')"
                                class="px-2 py-1 text-xs font-medium text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 rounded hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors cursor-pointer">
                                暂停
                            </button>
                            <button
                                v-else-if="offer?.status === 'paused'"
                                @click="toggleOfferStatus(offer?.id, 'activate')"
                                class="px-2 py-1 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 rounded hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors cursor-pointer">
                                激活
                            </button>
                            <button
                                v-else-if="offer?.status === 'archived'"
                                @click="restoreOffer(offer?.id)"
                                class="px-2 py-1 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 rounded hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors cursor-pointer">
                                恢复
                            </button>
                            <button
                                v-if="offer?.status !== 'archived'"
                                @click="editOffer(offer)"
                                class="px-2 py-1 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors cursor-pointer">
                                编辑
                            </button>
                            <button
                                v-if="offer?.status !== 'archived'"
                                @click="archiveOffer(offer?.id)"
                                class="px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 rounded hover:bg-gray-100 dark:hover:bg-gray-900/30 transition-colors cursor-pointer">
                                归档
                            </button>
                        </div>
                    </div>

                    <!-- 状态栏 -->
                    <div class="px-3 py-1 bg-gray-50 dark:bg-slate-800/30 border-t border-gray-200 dark:border-slate-800">
                        <div class="flex items-center justify-between text-xs">
                            <span :class="[
                                'px-1.5 py-0.5 font-medium rounded',
                                offer?.status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' :
                                offer?.status === 'paused' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' :
                                offer?.status === 'archived' ? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400' :
                                'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-400'
                            ]">
                                {{ offer?.status === 'active' ? '● 活跃' : offer?.status === 'paused' ? '● 已暂停' : offer?.status === 'archived' ? '● 已归档' : '● 未知' }}
                            </span>
                            <span class="text-gray-500 dark:text-slate-500">
                                {{ offer?.createdAt }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="!loading" class="mt-6">
                <P2PPagination
                    :current-page="currentPage"
                    :total="totalOffers"
                    :per-page="perPage"
                    @update:current-page="currentPage = $event"
                />
            </div>
        </div>

        <!-- Create/Edit Offer Modal -->
        <CreateOfferModal
            v-model="showCreateModal"
            :offer="editingOffer"
            :trade-settings="tradeSettings"
            :user-addresses="props.userAddresses"
            :payment-methods="paymentMethods"
            @success="handleOfferCreated"
        />

        <TradeSettingsModal
            v-model="showTradeSettings"
            @saved="handleSettingsSaved"
        />


        <!-- Archive Confirmation Dialog -->
        <P2PConfirmationBox
            :show="showArchiveConfirm"
            title="归档广告"
            confirm-text="确认归档"
            cancel-text="取消"
            :danger="false"
            @confirm="confirmArchive"
            @cancel="cancelArchive"
            @close="cancelArchive"
        >
            <p>确定要归档这个广告吗？</p>
            <p class="mt-2 text-xs">归档后的广告将不再显示在列表中，但您的数据会被保留。</p>
        </P2PConfirmationBox>

        <!-- Status Change Confirmation Dialog -->
        <P2PConfirmationBox
            :show="showStatusConfirm"
            :title="statusChangeAction === 'pause' ? '暂停广告' : '激活广告'"
            :confirm-text="statusChangeAction === 'pause' ? '确认暂停' : '确认激活'"
            cancel-text="取消"
            :danger="false"
            :confirm-variant="statusChangeAction === 'pause' ? 'warning' : 'primary'"
            @confirm="confirmStatusChange"
            @cancel="cancelStatusChange"
            @close="cancelStatusChange"
        >
            <p v-if="statusChangeAction === 'pause'">
                确定要暂停这个广告吗？暂停后其他用户将无法看到这个广告。
            </p>
            <p v-else>
                确定要激活这个广告吗？激活后其他用户将可以看到并与您进行交易。
            </p>
        </P2PConfirmationBox>
    </P2PAppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PToggle from '@/Components/UI/P2PToggle.vue';
import P2PPagination from '@/Components/UI/P2PPagination.vue';
import P2PConfirmationBox from '@/Components/UI/P2PConfirmationBox.vue';
import CreateOfferModal from '@/Components/Publish/CreateOfferModal.vue';
import TradeSettingsModal from '@/Components/Publish/TradeSettingsModal.vue';
import { getPaymentMethodDetails } from '@/Constants/paymentMethods';
import { useConfig } from '@/Composables/useConfig';
import { getCryptoIcon } from '@/Utils/crypto';

const page = usePage();

const props = defineProps({
    userAddresses: {
        type: Array,
        default: () => []
    }
});

// Stats
const activeOffers = ref(0);
const completionRate = ref(98);
const totalTrades = ref(245);
const avgResponse = ref(5); // 平均响应时间

// Filters
const filterStatus = ref('');
const filterType = ref('');

// Modals
const showCreateModal = ref(false);
const showTradeSettings = ref(false);
const editingOffer = ref(null);
const loading = ref(false);

// Archive confirmation
const showArchiveConfirm = ref(false);
const archivingOfferId = ref(null);

// Status change confirmation
const showStatusConfirm = ref(false);
const statusChangeAction = ref(null);
const statusChangeOfferId = ref(null);

// Trade Settings
const tradeSettings = ref({
    auto_reply_message: '',
    min_trade_limit: 100,
    max_trade_limit: 10000,
    verification_required: false,
    preferred_time_zone: 'Asia/Shanghai',
    trading_hours_enabled: false,
    trading_hours_start: '09:00',
    trading_hours_end: '22:00'
});

// Filter options
const statusOptions = [
    { value: '', label: '全部' },
    { value: 'active', label: '活跃' },
    { value: 'paused', label: '已暂停' },
    { value: 'archived', label: '已归档' }
];

const typeOptions = [
    { value: '', label: '全部' },
    { value: 'buy', label: '商家出售' },
    { value: 'sell', label: '商家收购' }
];

// Sample offers data
const offers = ref([]);
const totalOffers = ref(0);
const currentPage = ref(1);
const perPage = ref(10);

// Fetch ads from API
const fetchAds = async () => {
    loading.value = true;
    try {
        // 构建参数，过滤掉空值
        const params = {
            per_page: perPage.value,
            page: currentPage.value
        };

        // 只有非空值才添加到参数中
        if (filterStatus.value && filterStatus.value !== '') {
            params.status = filterStatus.value;
        }

        if (filterType.value && filterType.value !== '') {
            params.type = filterType.value;
        }

        console.log('Fetching ads with params:', params);

        const response = await axios.get('/web/api/ads/my', { params });

        console.log('API Response:', response.data.data);
        offers.value = (response.data.data.ads || []).filter(ad => ad).map(ad => {
            return {
                id: ad.id,
                type: ad.trade_type,
                trade_type: ad.trade_type,
                currency: ad.currency_key,
                currency_key: ad.currency_key,
                currency_label: ad.currency_label,
                chain: ad.chain,
                price: ad.price || 0,
                price_model: ad.price_model,
                priceType: ad.price_model === 'fixed' ? '固定' : '浮动',
                margin: ad.margin,
                minLimit: ad.min_limit || 0,
                min_limit: ad.min_limit || 0,
                maxLimit: ad.max_limit || 0,
                max_limit: ad.max_limit || 0,
                payment_method: ad.payment_method,
                payment_details: ad.payment_details,
                payment_time_limit: ad.payment_time_limit,
                paymentMethods: [ad.payment_method],
                status: ad.status === 'inactive' ? 'paused' : ad.status === 'archived' ? 'archived' : ad.status,
                fiat_currency: ad.fiat_currency,
                country: ad.country,
                vendor_toc: ad.vendor_toc,
                welcome_message: ad.welcome_message,
                notes: ad.notes,
                createdAt: new Date(ad.created_at).toLocaleDateString('zh-CN')
            };
        });

        console.log('Processed offers:', offers.value);

        // Update statistics
        activeOffers.value = offers.value.filter(o => o.status === 'active').length;
        totalTrades.value = response.data.data.ads.reduce((sum, ad) => sum + ad.trades_count, 0);

        // Update pagination
        totalOffers.value = response.data.data.pagination.total;

    } catch (error) {
        // 错误通知会通过 axios 拦截器自动显示
        console.error('Error fetching ads:', error);
        offers.value = []; // Ensure offers is an empty array on error
    } finally {
        loading.value = false;
    }
};

// Edit offer
const editOffer = (offer) => {
    editingOffer.value = offer;
    showCreateModal.value = true;
};

// Toggle offer status
const toggleOfferStatus = (offerId, action) => {
    statusChangeOfferId.value = offerId;
    statusChangeAction.value = action;
    showStatusConfirm.value = true;
};

const confirmStatusChange = async () => {
    try {
        const status = statusChangeAction.value === 'pause' ? 'inactive' : 'active';
        await axios.post(`/web/api/ads/${statusChangeOfferId.value}/toggle-status`, { status });
        // 通知会通过 axios 拦截器自动显示
        await fetchAds();
    } catch (error) {
        // 错误通知会通过 axios 拦截器自动显示
        console.error('Error toggling offer status:', error);
    }

    showStatusConfirm.value = false;
    statusChangeOfferId.value = null;
    statusChangeAction.value = null;
};

const cancelStatusChange = () => {
    showStatusConfirm.value = false;
    statusChangeOfferId.value = null;
    statusChangeAction.value = null;
};

// Archive offer
const archiveOffer = (offerId) => {
    archivingOfferId.value = offerId;
    showArchiveConfirm.value = true;
};

const confirmArchive = async () => {
    try {
        await axios.post(`/web/api/ads/${archivingOfferId.value}/archive`);
        // 通知会通过 axios 拦截器自动显示
        await fetchAds();
    } catch (error) {
        // 错误通知会通过 axios 拦截器自动显示
        console.error('Error archiving offer:', error);
    }

    showArchiveConfirm.value = false;
    archivingOfferId.value = null;
};

const cancelArchive = () => {
    showArchiveConfirm.value = false;
    archivingOfferId.value = null;
};

// Restore offer
const restoreOffer = async (offerId) => {
    try {
        await axios.post(`/web/api/ads/${offerId}/restore`);
        // 通知会通过 axios 拦截器自动显示
        await fetchAds();
    } catch (error) {
        // 错误通知会通过 axios 拦截器自动显示
        console.error('Error restoring offer:', error);
    }
};

// Handle offer created
const handleOfferCreated = async () => {
    showCreateModal.value = false;
    editingOffer.value = null;
    await fetchAds();
};

// Handle settings saved
const handleSettingsSaved = (settings) => {
    tradeSettings.value = settings;
    // 保存成功的通知由组件内部处理
};



// Watch filters
watch([filterStatus, filterType], () => {
    currentPage.value = 1;
    fetchAds();
});

// Use config composable
const { fetchConfig, getCountryByCode, paymentMethods } = useConfig();

// Helper function to get country name from code
const getCountryName = (code) => {
    if (!code) return '全球';
    const country = getCountryByCode(code);
    return country ? country.name : code;
};


// Load ads and config on mount
onMounted(async () => {
    await fetchConfig();
    fetchAds();
});
</script>
