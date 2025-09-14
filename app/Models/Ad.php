<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'network',
        'currency_key',
        'fiat_currency',
        'trade_type',
        'min_limit',
        'max_limit',
        'price',
        'price_model',
        'margin',
        'payment_method',
        'country',
        'vendor_toc',
        'welcome_message',
        'notes',
        'status',
        'trades_count',
        'trade_address',
    ];

    protected $casts = [
        'min_limit' => 'decimal:2',
        'max_limit' => 'decimal:2',
        'price' => 'decimal:2',
        'margin' => 'decimal:2',
        'trades_count' => 'integer',
    ];

    protected $attributes = [
        'price_model' => 'fixed',
        'status' => 'active',
        'trades_count' => 0,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBuyType($query)
    {
        return $query->where('trade_type', 'buy');
    }

    public function scopeSellType($query)
    {
        return $query->where('trade_type', 'sell');
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByCryptocurrency($query, $currencyKey)
    {
        return $query->where('currency_key', $currencyKey);
    }
    
    /**
     * Get cryptocurrency info from config
     */
    public function getCryptoInfoAttribute()
    {
        return config("cryptocurrencies.supported.{$this->currency_key}");
    }
    
    /**
     * Get cryptocurrency name
     */
    public function getCryptocurrencyAttribute()
    {
        $info = $this->crypto_info;
        return $info ? $info['currency'] : $this->currency_key;
    }

    public function scopeByNetwork($query, $network)
    {
        return $query->where('network', $network);
    }


    public function getCurrentPrice()
    {
        if ($this->price_model === 'fixed') {
            return $this->price;
        }
        
        // TODO: 实现动态价格计算逻辑
        // 这里需要从外部API获取市场价格并计算margin
        $marketPrice = $this->getMarketPrice();
        return $marketPrice * (1 + $this->margin / 100);
    }

    private function getMarketPrice()
    {
        // TODO: 实现从外部API获取市场价格
        // 临时返回模拟价格
        $prices = [
            'BTC' => 30000,
            'ETH' => 2000,
            'USDT' => 1,
        ];
        
        return $prices[$this->currency_key] ?? 1;
    }
}