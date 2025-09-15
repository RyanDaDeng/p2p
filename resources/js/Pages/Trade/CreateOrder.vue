<template>
    <P2PAppLayout>
        <Head :title="`${tradeType === 'buy' ? '购买' : '出售'} ${getCryptoInfo.label}`" />

        <div class="mx-auto max-w-5xl px-4 py-4 sm:px-6 lg:px-8">
            <!-- 加载状态 -->
            <div v-if="loading && !ad" class="flex items-center justify-center py-12">
                <div class="text-center">
                    <svg class="animate-spin h-8 w-8 text-emerald-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-slate-400">加载广告信息...</p>
                </div>
            </div>

            <div v-else>
            <!-- 页面标题 -->
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">
                    {{ tradeType === 'buy' ? '购买' : '出售' }} {{ getCryptoInfo.label }}
                    使用 {{ getPaymentMethodDetails(ad?.payment_method)?.label || ad?.payment_method || '银行转账' }}
                    来自 {{ ad?.user?.name || '商家' }}
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">
                    {{ tradeType === 'buy' ? '购买' : '出售' }} {{ getCryptoInfo.label }}
                    价格 {{ getFiatInfo.symbol }}{{ getCurrentPrice ? (ad?.price_model === 'dynamic' ? getCurrentPrice.toFixed(6) : getCurrentPrice.toFixed(2)) : '0.00' }}
                    使用 {{ getPaymentMethodDetails(ad?.payment_method)?.label || ad?.payment_method || '银行转账' }}
                </p>
            </div>

            <!-- 自己广告的警告 -->
            <div v-if="isOwnAd" class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-red-800 dark:text-red-200">无法创建订单</h3>
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">您不能在自己发布的广告上创建订单。请选择其他商家的广告进行交易。</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- 左侧：交易表单和交易详情 -->
                <div class="space-y-4">
                    <!-- 输入金额卡片 -->
                    <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800 p-5">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-slate-100 mb-4">
                            输入要{{ tradeType === 'buy' ? '购买' : '出售' }}的金额
                        </h2>

                        <!-- 动态价格警告 -->
                        <div v-if="ad?.price_model === 'dynamic'" class="mb-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-amber-800 dark:text-amber-200 font-medium">动态价格提示</p>
                                    <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                                        当前显示的是估算价格。实际交易价格将在订单创建时确定，可能会因市场波动而有所不同。
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 价格显示 -->
                        <div class="mb-4 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">当前价格:</span>
                                    <span class="text-lg font-semibold text-gray-900 dark:text-slate-100">
                                        {{ getFiatInfo.symbol }}{{ getCurrentPrice ? (ad?.price_model === 'dynamic' ? getCurrentPrice.toFixed(6) : getCurrentPrice.toFixed(2)) : '0.00' }}
                                    </span>
                                    <span v-if="ad?.price_model === 'dynamic'" class="text-xs px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded">
                                        动态价格
                                    </span>
                                    <span v-else class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">
                                        固定价格
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- 法币金额输入 -->
                            <div>
                                <div class="relative">
                                    <input
                                        v-model="fiatAmount"
                                        type="number"
                                        :min="ad?.min_limit"
                                        :max="ad?.max_limit"
                                        placeholder="0"
                                        class="w-full px-4 py-3 pr-12 text-lg border-2 border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-slate-100 placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                        @input="calculateCryptoAmount"
                                    />
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-slate-400 font-medium">
                                        {{ getFiatInfo.value }}
                                    </span>
                                </div>
                            </div>

                            <!-- 加密货币金额 -->
                            <div>
                                <div class="relative">
                                    <input
                                        v-model="cryptoAmount"
                                        type="number"
                                        placeholder="0"
                                        step="0.000001"
                                        class="w-full px-4 py-3 pr-12 text-lg border-2 border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-slate-100 placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                        @input="calculateFiatAmount"
                                    />
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-slate-400 font-medium">
                                        {{ getCryptoInfo.symbol }}
                                    </span>
                                </div>
                            </div>

                            <!-- 验证地址选择 -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                    {{ tradeType === 'sell' ? '发款地址' : '收款地址' }} <span class="text-red-500">*</span>
                                </label>
                                <P2PAddressPicker
                                    v-model="selectedAddress"
                                    :addresses="filteredAddresses"
                                />
                                <div v-if="!selectedAddress && fiatAmount" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    请选择{{ tradeType === 'sell' ? '发款' : '收款' }}地址
                                </div>
                            </div>

                            <!-- 限额提示 -->
                            <div class="text-sm text-gray-600 dark:text-slate-400">
                                限额: {{ getFiatInfo.symbol }}{{ ad?.min_limit?.toLocaleString() || '100' }} - {{ getFiatInfo.symbol }}{{ ad?.max_limit?.toLocaleString() || '50,000' }} {{ getFiatInfo.value }}
                            </div>

                            <!-- 错误提示 -->
                            <div v-if="fiatError" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <p class="text-sm text-red-600 dark:text-red-400">{{ fiatError }}</p>
                            </div>

                            <!-- 提交按钮 -->
                            <button
                                @click="showConfirmDialog"
                                :disabled="!canSubmit || submitting"
                                :class="[
                                    'w-full py-3 px-6 rounded-lg font-semibold text-white transition-all',
                                    canSubmit && !submitting
                                        ? tradeType === 'buy'
                                            ? 'bg-orange-500 hover:bg-orange-600 cursor-pointer'
                                            : 'bg-blue-600 hover:bg-blue-700 cursor-pointer'
                                        : 'bg-gray-300 dark:bg-slate-700 cursor-not-allowed'
                                ]"
                            >
                                {{ submitting ? '处理中...' : (tradeType === 'buy' ? '购买' : '出售') + ' ' + getCryptoInfo.symbol }}
                            </button>
                        </div>
                    </div>

                    <!-- 广告详情 -->
                    <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800 p-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-slate-100 mb-4">广告详情</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                                <span class="text-sm text-gray-600 dark:text-gray-400">法币</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ getFiatInfo.code || ad?.fiat_currency || 'CNY' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                                <span class="text-sm text-gray-600 dark:text-gray-400">网络</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ getCryptoInfo.network || ad?.network || 'ERC-20' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                                <span class="text-sm text-gray-600 dark:text-gray-400">加密货币</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ getCryptoInfo.label }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                                <span class="text-sm text-gray-600 dark:text-gray-400">支付方式</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ ad?.payment_method_name || ad?.payment_method || '银行转账' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                                <span class="text-sm text-gray-600 dark:text-gray-400">价格模型</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <template v-if="ad?.price_model === 'fixed'">
                                        固定价格
                                    </template>
                                    <template v-else>
                                        {{ ad?.margin || '0' }}% {{ ad?.margin >= 0 ? '高于' : '低于' }}市场
                                    </template>
                                </span>
                            </div>

                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">交易类型</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ ad?.trade_type === 'buy' ? '客户购买' : '客户出售' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 右侧：商家信息和条款 -->
                <div class="space-y-4">
                    <!-- 商家信息 -->
                    <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800 p-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-slate-100 mb-4">商家信息</h3>

                        <!-- 商家基本信息 -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="relative">
                                <a :href="`/trader/profile/${ad?.user?.id}`" target="_blank" class="block cursor-pointer hover:opacity-80 transition-opacity">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">
                                            {{ ad?.user?.name?.[0]?.toUpperCase() || 'C' }}
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <a :href="`/trader/profile/${ad?.user?.id}`" target="_blank" class="font-semibold text-gray-900 dark:text-slate-100 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer">
                                        {{ ad?.user?.name || 'cryptocoop' }}
                                    </a>
                                </div>
                                <div class="flex items-center gap-4 mt-1">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ ad?.user?.rating || 0 }}</span>
                                    </div>
                                    <div class="text-sm text-green-600 dark:text-green-400">
                                        <span class="font-medium">{{ ad?.user?.trades_count || 0 }}</span> 笔交易
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 商家条款 -->
                    <div v-if="ad?.vendor_toc" class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 p-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-slate-100 mb-4">商家条款</h3>

                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            <p class="text-sm text-gray-700 dark:text-slate-300 whitespace-pre-wrap">{{ ad.vendor_toc }}</p>
                        </div>
                    </div>

                    <!-- 交易提示 -->
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 dark:from-slate-800 dark:to-slate-900 rounded-lg p-5 text-white shadow-lg">
                        <h3 class="text-base font-semibold mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            交易提示
                        </h3>

                        <p class="text-sm mb-4 leading-relaxed text-gray-200">
                            请仔细检查价格并认真阅读条款。汇率和价格波动可能会改变最终金额。您输入的法币金额很重要，加密货币金额将在请求时计算。如果线下交易，请选择安全的公共场所进行交易。
                        </p>

                        <div class="space-y-3">
                            <div class="bg-white/10 backdrop-blur rounded p-3">
                                <h4 class="font-medium mb-1.5 text-blue-300">如何联系交易者？</h4>
                                <p class="text-xs leading-relaxed text-gray-300">
                                    一旦您进入交易，您将能够通过私人消息框与此交易者自由聊天。
                                </p>
                            </div>

                            <div class="bg-white/10 backdrop-blur rounded p-3">
                                <h4 class="font-medium mb-1.5 text-amber-300">如何取消交易？</h4>
                                <p class="text-xs leading-relaxed text-gray-300">
                                    一旦您进入交易，您不能自动取消交易，因为这可能会导致对方损失。如果出现错误，请提交支持工单或发起争议。
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <!-- 商家条款确认弹窗 -->
        <P2PDialog
            :show="showWarningDialog"
            title="商家条款确认"
            subtitle="请确认同意商家的交易条款"
            :terms="ad?.vendor_toc"
            :requires-agreement="true"
            :agreement-text="`我已阅读并同意商家条款，确认继续${tradeType === 'buy' ? '购买' : '出售'}`"
            cancel-text="取消"
            :confirm-text="`确认${tradeType === 'buy' ? '购买' : '出售'}`"
            @close="showWarningDialog = false"
            @confirm="handleConfirm"
        />
    </P2PAppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PSelect from '@/Components/UI/P2PSelect.vue';
