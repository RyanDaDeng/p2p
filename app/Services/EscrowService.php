<?php

namespace App\Services;

use App\Models\Order;
use App\Models\EscrowLog;
use App\Events\EscrowStatusUpdated;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class EscrowService
{
    /**
     * 商家确认订单
     */
    public function vendorConfirm($orderId)
    {
        return DB::transaction(function() use ($orderId) {
            $order = Order::findOrFail($orderId);

            // 验证权限
            if ($order->vendor_id !== auth()->id()) {
                throw new Exception('无权操作此订单');
            }

            // 验证订单是否已取消
            if ($order->status === 'cancelled') {
                throw new Exception('订单已取消，无法操作');
            }

            // 验证是否有争议
            if ($order->is_disputed) {
                throw new Exception('订单存在争议，无法操作');
            }

            // 验证状态
            if ($order->escrow_status !== 'order_initiated') {
                throw new Exception('订单状态不正确');
            }

            // 生成托管地址（这里简化处理，实际需要调用钱包服务）
            $escrowAddress = $this->generateEscrowAddress($order);

            $order->update([
                'escrow_status' => 'vendor_confirmed',
                'vendor_confirmed_at' => now(),
                'escrow_address' => $escrowAddress
            ]);

            // 记录日志
            EscrowLog::log(
                $order->id,
                'vendor_confirm',
                'order_initiated',
                'vendor_confirmed',
                ['escrow_address' => $escrowAddress],
                '商家已确认订单'
            );

            // 广播状态变更事件
            broadcast(new EscrowStatusUpdated(
                $order,
                'order_initiated',
                'vendor_confirmed',
                '商家已确认订单，请卖家转币到托管地址'
            ));

            // 发送系统消息
            ChatController::sendSystemMessage(
                $order->id,
                '商家已确认订单，请卖家转币到托管地址: ' . $escrowAddress
            );

            return $order;
        });
    }

    /**
     * 卖家标记已转币
     */
    public function sellerMarkPaid($orderId, $txHash)
    {
        return DB::transaction(function() use ($orderId, $txHash) {
            $order = Order::findOrFail($orderId);

            // 验证权限
            if ($order->seller_id !== auth()->id()) {
                throw new Exception('无权操作此订单');
            }

            // 验证订单是否已取消
            if ($order->status === 'cancelled') {
                throw new Exception('订单已取消，无法操作');
            }

            // 验证是否有争议
            if ($order->is_disputed) {
                throw new Exception('订单存在争议，无法操作');
            }

            // 验证状态
            if ($order->escrow_status !== 'vendor_confirmed') {
                throw new Exception('请等待商家确认');
            }

            // 验证交易哈希
            if (empty($txHash)) {
                throw new Exception('请提供交易哈希');
            }

            $order->update([
                'escrow_status' => 'seller_paid',
                'seller_paid_at' => now(),
                'escrow_tx_hash' => $txHash
            ]);

            // 记录日志
            EscrowLog::log(
                $order->id,
                'seller_mark_paid',
                'vendor_confirmed',
                'seller_paid',
                [
                    'tx_hash' => $txHash,
                    'amount' => $order->crypto_amount,
                    'currency' => $order->crypto_currency
                ],
                '卖家已标记转币'
            );

            // 广播状态变更事件
            broadcast(new EscrowStatusUpdated(
                $order,
                'vendor_confirmed',
                'seller_paid',
                '卖家已转币，等待系统确认'
            ));

            // 发送系统消息
            ChatController::sendSystemMessage(
                $order->id,
                '卖家已转币到托管，交易哈希: ' . $txHash . '，系统正在确认中...'
            );

            // 触发区块链监控（这里简化处理）
            $this->startBlockchainMonitor($order);

            return $order;
        });
    }

    /**
     * 系统确认托管到账
     */
    public function confirmEscrowReceived($orderId, $data)
    {
        return DB::transaction(function() use ($orderId, $data) {
            $order = Order::findOrFail($orderId);

            // 验证订单是否已取消
            if ($order->status === 'cancelled') {
                throw new Exception('订单已取消，无法操作');
            }

            // 验证状态
            if ($order->escrow_status !== 'seller_paid') {
                return $order; // 幂等处理
            }

            $order->update([
                'escrow_status' => 'escrow_received',
                'escrow_received_at' => now()
            ]);

            // 记录日志
            EscrowLog::systemLog(
                $order->id,
                'escrow_confirmed',
                'seller_paid',
                'escrow_received',
                $data,
                '系统已确认托管到账'
            );

            // 广播状态变更事件
            broadcast(new EscrowStatusUpdated(
                $order,
                'seller_paid',
                'escrow_received',
                '托管已确认到账，买家可以付款了'
            ));

            // 发送系统消息
            ChatController::sendSystemMessage(
                $order->id,
                '✅ 托管已确认到账！请买家二次确认。'
            );

            return $order;
        });
    }

    /**
     * 买家确认托管到账
     */
    public function buyerConfirmEscrow($orderId)
    {
        return DB::transaction(function() use ($orderId) {
            $order = Order::findOrFail($orderId);

            // 验证权限
            if ($order->buyer_id !== auth()->id()) {
                throw new Exception('无权操作此订单');
            }

            // 验证订单是否已取消
            if ($order->status === 'cancelled') {
                throw new Exception('订单已取消，无法操作');
            }

            // 验证是否有争议
            if ($order->is_disputed) {
                throw new Exception('订单存在争议，无法操作');
            }

            // 验证状态 - 必须是系统已确认托管到账
            if ($order->escrow_status !== 'escrow_received') {
                throw new Exception('请等待系统确认托管到账');
            }

            $order->update([
                'escrow_status' => 'buyer_confirmed_escrow',
                'buyer_confirmed_escrow_at' => now()
            ]);

            // 记录日志
            EscrowLog::log(
                $order->id,
                'buyer_confirm_escrow',
                'escrow_received',
                'buyer_confirmed_escrow',
                [
                    'confirmed_amount' => $order->crypto_amount,
                    'confirmed_currency' => $order->crypto_currency
                ],
                '买家已确认托管到账'
            );

            // 广播状态变更事件
            broadcast(new EscrowStatusUpdated(
                $order,
                'escrow_received',
                'buyer_confirmed_escrow',
                '买家已确认托管到账，请继续付款'
            ));

            // 发送系统消息
            ChatController::sendSystemMessage(
                $order->id,
                '✅ 买家已确认托管到账，请继续向卖家付款'
            );

            return $order;
        });
    }

    /**
     * 买家标记已付款
     */
    public function buyerMarkPaid($orderId, $paymentProof = null)
    {
        return DB::transaction(function() use ($orderId, $paymentProof) {
            $order = Order::findOrFail($orderId);

            // 验证权限
            if ($order->buyer_id !== auth()->id()) {
                throw new Exception('无权操作此订单');
            }

            // 验证订单是否已取消
            if ($order->status === 'cancelled') {
                throw new Exception('订单已取消，无法操作');
            }

            // 验证是否有争议
            if ($order->is_disputed) {
                throw new Exception('订单存在争议，无法操作');
            }

            // 验证状态
            if ($order->escrow_status !== 'buyer_confirmed_escrow') {
                throw new Exception('请先确认托管到账');
            }

            $order->update([
                'escrow_status' => 'buyer_paid',
                'buyer_paid_at' => now()
            ]);

            // 记录日志
            EscrowLog::log(
                $order->id,
                'buyer_mark_paid',
                'buyer_confirmed_escrow',
                'buyer_paid',
                [
                    'payment_proof' => $paymentProof,
                    'amount' => $order->fiat_amount,
                    'currency' => $order->fiat_currency
                ],
                '买家已标记付款'
            );

            // 广播状态变更事件
            broadcast(new EscrowStatusUpdated(
                $order,
                'buyer_confirmed_escrow',
                'buyer_paid',
                '买家已付款，请卖家确认收款'
            ));

            // 发送系统消息
            ChatController::sendSystemMessage(
                $order->id,
                '买家已付款，请卖家确认收款'
            );

            return $order;
        });
    }

    /**
     * 卖家确认收款
     */
    public function sellerConfirmReceived($orderId)
    {
        return DB::transaction(function() use ($orderId) {
            $order = Order::findOrFail($orderId);

            // 验证权限
            if ($order->seller_id !== auth()->id()) {
                throw new Exception('无权操作此订单');
            }

            // 验证订单是否已取消
            if ($order->status === 'cancelled') {
                throw new Exception('订单已取消，无法操作');
            }

            // 验证是否有争议
            if ($order->is_disputed) {
                throw new Exception('订单存在争议，无法操作');
            }

            // 验证状态
            if ($order->escrow_status !== 'buyer_paid') {
                throw new Exception('买家还未付款');
            }

            $order->update([
                'escrow_status' => 'seller_received',
                'seller_received_at' => now()
            ]);

            // 记录日志
            EscrowLog::log(
                $order->id,
                'seller_confirm_received',
                'buyer_paid',
                'seller_received',
                [
                    'confirmed_amount' => $order->fiat_amount,
                    'confirmed_currency' => $order->fiat_currency
                ],
                '卖家已确认收款'
            );

            // 广播状态变更事件
            broadcast(new EscrowStatusUpdated(
                $order,
                'buyer_paid',
                'seller_received',
                '卖家已确认收款，正在释放托管'
            ));

            // 发送系统消息
            ChatController::sendSystemMessage(
                $order->id,
                '卖家已确认收款，系统正在释放托管...'
            );

            $this->releaseEscrow($order);

            return $order;
        });
    }

    /**
     * 释放托管
     */
    public function releaseEscrow(Order $order)
    {
        $log = Log::channel('fireblocks');
        return DB::transaction(function() use ($order, $log) {
            // 验证状态
            if ($order->escrow_status !== 'seller_received') {
                throw new Exception('状态不正确，无法释放托管');
            }

            // 调用区块链服务进行实际的币释放
            $isSandbox = config('fireblocks.sandbox');

            $userId = $isSandbox ? 'AUS_TEST'.$order->buyer_id : 'AUS_PROD'.$order->buyer_id;
            $assetId = $isSandbox ? config('fireblocks.assets.test.' . $order->currency_key)
                : config('fireblocks.assets.prod.' . $order->currency_key);

            $service = new FireBlocksService();

            $amount = round((float)$order->crypto_amount - (float)$order->fee,8);

            if ($amount > 0){
                $log->info('Applying payment input: ', [
                    'order_no' => $order->order_no,
                    'user_id' => $userId,
                    'buy_address' => $order->buyer_address,
                    'asset_id' => $assetId,
                    'amount' => (string) $amount,
                ]);

                $res = $service->applyPayment(
                    $order->order_no,
                    $userId,
                    $order->buyer_address,
                    $assetId,
                    (string) $amount
                );

                if (!empty($res) && isset($res['success']) && $res['success'] == true) {
                    $log->info('Applying payment result: ', $res);
                    $order->release_tx_id = $res['transaction_id'];
                    $order->save();
                } else {
                    // todo 有未知的转账，需要提醒TG
                }
            } else {
                // todo 金额过小需要提醒TG
            }

            return $order;
        });
    }

    public function escrowReleased($orderId, $releaseTxHash, $data){
        return DB::transaction(function() use ($orderId, $releaseTxHash, $data) {

            $order = Order::findOrFail($orderId);

            $order->update([
                'escrow_status' => 'escrow_released',
                'escrow_released_at' => now(),
                'release_tx_hash' => $releaseTxHash,
                'status' => 'completed',
                'completed_at' => now()
            ]);

            // 记录日志
            EscrowLog::systemLog(
                $order->id,
                'escrow_released',
                'seller_received',
                'escrow_released',
                $data,
                '托管已释放，交易完成'
            );

            // 广播状态变更事件
            broadcast(new EscrowStatusUpdated(
                $order,
                'seller_received',
                'escrow_released',
                '交易完成！托管已释放给买家'
            ));

            // 发送系统消息
            ChatController::sendSystemMessage(
                $order->id,
                '🎉 交易完成！托管已释放给买家，感谢使用我们的平台！'
            );
        });
    }
    /**
     * 生成托管地址（模拟）
     */
    private function generateEscrowAddress($order)
    {

        $currencyKey = $order->currency_key;
        $isSandbox = config('fireblocks.sandbox');

        return $isSandbox ? config('fireblocks.escrow.test.'. $currencyKey) : config('fireblocks.escrow.prod.'. $currencyKey);
    }

    /**
     * 启动区块链监控（模拟）
     */
    private function startBlockchainMonitor($order)
    {
        // 实际项目中这里应该：
        // 1. 调用区块链API监控地址
        // 2. 设置定时任务检查交易
        // 3. 确认后调用 confirmEscrowReceived

        // 这里模拟5秒后自动确认
        // 实际项目可以使用队列延迟任务
        // dispatch(new CheckEscrowTransaction($order))->delay(now()->addSeconds(5));

        // 为了演示，这里直接确认
        // $this->confirmEscrowReceived($order->id);
    }

    /**
     * 标记托管未收到（重置到等待卖家转币状态）
     */
    public function markEscrowFailed($orderId)
    {
        return DB::transaction(function() use ($orderId) {
            $order = Order::findOrFail($orderId);

            // 验证状态
            if ($order->escrow_status !== 'seller_paid') {
                throw new Exception('只能在等待托管确认状态下标记未收到');
            }

            $oldTxHash = $order->escrow_tx_hash;

            // 重置到vendor_confirmed状态，清除交易哈希
            $order->update([
                'escrow_status' => 'vendor_confirmed',
                'escrow_tx_hash' => null,
                'seller_paid_at' => null
            ]);

            // 记录日志
            EscrowLog::systemLog(
                $order->id,
                'escrow_failed',
                'seller_paid',
                'vendor_confirmed',
                [
                    'failed_tx_hash' => $oldTxHash,
                    'reason' => '托管未收到资金'
                ],
                '托管未收到资金，请重新转币'
            );

            // 广播状态变更事件
            broadcast(new EscrowStatusUpdated(
                $order,
                'seller_paid',
                'vendor_confirmed',
                '托管未收到资金，请卖家重新转币到托管地址'
            ));

            // 发送系统消息
            ChatController::sendSystemMessage(
                $order->id,
                '❌ 托管未收到资金，请卖家重新转币到托管地址: ' . $order->escrow_address
            );

            return $order;
        });
    }

    /**
     * 获取托管状态详情
     */
    public function getEscrowStatus($orderId)
    {
        $order = Order::with(['escrowLogs' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($orderId);

        // 验证权限
        if (!$order->isParticipant(auth()->id())) {
            throw new Exception('无权访问此订单');
        }

        $statusInfo = [
            'order_initiated' => [
                'label' => '等待商家确认',
                'description' => '订单已创建，等待商家确认接单',
                'progress' => 14
            ],
            'vendor_confirmed' => [
                'label' => '等待卖家转币',
                'description' => '商家已确认，请卖家将币转入托管地址',
                'progress' => 28
            ],
            'seller_paid' => [
                'label' => '确认区块链交易',
                'description' => '卖家已转币，系统正在确认区块链交易',
                'progress' => 42
            ],
            'escrow_received' => [
                'label' => '等待买家确认',
                'description' => '托管已收到币，请买家确认托管到账',
                'progress' => 50
            ],
            'buyer_confirmed_escrow' => [
                'label' => '等待买家付款',
                'description' => '买家已确认托管，请向卖家付款',
                'progress' => 60
            ],
            'buyer_paid' => [
                'label' => '等待卖家确认',
                'description' => '买家已付款，等待卖家确认收款',
                'progress' => 75
            ],
            'seller_received' => [
                'label' => '释放托管中',
                'description' => '卖家已确认收款，系统正在释放托管',
                'progress' => 85
            ],
            'escrow_released' => [
                'label' => '交易完成',
                'description' => '托管已释放，交易成功完成',
                'progress' => 100
            ]
        ];

        return [
            'current_status' => $order->escrow_status,
            'status_info' => $statusInfo[$order->escrow_status] ?? null,
            'escrow_address' => $order->escrow_address,
            'escrow_tx_hash' => $order->escrow_tx_hash,
            'release_tx_hash' => $order->release_tx_hash,
            'logs' => $order->escrowLogs
        ];
    }
}
