export const PAYMENT_CATEGORIES = [
    {
        id: 'bank_transfers',
        name: 'Bank Transfers',
        icon: '🏦',
        color: 'blue',
        methods: [
            { value: 'bank_transfer', label: 'Bank Transfer', popular: true },
            { value: 'swift', label: 'SWIFT', popular: false },
            { value: 'sepa', label: 'SEPA', popular: false },
            { value: 'ach', label: 'ACH Transfer', popular: false },
            { value: 'wire_transfer', label: 'Wire Transfer', popular: true },
            { value: 'interac', label: 'Interac e-Transfer', popular: false },
            { value: 'imps', label: 'IMPS', popular: false },
            { value: 'rtgs', label: 'RTGS', popular: false },
            { value: 'neft', label: 'NEFT', popular: false },
            { value: 'upi', label: 'UPI', popular: true },
        ]
    },
    {
        id: 'online_wallets',
        name: 'Online Wallets',
        icon: '💳',
        color: 'emerald',
        methods: [
            { value: 'alipay', label: 'Alipay', popular: true },
            { value: 'wechat', label: 'WeChat Pay', popular: true },
            { value: 'paypal', label: 'PayPal', popular: true },
            { value: 'skrill', label: 'Skrill', popular: false },
            { value: 'neteller', label: 'Neteller', popular: false },
            { value: 'perfect_money', label: 'Perfect Money', popular: false },
            { value: 'webmoney', label: 'WebMoney', popular: false },
            { value: 'payoneer', label: 'Payoneer', popular: false },
            { value: 'wise', label: 'Wise (TransferWise)', popular: true },
            { value: 'revolut', label: 'Revolut', popular: true },
            { value: 'cashapp', label: 'Cash App', popular: true },
            { value: 'venmo', label: 'Venmo', popular: true },
            { value: 'zelle', label: 'Zelle', popular: true },
            { value: 'paytm', label: 'Paytm', popular: true },
            { value: 'phonepe', label: 'PhonePe', popular: false },
            { value: 'googlepay', label: 'Google Pay', popular: true },
            { value: 'applepay', label: 'Apple Pay', popular: true },
        ]
    },
    {
        id: 'cash_payments',
        name: 'Cash Payments',
        icon: '💵',
        color: 'amber',
        methods: [
            { value: 'cash_in_person', label: 'Cash in Person', popular: true },
            { value: 'cash_deposit', label: 'Cash Deposit', popular: true },
            { value: 'cash_by_mail', label: 'Cash by Mail', popular: false },
            { value: 'atm_cash', label: 'ATM Cash Code', popular: false },
        ]
    },
    {
        id: 'gift_cards',
        name: 'Gift Cards',
        icon: '🎁',
        color: 'purple',
        methods: [
            { value: 'amazon_gift', label: 'Amazon Gift Card', popular: true },
            { value: 'steam_gift', label: 'Steam Gift Card', popular: true },
            { value: 'itunes_gift', label: 'iTunes Gift Card', popular: true },
            { value: 'google_play_gift', label: 'Google Play Gift Card', popular: false },
            { value: 'ebay_gift', label: 'eBay Gift Card', popular: false },
            { value: 'walmart_gift', label: 'Walmart Gift Card', popular: false },
            { value: 'bestbuy_gift', label: 'Best Buy Gift Card', popular: false },
            { value: 'target_gift', label: 'Target Gift Card', popular: false },
            { value: 'starbucks_gift', label: 'Starbucks Gift Card', popular: false },
        ]
    },
    {
        id: 'digital_currencies',
        name: 'Digital Currencies',
        icon: '🪙',
        color: 'orange',
        methods: [
            { value: 'usdt_trc20', label: 'USDT (TRC20)', popular: true },
            { value: 'usdt_erc20', label: 'USDT (ERC20)', popular: true },
            { value: 'usdc', label: 'USDC', popular: true },
            { value: 'dai', label: 'DAI', popular: false },
            { value: 'busd', label: 'BUSD', popular: false },
            { value: 'tusd', label: 'TUSD', popular: false },
        ]
    }
];