import P2PAddressPicker from '@/Components/UI/P2PAddressPicker.vue';
import P2PDialog from '@/Components/UI/P2PDialog.vue';
import { getCurrencyByValue, FIAT_CURRENCIES } from '@/Constants/currencies';
import { getPaymentMethodDetails } from '@/Constants/paymentMethods';
import axios from 'axios';

const props = defineProps({
    adId: {
        type: String,
        required: true
    },
    tradeType: {
        type: String,
        required: true,
        validator: (value) => ['buy', 'sell'].includes(value)
    },
    userAddresses: {
        type: Array,
        default: () => []
    }
});

// 广告数据
const ad = ref(null);
const loading = ref(false);
const submitting = ref(false);

// 表单数据
const fiatAmount = ref('');
const cryptoAmount = ref('');
const selectedAddress = ref('');

// 错误信息
const fiatError = ref('');
const isOwnAd = ref(false);

// 弹窗状态
const showWarningDialog = ref(false);

// 实时价格数据
const cryptoPrices = ref({
    crypto_prices_usd: {},
    exchange_rates: {},
    updated_at: null
});
const pricesLoading = ref(false);

// 获取加密货币基础symbol（处理网络变体如erc-usdt -> USDT）
const getCryptoBaseSymbol = (cryptocurrency) => {
    if (!cryptocurrency) return 'USDT';
    // 处理格式如 'erc-usdt', 'trc-usdt' -> 'USDT'
    const parts = cryptocurrency.split('-');
    const symbol = parts[parts.length - 1].toUpperCase();
    return symbol;
};

