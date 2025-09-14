// 支持的虚拟货币列表
export const SUPPORTED_CURRENCIES = [
    {
        value: 'erc-usdt',
        symbol: 'USDT',
        network: 'ERC-20',
        chain: 'Ethereum',
        label: 'USDT',
        icon: '₮',
        addressPrefix: '0x',
        addressPlaceholder: '0x...',
        decimals: 6
    },
    {
        value: 'erc-usdc',
        symbol: 'USDC',
        network: 'ERC-20',
        chain: 'Ethereum',
        label: 'USDC',
        icon: '$',
        addressPrefix: '0x',
        addressPlaceholder: '0x...',
        decimals: 6
    },
    {
        value: 'trc-usdt',
        symbol: 'USDT',
        network: 'TRC-20',
        chain: 'Tron',
        label: 'USDT',
        icon: '₮',
        addressPrefix: 'T',
        addressPlaceholder: 'T...',
        decimals: 6
    },
    {
        value: 'trc-usdc',
        symbol: 'USDC',
        network: 'TRC-20',
        chain: 'Tron',
        label: 'USDC',
        icon: '$',
        addressPrefix: 'T',
        addressPlaceholder: 'T...',
        decimals: 6
    },
    {
        value: 'bep-usdt',
        symbol: 'USDT',
        network: 'BEP-20',
        chain: 'BSC',
        label: 'USDT',
        icon: '₮',
        addressPrefix: '0x',
        addressPlaceholder: '0x...',
        decimals: 18
    },
    {
        value: 'bep-usdc',
        symbol: 'USDC',
        network: 'BEP-20',
        chain: 'BSC',
        label: 'USDC',
        icon: '$',
        addressPrefix: '0x',
        addressPlaceholder: '0x...',
        decimals: 18
    },
    {
        value: 'btc',
        symbol: 'BTC',
        network: 'Bitcoin',
        chain: 'Bitcoin',
        label: 'Bitcoin',
        icon: '₿',
        addressPrefix: ['1', '3', 'bc1'],
        addressPlaceholder: '1... 或 3... 或 bc1...',
        decimals: 8
    },
    {
        value: 'eth',
        symbol: 'ETH',
        network: 'Ethereum',
        chain: 'Ethereum',
        label: 'Ethereum',
        icon: 'Ξ',
        addressPrefix: '0x',
        addressPlaceholder: '0x...',
        decimals: 18
    }
];

// 根据value获取货币信息
export const getCurrencyByValue = (value) => {
    return SUPPORTED_CURRENCIES.find(currency => currency.value === value);
};

// 获取用于选择器的格式化列表
export const getCurrencyOptions = () => {
    return SUPPORTED_CURRENCIES.map(currency => ({
        value: currency.value,
        label: currency.label,
        badge: currency.network,
        icon: currency.icon
    }));
};

// 获取地址验证页面使用的格式
export const getCurrenciesForAddressVerification = () => {
    return SUPPORTED_CURRENCIES.map(currency => ({
        value: currency.value,
        symbol: currency.symbol,
        network: currency.network,
        icon: currency.icon
    }));
};

// 获取简化的交易货币列表（不区分网络）
export const getTradingCurrencies = () => {
    const uniqueCurrencies = [];
    const seen = new Set();
    
    SUPPORTED_CURRENCIES.forEach(currency => {
        if (!seen.has(currency.symbol)) {
            seen.add(currency.symbol);
            uniqueCurrencies.push({
                value: currency.symbol.toLowerCase(),
                label: currency.symbol,
                icon: currency.icon,
                name: currency.symbol === 'BTC' ? 'Bitcoin' : 
                      currency.symbol === 'ETH' ? 'Ethereum' :
                      currency.symbol === 'USDT' ? 'Tether' :
                      currency.symbol === 'USDC' ? 'USD Coin' : currency.symbol
            });
        }
    });
    
    return uniqueCurrencies;
};

// 支持的国家/地区列表
export const SUPPORTED_COUNTRIES = [
    { value: 'cn', label: 'China', flag: '🇨🇳' },
    { value: 'us', label: 'United States', flag: '🇺🇸' },
    { value: 'uk', label: 'United Kingdom', flag: '🇬🇧' },
    { value: 'sg', label: 'Singapore', flag: '🇸🇬' },
    { value: 'hk', label: 'Hong Kong', flag: '🇭🇰' },
    { value: 'jp', label: 'Japan', flag: '🇯🇵' },
    { value: 'kr', label: 'South Korea', flag: '🇰🇷' },
    { value: 'au', label: 'Australia', flag: '🇦🇺' },
    { value: 'ca', label: 'Canada', flag: '🇨🇦' },
    { value: 'de', label: 'Germany', flag: '🇩🇪' },
    { value: 'fr', label: 'France', flag: '🇫🇷' },
    { value: 'in', label: 'India', flag: '🇮🇳' },
    { value: 'ru', label: 'Russia', flag: '🇷🇺' },
    { value: 'br', label: 'Brazil', flag: '🇧🇷' },
    { value: 'mx', label: 'Mexico', flag: '🇲🇽' }
];

// 获取用于选择器的国家列表 (英文)
export const getCountryOptions = () => {
    return SUPPORTED_COUNTRIES.map(country => ({
        value: country.value,
        label: `${country.flag} ${country.label}`
    }));
};

// 支持的法币列表
export const FIAT_CURRENCIES = [
    { value: 'CNY', label: '¥ CNY', symbol: '¥' },
    { value: 'USD', label: '$ USD', symbol: '$' },
    { value: 'GBP', label: '£ GBP', symbol: '£' },
    { value: 'SGD', label: 'S$ SGD', symbol: 'S$' },
    { value: 'HKD', label: 'HK$ HKD', symbol: 'HK$' },
    { value: 'JPY', label: '¥ JPY', symbol: '¥' },
    { value: 'KRW', label: '₩ KRW', symbol: '₩' },
    { value: 'AUD', label: 'A$ AUD', symbol: 'A$' },
    { value: 'CAD', label: 'C$ CAD', symbol: 'C$' },
    { value: 'EUR', label: '€ EUR', symbol: '€' },
    { value: 'INR', label: '₹ INR', symbol: '₹' },
    { value: 'RUB', label: '₽ RUB', symbol: '₽' },
    { value: 'BRL', label: 'R$ BRL', symbol: 'R$' },
    { value: 'MXN', label: '$ MXN', symbol: '$' }
];

// 获取法币选项列表
export const getFiatCurrencyOptions = () => {
    return FIAT_CURRENCIES;
};

// 支持的支付方式
export const PAYMENT_METHODS = [
    { value: 'alipay', label: '支付宝', badge: '推荐', icon: '💳' },
    { value: 'wechat', label: '微信支付', badge: '推荐', icon: '💳' },
    { value: 'bank', label: '银行转账', icon: '🏦' },
    { value: 'unionpay', label: '云闪付', icon: '💳' },
    { value: 'paypal', label: 'PayPal', icon: '💰' },
    { value: 'visa', label: 'Visa/Mastercard', icon: '💳' },
    { value: 'crypto', label: '加密货币', icon: '₿' },
    { value: 'cash', label: '现金', icon: '💵' },
    { value: 'other', label: '其他', icon: '📝' }
];