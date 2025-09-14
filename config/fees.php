<?php

return [
    /**
     * Trading fee configuration for different cryptocurrencies
     */
    'trading_fees' => [
        'btc' => [
            'threshold' => 0.005,
            'percentage_fee' => 0.01, // 1%
            'flat_fee' => 0.00005,
            'currency' => 'BTC',
            'description' => 'For trades >= 0.005 BTC: 1% fee, For trades < 0.005 BTC: 0.00005 BTC flat fee'
        ],

        'eth' => [
            'threshold' => 0.1,
            'percentage_fee' => 0.01, // 1%
            'flat_fee' => 0.001,
            'currency' => 'ETH',
            'description' => 'For trades >= 0.1 ETH: 1% fee, For trades < 0.1 ETH: 0.001 ETH flat fee'
        ],

        'trc-usdt' => [
            'percentage_fee' => 0.01, // 1%
            'additional_flat_fee' => 2,
            'currency' => 'USDT',
            'description' => '1% + 2 USDT for all trades'
        ],

        'erc-usdt' => [
            'percentage_fee' => 0.01, // 1%
            'additional_flat_fee' => 4,
            'currency' => 'USD',
            'description' => '1% + 4 USD for all trades'
        ],

        'erc-usdc' => [
            'percentage_fee' => 0.01, // 1%
            'additional_flat_fee' => 4,
            'currency' => 'USD',
            'description' => '1% + 4 USD for all trades'
        ],

        'bep-usdt' => [
            'percentage_fee' => 0.01, // 1%
            'additional_flat_fee' => 2,
            'currency' => 'USD',
            'description' => '1% + 2 USD for all trades'
        ],

        'bep-usdc' => [
            'percentage_fee' => 0.01, // 1%
            'additional_flat_fee' => 2,
            'currency' => 'USD',
            'description' => '1% + 2 USD for all trades'
        ],
    ],

    /**
     * Default fee for currencies not listed above
     */
    'default_fee' => [
        'percentage_fee' => 0.01, // 1%
        'description' => '1% for all trades'
    ],

    /**
     * Minimum and maximum fee limits (optional)
     */
    'limits' => [
        'min_fee_usd' => 0.10, // Minimum fee in USD equivalent
        'max_fee_percentage' => 0.05, // Maximum 5% fee cap
    ]
];