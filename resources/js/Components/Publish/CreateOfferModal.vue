<template>
    <P2PPublishDialog
        v-model="show"
        :title="offer ? `编辑广告 #${offer.id}` : '创建广告'"
        :subtitle="offer ? '修改您的交易广告信息' : '创建新的交易广告，开始接受订单'"
        :confirm-text="offer ? '保存修改' : '创建广告'"
        :confirm-disabled="!canSubmit"
        :loading="loading"
        @confirm="submitOffer"
        @cancel="close"
    >
        <template #content>
            <form @submit.prevent class="space-y-6">
                <!-- Trade Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">交易类型</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label
                            class="relative flex items-center justify-center p-4 border-2 rounded cursor-pointer transition-all"
                            :class="form.type === 'buy'
                                ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20'
                                : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'"
                        >
                            <input type="radio" v-model="form.type" value="buy" class="sr-only" />
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="font-medium text-gray-900 dark:text-gray-100">商家出售</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">向用户出售虚拟货币</p>
                            </div>
                            <div v-if="form.type === 'buy'" class="absolute top-2 right-2">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </label>

                        <label
                            class="relative flex items-center justify-center p-4 border-2 rounded cursor-pointer transition-all"
                            :class="form.type === 'sell'
                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                                : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'"
                        >
                            <input type="radio" v-model="form.type" value="sell" class="sr-only" />
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="font-medium text-gray-900 dark:text-gray-100">商家收购</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">从用户手中收购虚拟货币</p>
                            </div>
                            <div v-if="form.type === 'sell'" class="absolute top-2 right-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Currency, Fiat Currency and Country -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">虚拟货币 <span class="text-red-500">*</span></label>
                        <div class="relative" ref="currencyDropdownRef">
                            <button
                                type="button"
                                @click="showCurrencyDropdown = !showCurrencyDropdown"
                                :class="[
                                    'w-full px-4 h-10 pr-10 text-sm text-left bg-white dark:bg-gray-900/50 border rounded transition-all duration-200 cursor-pointer focus:outline-none focus:ring-1 focus:ring-emerald-500/20 flex items-center',
                                    showCurrencyDropdown
                                        ? 'border-emerald-500/50 text-gray-900 dark:text-gray-100'
                                        : 'border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500'
                                ]"
                            >
                                <span class="flex items-center">
                                    <component v-if="form.currency && getCryptoIcon(form.currency)" :is="getCryptoIcon(form.currency)" :size="20" class="mr-2" />
                                    <span :class="form.currency ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400'">
                                        {{ form.currency ? currencies.find(c => c.value === form.currency)?.label : '选择币种' }}
                                    </span>
                                </span>
                                <svg class="absolute right-3 top-3 h-4 w-4 text-gray-500 dark:text-gray-500 transition-transform duration-200"
                                     :class="{ 'rotate-180': showCurrencyDropdown }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <Transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="opacity-0 scale-95"
                                enter-to-class="opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="opacity-100 scale-100"
                                leave-to-class="opacity-0 scale-95"
                            >
                                <div
                                    v-show="showCurrencyDropdown"
                                    class="absolute z-50 mt-2 w-full rounded bg-white dark:bg-gray-900 shadow-xl ring-1 ring-gray-200 dark:ring-gray-700"
                                >
                                    <div class="px-3 py-2 border-b border-gray-200 dark:border-gray-700/50">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">选择币种</p>
                                    </div>

                                    <div class="max-h-60 overflow-auto py-1 custom-scrollbar">
                                        <button
                                            v-for="option in currencies"
                                            :key="option.value"
                                            type="button"
                                            @click="selectCurrency(option)"
                                            :class="[
                                                'w-full text-left px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer flex items-center justify-between',
                                                option.value === form.currency ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300'
                                            ]"
                                        >
                                            <span class="flex items-center">
                                                <component v-if="getCryptoIcon(option.value)" :is="getCryptoIcon(option.value)" :size="20" class="mr-2" />
                                                <span>{{ option.label }}</span>
                                            </span>
                                            <svg v-if="option.value === form.currency" class="h-4 w-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">交易货币 <span class="text-red-500">*</span></label>
                        <div class="emerald-focus">
                            <P2PSelect
                                v-model="form.fiatCurrency"
                                :options="fiatCurrencies"
                                placeholder="选择法币"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">国家 <span class="text-red-500">*</span></label>
                        <div class="emerald-focus">
                            <P2PSelect
                                v-model="form.country"
                                :options="countries"
                                placeholder="选择国家"
                            />
                        </div>
                    </div>
                </div>

                <!-- Verified Address (Always shown for both Buy and Sell) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ form.type === 'buy' ? '发款地址' : '收款地址' }} <span class="text-red-500">*</span>
                    </label>
                    <div class="emerald-focus">
                        <P2PSelect
                            v-model="form.verifiedAddress"
                            :options="filteredVerifiedAddresses"
                            :placeholder="`选择已验证的${form.type === 'buy' ? '发款' : '收款'}地址`"
                        />
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        需要更多验证地址？<a href="/wallet/address-verification" class="text-emerald-600 dark:text-emerald-400 hover:underline cursor-pointer">前往验证 →</a>
                    </p>
                </div>

                <!-- Price Settings -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">价格设置 <span class="text-red-500">*</span></label>
                    <div class="space-y-3">
                        <div class="flex gap-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" v-model="form.priceType" value="fixed" class="mr-2 text-emerald-600 focus:ring-emerald-500 focus:ring-2 focus:ring-offset-0" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">固定价格</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" v-model="form.priceType" value="floating" class="mr-2 text-emerald-600 focus:ring-emerald-500 focus:ring-2 focus:ring-offset-0" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">浮动价格</span>
                            </label>
                        </div>

                        <!-- Fixed Price -->
                        <div v-if="form.priceType === 'fixed'" class="flex items-center gap-2">
                            <input
                                v-model="form.fixedPrice"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            />
                            <span class="text-gray-500 dark:text-gray-400">{{ form.fiatCurrency || 'CNY' }}</span>
                        </div>

                        <!-- Floating Price -->
                        <div v-if="form.priceType === 'floating'" class="flex items-center gap-2">
                            <div class="w-32 emerald-focus">
                                <P2PSelect
                                    v-model="form.floatingMargin"
                                    :options="[
                                        { value: '+', label: '高于' },
                                        { value: '-', label: '低于' }
                                    ]"
                                />
                            </div>
                            <input
                                v-model="form.marginPercent"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            />
                            <span class="text-gray-500 dark:text-gray-400">%</span>
                        </div>
                    </div>
                </div>

                <!-- Limits -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">交易限额 <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <div class="relative">
                                <input
                                    v-model="form.minLimit"
                                    type="number"
                                    placeholder="最低限额"
                                    class="w-full px-3 py-2 pr-12 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                />
                                <span class="absolute right-3 top-2.5 text-gray-500 dark:text-gray-400 text-sm">{{ form.fiatCurrency || 'CNY' }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <input
                                    v-model="form.maxLimit"
                                    type="number"
                                    placeholder="最高限额"
                                    class="w-full px-3 py-2 pr-12 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                />
                                <span class="absolute right-3 top-2.5 text-gray-500 dark:text-gray-400 text-sm">{{ form.fiatCurrency || 'CNY' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">支付方式 <span class="text-red-500">*</span></label>
                    <P2PPaymentMethodPicker
                        v-model="form.paymentMethod"
                        :categories="props.paymentMethods"
                    />
                </div>


                <!-- Quick Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        快速提示
                    </label>
                    <input
                        v-model="form.notes"
                        type="text"
                        placeholder="例如：仅工作日交易、大额优惠、新手友好等"
                        maxlength="255"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        简短的提示信息会显示在广告卡片上，让买家快速了解您的特色
                    </p>
                </div>

                <!-- Trade Settings Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4 space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            交易条款与消息设置
                        </h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            设置交易条款和欢迎消息，让交易更顺畅
                        </p>
                    </div>

                    <!-- Settings Tip -->
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded border border-amber-200 dark:border-amber-800">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <p class="ml-2 text-xs text-amber-700 dark:text-amber-300">
                                <span class="font-medium">提示：</span>您可以在交易设置中配置默认条款和消息，创建新广告时将自动填充，节省您的时间。
                            </p>
                        </div>
                    </div>

                    <!-- Welcome Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">欢迎消息</label>
                        <textarea
                            v-model="form.welcomeMessage"
                            rows="3"
                            placeholder="交易开始时自动发送的消息（选填）"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none text-sm"
                        ></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            当用户开始交易时自动发送的欢迎消息
                        </p>
                    </div>

                    <!-- Trade Terms -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            交易须知
                        </label>
                        <textarea
                            v-model="form.tradeTerms"
                            rows="5"
                            placeholder="设置您的交易须知和注意事项，例如：请使用本人实名账户付款、付款后请主动提供付款截图、付款备注请勿包含敏感词汇等"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none text-sm"
                        ></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            交易条款会展示给对方，请详细说明交易要求和注意事项
                        </p>
                    </div>

                    <!-- Important Notice -->
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/20 rounded border border-gray-200 dark:border-gray-700">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <p class="ml-2 text-xs text-gray-700 dark:text-gray-300">
                                <span class="font-medium">重要提示：</span>订单生成后，交易条款将作为副本保存在订单中。后续修改广告条款不会影响已生成的订单，确保交易双方遵循订单创建时的约定。
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </template>
    </P2PPublishDialog>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import P2PPublishDialog from '@/Components/UI/P2PPublishDialog.vue';
import P2PSelect from '@/Components/UI/P2PSelect.vue';
import P2PPaymentMethodPicker from '@/Components/UI/P2PPaymentMethodPicker.vue';
import { useConfig } from '@/Composables/useConfig';
import { getPaymentMethodDetails, getPaymentMethodPlaceholder } from '@/Constants/paymentMethods';
import axios from 'axios';
import MessageDialog from '@/Components/MessageDialog';
import BitcoinIcon from '@/Components/Icons/BitcoinIcon.vue';
import EthereumIcon from '@/Components/Icons/EthereumIcon.vue';
import UsdtIcon from '@/Components/Icons/UsdtIcon.vue';
import UsdcIcon from '@/Components/Icons/UsdcIcon.vue';
import TronIcon from '@/Components/Icons/TronIcon.vue';
import BnbIcon from '@/Components/Icons/BnbIcon.vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    },
    offer: {
        type: Object,
        default: null
    },
    tradeSettings: {
        type: Object,
        default: null
    },
    userAddresses: {
        type: Array,
        default: () => []
    },
    paymentMethods: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:modelValue', 'success']);

// Message dialog for notifications

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const loading = ref(false);
const showCurrencyDropdown = ref(false);
const currencyDropdownRef = ref(null);

const form = ref({
    type: 'buy',
    currency: '',
    fiatCurrency: 'CNY',
    country: '',
    verifiedAddress: '',
    priceType: 'fixed',
    fixedPrice: '',
    floatingMargin: '+',
    marginPercent: '',
    minLimit: '',
    maxLimit: '',
    paymentMethod: '',
    paymentTimeLimit: 15,
    welcomeMessage: '',
    tradeTerms: '',
    notes: ''
});


// Icon mapping for cryptocurrencies
const iconComponents = {
    'btc': BitcoinIcon,
    'eth': EthereumIcon,
    'erc-usdt': UsdtIcon,
    'trc-usdt': UsdtIcon,
    'bep-usdt': UsdtIcon,
    'erc-usdc': UsdcIcon,
    'trc-usdc': UsdcIcon,
    'bep-usdc': UsdcIcon,
};

const getCryptoIcon = (key) => {
    if (!key) return null;
    if (iconComponents[key]) {
        return iconComponents[key];
    }
    // Fallback based on currency type
    if (key.includes('usdt')) return UsdtIcon;
    if (key.includes('usdc')) return UsdcIcon;
    if (key.includes('btc')) return BitcoinIcon;
    if (key.includes('eth')) return EthereumIcon;
    return UsdtIcon;
};

const selectCurrency = (option) => {
    form.value.currency = option.value;
    showCurrencyDropdown.value = false;
};

// Handle click outside to close dropdown
const handleClickOutside = (event) => {
    if (currencyDropdownRef.value && !currencyDropdownRef.value.contains(event.target)) {
        showCurrencyDropdown.value = false;
    }
};

// Use config composable
const {
    fetchConfig,
    getCryptocurrencyOptions,
    getPaymentMethodOptions,
    getFiatCurrencyOptions,
    getCountryOptions,
    getCryptocurrencyByKey
} = useConfig();

// Config options
const currencies = computed(() => getCryptocurrencyOptions());
const countries = computed(() => getCountryOptions());
const fiatCurrencies = computed(() => getFiatCurrencyOptions());
const paymentMethods = computed(() => getPaymentMethodOptions());

// Load config on mount and add click outside listener
onMounted(async () => {
    try {
        await fetchConfig();
    } catch (error) {
        console.error('Failed to load config:', error);
    }

    // Add click outside listener
    document.addEventListener('click', handleClickOutside);
});

// Clean up event listener on unmount
onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});



// Computed property to show all addresses with disabled state for non-matching ones
const filteredVerifiedAddresses = computed(() => {
    if (!props.userAddresses || props.userAddresses.length === 0) return [];

    // Return all addresses with disabled state for non-matching ones
    return props.userAddresses.map(addr => ({
        ...addr,
        disabled: form.value.currency && addr.currency_key !== form.value.currency
    }));
});

const canSubmit = computed(() => {
    const baseRequirements = form.value.type &&
        form.value.currency &&
        form.value.country &&
        form.value.verifiedAddress && // Address required for both buy and sell
        form.value.minLimit &&
        form.value.maxLimit &&
        form.value.paymentMethod &&
        ((form.value.priceType === 'fixed' && form.value.fixedPrice) ||
            (form.value.priceType === 'floating' && form.value.marginPercent));

    return baseRequirements;
});

const selectedPaymentMethod = computed(() => {
    if (!form.value.paymentMethod) return null;
    return getPaymentMethodDetails(form.value.paymentMethod);
});


const close = () => {
    emit('update:modelValue', false);
};

const submitOffer = async () => {
    if (!canSubmit.value) {
        // Provide specific error message for missing verified address
        if (!form.value.verifiedAddress) {
            MessageDialog.error(`请选择${form.value.type === 'buy' ? '发款' : '收款'}地址`);
            return;
        }
        return;
    }

    loading.value = true;

    try {
        const offerData = {
            currency_key: form.value.currency, // This is already the key like 'erc-usdt', 'btc', etc.
            fiat_currency: form.value.fiatCurrency,
            trade_type: form.value.type,
            trade_address: form.value.verifiedAddress, // Address required for both buy and sell
            min_limit: parseFloat(form.value.minLimit),
            max_limit: parseFloat(form.value.maxLimit),
            price: form.value.priceType === 'fixed' ? parseFloat(form.value.fixedPrice) : null,
            price_model: form.value.priceType === 'fixed' ? 'fixed' : 'dynamic',
            margin: form.value.priceType === 'floating' ?
                parseFloat((form.value.floatingMargin === '+' ? 1 : -1) * form.value.marginPercent) : null,
            payment_method: form.value.paymentMethod,
            payment_time_limit: form.value.paymentTimeLimit,
            country: form.value.country,
            vendor_toc: form.value.tradeTerms || '',
            welcome_message: form.value.welcomeMessage || '',
            notes: form.value.notes || ''
        };

        let response;
        if (props.offer) {
            response = await axios.put(`/web/api/ads/${props.offer.id}`, offerData);
        } else {
            response = await axios.post('/web/api/ads', offerData);
        }

        if (response.data.success) {
            // Success message is handled by axios interceptor when notify: true
            emit('success');
            close();
        }
    } catch (error) {
        MessageDialog.error(error.response?.data?.message || '操作失败');
        console.error('Error submitting offer:', error);
    } finally {
        loading.value = false;
    }
};

const openTradeSettings = () => {
    // Close current modal and open trade settings
    close();
    // In real app, would emit event to parent to open trade settings
    window.location.href = '/publish#settings';
};

// Clear verified address when currency changes
watch(() => form.value.currency, () => {
    form.value.verifiedAddress = '';
});

// Clear verified address when type changes from sell to buy and update trade terms
watch(() => form.value.type, (newType) => {
    if (newType === 'buy') {
        form.value.verifiedAddress = '';
    }
    // 切换交易类型时，更新交易条款的预填充
    if (props.tradeSettings && props.tradeSettings.vendor_toc) {
        form.value.tradeTerms = props.tradeSettings.vendor_toc;
    }
});

// Load offer data if editing
watch(() => props.offer, (newOffer) => {
    if (newOffer) {
        const offerType = newOffer.type || newOffer.trade_type;
        form.value = {
            type: offerType,
            currency: newOffer.currency || newOffer.cryptocurrency,
            fiatCurrency: newOffer.fiat_currency || 'CNY',
            country: newOffer.country,
            verifiedAddress: newOffer.verifiedAddress || '',
            priceType: newOffer.price_model === 'fixed' ? 'fixed' : 'floating',
            fixedPrice: newOffer.price || newOffer.fixedPrice || '',
            floatingMargin: (newOffer.margin !== null && newOffer.margin !== undefined && newOffer.margin < 0) ? '-' : '+',
            marginPercent: Math.abs(newOffer.margin || 0).toString(),
            minLimit: newOffer.minLimit || newOffer.min_limit || '',
            maxLimit: newOffer.maxLimit || newOffer.max_limit || '',
            paymentMethod: newOffer.payment_method || '',
            paymentTimeLimit: newOffer.payment_time_limit || 15,
            welcomeMessage: newOffer.welcome_message || '',
            tradeTerms: newOffer.vendor_toc || '',
            notes: newOffer.notes || ''
        };
    }
}, { immediate: true });


// Load trade settings from API on mount
// Watch for modal open to reset form and apply trade settings
watch(() => props.modelValue, (isOpen) => {
    if (isOpen && !props.offer) {
        // Reset form for new offer
        form.value = {
            type: 'buy',
            currency: '',
            fiatCurrency: 'CNY',
            country: '',
            verifiedAddress: '',
            priceType: 'fixed',
            fixedPrice: '',
            floatingMargin: '+',
            marginPercent: '',
            minLimit: '',
            maxLimit: '',
            paymentMethod: '',
            paymentTimeLimit: 15,
            welcomeMessage: '',
            tradeTerms: ''
        };

        // Apply trade settings
        if (props.tradeSettings) {
            if (props.tradeSettings.welcome_message) {
                form.value.welcomeMessage = props.tradeSettings.welcome_message;
            }
            if (props.tradeSettings.vendor_toc) {
                form.value.tradeTerms = props.tradeSettings.vendor_toc;
            }
        }
    }
});
</script>

<style scoped>
/* Light mode scrollbar */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Dark mode scrollbar */
.dark .custom-scrollbar {
    scrollbar-color: #475569 #1e293b;
}

.dark .custom-scrollbar::-webkit-scrollbar-track {
    background: #1e293b;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #475569;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}

/* Emerald focus for P2PSelect components */
.emerald-focus :deep(button:focus) {
    border-color: rgb(16 185 129 / 0.5) !important;
    ring-color: rgb(16 185 129 / 0.2) !important;
    --tw-ring-color: rgb(16 185 129 / 0.2) !important;
}

.emerald-focus :deep(button:focus) {
    box-shadow: 0 0 0 1px rgb(16 185 129 / 0.2) !important;
}

</style>
