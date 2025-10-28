<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\EscrowService;
use App\Services\ResponseTimeService;
use App\Traits\ValidatesTwoFactorAuthentication;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
    use ValidatesTwoFactorAuthentication;

    protected $orderService;
    protected $escrowService;
    protected $responseTimeService;

    public function __construct(OrderService $orderService, EscrowService $escrowService, ResponseTimeService $responseTimeService)
    {
        $this->orderService = $orderService;
        $this->escrowService = $escrowService;
        $this->responseTimeService = $responseTimeService;
    }

    /**
     * 创建订单
     */
    public function create(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|exists:ads,id',
            'fiat_amount' => 'required|numeric|min:0',
            'address_id' => 'nullable|exists:addresses,id'
        ]);

        try {
            $order = $this->orderService->createOrder(
                $request->ad_id,
                $request->fiat_amount,
                $request->address_id
            );

            return response()->json([
                'success' => true,
                'message' => '订单创建成功',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 获取订单详情
     */
    public function show($id)
    {
        try {
            $order = $this->orderService->getOrderDetail($id);

            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 获取订单列表
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'status',
            'escrow_status',
            'trade_type',
            'cryptocurrency',
            'time_range',
            'search',
            'per_page'
        ]);

        $orders = $this->orderService->getOrderList($filters);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * 获取订单状态
     */
    public function status($id)
    {
        try {
            $status = $this->orderService->getOrderStatus($id);

            return response()->json([
                'success' => true,
                'data' => $status
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 取消订单
     */
    public function cancel($id, Request $request)
    {
        try {
            $order = $this->orderService->cancelOrder($id);

            return response()->json([
                'success' => true,
                'message' => '订单已取消',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 商家确认订单
     */
    public function vendorConfirm($id)
    {
        try {
            $order = $this->escrowService->vendorConfirm($id);

            return response()->json([
                'success' => true,
                'message' => '订单已确认',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 卖家标记已转币
     */
    public function sellerPaid($id, Request $request)
    {
        $request->validate([
            'tx_hash' => 'required|string|min:10'  // 交易哈希必填，最少10个字符
        ]);

        try {
            //todo 检查index
            if (Order::query()->where('escorw_tx_hash', $request->tx_hash)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => '该HASH已经被使用过了！'
                ], 400);
            }

            $order = $this->escrowService->sellerMarkPaid($id, $request->tx_hash);

            return response()->json([
                'success' => true,
                'message' => '已标记转币',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 买家确认托管到账
     */
    public function buyerConfirmEscrow($id)
    {
        try {
            $order = $this->escrowService->buyerConfirmEscrow($id);

            return response()->json([
                'success' => true,
                'message' => '已确认托管到账',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 买家标记已付款
     */
    public function buyerPaid($id, Request $request)
    {
        $request->validate([
            'payment_proof' => 'nullable|string',
            'two_factor_code' => 'required|string|min:6'
        ]);

        try {
            $user = auth()->user();

            // 强制要求2FA验证
            if (!$this->userHasTwoFactorEnabled($user)) {
                // 如果用户未启用2FA，必须先启用
                return response()->json([
                    'success' => false,
                    'message' => '为了您的资金安全，买家付款必须启用双重验证',
                    'require_2fa' => true
                ], 403);
            }

            // 验证2FA代码
            $this->validateTwoFactorCode($user, $request->two_factor_code);

            $order = $this->escrowService->buyerMarkPaid($id, $request->payment_proof);

            return response()->json([
                'success' => true,
                'message' => '已标记付款',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 卖家确认收款
     */
    public function sellerReceived($id, Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|string|min:6'
        ]);

        try {
            $user = auth()->user();

            // 强制要求2FA验证
            if (!$this->userHasTwoFactorEnabled($user)) {
                return response()->json([
                    'success' => false,
                    'message' => '为了您的资金安全，卖家确认收款必须启用双重验证',
                    'require_2fa' => true
                ], 403);
            }

            // 验证2FA代码
            $this->validateTwoFactorCode($user, $request->two_factor_code);

            $order = $this->escrowService->sellerConfirmReceived($id);

            return response()->json([
                'success' => true,
                'message' => '已确认收款，等待托管放币',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 获取托管状态详情
     */
    public function escrowStatus($id)
    {
        try {
            $status = $this->escrowService->getEscrowStatus($id);

            return response()->json([
                'success' => true,
                'data' => $status
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 获取托管日志
     */
    public function escrowLogs($id)
    {
        try {
            $order = $this->orderService->getOrderDetail($id);

            return response()->json([
                'success' => true,
                'data' => $order->escrowLogs
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 模拟系统确认托管到账（仅用于测试）
     */
    public function mockEscrowReceived($id)
    {
        // 仅在开发环境使用
        if (app()->environment('production')) {
            return response()->json([
                'success' => false,
                'message' => '此功能仅在测试环境可用'
            ], 403);
        }

        try {
            $order = $this->escrowService->confirmEscrowReceived($id, 3);

            return response()->json([
                'success' => true,
                'message' => '模拟托管到账成功',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 模拟托管未收到（仅用于测试）
     */
    public function mockEscrowNotReceived($id)
    {
        // 仅在开发环境使用
        if (app()->environment('production')) {
            return response()->json([
                'success' => false,
                'message' => '此功能仅在测试环境可用'
            ], 403);
        }

        try {
            $order = $this->escrowService->markEscrowFailed($id);

            return response()->json([
                'success' => true,
                'message' => '已标记托管未到账',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 发起争议
     */
    public function dispute($id, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500'
        ]);

        try {
            $order = $this->orderService->createDispute($id, $request->reason);

            return response()->json([
                'success' => true,
                'message' => '争议已发起',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 解除争议
     */
    public function resolveDispute($id, Request $request)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            $order = $this->orderService->resolveDispute($id, $request->reason);

            return response()->json([
                'success' => true,
                'message' => '争议已解除',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
