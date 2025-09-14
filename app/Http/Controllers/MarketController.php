<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CryptoPriceService;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MarketController extends Controller
{
    protected CryptoPriceService $cryptoService;
    protected ExchangeRateService $exchangeService;

    public function __construct(
        CryptoPriceService $cryptoService,
        ExchangeRateService $exchangeService
    ) {
        $this->cryptoService = $cryptoService;
        $this->exchangeService = $exchangeService;
    }

    public function getPrices(Request $request): JsonResponse
    {
        $btcUsd = $this->cryptoService->getBtcPrice();
        $ethUsd = $this->cryptoService->getEthPrice();
        $usdtUsd = 1.0;
        $usdcUsd = 1.0; // USDC is a stablecoin pegged to USD

        if (!$btcUsd || !$ethUsd) {
            return response()->json([
                'error' => '无法获取加密货币价格'
            ], 500);
        }

        $exchangeRates = $this->exchangeService->getAllRates();

        if (empty($exchangeRates)) {
            return response()->json([
                'error' => '无法获取汇率信息'
            ], 500);
        }

        return response()->json([
            'crypto_prices_usd' => [
                'USDT' => $usdtUsd,
                'USDC' => $usdcUsd,
                'BTC' => $btcUsd,
                'ETH' => $ethUsd
            ],
            'exchange_rates' => $exchangeRates,
            'updated_at' => now()->toDateTimeString()
        ]);
    }
}
