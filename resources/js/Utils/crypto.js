import BitcoinIcon from '@/Components/Icons/BitcoinIcon.vue';
import EthereumIcon from '@/Components/Icons/EthereumIcon.vue';
import UsdtIcon from '@/Components/Icons/UsdtIcon.vue';
import UsdcIcon from '@/Components/Icons/UsdcIcon.vue';

/**
 * 获取加密货币图标组件
 * @param {string} currency - 可以是 cryptocurrency (如 'USDT') 或 currency_key (如 'erc-usdt')
 * @returns {Component} Vue 组件
 */
export const getCryptoIcon = (currency) => {
    if (!currency) return UsdtIcon;

    const crypto = currency.toLowerCase();

    // USDT 的各种网络版本
    if (crypto.includes('usdt')) return UsdtIcon;

    // USDC 的各种网络版本
    if (crypto.includes('usdc')) return UsdcIcon;

    // Bitcoin
    if (crypto === 'btc' || crypto === 'bitcoin' || crypto.includes('btc')) return BitcoinIcon;

    // Ethereum
    if (crypto === 'eth' || crypto === 'ethereum' || crypto.includes('eth')) return EthereumIcon;

    // 默认返回 USDT 图标
    return UsdtIcon;
};

/**
 * 获取加密货币标签
 * @param {string} cryptocurrency - 币种标识
 * @returns {string} 显示标签
 */
export const getCryptoLabel = (cryptocurrency) => {
    if (!cryptocurrency) return '';

    const crypto = cryptocurrency.toUpperCase();

    // 处理带网络的币种
    if (crypto.includes('-')) {
        const parts = crypto.split('-');
        return parts[parts.length - 1];
    }

    return crypto;
};