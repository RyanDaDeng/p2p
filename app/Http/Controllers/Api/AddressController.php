<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'addresses' => $addresses
            ]
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency_key' => 'required|string',
            'address' => 'required|string',
        ]);
        
        // Get cryptocurrency info from config
        $cryptoConfig = config("cryptocurrencies.supported.{$validated['currency_key']}");
        
        if (!$cryptoConfig) {
            return response()->json([
                'success' => false,
                'message' => '不支持的加密货币类型'
            ], 422);
        }
        
        // Check if this address already exists for this user
        $userExists = Address::where('user_id', Auth::id())
            ->where('currency_key', $validated['currency_key'])
            ->where('address', $validated['address'])
            ->exists();
            
        if ($userExists) {
            return response()->json([
                'success' => false,
                'message' => '您已添加过该地址'
            ], 422);
        }
        
        // Check if this address is already used by another user (unique constraint)
        $addressExists = Address::where('currency_key', $validated['currency_key'])
            ->where('address', $validated['address'])
            ->exists();
            
        if ($addressExists) {
            return response()->json([
                'success' => false,
                'message' => '该地址已被其他用户使用'
            ], 422);
        }
        
        try {
            $address = Address::create([
                'user_id' => Auth::id(),
                'chain' => $cryptoConfig['chain'],
                'currency' => $cryptoConfig['currency'],
                'currency_key' => $validated['currency_key'],
                'network' => $cryptoConfig['network'],
                'chain_label' => $cryptoConfig['chain_label'],
                'address' => $validated['address'],
                'is_verified' => true, // Default to true for now
            ]);
            
            return response()->json([
                'success' => true,
                'message' => '地址添加成功',
                'data' => [
                    'address' => $address
                ]
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if ($e->getCode() === '23000') { // Integrity constraint violation
                return response()->json([
                    'success' => false,
                    'message' => '该地址已被使用或数据冲突'
                ], 422);
            }
            
            // Re-throw other database errors
            throw $e;
        }
    }
}