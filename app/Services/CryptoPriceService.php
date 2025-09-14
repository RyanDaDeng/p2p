<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CryptoPriceService
{
    protected string $cacheKey = 'crypto_prices';
    protected int $cacheMinutes = 2;

    public function getPrices(): array
    {
        if (Cache::has($this->cacheKey)) {
            return Cache::get($this->cacheKey);
        }

        $data = $this->fetchFromApi();
        $prices = $this->mapApiData($data);
        
        if (!empty($prices)) {
            Cache::put($this->cacheKey, $prices, now()->addMinutes($this->cacheMinutes));
        }
        
        return $prices;
    }

    protected function fetchFromApi(): array
    {
        try {
            $response = Http::timeout(10)->get('https://api.binance.com/api/v3/ticker/price', [
                'symbols' => '["BTCUSDT","ETHUSDT"]'
            ]);
            
            if (!$response->successful()) {
                Log::error('Failed to fetch crypto prices', ['status' => $response->status()]);
                return [];
            }
            
            return $response->json();
            
        } catch (\Exception $e) {
            Log::error('Binance API error', ['message' => $e->getMessage()]);
            return [];
        }
    }

    protected function mapApiData(array $data): array
    {
        $prices = [];
        
        foreach ($data as $item) {
            if ($item['symbol'] === 'BTCUSDT') {
                $prices['BTC'] = (float) $item['price'];
            } elseif ($item['symbol'] === 'ETHUSDT') {
                $prices['ETH'] = (float) $item['price'];
            }
        }
        
        return $prices;
    }

    public function getBtcPrice(): ?float
    {
        $prices = $this->getPrices();
        return $prices['BTC'] ?? null;
    }

    public function getEthPrice(): ?float
    {
        $prices = $this->getPrices();
        return $prices['ETH'] ?? null;
    }

    public function refreshPrices(): array
    {
        Cache::forget($this->cacheKey);
        return $this->getPrices();
    }
}