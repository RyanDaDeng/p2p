<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Common\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ConfigController extends ApiController
{
    /**
     * Get all configuration data
     * Cached for 1 hour
     */
    public function index(Request $request)
    {
        $cacheKey = "config_data";
        
        $data = Cache::remember($cacheKey, 3600, function() {
            return [
                'cryptocurrencies' => $this->getCryptocurrencies(),
                'payment_methods' => $this->getPaymentMethods(),
                'fiat_currencies' => $this->getFiatCurrencies(),
                'countries' => $this->getCountries()
            ];
        });
        
        return $this->sendSuccess($data);
    }
    
    
    /**
     * Get formatted cryptocurrencies
     */
    private function getCryptocurrencies()
    {
        $cryptos = config('cryptocurrencies.supported', []);
        
        $result = [];
        foreach ($cryptos as $key => $crypto) {
            $result[] = [
                'key' => $crypto['key'],
                'currency' => $crypto['currency'],
                'label' => $crypto['label'],
                'network' => $crypto['network']
            ];
        }
        
        return $result;
    }
    
    /**
     * Get formatted payment methods with categories
     */
    private function getPaymentMethods()
    {
        return config('payment_methods.categories', []);
    }
    
    /**
     * Get formatted fiat currencies
     */
    private function getFiatCurrencies()
    {
        $currencies = config('fiat_currencies.supported', []);
        
        $result = [];
        foreach ($currencies as $code => $currency) {
            $result[] = [
                'value' => $currency['code'],
                'code' => $currency['code'],
                'symbol' => $currency['symbol'],
                'label' => $currency['label'],
                'decimal_places' => $currency['decimal_places']
            ];
        }
        
        return $result;
    }
    
    /**
     * Get formatted countries
     */
    private function getCountries()
    {
        $countries = config('countries.supported', []);
        
        $result = [];
        foreach ($countries as $code => $country) {
            $result[] = [
                'value' => $country['code'],
                'code' => $country['code'],
                'name' => $country['name'],  // Use English name
                'flag' => $country['flag']
            ];
        }
        
        return $result;
    }
}