<?php

return [
    /**
     * Payment method categories
     */
    'categories' => [
        [
            'id' => 'bank_transfers',
            'name' => 'Bank Transfers',
            'icon' => '🏦',
            'color' => 'blue',
            'methods' => [
                ['value' => 'bank_transfer', 'label' => 'Bank Transfer', 'popular' => true],
                ['value' => 'swift', 'label' => 'SWIFT', 'popular' => false],
                ['value' => 'sepa', 'label' => 'SEPA', 'popular' => false],
                ['value' => 'ach', 'label' => 'ACH Transfer', 'popular' => false],
                ['value' => 'wire_transfer', 'label' => 'Wire Transfer', 'popular' => true],
                ['value' => 'interac', 'label' => 'Interac e-Transfer', 'popular' => false],
                ['value' => 'imps', 'label' => 'IMPS', 'popular' => false],
                ['value' => 'rtgs', 'label' => 'RTGS', 'popular' => false],
                ['value' => 'neft', 'label' => 'NEFT', 'popular' => false],
                ['value' => 'upi', 'label' => 'UPI', 'popular' => true],
            ]
        ],
        [
            'id' => 'online_wallets',
            'name' => 'Online Wallets',
            'icon' => '💳',
            'color' => 'emerald',
            'methods' => [
                ['value' => 'alipay', 'label' => 'Alipay', 'popular' => true],
                ['value' => 'wechat', 'label' => 'WeChat Pay', 'popular' => true],
                ['value' => 'paypal', 'label' => 'PayPal', 'popular' => true],
                ['value' => 'skrill', 'label' => 'Skrill', 'popular' => false],
                ['value' => 'neteller', 'label' => 'Neteller', 'popular' => false],
                ['value' => 'perfect_money', 'label' => 'Perfect Money', 'popular' => false],
                ['value' => 'webmoney', 'label' => 'WebMoney', 'popular' => false],
                ['value' => 'payoneer', 'label' => 'Payoneer', 'popular' => false],
                ['value' => 'wise', 'label' => 'Wise (TransferWise)', 'popular' => true],
                ['value' => 'revolut', 'label' => 'Revolut', 'popular' => true],
                ['value' => 'cashapp', 'label' => 'Cash App', 'popular' => true],
                ['value' => 'venmo', 'label' => 'Venmo', 'popular' => true],
                ['value' => 'zelle', 'label' => 'Zelle', 'popular' => true],
                ['value' => 'paytm', 'label' => 'Paytm', 'popular' => true],
                ['value' => 'phonepe', 'label' => 'PhonePe', 'popular' => false],
                ['value' => 'googlepay', 'label' => 'Google Pay', 'popular' => true],
                ['value' => 'applepay', 'label' => 'Apple Pay', 'popular' => true],
            ]
        ],
        [
            'id' => 'cash_payments',
            'name' => 'Cash Payments',
            'icon' => '💵',
            'color' => 'amber',
            'methods' => [
                ['value' => 'cash_in_person', 'label' => 'Cash in Person', 'popular' => true],
                ['value' => 'cash_deposit', 'label' => 'Cash Deposit', 'popular' => true],
                ['value' => 'cash_by_mail', 'label' => 'Cash by Mail', 'popular' => false],
                ['value' => 'atm_cash', 'label' => 'ATM Cash Code', 'popular' => false],
            ]
        ],
        [
            'id' => 'gift_cards',
            'name' => 'Gift Cards',
            'icon' => '🎁',
            'color' => 'purple',
            'methods' => [
                ['value' => 'amazon_gift', 'label' => 'Amazon Gift Card', 'popular' => true],
                ['value' => 'steam_gift', 'label' => 'Steam Gift Card', 'popular' => true],
                ['value' => 'itunes_gift', 'label' => 'iTunes Gift Card', 'popular' => true],
                ['value' => 'google_play_gift', 'label' => 'Google Play Gift Card', 'popular' => false],
                ['value' => 'ebay_gift', 'label' => 'eBay Gift Card', 'popular' => false],
                ['value' => 'walmart_gift', 'label' => 'Walmart Gift Card', 'popular' => false],
                ['value' => 'bestbuy_gift', 'label' => 'Best Buy Gift Card', 'popular' => false],
                ['value' => 'target_gift', 'label' => 'Target Gift Card', 'popular' => false],
                ['value' => 'starbucks_gift', 'label' => 'Starbucks Gift Card', 'popular' => false],
            ]
        ],
        [
            'id' => 'digital_currencies',
            'name' => 'Digital Currencies',
            'icon' => '🪙',
            'color' => 'orange',
            'methods' => [
                ['value' => 'usdt_trc20', 'label' => 'USDT (TRC20)', 'popular' => true],
                ['value' => 'usdt_erc20', 'label' => 'USDT (ERC20)', 'popular' => true],
                ['value' => 'usdc', 'label' => 'USDC', 'popular' => true],
                ['value' => 'dai', 'label' => 'DAI', 'popular' => false],
                ['value' => 'busd', 'label' => 'BUSD', 'popular' => false],
                ['value' => 'tusd', 'label' => 'TUSD', 'popular' => false],
            ]
        ]
    ],

    /**
     * Helper function to get all payment methods as key-value pairs
     * @return array ['payment_value' => 'Payment Label', ...]
     */
    'key_value' => function() {
        $keyValue = [];
        $categories = config('payment_methods.categories', []);

        foreach ($categories as $category) {
            foreach ($category['methods'] as $method) {
                $keyValue[$method['value']] = $method['label'];
            }
        }

        return $keyValue;
    }
];