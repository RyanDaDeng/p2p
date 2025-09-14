<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Common\ApiController;
use App\Models\TradeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeSettingController extends ApiController
{
    public function get()
    {
        $setting = TradeSetting::where('user_id', Auth::id())->first();
        
        if (!$setting) {
            $setting = [
                'vendor_toc' => '',
                'welcome_message' => '',
            ];
        }
        
        return $this->sendSuccess([
            'settings' => $setting
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'vendor_toc' => 'nullable|string|max:5000',
            'welcome_message' => 'nullable|string|max:1000',
        ]);

        $setting = TradeSetting::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'vendor_toc' => $request->vendor_toc,
                'welcome_message' => $request->welcome_message,
            ]
        );

        return $this->sendSuccessWithNotify('交易设置保存成功', [
            'settings' => $setting
        ]);
    }
}