<?php

namespace App\Services;

class FeeService
{
    /**
     * Calculate trading fee based on currency and amount
     *
     * @param string $currencyKey
     * @param float $amount
     * @return array ['fee' => float, 'fee_type' => 'percentage' | 'flat', 'fee_currency' => string]
     */
    public function calculateFee(string $currencyKey, float $amount): array
    {
        $key = strtolower($currencyKey);

        switch ($key) {
            case 'btc':
                return $this->calculateBtcFee($amount);

            case 'eth':
                return $this->calculateEthFee($amount);

            case 'trc-usdt':
                return $this->calculateTrc20UsdtFee($amount);

            case 'erc-usdt':
            case 'erc-usdc':
                return $this->calculateErc20Fee($amount);

            case 'bep-usdt':
            case 'bep-usdc':
                return $this->calculateBep20Fee($amount);

            default:
                // Default 1% fee for unknown currencies
                return [
                    'fee' => $amount * 0.01,
                    'fee_type' => 'percentage',
                    'fee_rate' => 0.01,
                    'fee_currency' => $currencyKey
                ];
        }
    }

    /**
     * Calculate BTC trading fee
     * For trades >= 0.005 BTC: 1% fee
     * For trades < 0.005 BTC: flat 0.00005 BTC fee
     */
    private function calculateBtcFee(float $amount): array
    {
        if ($amount >= 0.005) {
            return [
                'fee' => $amount * 0.01,
                'fee_type' => 'percentage',
                'fee_rate' => 0.01,
                'fee_currency' => 'BTC'
            ];
        } else {
            return [
                'fee' => 0.00005,
                'fee_type' => 'flat',
                'fee_currency' => 'BTC'
            ];
        }
    }

    /**
     * Calculate ETH trading fee
     * For trades >= 0.1 ETH: 1% fee
     * For trades < 0.1 ETH: flat 0.001 ETH fee
     */
    private function calculateEthFee(float $amount): array
    {
        if ($amount >= 0.1) {
            return [
                'fee' => $amount * 0.01,
                'fee_type' => 'percentage',
                'fee_rate' => 0.01,
                'fee_currency' => 'ETH'
            ];
        } else {
            return [
                'fee' => 0.001,
                'fee_type' => 'flat',
                'fee_currency' => 'ETH'
            ];
        }
    }

    /**
     * Calculate TRC20 USDT fee
     * 1% + 2 USDT
     */
    private function calculateTrc20UsdtFee(float $amount): array
    {
        return [
            'fee' => ($amount * 0.01) + 2,
            'fee_type' => 'mixed',
            'fee_rate' => 0.01,
            'flat_fee' => 2,
            'fee_currency' => 'USDT'
        ];
    }

    /**
     * Calculate ERC20 (USDT/USDC) fee
     * 1% + 4 USD
     */
    private function calculateErc20Fee(float $amount): array
    {
        return [
            'fee' => ($amount * 0.01) + 4,
            'fee_type' => 'mixed',
            'fee_rate' => 0.01,
            'flat_fee' => 4,
            'fee_currency' => 'USD'
        ];
    }

    /**
     * Calculate BEP20 (USDT/USDC) fee
     * 1% + 2 USD
     */
    private function calculateBep20Fee(float $amount): array
    {
        return [
            'fee' => ($amount * 0.01) + 2,
            'fee_type' => 'mixed',
            'fee_rate' => 0.01,
            'flat_fee' => 2,
            'fee_currency' => 'USD'
        ];
    }

    /**
     * Get fee structure information for a currency
     */
    public function getFeeStructure(string $currencyKey): array
    {
        $key = strtolower($currencyKey);

        $feeStructures = [
            'btc' => [
                'currency' => 'BTC',
                'rules' => [
                    ['condition' => '>= 0.005 BTC', 'fee' => '1%', 'type' => 'percentage'],
                    ['condition' => '< 0.005 BTC', 'fee' => '0.00005 BTC', 'type' => 'flat']
                ]
            ],
            'eth' => [
                'currency' => 'ETH',
                'rules' => [
                    ['condition' => '>= 0.1 ETH', 'fee' => '1%', 'type' => 'percentage'],
                    ['condition' => '< 0.1 ETH', 'fee' => '0.001 ETH', 'type' => 'flat']
                ]
            ],
            'trc-usdt' => [
                'currency' => 'USDT',
                'rules' => [
                    ['condition' => 'All trades', 'fee' => '1% + 2 USDT', 'type' => 'mixed']
                ]
            ],
            'erc-usdt' => [
                'currency' => 'USDT',
                'rules' => [
                    ['condition' => 'All trades', 'fee' => '1% + 4 USD', 'type' => 'mixed']
                ]
            ],
            'erc-usdc' => [
                'currency' => 'USDC',
                'rules' => [
                    ['condition' => 'All trades', 'fee' => '1% + 4 USD', 'type' => 'mixed']
                ]
            ],
            'bep-usdt' => [
                'currency' => 'USDT',
                'rules' => [
                    ['condition' => 'All trades', 'fee' => '1% + 2 USD', 'type' => 'mixed']
                ]
            ],
            'bep-usdc' => [
                'currency' => 'USDC',
                'rules' => [
                    ['condition' => 'All trades', 'fee' => '1% + 2 USD', 'type' => 'mixed']
                ]
            ]
        ];

        // Check if specific fee structure exists
        if (isset($feeStructures[$key])) {
            return $feeStructures[$key];
        }

        // Default
        return [
            'currency' => strtoupper($currencyKey),
            'rules' => [
                ['condition' => 'All trades', 'fee' => '1%', 'type' => 'percentage']
            ]
        ];
    }

    /**
     * Format fee for display
     */
    public function formatFee(array $feeData): string
    {
        if ($feeData['fee_type'] === 'percentage') {
            return number_format($feeData['fee'], 8) . ' ' . $feeData['fee_currency'];
        } elseif ($feeData['fee_type'] === 'flat') {
            return number_format($feeData['fee'], 8) . ' ' . $feeData['fee_currency'];
        } else {
            // Mixed type
            return number_format($feeData['fee'], 2) . ' ' . $feeData['fee_currency'];
        }
    }
}
