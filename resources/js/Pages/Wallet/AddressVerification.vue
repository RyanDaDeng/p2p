<template>
    <P2PAppLayout>
        <Head title="地址管理" />

        <!-- Header -->
        <div class="bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-slate-100">
                            地址管理
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                            管理您的发款/收款地址
                        </p>
                    </div>

                    <P2PButton
                        @click="showAddModal = true"
                        variant="primary"
                        size="sm"
                    >
                        <template #icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </template>
                        添加地址
                    </P2PButton>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Important Notice -->
            <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm text-amber-800 dark:text-amber-200">
                        <p class="font-semibold mb-1">重要提示</p>
                        <ul class="space-y-1 text-xs">
                            <li>• 每个地址只能被一个用户使用，防止地址冒用</li>
                            <li>• 地址一旦添加后无法删除，请谨慎添加</li>
                            <li>• 请确保地址准确无误，错误的地址可能导致资金损失</li>
                            <li>• 如果钱包地址在30天内没有任何关联订单将会被自动移除</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Address List -->
            <div v-if="userAddresses.length > 0" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="address in userAddresses" :key="address.id"
                     class="bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-800 p-4">
                    <!-- Currency Header -->
                    <div class="mb-3 flex items-center gap-3">
                        <component :is="getCryptoIcon(address.currency_key)" :size="32" />
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-slate-100">
                                {{ address.currency }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-slate-400">
                                {{ address.chain_label || address.network || getChainLabel(address.chain) }}
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-3 mb-3">
                        <div class="text-xs text-gray-500 dark:text-slate-400 mb-1">地址</div>
                        <div class="font-mono text-xs text-gray-900 dark:text-slate-100 break-all">
                            {{ address.address }}
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end">
                        <div class="text-xs text-gray-500 dark:text-slate-400">
                            添加于 {{ formatDate(address.created_at) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-800 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-slate-100">暂无地址</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                    添加您的加密货币地址以开始交易
                </p>
                <div class="mt-6">
                    <P2PButton @click="showAddModal = true" variant="primary" size="sm">
                        <template #icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </template>
                        添加第一个地址
                    </P2PButton>
                </div>
            </div>
        </div>

        <!-- Add Address Modal -->
        <P2PModal :show="showAddModal" max-width="2xl" @close="closeAddModal">
            <!-- Modal Header -->
            <div class="flex-shrink-0 px-6 py-4 border-b border-gray-200 dark:border-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-slate-100">
                            添加地址
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">
                            添加您的加密货币发款地址
                        </p>
                    </div>
                    <button
                        @click="closeAddModal"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                    >
                        <svg class="w-5 h-5 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto px-6 py-4">

                <!-- Currency Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        选择币种和网络
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                        <button v-for="crypto in cryptocurrencies"
                                :key="crypto.key"
                                @click="selectCurrency(crypto)"
                                :class="[
                                    'p-3 text-sm rounded-lg border transition-colors cursor-pointer',
                                    selectedCurrency?.key === crypto.key
                                        ? 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-500 text-emerald-700 dark:text-emerald-400'
                                        : 'bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700'
                                ]">
                            <div class="mb-1 flex justify-center">
                                <component :is="getCryptoIcon(crypto.key)" :size="24" />
                            </div>
                            <div class="text-xs font-medium">{{ crypto.label }}</div>
                            <div class="text-xs opacity-60">{{ crypto.network }}</div>
                        </button>
                    </div>
                </div>

                <!-- Address Input -->
                <Transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 transform translate-y-2"
                    enter-to-class="opacity-100 transform translate-y-0"
                >
                    <div v-if="selectedCurrency" class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            钱包地址
                        </label>
                        <input v-model="addressInput"
                               type="text"
                               :placeholder="getAddressPlaceholder()"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono text-sm">
                        <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                            请确保地址正确，错误的地址可能导致资金损失
                        </p>
                    </div>
                </Transition>

                <!-- Address Preview -->
                <Transition
                    enter-active-class="transition-all duration-300 ease-out delay-150"
                    enter-from-class="opacity-0 transform translate-y-2"
                    enter-to-class="opacity-100 transform translate-y-0"
                >
                    <div v-if="canSave" class="mb-4 p-4 bg-gray-50 dark:bg-slate-900/50 rounded-lg border border-gray-200 dark:border-slate-700">
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-12 text-xs text-gray-500 dark:text-slate-400 pt-0.5">币种:</div>
                            <div class="flex-1 text-sm text-gray-900 dark:text-slate-100 flex items-center gap-2">
                                <component :is="getCryptoIcon(selectedCurrency?.key)" :size="18" />
                                <span class="font-medium">{{ selectedCurrency?.label }}</span>
                                <span class="text-xs text-gray-500 dark:text-slate-400">({{ selectedCurrency?.network }})</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-12 text-xs text-gray-500 dark:text-slate-400 pt-0.5">网络:</div>
                            <div class="flex-1 text-sm text-gray-900 dark:text-slate-100 font-medium">
                                {{ selectedCurrency?.network }}
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-12 text-xs text-gray-500 dark:text-slate-400 pt-0.5 flex-shrink-0">地址:</div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm text-gray-900 dark:text-slate-100 font-mono break-all bg-gray-100 dark:bg-slate-800 p-2 rounded border border-gray-200 dark:border-slate-600">
                                    {{ addressInput }}
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </Transition>
            </div>

            <!-- Modal Footer -->
            <div class="flex-shrink-0 px-6 py-4 border-t border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-900/50 rounded-b-xl">
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button
                        @click="closeAddModal"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                    >
                        取消
                    </button>
                    <button
                        @click="saveAddress"
                        :disabled="!canSave || saving"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer flex items-center justify-center"
                    >
                        <svg v-if="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ saving ? '保存中...' : '添加地址' }}
                    </button>
                </div>
            </div>
        </P2PModal>
    </P2PAppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PModal from '@/Components/UI/P2PModal.vue';
import axios from 'axios';
import MessageDialog from '@/Components/MessageDialog';
import BitcoinIcon from '@/Components/Icons/BitcoinIcon.vue';
import EthereumIcon from '@/Components/Icons/EthereumIcon.vue';
import UsdtIcon from '@/Components/Icons/UsdtIcon.vue';
import UsdcIcon from '@/Components/Icons/UsdcIcon.vue';
import TronIcon from '@/Components/Icons/TronIcon.vue';
import BnbIcon from '@/Components/Icons/BnbIcon.vue';

const props = defineProps({
    addresses: {
        type: Array,
        default: () => []
    },
    cryptocurrencies: {
        type: Array,
        default: () => []
    },
    locations: {
        type: Array,
        default: () => []
    }
});

// State
const userAddresses = ref(props.addresses);
const showAddModal = ref(false);
const saving = ref(false);

// Form State
const selectedCurrency = ref(null);
const addressInput = ref('');

// Computed
const canSave = computed(() => {
    return selectedCurrency.value && addressInput.value.trim().length > 0;
});

// Icon mapping
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

// Methods
const getCryptoIcon = (key) => {
    // For addresses already saved, determine icon based on currency
    if (!key) return BitcoinIcon;

    // Direct mapping first
    if (iconComponents[key]) {
        return iconComponents[key];
    }

    // Fallback based on currency type
    if (key.includes('usdt')) return UsdtIcon;
    if (key.includes('usdc')) return UsdcIcon;
    if (key.includes('btc')) return BitcoinIcon;
    if (key.includes('eth')) return EthereumIcon;

    return UsdtIcon; // Default fallback
};

const selectCurrency = (crypto) => {
    selectedCurrency.value = crypto;
    addressInput.value = '';

    // 自动滚动到地址输入区域
    setTimeout(() => {
        const addressInput = document.querySelector('input[type="text"]');
        if (addressInput) {
            addressInput.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            // 延迟聚焦以确保滚动完成
            setTimeout(() => {
                addressInput.focus();
            }, 300);
        }
    }, 100);
};

const getChainLabel = (chain) => {
    const crypto = props.cryptocurrencies.find(c => c.chain === chain);
    return crypto?.chainLabel || chain;
};

const getCryptoByChain = (chain, currency) => {
    return props.cryptocurrencies.find(c => c.chain === chain && c.currency === currency);
};

const getAddressPlaceholder = () => {
    if (!selectedCurrency.value) return '请先选择币种';

    const key = selectedCurrency.value.key;
    const chain = selectedCurrency.value.chain;

    if (key === 'btc') {
        return '例如: 1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa';
    } else if (chain === 'tron') {
        return '例如: TN9RRaXkCFtTXRso2GdTZxSxxwufzxLQPP';
    } else if (chain === 'ethereum' || chain === 'bsc') {
        return '例如: 0x742d35Cc6634C0532925a3b844Bc9e7595f0bEB4';
    } else {
        return '输入您的钱包地址';
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('zh-CN');
};

const closeAddModal = () => {
    showAddModal.value = false;
    selectedCurrency.value = null;
    addressInput.value = '';
};

const saveAddress = async () => {
    if (!canSave.value) return;

    saving.value = true;

    try {
        const response = await axios.post('/web/api/addresses', {
            currency_key: selectedCurrency.value.key,
            address: addressInput.value.trim()
        });

        if (response.data.success) {
            userAddresses.value.unshift(response.data.data.address);
            MessageDialog.success('地址添加成功');
            closeAddModal();
        } else {
            MessageDialog.error(response.data.message || '添加失败');
        }
    } catch (error) {
        console.error('Save address error:', error);
        if (error.response?.status === 422 && error.response?.data?.message?.includes('已存在')) {
            MessageDialog.error('该地址已被其他用户使用');
        } else {
            MessageDialog.error(error.response?.data?.message || '添加失败');
        }
    } finally {
        saving.value = false;
    }
};

// Load addresses on mount
onMounted(() => {
    loadAddresses();
});

const loadAddresses = async () => {
    try {
        const response = await axios.get('/web/api/addresses');
        if (response.data.success) {
            userAddresses.value = response.data.data.addresses;
        }
    } catch (error) {
        console.error('Load addresses error:', error);
    }
};
</script>