// 获取当前显示价格
const getCurrentPrice = computed(() => {
    if (!ad.value) return 0;

    if (ad.value.price_model === 'fixed') {
        return Number(ad.value.price) || 0;
    } else {
        // 动态价格：使用市场价格 * (1 + margin%)
        const baseSymbol = getCryptoBaseSymbol(ad.value.cryptocurrency);
        let usdPrice = cryptoPrices.value.crypto_prices_usd?.[baseSymbol];

        // 如果是稳定币（USDC, USDT等），默认价格为1
        if (!usdPrice && (baseSymbol === 'USDC' || baseSymbol === 'USDT')) {
            usdPrice = 1;
        }

        // 如果是USD，汇率为1；如果是CNY，使用CNY汇率
        let exchangeRate = 1;
        if (ad.value.fiat_currency !== 'USD') {
            exchangeRate = cryptoPrices.value.exchange_rates?.[ad.value.fiat_currency] || 1;
        }

        console.log('Dynamic price debug:', {
            baseSymbol,
            usdPrice,
            fiatCurrency: ad.value.fiat_currency,
            exchangeRate,
            availableCryptos: Object.keys(cryptoPrices.value.crypto_prices_usd || {})
        });

        if (!usdPrice) return Number(ad.value.price) || 0;

        // USD价格 * 汇率 = 目标货币价格
        const marketPrice = usdPrice * exchangeRate;
        const margin = Number(ad.value.margin) || 0;
        return marketPrice * (1 + margin / 100);
    }
});