// Helper function to get all payment methods as flat array
export const getAllPaymentMethods = () => {
    const methods = [];
    PAYMENT_CATEGORIES.forEach(category => {
        category.methods.forEach(method => {
            methods.push({
                ...method,
                category: category.name,
                categoryId: category.id
            });
        });
    });
    return methods;
};

// Helper function to get popular payment methods
export const getPopularPaymentMethods = () => {
    const methods = [];
    PAYMENT_CATEGORIES.forEach(category => {
        category.methods.filter(m => m.popular).forEach(method => {
            methods.push({
                ...method,
                category: category.name,
                categoryId: category.id
            });
        });
    });
    return methods;
};

// Helper function to get payment method details
export const getPaymentMethodDetails = (value) => {
    for (const category of PAYMENT_CATEGORIES) {
        const method = category.methods.find(m => m.value === value);
        if (method) {
            return {
                ...method,
                category: category.name,
                categoryId: category.id,
                categoryIcon: category.icon,
                categoryColor: category.color
            };
        }
    }
    return null;
};

// Helper function to get placeholder text for payment method
export const getPaymentMethodPlaceholder = (value) => {
    const placeholders = {
        'bank_transfer': '请输入银行名称和账号',
        'swift': '请输入SWIFT代码和账户详情',
        'sepa': '请输入IBAN和BIC代码',
        'ach': '请输入路由号码和账号',
        'wire_transfer': '请输入银行电汇信息',
        'interac': '请输入Interac邮箱地址',
        'imps': '请输入IMPS详情',
        'rtgs': '请输入RTGS详情',
        'neft': '请输入NEFT详情',
        'upi': '请输入UPI ID',
        'alipay': '请输入支付宝账号或二维码',
        'wechat': '请输入微信ID或二维码',
        'paypal': '请输入PayPal邮箱地址',
        'skrill': '请输入Skrill邮箱',
        'neteller': '请输入Neteller账号',
        'perfect_money': '请输入Perfect Money账号',
        'webmoney': '请输入WebMoney钱包号',
        'payoneer': '请输入Payoneer邮箱',
        'wise': '请输入Wise邮箱地址',
        'revolut': '请输入Revolut手机号或邮箱',
        'cashapp': '请输入Cash App标签',
        'venmo': '请输入Venmo用户名',
        'zelle': '请输入Zelle邮箱或手机号',
        'paytm': '请输入Paytm手机号',
        'phonepe': '请输入PhonePe号码',
        'googlepay': '请输入Google Pay详情',
        'applepay': '请输入Apple Pay详情',
        'cash_in_person': '请输入见面地点和说明',
        'cash_deposit': '请输入存款方式和说明',
        'cash_by_mail': '请输入邮寄地址和说明',
        'atm_cash': '请输入ATM取款码说明',
        'amazon_gift': '请输入礼品卡交付说明',
        'steam_gift': '请输入Steam礼品卡代码交付方式',
        'itunes_gift': '请输入iTunes礼品卡交付方式',
        'google_play_gift': '请输入Google Play礼品卡交付方式',
        'ebay_gift': '请输入eBay礼品卡交付方式',
        'walmart_gift': '请输入Walmart礼品卡交付方式',
        'bestbuy_gift': '请输入Best Buy礼品卡交付方式',
        'target_gift': '请输入Target礼品卡交付方式',
        'starbucks_gift': '请输入Starbucks礼品卡交付方式',
        'usdt_trc20': '请输入TRC20钱包地址',
        'usdt_erc20': '请输入ERC20钱包地址',
        'usdc': '请输入USDC钱包地址',
        'dai': '请输入DAI钱包地址',
        'busd': '请输入BUSD钱包地址',
        'tusd': '请输入TUSD钱包地址',
    };
    
    return placeholders[value] || '请输入支付详情';
};