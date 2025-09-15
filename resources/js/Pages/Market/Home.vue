<template>
    <P2PAppLayout>
        <!-- 信任与安全保障头部 -->
        <div class="bg-gradient-to-b from-gray-100 to-gray-50 dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
                <!-- 安全横幅 + Crypto Price Bar 合并 -->
                <div class="bg-white/70 dark:bg-gray-900/30 rounded-lg p-1 sm:p-1.5 mb-3">
                    <div v-if="cryptoPrices.loading" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-1 min-h-[1.5rem]">
                        <!-- 左侧：安全标识 (不需要skeleton，显示实际内容) -->
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                            <div class="flex items-center gap-1.5">
                                <div class="p-1 bg-emerald-500/10 rounded">
                                    <svg class="w-3.5 sm:w-4 h-3.5 sm:h-4 text-emerald-600 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">安全托管</span>
                            </div>
                            <div class="hidden sm:block h-4 w-px bg-gray-300 dark:bg-gray-700"></div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">实名认证</span>
                            </div>
                        </div>

                        <!-- 右侧：价格显示 skeleton -->
                        <div class="relative flex-1 sm:flex-initial overflow-hidden">
                            <!-- 移动端skeleton -->
                            <div class="sm:hidden overflow-x-auto scrollbar-hide">
                                <div class="flex items-center gap-3 min-w-max pr-2">
                                    <div class="flex items-center gap-1 whitespace-nowrap">
                                        <div class="w-6 h-3.5 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                        <div class="w-8 h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                    </div>
                                    <div class="flex items-center gap-1 whitespace-nowrap">
                                        <div class="w-5 h-3.5 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                        <div class="w-10 h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                    </div>
                                    <div class="flex items-center gap-1 whitespace-nowrap">
                                        <div class="w-5 h-3.5 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                        <div class="w-9 h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- 桌面端skeleton -->
                            <div class="hidden sm:flex items-center gap-3 md:gap-4">
                                <div class="flex items-center gap-1">
                                    <div class="w-12 h-3 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                    <div class="w-10 h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-10 h-3 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                    <div class="w-12 h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-10 h-3 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                    <div class="w-11 h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-1 min-h-[1.5rem]">
                        <!-- 左侧：安全标识 -->
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                            <div class="flex items-center gap-1.5">
                                <div class="p-1 bg-emerald-500/10 rounded">
                                    <svg class="w-3.5 sm:w-4 h-3.5 sm:h-4 text-emerald-600 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">安全托管</span>
                            </div>
                            <div class="hidden sm:block h-4 w-px bg-gray-300 dark:bg-gray-700"></div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">实名认证</span>
                            </div>
                        </div>

                        <!-- 右侧：价格显示 - 移动端使用横向滚动容器 -->
                        <div class="relative flex-1 sm:flex-initial overflow-hidden">
                            <!-- 移动端：可横向滚动的价格容器 -->
                            <div class="sm:hidden overflow-x-auto scrollbar-hide">
                                <div class="flex items-center gap-3 min-w-max pr-2">
                                    <div class="flex items-center gap-1 whitespace-nowrap">
                                        <span class="text-[10px] text-gray-500 dark:text-gray-500">USDT</span>
                                        <span class="font-semibold text-xs text-gray-700 dark:text-gray-300">
                                            {{ formatCryptoPrice('USDT', selectedFiatCurrency || 'USD') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1 whitespace-nowrap">
                                        <span class="text-[10px] text-gray-500 dark:text-gray-500">BTC</span>
                                        <span class="font-semibold text-xs text-gray-700 dark:text-gray-300">
                                            {{ formatCryptoPrice('BTC', selectedFiatCurrency || 'USD') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1 whitespace-nowrap">
                                        <span class="text-[10px] text-gray-500 dark:text-gray-500">ETH</span>
                                        <span class="font-semibold text-xs text-gray-600 dark:text-gray-400">
                                            {{ formatCryptoPrice('ETH', selectedFiatCurrency || 'USD') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- 桌面端：正常显示 -->
                            <div class="hidden sm:flex items-center gap-3 md:gap-4">
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-gray-600 dark:text-gray-400">USDT·{{ selectedFiatCurrency || 'USD' }}</span>
                                    <span class="font-semibold text-sm text-gray-700 dark:text-gray-300">
                                        {{ formatCryptoPrice('USDT', selectedFiatCurrency || 'USD') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-gray-600 dark:text-gray-400">BTC·{{ selectedFiatCurrency || 'USD' }}</span>
                                    <span class="font-semibold text-sm text-gray-700 dark:text-gray-300">
                                        {{ formatCryptoPrice('BTC', selectedFiatCurrency || 'USD') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-gray-600 dark:text-gray-400">ETH·{{ selectedFiatCurrency || 'USD' }}</span>
                                    <span class="font-semibold text-sm text-gray-700 dark:text-gray-300">
                                        {{ formatCryptoPrice('ETH', selectedFiatCurrency || 'USD') }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- 专业筛选栏 -->
                <!-- 桌面端一行显示 -->
                <div class="hidden sm:flex flex-wrap items-center gap-1.5">
                    <!-- 买卖切换 -->
                    <div class="flex items-center bg-white/80 dark:bg-gray-900/50 rounded p-1 border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-600 dark:text-gray-400 px-2">我想要</span>
                        <P2PButton
                            @click="filters.tradeType = 'buy'"
                            :variant="filters.tradeType === 'buy' ? 'primary' : 'ghost'"
                            size="sm"
                        >
                            购买
                        </P2PButton>
                        <P2PButton
                            @click="filters.tradeType = 'sell'"
                            :variant="filters.tradeType === 'sell' ? 'blue' : 'ghost'"
                            size="sm"
                        >
                            出售
                        </P2PButton>
                    </div>

                    <!-- 筛选项 -->
                    <P2PSelect
                        v-model="filters.cryptocurrency"
                        :options="cryptocurrencyOptions"
                        placeholder="选择币种"
                        size="md"
                        class="w-40"
                    >
                        <!-- Custom selected option display -->
                        <template #selected="{ option }">
                            <component
                                v-if="option && getCryptoIcon(option.value)"
                                :is="getCryptoIcon(option.value)"
                                :size="16"
                                class="mr-2 flex-shrink-0"
                            />
                            <span class="truncate text-gray-900 dark:text-slate-100">
                                {{ option?.label }}
                            </span>
                        </template>
                        <!-- Custom option display in dropdown -->
                        <template #option="{ option }">
                            <component
                                v-if="getCryptoIcon(option.value)"
                                :is="getCryptoIcon(option.value)"
                                :size="16"
                                class="mr-2 flex-shrink-0"
                            />
                            <span class="truncate text-gray-700 dark:text-slate-300">
                                {{ option.label }}
                            </span>
                        </template>
                    </P2PSelect>

                    <div class="min-w-[160px] max-w-[200px]">
                        <P2PPaymentMethodPicker
                            v-model="filters.paymentMethod"
                            placeholder="选择支付"
                            :categories="paymentMethods"
                        />
                    </div>

                    <P2PSelect
                        v-model="selectedFiatCurrency"
                        :options="fiatCurrencyOptions"
                        placeholder="选择法币"
                        size="md"
                        class="w-32"
                    />

                    <P2PSelect
                        v-model="filters.sortBy"
                        :options="sortOptions"
                        placeholder="排序方式"
                        :default-value="'recent'"
                        size="md"
                        class="w-36"
                    />
                </div>

                <!-- 移动端分两行显示 -->
                <div class="sm:hidden space-y-2">
                    <!-- 第一行：买卖切换和只看在线 -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center bg-white/80 dark:bg-gray-900/50 rounded p-1 border border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-600 dark:text-gray-400 px-2">我想要</span>
                            <P2PButton
                                @click="filters.tradeType = 'buy'"
                                :variant="filters.tradeType === 'buy' ? 'primary' : 'ghost'"
                                size="sm"
                            >
                                购买
                            </P2PButton>
                            <P2PButton
                                @click="filters.tradeType = 'sell'"
                                :variant="filters.tradeType === 'sell' ? 'blue' : 'ghost'"
                                size="sm"
                            >
                                出售
                            </P2PButton>
                        </div>

                    </div>

                    <!-- 第二行：筛选项 - 网格布局 -->
                    <div class="grid grid-cols-2 gap-2">
                        <P2PSelect
                            v-model="filters.cryptocurrency"
                            :options="cryptocurrencyOptions"
                            placeholder="选择币种"
                            size="md"
                            class="w-full"
                        >
                            <!-- Custom selected option display -->
                            <template #selected="{ option }">
                                <component
                                    v-if="option && getCryptoIcon(option.value)"
                                    :is="getCryptoIcon(option.value)"
                                    :size="16"
                                    class="mr-2 flex-shrink-0"
                                />
                                <span class="truncate text-gray-900 dark:text-slate-100">
                                    {{ option?.label }}
                                </span>
                            </template>
                            <!-- Custom option display in dropdown -->
                            <template #option="{ option }">
                                <component
                                    v-if="getCryptoIcon(option.value)"
                                    :is="getCryptoIcon(option.value)"
                                    :size="16"
                                    class="mr-2 flex-shrink-0"
                                />
                                <span class="truncate text-gray-700 dark:text-slate-300">
                                    {{ option.label }}
                                </span>
                            </template>
                        </P2PSelect>

                        <P2PPaymentMethodPicker
                            v-model="filters.paymentMethod"
                            placeholder="选择支付"
                            :categories="paymentMethods"
                        />

                        <P2PSelect
                            v-model="selectedFiatCurrency"
                            :options="fiatCurrencyOptions"
                            placeholder="选择法币"
                            size="md"
                            class="w-full"
                        />

                        <P2PSelect
                            v-model="filters.sortBy"
                            :options="sortOptions"
                            placeholder="排序方式"
                            :default-value="'recent'"
                            size="md"
                            class="w-full"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- 主交易区域 -->
        <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
            <div class="mx-auto max-w-7xl px-2 sm:px-4 py-4 lg:px-8">
                <!-- 卡片列表控制栏 -->
                <div class="mb-4">
                    <!-- 认证商家筛选 -->
                    <label class="inline-flex items-center gap-1.5 sm:gap-2 px-3 py-2.5 sm:py-2 bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 cursor-pointer transition-colors">
                        <input
                            type="checkbox"
                            v-model="filters.onlyVerified"
                            class="w-4 h-4 sm:w-4 sm:h-4 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500 dark:focus:ring-amber-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer"
                        />
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap">认证商家</span>
                    </label>
                </div>

                <!-- 加载状态 -->
                <div v-if="loading" class="text-center py-20">
                    <svg class="animate-spin h-10 w-10 text-emerald-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">正在加载广告...</p>
                </div>

                <!-- 错误状态 -->
                <div v-else-if="error" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-red-600 dark:text-red-400">{{ error }}</p>
                    <button @click="fetchAds" class="mt-4 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors cursor-pointer">
                        重新加载
                    </button>
                </div>

                <!-- 空状态 -->
                <div v-else-if="filteredListings.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">暂无广告</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">调整筛选条件或稍后再试</p>
                </div>

                <!-- 桌面版交易卡片 -->
                <div v-else-if="!loading && !error && filteredListings.length > 0" class="hidden md:block space-y-2">
                    <div
                        v-for="listing in paginatedListings"
                        :key="listing.id"
                        class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded hover:border-gray-300 dark:hover:border-gray-700 transition-all"
                    >
                        <div class="p-4">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- 商家身份与信任评分 - Col 1-3 -->
                                <div class="col-span-3">
                                    <div class="flex items-center gap-2">
                                        <Link :href="`/trader/profile/${listing.seller.id || listing.id}`" class="relative flex-shrink-0 cursor-pointer hover:scale-105 transition-transform">
                                            <img
                                                v-if="listing.seller.avatar || listing.seller.profile_photo_url"
                                                :src="listing.seller.avatar || listing.seller.profile_photo_url"
                                                :alt="listing.seller.name"
                                                :class="[
                                                    'h-8 w-8 rounded-full object-cover border',
                                                    isUserOnline(listing.seller.lastSeen)
                                                        ? 'border-emerald-500'
                                                        : 'border-gray-200 dark:border-gray-700'
                                                ]"
                                            />
                                            <div v-else
                                                 :class="[
                                                    'h-8 w-8 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 dark:from-slate-500 dark:to-slate-600 flex items-center justify-center border',
                                                    isUserOnline(listing.seller.lastSeen)
                                                        ? 'border-emerald-500'
                                                        : 'border-gray-200 dark:border-gray-700'
                                                 ]">
                                                <span class="text-white font-medium text-xs">
                                                    {{ listing.seller.name?.[0]?.toUpperCase() || 'U' }}
                                                </span>
                                            </div>
                                        </Link>

                                        <Link :href="`/trader/profile/${listing.seller.id || listing.id}`" class="flex items-center gap-1.5 text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-slate-300 transition-colors cursor-pointer">
                                            {{ listing.seller.name || 'Unknown' }}
                                            <span v-if="listing.country" class="inline-block w-4 h-3" v-html="getCountryFlag(listing.country)"></span>
                                        </Link>

                                        <svg v-if="listing.seller.isVerified" class="w-3.5 h-3.5 text-green-600 dark:text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <div class="mt-1.5 space-y-1">
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                <span class="font-medium">{{ listing.seller.rating }}</span>
                                                <span class="text-gray-400">({{ listing.seller.totalReviews }})</span>
                                            </div>
                                            <div v-if="listing.seller.responseTime" class="flex items-center gap-0.5 text-gray-500 dark:text-gray-400">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span><{{ listing.seller.responseTime }}分钟</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ listing.seller.trades }} 笔交易</span>
                                            <span class="text-gray-400 dark:text-gray-500">{{ formatLastSeen(listing.seller.lastSeen) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- 支付方式与备注 - Col 4-7 -->
                                <div class="col-span-4">
                                    <div class="space-y-2">
                                        <div class="flex items-start gap-2 flex-wrap">
                                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs font-medium text-gray-700 dark:text-gray-300">
                                                {{ getPaymentMethodDetails(listing.paymentMethod)?.label || listing.paymentMethod }}
                                            </span>
                                        </div>
                                        <div v-if="listing.notes" class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                            {{ listing.notes }}
                                        </div>
                                    </div>
                                </div>

                                <!-- 资产与价格 - Col 8-9 -->
                                <div class="col-span-2 text-right flex flex-col justify-center">
                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-500 mb-2">
                                        <span v-if="listing.price_model === 'dynamic'" class="text-gray-600 dark:text-gray-400">
                                            动态单价 · {{ listing.fiat_currency || 'USD' }}
                                        </span>
                                        <span v-else class="text-gray-600 dark:text-gray-400">
                                            固定单价 · {{ listing.fiat_currency || 'USD' }}
                                        </span>
                                    </div>
                                    <p class="text-2xl text-gray-900 dark:text-gray-100 font-bold leading-tight tabular-nums mb-1">
                                        {{ getCurrencySymbol(listing.fiat_currency) }}{{ getDisplayPrice(listing).toLocaleString() }}
                                    </p>
                                    <div v-if="calculateMarketPricePercentage(listing) !== null" class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ calculateMarketPricePercentage(listing) > 0 ? '+' : '' }}{{ Number(calculateMarketPricePercentage(listing) || 0).toFixed(2) }}% 市场价
                                    </div>
                                </div>

                                <!-- 操作按钮与限额 - Col 10-12 -->
                                <div class="col-span-3">
                                    <div class="text-right">
                                        <div class="text-sm mb-2 text-gray-700 dark:text-gray-300 font-medium">
                                            {{ getCurrencySymbol(listing.fiat_currency) }}{{ (listing.limits?.min || 0).toLocaleString() }} - {{ getCurrencySymbol(listing.fiat_currency) }}{{ (listing.limits?.max || 10000).toLocaleString() }}
                                        </div>
                                        <P2PButton
                                            v-if="filters.tradeType === 'buy'"
                                            @click="handleTradeClick(listing, 'buy')"
                                            variant="primary"
                                            size="md"
                                            class="font-medium px-6">
                                            购买 {{ getCryptocurrencyByKey(listing.cryptocurrency)?.label || getCryptoLabel(listing.cryptocurrency) }}
                                        </P2PButton>
                                        <P2PButton
                                            v-else
                                            @click="handleTradeClick(listing, 'sell')"
                                            variant="blue"
                                            size="md"
                                            class="font-medium px-6">
                                            出售 {{ getCryptocurrencyByKey(listing.cryptocurrency)?.label || getCryptoLabel(listing.cryptocurrency) }}
                                        </P2PButton>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 移动版交易卡片 -->
                <div v-if="!loading && !error && filteredListings.length > 0" class="md:hidden space-y-2">
                    <div
                        v-for="listing in paginatedListings"
                        :key="listing.id"
                        class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded hover:border-gray-300 dark:hover:border-gray-700 transition-all"
                    >
                        <div class="p-4">
                            <!-- 商家信息与评分 -->
                            <div class="flex items-center gap-2.5 mb-3">
                                <Link :href="`/trader/profile/${listing.seller.id || listing.id}`" class="relative flex-shrink-0 cursor-pointer hover:scale-105 transition-transform">
                                    <img
                                        v-if="listing.seller.avatar || listing.seller.profile_photo_url"
                                        :src="listing.seller.avatar || listing.seller.profile_photo_url"
                                        :alt="listing.seller.name"
                                        :class="[
                                            'h-8 w-8 rounded-full object-cover border',
                                            isUserOnline(listing.seller.lastSeen)
                                                ? 'border-emerald-500'
                                                : 'border-gray-200 dark:border-gray-700'
                                        ]"
                                    />
                                    <div v-else
                                         :class="[
                                            'h-8 w-8 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 dark:from-slate-500 dark:to-slate-600 flex items-center justify-center border',
                                            isUserOnline(listing.seller.lastSeen)
                                                ? 'border-emerald-500'
                                                : 'border-gray-200 dark:border-gray-700'
                                         ]">
                                        <span class="text-white font-medium text-xs">
                                            {{ listing.seller.name?.[0]?.toUpperCase() || 'U' }}
                                        </span>
                                    </div>
                                </Link>

                                <div class="flex-1">
                                    <Link :href="`/trader/profile/${listing.seller.id || listing.id}`" class="flex items-center gap-1.5 text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-slate-300 transition-colors cursor-pointer">
                                        {{ listing.seller.name || 'Unknown' }}
                                        <span v-if="listing.country" class="inline-block w-4 h-3" v-html="getCountryFlag(listing.country)"></span>
                                        <svg v-if="listing.seller.isVerified" class="w-3.5 h-3.5 text-green-600 dark:text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </Link>

                                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <div class="flex items-center gap-0.5">
                                            <span v-if="isUserOnline(listing.seller.lastSeen)" class="text-emerald-600 dark:text-emerald-500 text-xs">在线</span>
                                            <span v-else class="text-gray-500 dark:text-gray-400 text-xs">{{ formatLastSeen(listing.seller.lastSeen) }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="font-medium">{{ listing.seller.rating }}</span>
                                            <span class="text-gray-400">({{ listing.seller.totalReviews }})</span>
                                        </div>
                                        <div class="flex items-center gap-0.5 text-gray-500 dark:text-gray-400">
                                            <span>{{ listing.seller.trades || 0 }}笔交易</span>
                                        </div>
                                        <div v-if="listing.seller.responseTime" class="flex items-center gap-0.5 text-gray-500 dark:text-gray-400">
                                            <span>{{ listing.seller.responseTime }}分钟</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 支付方式 -->
                            <div class="mb-3">
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="inline-block px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-medium rounded">
                                        {{ getPaymentMethodDetails(listing.paymentMethod)?.label || listing.paymentMethod }}
                                    </span>
                                </div>
                                <div v-if="listing.notes" class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed mt-2">
                                    {{ listing.notes }}
                                </div>
                            </div>

                            <!-- 价格与按钮区域 -->
                            <div class="flex items-end justify-between gap-4">
                                <!-- 价格信息 -->
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500 dark:text-gray-500 mb-1">
                                        <span v-if="listing.price_model === 'dynamic'" class="text-gray-600 dark:text-gray-400">
                                            动态单价 · {{ listing.fiat_currency || 'USD' }}
                                        </span>
                                        <span v-else class="text-gray-600 dark:text-gray-400">
                                            固定单价 · {{ listing.fiat_currency || 'USD' }}
                                        </span>
                                    </div>
                                    <p class="text-xl text-gray-900 dark:text-gray-100 font-bold leading-tight tabular-nums mb-1">
                                        {{ getCurrencySymbol(listing.fiat_currency) }}{{ getDisplayPrice(listing).toLocaleString() }}
                                    </p>
                                    <div v-if="calculateMarketPricePercentage(listing) !== null" class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                        {{ calculateMarketPricePercentage(listing) > 0 ? '+' : '' }}{{ Number(calculateMarketPricePercentage(listing) || 0).toFixed(2) }}% 市场价
                                    </div>
                                </div>

                                <!-- 限额与按钮 -->
                                <div class="text-right">
                                    <div class="text-sm mb-2 text-gray-700 dark:text-gray-300 font-medium">
                                        {{ getCurrencySymbol(listing.fiat_currency) }}{{ (listing.limits?.min || 0).toLocaleString() }} - {{ getCurrencySymbol(listing.fiat_currency) }}{{ (listing.limits?.max || 10000).toLocaleString() }}
                                    </div>
                                    <P2PButton
                                        v-if="filters.tradeType === 'buy'"
                                        @click="handleTradeClick(listing, 'buy')"
                                        variant="primary"
                                        size="md"
                                        class="font-medium px-4">
                                        购买 {{ getCryptocurrencyByKey(listing.cryptocurrency)?.label || getCryptoLabel(listing.cryptocurrency) }}
                                    </P2PButton>
                                    <P2PButton
                                        v-else
                                        @click="handleTradeClick(listing, 'sell')"
                                        variant="blue"
                                        size="md"
                                        class="font-medium px-4">
                                        出售 {{ getCryptocurrencyByKey(listing.cryptocurrency)?.label || getCryptoLabel(listing.cryptocurrency) }}
                                    </P2PButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 分页与信任信息 -->
                <div class="mt-6 border-t border-gray-200 dark:border-slate-800 pt-6">
                    <P2PPagination
                        v-model:current-page="currentPage"
                        :total="filteredListings.length"
                        :per-page="perPage"
                    />
                </div>
            </div>
        </div>
        <!-- Trade Confirmation Dialog -->
        <P2PDialog
            :show="showWarningDialog"
            title="交易安全提醒"
            subtitle="请仔细阅读以下重要提示"
            icon-color="amber"
            cancel-text="取消"
            confirm-text="确认了解"
            @close="showWarningDialog = false"
            @confirm="handleTradeConfirm"
        >
            <div class="space-y-4 text-sm">
                <!-- Warning 1 -->
                <div class="flex gap-2">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                            资金安全警告
                        </p>
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="font-bold text-red-600 dark:text-red-400">切勿在资金到达托管前进行任何转账！</span>
                            请等待系统确认后再进行支付。
                        </p>
                    </div>
                </div>

                <!-- Warning 2 -->
                <div class="flex gap-2">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                            沟通凭证提醒
                        </p>
                        <p class="text-gray-600 dark:text-gray-300">
                            只有在本平台内的聊天记录才能作为交易凭证。站外沟通内容无法作为争议处理依据。
                        </p>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex gap-2">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                            交易条款确认
                        </p>
                        <p class="text-gray-600 dark:text-gray-300">
                            点击确认即表示您同意：
                        </p>
                        <ul class="mt-1 space-y-1 text-gray-600 dark:text-gray-300">
                            <li class="flex items-start gap-1">
                                <span class="text-gray-400">•</span>
                                <span>遵守商家的交易条款</span>
                            </li>
                            <li class="flex items-start gap-1">
                                <span class="text-gray-400">•</span>
                                <span>在规定时间内完成交易</span>
                            </li>
                            <li class="flex items-start gap-1">
                                <span class="text-gray-400">•</span>
                                <span>使用实名账户进行支付</span>
                            </li>
                            <li class="flex items-start gap-1">
                                <span class="text-gray-400">•</span>
                                <span>诚信交易，不进行欺诈行为</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </P2PDialog>

        <!-- Login Prompt Dialog -->
        <P2PLoginPrompt
            v-model="showLoginPrompt"
            :message="loginPromptMessage"
            :return-url="returnUrl"
        />
    </P2PAppLayout>
</template>

<script setup>
import {ref, computed, watch, onMounted} from 'vue';
import {Head, Link, router} from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PBadge from '@/Components/UI/P2PBadge.vue';
import P2PSelect from '@/Components/UI/P2PSelect.vue';
import P2PPagination from '@/Components/UI/P2PPagination.vue';
import P2PPaymentMethodPicker from '@/Components/UI/P2PPaymentMethodPicker.vue';
import P2PDialog from '@/Components/UI/P2PDialog.vue';
import P2PLoginPrompt from '@/Components/UI/P2PLoginPrompt.vue';
import {useConfig} from '@/Composables/useConfig';
import {useAuthCheck} from '@/Composables/useAuthCheck';
import axios from 'axios';

// Crypto Icons
import {getCryptoIcon, getCryptoLabel} from '@/Utils/crypto';
import {getCountryFlag, getCountryFlagWithFallback} from '@/Utils/countryFlags';

const props = defineProps({
    // No props needed, all data comes from API
});

// Use config composable
const {
    fetchConfig,
    getCryptocurrencyOptions,
    getPaymentMethodOptions,
    getFiatCurrencyOptions,
    getCryptocurrencyByKey,
    getPaymentMethodDetails,
    getFiatCurrencyByCode,
    paymentMethods
} = useConfig();

// Local currency state
const storedCurrency = localStorage.getItem('selectedFiatCurrency');
const selectedFiatCurrency = ref(
    (!storedCurrency || storedCurrency === 'null' || storedCurrency === 'undefined')
        ? null
        : storedCurrency
);
const fiatCurrencyOptions = computed(() => getFiatCurrencyOptions());

// Crypto prices state
const cryptoPrices = ref({
    crypto_prices_usd: {},
    exchange_rates: {},
    loading: false
});

const filters = ref({
    tradeType: 'buy', // 新增交易类型：buy 或 sell
    cryptocurrency: null,
    paymentMethod: null,
    sortBy: 'recent', // 默认按最新发布排序
    onlyVerified: false, // 只看认证商家，默认不勾选
});

// 广告列表数据
const listings = ref([]);
const loading = ref(false);
const error = ref(null);

// Trade warning dialog state
const showWarningDialog = ref(false);
const selectedListing = ref(null);
const selectedTradeType = ref('buy');

// Auth check composable
const {requireAuth, showLoginPrompt, loginPromptMessage, returnUrl} = useAuthCheck();

// Fetch crypto prices
const fetchCryptoPrices = async () => {
    cryptoPrices.value.loading = true;
    try {
        const response = await axios.get('/web/api/market/prices');

        if (response.data) {
            cryptoPrices.value = {
                ...response.data,
                loading: false
            };
        }
    } catch (error) {
        console.error('Failed to fetch crypto prices:', error);
        cryptoPrices.value.loading = false;
    }
};

// 获取广告数据
const fetchAds = async () => {
    loading.value = true;
    error.value = null;
    try {
        const params = new URLSearchParams();
        // ad的trade_type就是从用户视角的
        // 用户想买(buy) -> 显示trade_type='buy'的广告
        // 用户想卖(sell) -> 显示trade_type='sell'的广告
        params.append('trade_type', filters.value.tradeType);

        // 添加法币筛选 - 只有在选择了货币时才添加参数
        if (selectedFiatCurrency.value && selectedFiatCurrency.value !== 'null') {
            params.append('fiat_currency', selectedFiatCurrency.value);
        }

        if (filters.value.cryptocurrency) {
            params.append('currency_key', filters.value.cryptocurrency);
        }

        if (filters.value.paymentMethod) {
            params.append('payment_method', filters.value.paymentMethod);
        }


        // Map frontend sort values to backend parameters
        const sortMap = {
            'rating': 'rating',
            'trades': 'trades',
            'recent': 'created_at'
        };
        params.append('sort_by', sortMap[filters.value.sortBy] || 'rating');
        params.append('sort_order', 'desc');
        params.append('per_page', '50');

        const response = await fetch(`/web/api/ads?${params}`);
        const data = await response.json();

        if (data.success) {
            console.log('Raw ad data:', data.data.ads[0]); // 调试：查看原始数据
            // 转换数据格式以匹配现有结构
            listings.value = data.data.ads.map(ad => {
                return {
                    id: ad.id,
                    type: ad.trade_type,
                    cryptocurrency: ad.currency_key,
                    fiat_currency: ad.fiat_currency,
                    price: ad.price,
                    price_model: ad.price_model,
                    margin: ad.margin,
                    minAmount: ad.min_limit,
                    maxAmount: ad.max_limit,
                    limits: {
                        min: ad.min_limit || 0,
                        max: ad.max_limit || 10000
                    },
                    paymentMethod: ad.payment_method,
                    paymentWindow: ad.payment_time_limit || 15,
                    notes: ad.notes,
                    country: ad.country,
                    country_name: ad.country_name,
                    seller: {
                        id: ad.user?.id || 0,
                        name: ad.user?.name || 'Unknown',
                        avatar: ad.user?.profile_photo_url || null,
                        profile_photo_url: ad.user?.profile_photo_url || null,
                        rating: ad.user?.avg_rating || 5.0,
                        totalReviews: ad.user?.total_reviews || 0,
                        trades: ad.user?.trades_count || 0,
                        isVerified: ad.user?.is_verified || false,
                        lastSeen: ad.user?.last_seen || null,
                        responseTime: ad.user?.response_time || null,
                        completionRate: 98.5 // 暂时使用固定值
                    }
                };
            });
        } else {
            error.value = data.message || '获取广告列表失败';
        }
    } catch (err) {
        error.value = '网络错误，请稍后重试';
        console.error('Failed to fetch ads:', err);
    } finally {
        loading.value = false;
    }
};

// 计算动态价格
const calculateDynamicPrice = (ad) => {
    // 使用实时价格
    const marketPrice = cryptoPrices.value.rates?.[ad.cryptocurrency.toUpperCase()] || 0;
    if (!marketPrice) return ad.price; // 如果没有市场价格，返回固定价格
    return marketPrice * (1 + (ad.margin || 0) / 100);
};

// 获取虚拟货币的基础symbol（处理USDT/USDC的各种网络版本）
const getCryptoSymbol = (cryptocurrency) => {
    if (!cryptocurrency) return '';

    const crypto = cryptocurrency.toLowerCase();
    // 将所有USDT/USDC变体映射到USDT（因为它们都是USD稳定币）
    // 格式可能是: erc-usdt, trc-usdt, bep-usdt, erc-usdc, trc-usdc, bep-usdc, usdt, usdc
    if (crypto.includes('usdt') || crypto.includes('usdc')) {
        return 'USDT';
    }
    // BTC和ETH直接返回大写
    if (crypto === 'btc' || crypto === 'bitcoin') {
        return 'BTC';
    }
    if (crypto === 'eth' || crypto === 'ethereum') {
        return 'ETH';
    }
    return crypto.toUpperCase();
};

// 获取货币符号
const getCurrencySymbol = (currency) => {
    if (!currency) return '';

    // First try to get from config
    const fiatCurrency = getFiatCurrencyByCode(currency);
    if (fiatCurrency?.symbol) return fiatCurrency.symbol;

    // Fallback to price service data
    const currencyInfo = cryptoPrices.value.currencies?.[currency];
    if (currencyInfo?.symbol) return currencyInfo.symbol;

    return '';
};

// 格式化加密货币价格显示（用于顶部价格栏）
const formatCryptoPrice = (crypto, currency) => {
    const usdPrice = cryptoPrices.value.crypto_prices_usd?.[crypto];
    const exchangeRate = cryptoPrices.value.exchange_rates?.[currency] || 1;

    if (!usdPrice) return '-';

    const price = usdPrice * exchangeRate;
    const symbol = getCurrencySymbol(currency);

    // Get decimal places from config
    const fiatCurrency = getFiatCurrencyByCode(currency);
    const decimalPlaces = fiatCurrency?.decimal_places ?? 2;

    // Format based on decimal places
    if (decimalPlaces === 0) {
        return symbol + Math.round(price).toLocaleString();
    }

    return symbol + price.toFixed(decimalPlaces);
};


// 获取市场价格（某个法币下的加密货币价格）
const getMarketPrice = (cryptocurrency, fiatCurrency) => {
    if (!cryptocurrency || !fiatCurrency) return 0;

    const symbol = getCryptoSymbol(cryptocurrency);
    const usdPrice = cryptoPrices.value.crypto_prices_usd?.[symbol];
    const exchangeRate = cryptoPrices.value.exchange_rates?.[fiatCurrency] || 1;

    if (!usdPrice) return 0;

    // USD价格 * 汇率 = 目标货币价格
    return usdPrice * exchangeRate;
};

// 获取显示价格
const getDisplayPrice = (listing) => {
    if (!listing) return 0;

    if (listing.price_model === 'dynamic') {
        // 动态价格模式：根据市场价格和margin计算
        const marketPrice = getMarketPrice(listing.cryptocurrency, listing.fiat_currency);

        // 如果市场价格还没加载，返回默认价格或0
        if (!marketPrice || marketPrice === 0) {
            return listing.price || 0;
        }

        // 计算带margin的价格
        const margin = listing.margin || 0;
        return marketPrice * (1 + margin / 100);
    } else {
        // 固定价格模式：直接返回price
        return listing.price || 0;
    }
};

// 计算市场价格百分比
const calculateMarketPricePercentage = (listing) => {
    const marketPrice = getMarketPrice(listing.cryptocurrency, listing.fiat_currency);
    if (!marketPrice || marketPrice === 0) return null;

    if (listing.price_model === 'dynamic') {
        // 动态价格模式：直接返回margin
        return listing.margin || 0;
    } else {
        // 固定价格模式：计算价格相对于市场价的百分比
        const percentage = ((listing.price - marketPrice) / marketPrice) * 100;
        return percentage;
    }
};

// 监听筛选条件变化
watch(filters, () => {
    currentPage.value = 1;
    fetchAds();
}, {deep: true});

// Watch currency changes
watch(selectedFiatCurrency, (newCurrency) => {
    if (newCurrency) {
        localStorage.setItem('selectedFiatCurrency', newCurrency);
    } else {
        localStorage.removeItem('selectedFiatCurrency');
    }
    fetchAds(); // 重新获取对应法币的广告
});

// 读取URL参数设置初始交易类型
// Handle trade button click
const handleTradeClick = (listing, tradeType) => {
    const action = () => {
        selectedListing.value = listing;
        selectedTradeType.value = tradeType;
        showWarningDialog.value = true;
    };

    const message = tradeType === 'buy'
        ? `请先登录以购买 ${getCryptocurrencyByKey(listing.cryptocurrency)?.label || getCryptoLabel(listing.cryptocurrency)}`
        : `请先登录以出售 ${getCryptocurrencyByKey(listing.cryptocurrency)?.label || getCryptoLabel(listing.cryptocurrency)}`;

    requireAuth(action, message);
};

// Handle warning dialog confirmation
const handleTradeConfirm = () => {
    showWarningDialog.value = false;
    if (selectedListing.value) {
        // Navigate to create order page
        router.visit(`/trade/create-order?ad_id=${selectedListing.value.id}&type=${selectedTradeType.value}`);
    }
};

onMounted(async () => {
    // Load config data first
    try {
        await fetchConfig();
    } catch (error) {
        console.error('Failed to load config:', error);
    }

    // 测试国旗功能

    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');
    if (type === 'sell') {
        filters.value.tradeType = 'sell';
    }
    fetchAds();
    fetchCryptoPrices();
});

const currentPage = ref(1);
const perPage = ref(10);

// 获取货币选项 - 使用完整的货币列表（包含网络信息）
const cryptocurrencyOptions = computed(() => {
    const options = getCryptocurrencyOptions();
    // 为每个选项添加图标组件
    return options.map(option => {
        return {
            ...option,
            iconComponent: getCryptoIcon(option.value)
        };
    });
});


// Helper function to get cryptocurrency label from key

// Helper function to check if user is online (within 5 minutes)
const isUserOnline = (lastSeen) => {
    if (!lastSeen) return false;

    const date = new Date(lastSeen);
    const now = new Date();
    const diffMinutes = Math.floor((now - date) / 60000);

    return diffMinutes < 5;
};

// Helper function to format last seen time
const formatLastSeen = (lastSeen) => {
    if (!lastSeen) return '未知';

    const date = new Date(lastSeen);
    const now = new Date();
    const diffMinutes = Math.floor((now - date) / 60000);

    if (diffMinutes < 1) return '刚刚';
    if (diffMinutes < 5) return '在线';
    if (diffMinutes < 60) return `${diffMinutes}分钟前`;

    const diffHours = Math.floor(diffMinutes / 60);
    if (diffHours < 24) return `${diffHours}小时前`;

    const diffDays = Math.floor(diffHours / 24);
    if (diffDays < 7) return `${diffDays}天前`;

    return '7天前';
};

const sortOptions = [
    {value: 'rating', label: '按评分', icon: '⭐'},
    {value: 'trades', label: '按成交量', icon: '📊'},
    {value: 'response', label: '按响应时间', icon: '⚡'},
    {value: 'recent', label: '最新发布', icon: '🕐'},
];


const filteredListings = computed(() => {
    let filtered = listings.value;


    // 客户端额外过滤 - 只看认证商家
    if (filters.value.onlyVerified) {
        filtered = filtered.filter(listing => listing.seller.isVerified);
    }

    return filtered;
});

const paginatedListings = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    const end = start + perPage.value;
    return filteredListings.value.slice(start, end);
});

// Reset to page 1 when filters change
watch(filters, () => {
    currentPage.value = 1;
}, {deep: true});
</script>

<style scoped>
/* 隐藏滚动条但保持滚动功能 */
.scrollbar-hide {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}

.scrollbar-hide::-webkit-scrollbar {
    display: none; /* Chrome, Safari and Opera */
}
</style>