// 获取法币信息
const getFiatInfo = computed(() => {
    const currency = ad.value?.fiat_currency || 'CNY';
    const fiatCurrency = FIAT_CURRENCIES.find(f => f.value === currency);
    return fiatCurrency || { value: 'CNY', symbol: '¥', label: 'CNY' };
});

// 获取加密货币信息
const getCryptoInfo = computed(() => {
    if (!ad.value?.currency_key) return { symbol: 'USDT', label: 'USDT', network: '' };

    // 从 crypto_info 获取（后端会返回从 config 获取的信息）
    if (ad.value.crypto_info) {
        return {
            symbol: ad.value.crypto_info.currency,
            label: ad.value.crypto_info.label,
            network: ad.value.crypto_info.network
        };
    }

    // 如果没有 crypto_info，尝试从 currency_key 解析
    const baseSymbol = getCryptoBaseSymbol(ad.value.currency_key);
    return {
        symbol: baseSymbol,
        label: baseSymbol,
        network: ad.value.network || ''
    };
});

// 处理用户地址，标记不匹配的地址为禁用
const filteredAddresses = computed(() => {
    if (!props.userAddresses) return [];

    if (!ad.value) return props.userAddresses.map(address => ({
        id: address.value,  // 后端返回的ID在value字段
        address: address.address,
        currency: address.currency || 'USDT',
        network: address.chain || address.network || 'TRC20',
        disabled: false
    }));

    return props.userAddresses.map(address => {
        // 检查currency_key是否匹配
        const isMatching = address.currency_key === ad.value.currency_key;

        return {
            id: address.value,  // 后端返回的ID在value字段
            address: address.address,
            currency: address.currency || address.currency_key?.split('-')[1]?.toUpperCase() || 'USDT',
            network: address.chain || address.network || 'TRC20',
            disabled: !isMatching
        };
    });
});


// 计算加密货币数量
const calculateCryptoAmount = () => {
    const price = getCurrentPrice.value;
    if (!fiatAmount.value || !price) {
        cryptoAmount.value = '';
        return;
    }

    const amount = parseFloat(fiatAmount.value);

    // 验证限额
    if (ad.value) {
        if (amount < ad.value.min_limit) {
            fiatError.value = `最小金额为 ${getFiatInfo.value.symbol}${ad.value.min_limit}`;
        } else if (amount > ad.value.max_limit) {
            fiatError.value = `最大金额为 ${getFiatInfo.value.symbol}${ad.value.max_limit}`;
        } else {
            fiatError.value = '';
        }
    }

    cryptoAmount.value = (amount / price).toFixed(6);
};

