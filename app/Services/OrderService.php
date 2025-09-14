<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ad;
use App\Models\EscrowLog;
use App\Services\CryptoPriceService;
use App\Services\ExchangeRateService;
use App\Services\FeeService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    protected CryptoPriceService $cryptoService;
    protected ExchangeRateService $exchangeService;
    protected FeeService $feeService;

    public function __construct(
        CryptoPriceService $cryptoService,
        ExchangeRateService $exchangeService,
        FeeService $feeService
    ) {
        $this->cryptoService = $cryptoService;
        $this->exchangeService = $exchangeService;
        $this->feeService = $feeService;
    }

    /**
     * 创建订单
     */
    public function createOrder($adId, $fiatAmount, $addressId = null, $clientId = null)
    {
        $clientId = $clientId ?: auth()->id();

        return DB::transaction(function() use ($adId, $fiatAmount, $addressId, $clientId) {
            $ad = Ad::with('user')->findOrFail($adId);

            // 验证广告状态
            if ($ad->status !== 'active') {
                throw new Exception('广告不可用');
            }

            // 验证金额范围（法币金额）
            if ($fiatAmount < $ad->min_limit || $fiatAmount > $ad->max_limit) {
                throw new Exception('金额超出限制范围');
            }

            // 不能和自己交易
            if ($ad->user_id === $clientId) {
                throw new Exception('您不能在自己发布的广告上创建订单');
            }

            // 确定角色关系
            $vendorId = $ad->user_id;

            // 处理地址
            $sellerAddress = null;
            $buyerAddress = null;

            // 验证用户提供的地址
            if ($addressId) {
                $address = \App\Models\Address::find($addressId);

                if (!$address) {
                    throw new Exception('地址不存在');
                }

                if ($address->user_id !== $clientId) {
                    throw new Exception('该地址不属于您');
                }

                // 验证地址是否已验证
                if (!$address->is_verified) {
                    throw new Exception('该地址尚未验证，请先完成地址验证');
                }

                // 验证地址与广告的currency_key匹配
                if ($address->currency_key !== $ad->currency_key) {
                    throw new Exception('选择的地址与广告的加密货币类型不匹配，请选择 ' . $ad->currency_key . ' 地址');
                }

                // 根据交易类型设置对应的地址
                if ($ad->trade_type === 'sell') {
                    // 卖币广告：客户是卖方，设置seller_address
                    $sellerAddress = $address->address;
                } else {
                    // 买币广告：客户是买方，设置buyer_address
                    $buyerAddress = $address->address;
                }
            } else {
                // 如果没有提供地址，根据交易类型检查是否必需
                if ($ad->trade_type === 'sell') {
                    throw new Exception('出售加密货币需要选择发款地址');
                } elseif ($ad->trade_type === 'buy') {
                    throw new Exception('购买加密货币需要选择收款地址');
                }
            }

            // 处理广告中的预设地址（商家的地址）
            if ($ad->trade_type === 'buy' && $ad->trade_address) {
                // Buy广告：商家是卖方，使用广告中的地址作为seller_address
                $sellerAddress = $ad->trade_address;
            } elseif ($ad->trade_type === 'sell' && $ad->trade_address) {
                // Sell广告：商家是买方，使用广告中的地址作为buyer_address  
                $buyerAddress = $ad->trade_address;
            }

            // 注意：ad.trade_type 是从用户视角看的
            // - trade_type='buy' 表示用户要买币，vendor在卖币
            // - trade_type='sell' 表示用户要卖币，vendor在买币

            if ($ad->trade_type === 'sell') {
                // Sell广告：用户要卖币，vendor是买家
                $sellerId = $clientId;  // 用户是卖家
                $buyerId = $vendorId;   // 商家是买家
            } else {
                // Buy广告：用户要买币，vendor是卖家
                $sellerId = $vendorId;  // 商家是卖家
                $buyerId = $clientId;   // 用户是买家
            }

            // 获取价格
            $price = 0;

            // 根据价格模型获取价格
            if ($ad->price_model === 'fixed') {
                // 固定价格模式
                $price = floatval($ad->price);
            } else if ($ad->price_model === 'dynamic') {
                // 动态价格模式：从市场获取基础价格并应用margin
                $basePrice = $this->getMarketPrice($ad->currency_key, $ad->fiat_currency);
                if ($basePrice <= 0) {
                    // 如果无法获取市场价格，使用广告中的备用价格
                    $basePrice = floatval($ad->price) ?: 7.2; // 默认USDT价格
                }
                $margin = floatval($ad->margin) ?: 0;
                $price = $basePrice * (1 + $margin / 100);
            }

            // 验证价格是否有效
            if ($price <= 0) {
                throw new Exception('无法获取有效价格，请稍后重试或联系广告发布者');
            }

            // 计算交易金额（法币金额 / 价格 = 加密货币数量）
            $cryptoAmount = $fiatAmount / $price;

            // 计算交易费用
            $feeData = $this->feeService->calculateFee($ad->currency_key, $cryptoAmount);
            $fee = $feeData['fee'];

            // 创建订单
            $orderData = [
                'order_no' => Order::generateOrderNo(),
                'vendor_id' => $vendorId,
                'client_id' => $clientId,
                'seller_id' => $sellerId,
                'buyer_id' => $buyerId,
                'ad_id' => $adId,
                'currency_key' => $ad->currency_key,
                'crypto_amount' => $cryptoAmount,
                'fiat_currency' => $ad->fiat_currency ?? 'CNY',
                'fiat_amount' => $fiatAmount,
                'price' => $price,
                'fee' => $fee,
                'ad_snapshot' => $ad->toArray(),
                'status' => 'trading',
                'escrow_status' => 'order_initiated'
            ];

            // 保存地址
            if ($sellerAddress) {
                $orderData['seller_address'] = $sellerAddress;
            }
            if ($buyerAddress) {
                $orderData['buyer_address'] = $buyerAddress;
            }

            $order = Order::create($orderData);

            // 记录日志
            EscrowLog::log(
                $order->id,
                'order_created',
                null,
                'order_initiated',
                [
                    'ad_id' => $adId,
                    'amount' => $cryptoAmount,
                    'price' => $ad->price,
                    'total' => $fiatAmount
                ],
                '订单创建成功'
            );

            // 发送通知给商家
            $client = \App\Models\User::find($clientId);
            $notificationMessage = sprintf(
                '您有新的订单 #%s，客户 %s %s %.4f %s',
                $order->order_no,
                $client->name,
                $ad->trade_type === 'buy' ? '想要购买' : '想要出售',
                $cryptoAmount,
                $ad->currency_key
            );

            NotificationService::sendSystemNotification(
                $vendorId,
                $notificationMessage,
                'order',
                [
                    'order_id' => $order->id,
                    'order_no' => $order->order_no,
                    'action' => 'vendor_confirm'
                ]
            );

            return $order->load(['vendor', 'client', 'seller', 'buyer']);
        });
    }

    /**
     * 获取订单详情
     */
    public function getOrderDetail($orderId)
    {
        $order = Order::with([
            'vendor',
            'client',
            'seller',
            'buyer',
            'ad',
            'escrowLogs' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($orderId);

        // 验证访问权限
        if (!$order->isParticipant(auth()->id())) {
            throw new Exception('无权访问此订单');
        }

        // 添加货币label和法币符号
        $cryptoConfig = config("cryptocurrencies.supported.{$order->currency_key}");
        $order->currency_label = $cryptoConfig ? $cryptoConfig['label'] : $order->currency_key;

        $fiatConfig = config("fiat_currencies.supported.{$order->fiat_currency}");
        $order->fiat_currency_symbol = $fiatConfig ? $fiatConfig['symbol'] : '¥';

        return $order;
    }

    /**
     * 获取订单列表
     */
    public function getOrderList($filters = [])
    {
        $userId = auth()->id();

        $query = Order::with(['vendor', 'client', 'seller', 'buyer', 'ad'])
            ->where(function($q) use ($userId) {
                $q->where('vendor_id', $userId)
                  ->orWhere('client_id', $userId);
            });

        // 状态筛选
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 托管状态筛选
        if (!empty($filters['escrow_status'])) {
            $query->where('escrow_status', $filters['escrow_status']);
        }

        // 交易类型筛选（从当前用户视角）
        if (!empty($filters['trade_type'])) {
            if ($filters['trade_type'] === 'buy') {
                $query->where('buyer_id', $userId);
            } elseif ($filters['trade_type'] === 'sell') {
                $query->where('seller_id', $userId);
            }
        }

        // 币种筛选
        if (!empty($filters['cryptocurrency'])) {
            $query->where('currency_key', $filters['cryptocurrency']);
        }

        // 搜索功能 - 搜索订单号或用户名
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('client', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 时间范围筛选
        if (!empty($filters['time_range'])) {
            $now = now();
            switch ($filters['time_range']) {
                case '24hours':
                    $query->where('created_at', '>=', $now->subHours(24));
                    break;
                case '7days':
                    $query->where('created_at', '>=', $now->subDays(7));
                    break;
                case '30days':
                    $query->where('created_at', '>=', $now->subDays(30));
                    break;
                case '3months':
                    $query->where('created_at', '>=', $now->subMonths(3));
                    break;
            }
        }

        $perPage = $filters['per_page'] ?? 20;
        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // 添加货币label和法币符号
        $orders->getCollection()->transform(function ($order) {
            // 获取加密货币label
            $cryptoConfig = config("cryptocurrencies.supported.{$order->currency_key}");
            $order->currency_label = $cryptoConfig ? $cryptoConfig['label'] : $order->currency_key;

            // 获取法币符号
            $fiatConfig = config("fiat_currencies.supported.{$order->fiat_currency}");
            $order->fiat_currency_symbol = $fiatConfig ? $fiatConfig['symbol'] : '¥';

            return $order;
        });

        return $orders;
    }

    /**
     * 获取市场价格
     */
    private function getMarketPrice($currencyKey, $fiatCurrency = 'CNY')
    {
        // 从配置获取货币信息
        $cryptoConfig = config("cryptocurrencies.supported.{$currencyKey}");
        $baseCrypto = $cryptoConfig ? $cryptoConfig['currency'] : strtoupper(preg_replace('/^[^-]+-/', '', $currencyKey));

        // 获取USD价格
        $usdPrice = 0;
        switch ($baseCrypto) {
            case 'BTC':
                $usdPrice = $this->cryptoService->getBtcPrice();
                break;
            case 'ETH':
                $usdPrice = $this->cryptoService->getEthPrice();
                break;
            case 'USDT':
            case 'USDC':
                $usdPrice = 1.0; // 稳定币固定为1美元
                break;
            default:
                // 对于其他币种，暂时返回0
                return 0;
        }

        if (!$usdPrice) {
            return 0;
        }

        // 如果需要的是USD，直接返回
        if ($fiatCurrency === 'USD') {
            return $usdPrice;
        }

        // 转换为目标法币
        $rate = $this->exchangeService->getRate('USD', $fiatCurrency);
        if (!$rate) {
            return 0;
        }

        return $usdPrice * $rate;
    }

    /**
     * 取消订单
     */
    public function cancelOrder($orderId)
    {
        return DB::transaction(function() use ($orderId) {
            $order = Order::findOrFail($orderId);
            $userId = auth()->id();

            // 验证权限
            if (!$order->isParticipant($userId)) {
                throw new Exception('无权操作此订单');
            }

            // 验证订单是否已经取消
            if ($order->status === 'cancelled') {
                throw new Exception('订单已经取消，无法重复操作');
            }

            // 验证是否有争议
            if ($order->is_disputed) {
                throw new Exception('订单存在争议，无法取消');
            }

            // 根据不同状态判断取消权限
            $canCancel = false;
            $penaltyMessage = null;

            switch ($order->escrow_status) {
                case 'order_initiated':
                case 'vendor_confirmed':
                    // 第1-2步：双方都能取消
                    $canCancel = true;
                    break;

                case 'seller_paid':
                case 'escrow_received':
                case 'buyer_confirmed_escrow':
                    // 第3-5步：只有买家能取消，且会扣信誉
                    if ($order->isBuyer($userId)) {
                        $canCancel = true;
                        $penaltyMessage = '由于已进入托管阶段取消订单，买家信誉 -1';
                        // TODO: 实现信誉扣分逻辑
                        // $this->userService->decreaseReputation($userId, 1);
                    } else {
                        throw new Exception('托管阶段只有买家可以取消订单');
                    }
                    break;

                case 'buyer_paid':
                case 'seller_received':
                case 'escrow_released':
                    // 第6-8步：双方都无法取消
                    throw new Exception('当前状态不允许取消订单，如有问题请申诉');
                    break;

                default:
                    throw new Exception('未知的订单状态');
            }

            if (!$canCancel) {
                throw new Exception('当前状态下您无权取消订单');
            }

            $previousStatus = $order->escrow_status;

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            // 记录日志
            $logMessage = '订单已取消';
            if ($penaltyMessage) {
                $logMessage .= ' - ' . $penaltyMessage;
            }

            EscrowLog::log(
                $order->id,
                'order_cancelled',
                $previousStatus,
                'cancelled',
                [
                    'reason' => request()->input('reason'),
                    'cancelled_by' => $userId,
                    'penalty' => $penaltyMessage ? true : false
                ],
                $logMessage
            );

            // 发送系统消息
            $cancelMessage = '订单已取消';
            if (request()->input('reason')) {
                $cancelMessage .= ': ' . request()->input('reason');
            }
            if ($penaltyMessage) {
                $cancelMessage .= "\n⚠️ " . $penaltyMessage;
            }

            \App\Http\Controllers\ChatController::sendSystemMessage(
                $order->id,
                $cancelMessage
            );

            // 广播订单状态更新事件
            broadcast(new \App\Events\OrderStatusUpdated($order, 'cancelled', $cancelMessage))->toOthers();

            return $order;
        });
    }

    /**
     * 发起争议
     */
    public function createDispute($orderId, $reason)
    {
        return DB::transaction(function() use ($orderId, $reason) {
            $order = Order::findOrFail($orderId);
            $userId = auth()->id();

            // 验证是否可以发起争议
            if (!$order->canDispute($userId)) {
                throw new Exception('当前状态不允许发起争议');
            }

            // 更新订单状态
            $order->update([
                'is_disputed' => true,
                'disputed_by' => $userId
            ]);

            // 记录托管日志
            EscrowLog::log(
                $order->id,
                'dispute_created',
                $order->escrow_status,
                $order->escrow_status, // 状态不变
                [
                    'reason' => $reason,
                    'disputed_by' => $userId,
                    'user_name' => auth()->user()->name
                ],
                '争议已发起：' . $reason
            );

            // 发送系统消息到聊天
            \App\Http\Controllers\ChatController::sendSystemMessage(
                $order->id,
                '⚠️ ' . auth()->user()->name . ' 发起争议：' . $reason . "\n订单已被冻结，等待平台介入处理。"
            );

            // 广播争议事件
            broadcast(new \App\Events\DisputeCreated($order, $reason))->toOthers();

            return $order;
        });
    }

    /**
     * 解除争议
     */
    public function resolveDispute($orderId, $reason = null)
    {
        return DB::transaction(function() use ($orderId, $reason) {
            $order = Order::findOrFail($orderId);
            $userId = auth()->id();

            // 验证是否可以解除争议
            if (!$order->canResolveDispute($userId)) {
                throw new Exception('您无权解除此争议');
            }

            // 更新订单状态
            $order->update([
                'is_disputed' => false,
                'disputed_by' => null
            ]);

            // 记录托管日志
            EscrowLog::log(
                $order->id,
                'dispute_resolved',
                $order->escrow_status,
                $order->escrow_status, // 状态不变
                [
                    'resolved_by' => $userId,
                    'user_name' => auth()->user()->name,
                    'reason' => $reason
                ],
                '争议已解除' . ($reason ? '：' . $reason : '')
            );

            // 发送系统消息到聊天
            $message = '✅ ' . auth()->user()->name . ' 解除了争议';
            if ($reason) {
                $message .= '：' . $reason;
            }
            $message .= "\n订单已恢复正常，可以继续交易。";

            \App\Http\Controllers\ChatController::sendSystemMessage(
                $order->id,
                $message
            );

            // 广播争议解除事件
            broadcast(new \App\Events\DisputeResolved($order))->toOthers();

            return $order;
        });
    }

    /**
     * 获取订单状态和可用操作
     */
    public function getOrderStatus($orderId)
    {
        $order = Order::with('escrowLogs')->findOrFail($orderId);
        $userId = auth()->id();

        // 验证权限
        if (!$order->isParticipant($userId)) {
            throw new Exception('无权访问此订单');
        }

        return [
            'order' => $order,
            'current_status' => $order->escrow_status,
            'user_role' => $order->getUserRole($userId),
            'available_actions' => $order->getAvailableActions($userId),
            'logs' => $order->escrowLogs
        ];
    }
}
