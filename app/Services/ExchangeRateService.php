<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    protected array $currencies = [
        'EUR' => ['name' => 'Euro', 'country' => 'Germany', 'symbol' => '€'],
        'BRL' => ['name' => 'Brazilian Real', 'country' => 'Brazil', 'symbol' => 'R$'],
        'RUB' => ['name' => 'Russian Ruble', 'country' => 'Russia', 'symbol' => '₽'],
        'INR' => ['name' => 'Indian Rupee', 'country' => 'India', 'symbol' => '₹'],
        'CAD' => ['name' => 'Canadian Dollar', 'country' => 'Canada', 'symbol' => 'C$'],
        'AUD' => ['name' => 'Australian Dollar', 'country' => 'Australia', 'symbol' => 'A$'],
        'KRW' => ['name' => 'Korean Won', 'country' => 'South Korea', 'symbol' => '₩'],
        'JPY' => ['name' => 'Japanese Yen', 'country' => 'Japan', 'symbol' => '¥'],
        'HKD' => ['name' => 'Hong Kong Dollar', 'country' => 'Hong Kong', 'symbol' => 'HK$'],
        'SGD' => ['name' => 'Singapore Dollar', 'country' => 'Singapore', 'symbol' => 'S$'],
        'GBP' => ['name' => 'British Pound', 'country' => 'United Kingdom', 'symbol' => '£'],
        'USD' => ['name' => 'US Dollar', 'country' => 'United States', 'symbol' => '$'],
        'CNY' => ['name' => 'Chinese Yuan', 'country' => 'China', 'symbol' => '¥'],
    ];

    protected string $cacheKey = 'exchange_rates';
    protected int $cacheHours = 6;

    public function getRate(string $fromCurrency = 'USD', string $toCurrency = 'CNY'): ?float
    {
        $rates = $this->getAllRates();

        if (empty($rates)) {
            return null;
        }

        if ($fromCurrency === 'USD') {
            return $rates[$toCurrency] ?? null;
        }

        if ($toCurrency === 'USD') {
            return $rates[$fromCurrency] ? 1 / $rates[$fromCurrency] : null;
        }

        $fromRate = $rates[$fromCurrency] ?? null;
        $toRate = $rates[$toCurrency] ?? null;

        if ($fromRate && $toRate) {
            return $toRate / $fromRate;
        }

        return null;
    }

    public function getAllRates(bool $forceRefresh = false): array
    {
        if (!$forceRefresh && Cache::has($this->cacheKey)) {
            return Cache::get($this->cacheKey);
        }

        $data = $this->fetchFromApi();
        $rates = $this->mapApiData($data);

        if (!empty($rates)) {
            Cache::put($this->cacheKey, $rates, now()->addHours($this->cacheHours));
        }

        return $rates;
    }

    protected function fetchFromApi(): array
    {
        try {
            $response = Http::timeout(10)->get('https://api.exchangerate-api.com/v4/latest/USD');

            if (!$response->successful()) {
                Log::error('Failed to fetch exchange rates', [
                    'status' => $response->status()
                ]);
                return [];
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Exchange rate API error', [
                'message' => $e->getMessage()
            ]);

            return [];
        }
    }

    protected function mapApiData(array $data): array
    {
        $apiRates = $data['rates'] ?? [];

        if (empty($apiRates)) {
            return [];
        }

        $rates = [];
        foreach ($this->currencies as $code => $info) {
            if (isset($apiRates[$code])) {
                $rates[$code] = (float) $apiRates[$code];
            }
        }

        return $rates;
    }

    public function convert(float $amount, string $fromCurrency = 'USD', string $toCurrency = 'CNY'): ?float
    {
        $rate = $this->getRate($fromCurrency, $toCurrency);

        if ($rate === null) {
            return null;
        }

        return round($amount * $rate, 2);
    }

    public function getCurrencyInfo(string $code): ?array
    {
        return $this->currencies[$code] ?? null;
    }

    public function getSupportedCurrencies(): array
    {
        return $this->currencies;
    }

    public function formatCurrency(float $amount, string $currency): string
    {
        $info = $this->getCurrencyInfo($currency);

        if (!$info) {
            return number_format($amount, 2);
        }

        $symbol = $info['symbol'];

        if (in_array($currency, ['USD', 'CAD', 'AUD', 'SGD', 'HKD', 'BRL'])) {
            return $symbol . number_format($amount, 2);
        }

        if (in_array($currency, ['EUR', 'GBP'])) {
            return $symbol . number_format($amount, 2, '.', ',');
        }

        if ($currency === 'JPY' || $currency === 'KRW') {
            return $symbol . number_format($amount, 0);
        }

        return number_format($amount, 2) . ' ' . $symbol;
    }

    public function refreshRates(): array
    {
        return $this->getAllRates(true);
    }
}
