<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Common\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Services\FireBlocksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends ApiController
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

    public function store(Request $request, FireBlocksService $fireBlocksService)
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

        $userId = Auth::id();
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


        $assetId = $fireBlocksService->getAssetId($validated['currency_key']);

        if(empty($assetId)){
            return response()->json([
                'success' => false,
                'message' => '外部服务不支持的加密货币类型'
            ], 422);
        }

        try {
            $fireBlocksUserRef = Address::generateFireBlocksUserId($userId);
            // check compliance from 3rd party
            $res = $fireBlocksService->complianceAddressCheck(
                $fireBlocksUserRef,
                $assetId,
                $validated['address'],
                ''
            );


            if ($res['success']){

                $address = Address::create([
                    'user_id' => Auth::id(),
                    'chain' => $cryptoConfig['chain'],
                    'currency' => $cryptoConfig['currency'],
                    'currency_key' => $validated['currency_key'],
                    'network' => $cryptoConfig['network'],
                    'chain_label' => $cryptoConfig['chain_label'],
                    'address' => $validated['address'],
                    'status' => Address::PENDING_STATUS,

                    'fireblocks_status' => $res['compliance_status'],
                    'fireblocks_status_ref' => $res['analysis_id'], // Default to true for now
                    'fireblocks_data' => json_encode([
                        'user_id' => $fireBlocksUserRef,
                        'asset_id' => $assetId,
                        'address' => $validated['address']
                    ]),
                    'fireblocks_report' => null
                ]);

                return  $this->sendSuccessWithNotify(
                    '地址已经提交，系统正在进行安全检测，大约需要5分钟。'
                );

            } else {

                if ($res['error_code'] === 410){
                    return response()->json([
                        'success' => false,
                        'message' => '该账户已注册同币种的其他地址,不能重复注册。',
                    ]);
                }
                return response()->json([
                    'success' => false,
                    'message' => '无法添加您的地址，外部服务不可用。',
                ]);
            }

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if ($e->getCode() === '23000') { // Integrity constraint violation
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            // Re-throw other database errors
            throw $e;
        }
    }

    public function destroy($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => '地址不存在或无权限删除'
            ], 404);
        }

        if ($address->status === Address::PENDING_STATUS || $address->status === Address::REGISTERED_STATUS) {
            return response()->json([
                'success' => false,
                'message' => '该地址正在使用中或待验证，无法删除'
            ], 422);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => '地址已删除'
        ]);
    }
}