// 计算法币金额
const calculateFiatAmount = () => {
    const price = getCurrentPrice.value;
    if (!cryptoAmount.value || !price) {
        fiatAmount.value = '';
        return;
    }

    const amount = parseFloat(cryptoAmount.value) * price;
    fiatAmount.value = amount.toFixed(2);

    // 验证限额
    if (ad.value) {
        if (amount < ad.value.min_limit) {
            fiatError.value = `最小金额为 ${getFiatInfo.value.symbol}${ad.value.min_limit}`;
        } else if (amount > ad.value.max_limit) {
            fiatError.value = `最大金额为 ${getFiatInfo.value.symbol}${ad.value.max_limit}`;
        } else {
            fiatError.value = '';
        }
    }
};

// 是否可以提交
const canSubmit = computed(() => {
    // 检查是否是自己的广告
    if (isOwnAd.value) return false;
    // 暂时只检查是否有输入值，不检查限额
    if (!fiatAmount.value || !cryptoAmount.value) return false;
    // 需要选择地址（买币需要收款地址，卖币需要发款地址）
    if (!selectedAddress.value) return false;
    return true;
});

// 格式化日期
const formatDate = (date) => {
    if (!date) return 'Feb 25, 2021';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// 获取加密货币价格
const fetchCryptoPrices = async () => {
    try {
        pricesLoading.value = true;
        const response = await axios.get('/web/api/market/prices');

        console.log('Fetched price data:', response.data);

        if (response.data) {
            cryptoPrices.value = response.data;

            // 价格更新后，如果有输入值，重新计算
            if (fiatAmount.value) {
                calculateCryptoAmount();
            }
        }
    } catch (error) {
        console.error('Failed to fetch crypto prices:', error);
    } finally {
        pricesLoading.value = false;
    }
};

// 显示确认弹窗
const showConfirmDialog = () => {
    if (!canSubmit.value) return;
    showWarningDialog.value = true;
};

// 确认创建订单
const handleConfirm = () => {
    showWarningDialog.value = false;
    createOrder();
};

// 创建订单
const createOrder = async () => {
    if (!canSubmit.value) return;

    try {
        submitting.value = true;

        // 准备请求数据
        const requestData = {
            ad_id: props.adId,
            fiat_amount: parseFloat(fiatAmount.value) // API接收的是法币金额
        };

        // 添加地址（买币和卖币都需要）
        if (selectedAddress.value) {
            requestData.address_id = selectedAddress.value;
        }

        // 调用新的订单创建API
        const response = await axios.post('/web/api/orders', requestData);

        if (response.data.success) {
            // 跳转到订单聊天页面，使用订单编号
            router.visit(`/trade/${response.data.data.order_no}/chat`);
        } else {
            // 显示错误信息
            fiatError.value = response.data.message || '创建订单失败';
        }
    } catch (error) {
        console.error('Error creating order:', error);
        // 显示错误信息
        if (error.response?.data?.message) {
            fiatError.value = error.response.data.message;
        } else {
            fiatError.value = '创建订单失败，请稍后重试';
        }
    } finally {
        submitting.value = false;
    }
};

// 获取广告详情
const fetchAdDetails = async () => {
    try {
        loading.value = true;
        const response = await axios.get(`/web/api/ads/${props.adId}`);

        if (response.data.success) {
            ad.value = response.data.data.ad;
            console.log('Ad details loaded:', ad.value);

            // 检查当前用户是否是广告发布者
            const currentUser = usePage().props.auth?.user;
            if (currentUser && ad.value.user_id === currentUser.id) {
                isOwnAd.value = true;
                fiatError.value = '您不能在自己的广告上创建订单';
            }

            // 如果是动态价格，获取实时价格
            if (ad.value.price_model === 'dynamic') {
                await fetchCryptoPrices();
            }
        } else {
            console.error('Failed to load ad:', response.data.message);
            fiatError.value = response.data.message || '加载广告失败';
        }
    } catch (error) {
        console.error('Error fetching ad details:', error);
        fiatError.value = '加载广告失败，请稍后重试';
    } finally {
        loading.value = false;
    }
};

// 监听价格变化，重新计算
watch(getCurrentPrice, () => {
    if (fiatAmount.value) {
        calculateCryptoAmount();
    }
});

onMounted(() => {
    fetchAdDetails();
});
</script>
