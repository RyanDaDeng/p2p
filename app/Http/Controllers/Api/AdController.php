<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Common\ApiController;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends ApiController
{
    public function index(Request $request)
    {
        $query = Ad::active()->with('user');

        if ($request->has('trade_type')) {
            $query->where('trade_type', $request->trade_type);
        }

        if ($request->has('currency_key')) {
            $query->where('currency_key', $request->currency_key);
        }

        if ($request->has('network')) {
            $query->where('network', $request->network);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('country')) {
            $query->where('country', $request->country);
        }

        if ($request->has('fiat_currency')) {
            $query->where('fiat_currency', $request->fiat_currency);
        }

        if ($request->has('min_amount')) {
            $query->where('max_limit', '>=', $request->min_amount);
        }

        if ($request->has('max_amount')) {
            $query->where('min_limit', '<=', $request->max_amount);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'rating':
                // Join with users table to sort by avg_rating
                $query->leftJoin('users', 'ads.user_id', '=', 'users.id')
                      ->orderBy('users.avg_rating', $sortOrder)
                      ->select('ads.*');
                break;
            case 'trades':
                $query->orderBy('trades_count', $sortOrder);
                break;
            case 'response':
                // Join with users table to sort by response_time
                $query->leftJoin('users', 'ads.user_id', '=', 'users.id')
                      ->orderByRaw('users.response_time IS NULL')
                      ->orderBy('users.response_time', 'asc')
                      ->select('ads.*');
                break;
            case 'created_at':
            case 'recent':
                $query->orderBy('created_at', $sortOrder);
                break;
            default:
                // Default to created_at (recent)
                $query->orderBy('created_at', $sortOrder);
        }

        $perPage = $request->get('per_page', 10);
        $ads = $query->paginate($perPage);

        // Add payment method names and country names to each ad
        $countries = config('countries.supported', []);
        $adsData = collect($ads->items())->map(function ($ad) use ($countries) {
            $ad->payment_method_name = \App\Helpers\PaymentHelper::getLabel($ad->payment_method) ?? $ad->payment_method;
            $ad->country_name = $countries[$ad->country]['name'] ?? $ad->country;

            // Add user response time and profile photo
            if ($ad->user) {
                $ad->user->response_time = $ad->user->response_time;
                $ad->user->profile_photo_url = $ad->user->profile_photo_url;
            }

            return $ad;
        });

        return $this->sendSuccess([
            'ads' => $adsData,
            'pagination' => [
                'current_page' => $ads->currentPage(),
                'last_page' => $ads->lastPage(),
                'per_page' => $ads->perPage(),
                'total' => $ads->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $ad = Ad::with('user')->findOrFail($id);

        // Add payment method name and country name
        $ad->payment_method_name = \App\Helpers\PaymentHelper::getLabel($ad->payment_method) ?? $ad->payment_method;

        // Get country name from config
        $countries = config('countries.supported', []);
        $ad->country_name = $countries[$ad->country]['name'] ?? $ad->country;

        // 如果是广告所有者，可以查看任何状态的广告
        if ($ad->user_id === Auth::id()) {
            return $this->sendSuccess([
                'ad' => $ad
            ]);
        }

        // 其他用户只能查看active状态的广告
        if ($ad->status !== 'active') {
            return $this->sendError('广告不存在或已下架');
        }

        return $this->sendSuccess([
            'ad' => $ad
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency_key' => 'required|string|max:50',
            'fiat_currency' => 'required|string|max:10',
            'trade_type' => 'required|in:buy,sell',
            'trade_address' => 'required|exists:addresses,id', // Required for both buy and sell
            'min_limit' => 'required|numeric|min:0',
            'max_limit' => 'required|numeric|gt:min_limit',
            'price_model' => 'required|in:fixed,dynamic',
            'price' => 'required_if:price_model,fixed|nullable|numeric|min:0',
            'margin' => 'required_if:price_model,dynamic|nullable|numeric|min:-99|max:999',
            'payment_method' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'vendor_toc' => 'nullable|string',
            'welcome_message' => 'nullable|string',
            'notes' => 'nullable|string|max:255',
        ]);

        // Validate that the address belongs to the user and matches the currency
        $actualAddress = null;
        if ($request->trade_address) { // Check for both buy and sell
            $address = \App\Models\Address::find($request->trade_address);
            
            if (!$address) {
                return $this->sendError('地址不存在');
            }
            
            if ($address->user_id !== Auth::id()) {
                return $this->sendError('该地址不属于您');
            }
            
            // Strict validation: currency_key must match exactly
            if ($address->currency_key !== $request->currency_key) {
                return $this->sendError('选择的地址与加密货币不匹配，请选择正确的' . $request->currency_key . '地址');
            }
            
            // Store the actual address string
            $actualAddress = $address->address;
        }

        // Get network from config based on currency_key
        $cryptocurrencies = config('cryptocurrencies.supported', []);
        $network = null;
        foreach ($cryptocurrencies as $crypto) {
            if ($crypto['key'] === $request->currency_key) {
                $network = $crypto['network'] ?? null;
                break;
            }
        }

        $adData = $request->only([
            'currency_key',
            'fiat_currency',
            'trade_type',
            'min_limit',
            'max_limit',
            'price_model',
            'payment_method',
            'country',
            'vendor_toc',
            'welcome_message',
            'notes',
        ]);

        // Add network from config
        $adData['network'] = $network;
        
        // Replace the ID with the actual address string
        if ($actualAddress) {
            $adData['trade_address'] = $actualAddress;
        }
        
        // 根据定价模式设置price或margin
        if ($request->price_model === 'fixed') {
            $adData['price'] = $request->price;
            $adData['margin'] = null;
        } else {
            $adData['price'] = null;
            $adData['margin'] = $request->margin;
        }
        
        $adData['user_id'] = Auth::id();

        $ad = Ad::create($adData);

        return $this->sendSuccessWithNotify('广告发布成功', [
            'ad' => $ad
        ]);
    }

    public function update(Request $request, $id)
    {
        $ad = Ad::findOrFail($id);

        if ($ad->user_id !== Auth::id()) {
            return $this->sendError('无权操作此广告');
        }

        $request->validate([
            'currency_key' => 'string|max:50',
            'fiat_currency' => 'string|max:10',
            'trade_type' => 'in:buy,sell',
            'trade_address' => 'nullable|exists:addresses,id',
            'min_limit' => 'numeric|min:0',
            'max_limit' => 'numeric|gt:min_limit',
            'price_model' => 'in:fixed,dynamic',
            'price' => 'required_if:price_model,fixed|nullable|numeric|min:0',
            'margin' => 'required_if:price_model,dynamic|nullable|numeric|min:-99|max:999',
            'payment_method' => 'string|max:100',
            'country' => 'string|max:100',
            'vendor_toc' => 'nullable|string',
            'welcome_message' => 'nullable|string',
            'notes' => 'nullable|string|max:255',
            'status' => 'in:active,inactive',
        ]);

        // Validate that the address belongs to the user and matches the currency if updating
        $actualAddress = null;
        if ($request->has('trade_address')) { // Check for both buy and sell
            $address = \App\Models\Address::find($request->trade_address);
            
            if (!$address) {
                return $this->sendError('地址不存在');
            }
            
            if ($address->user_id !== Auth::id()) {
                return $this->sendError('该地址不属于您');
            }
            
            // If currency_key is being updated, validate it matches the address
            if ($request->has('currency_key')) {
                if ($address->currency_key !== $request->currency_key) {
                    return $this->sendError('选择的地址与加密货币不匹配，请选择正确的' . $request->currency_key . '地址');
                }
            } else {
                // If currency_key is not being updated, check against existing ad's currency_key
                if ($address->currency_key !== $ad->currency_key) {
                    return $this->sendError('选择的地址与广告的加密货币不匹配');
                }
            }
            
            // Store the actual address string
            $actualAddress = $address->address;
        }

        $updateData = $request->only([
            'currency_key',
            'fiat_currency',
            'trade_type',
            'min_limit',
            'max_limit',
            'price_model',
            'payment_method',
            'country',
            'vendor_toc',
            'welcome_message',
            'notes',
            'status',
        ]);

        // If currency_key is being updated, get network from config
        if ($request->has('currency_key')) {
            $cryptocurrencies = config('cryptocurrencies.supported', []);
            $network = null;
            foreach ($cryptocurrencies as $crypto) {
                if ($crypto['key'] === $request->currency_key) {
                    $network = $crypto['network'] ?? null;
                    break;
                }
            }
            $updateData['network'] = $network;
        }
        
        // Replace the ID with the actual address string
        if ($actualAddress) {
            $updateData['trade_address'] = $actualAddress;
        }
        
        // 根据定价模式设置price或margin
        if ($request->has('price_model')) {
            if ($request->price_model === 'fixed') {
                $updateData['price'] = $request->price;
                $updateData['margin'] = null;
            } else {
                $updateData['price'] = null;
                $updateData['margin'] = $request->margin;
            }
        }
        
        $ad->update($updateData);

        return $this->sendSuccessWithNotify('广告更新成功', [
            'ad' => $ad
        ]);
    }

    public function archive($id)
    {
        $ad = Ad::findOrFail($id);

        if ($ad->user_id !== Auth::id()) {
            return $this->sendError('无权操作此广告');
        }

        $ad->update(['status' => 'archived']);

        return $this->sendSuccessWithNotify('广告已归档');
    }

    public function restore($id)
    {
        $ad = Ad::findOrFail($id);

        if ($ad->user_id !== Auth::id()) {
            return $this->sendError('无权操作此广告');
        }

        if ($ad->status !== 'archived') {
            return $this->sendError('只能恢复已归档的广告');
        }

        $ad->update(['status' => 'inactive']);

        return $this->sendSuccessWithNotify('广告已恢复，当前状态为暂停');
    }

    public function myAds(Request $request)
    {
        $query = Ad::where('user_id', Auth::id());

        // Debug log
        \Log::info('MyAds Request Parameters:', [
            'status' => $request->status,
            'type' => $request->type,
            'has_status' => $request->has('status'),
            'has_type' => $request->has('type'),
        ]);

        // 根据状态过滤
        if ($request->has('status')) {
            if ($request->status === 'archived') {
                // 只显示归档的广告
                $query->where('status', 'archived');
            } else {
                // 显示特定状态，但排除已删除的
                $query->where('status', $request->status)
                      ->where('status', '!=', 'deleted');
            }
        } else {
            // 默认不显示归档和已删除的广告
            $query->whereNotIn('status', ['archived', 'deleted']);
        }

        // 根据类型过滤（buy/sell）
        if ($request->has('type')) {
            $query->where('trade_type', $request->type);
        }

        // Debug: Log the query before pagination
        \Log::info('Query SQL:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $ads = $query->orderBy('created_at', 'desc')
                     ->paginate($request->get('per_page', 10));

        \Log::info('MyAds Query Result:', [
            'total_ads' => $ads->total(),
            'current_page' => $ads->currentPage(),
            'items_count' => count($ads->items())
        ]);

        // Add currency_label to each ad
        $cryptocurrencies = config('cryptocurrencies.supported', []);
        $adsWithLabels = collect($ads->items())->map(function ($ad) use ($cryptocurrencies) {
            $ad->currency_label = $cryptocurrencies[$ad->currency_key]['label'] ?? $ad->cryptocurrency;
            return $ad;
        });

        return $this->sendSuccess([
            'ads' => $adsWithLabels,
            'pagination' => [
                'current_page' => $ads->currentPage(),
                'last_page' => $ads->lastPage(),
                'per_page' => $ads->perPage(),
                'total' => $ads->total(),
            ]
        ]);
    }

    public function toggleStatus($id)
    {
        $ad = Ad::findOrFail($id);

        if ($ad->user_id !== Auth::id()) {
            return $this->sendError('无权操作此广告');
        }

        $newStatus = $ad->status === 'active' ? 'inactive' : 'active';
        $ad->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? '广告已上架' : '广告已下架';
        
        return $this->sendSuccessWithNotify($message, [
            'ad' => $ad
        ]);
    }
}