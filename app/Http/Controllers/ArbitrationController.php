<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArbitrationController extends Controller
{
    /**
     * 显示需要仲裁的订单列表
     */
    public function index(Request $request)
    {
        $query = Order::with(['vendor', 'client', 'buyer', 'seller'])
            ->where('is_disputed', true)
            ->orderBy('created_at', 'desc');
        
        // 筛选状态
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // 搜索订单号
        if ($request->has('search') && $request->search) {
            $query->where('order_no', 'like', '%' . $request->search . '%');
        }
        
        $orders = $query->paginate(20);
        
        return Inertia::render('Arbitration/Index', [
            'orders' => $orders->through(function ($order) {
                return [
                    'id' => $order->id,
                    'order_no' => $order->order_no,
                    'vendor' => [
                        'id' => $order->vendor->id,
                        'name' => $order->vendor->name,
                    ],
                    'client' => [
                        'id' => $order->client->id,
                        'name' => $order->client->name,
                    ],
                    'buyer' => [
                        'id' => $order->buyer->id,
                        'name' => $order->buyer->name,
                    ],
                    'seller' => [
                        'id' => $order->seller->id,
                        'name' => $order->seller->name,
                    ],
                    'crypto_currency' => $order->currency_key,
                    'crypto_amount' => $order->crypto_amount,
                    'fiat_currency' => $order->fiat_currency,
                    'fiat_amount' => $order->fiat_amount,
                    'status' => $order->status,
                    'escrow_status' => $order->escrow_status,
                    'is_disputed' => $order->is_disputed,
                    'disputed_by' => $order->disputed_by,
                    'disputed_at' => $order->disputed_at,
                    'dispute_reason' => $order->dispute_reason,
                    'created_at' => $order->created_at->toISOString(),
                ];
            }),
            'filters' => [
                'status' => $request->status ?? 'all',
                'search' => $request->search ?? '',
            ],
        ]);
    }
    
}