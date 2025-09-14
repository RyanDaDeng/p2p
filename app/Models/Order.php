<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'vendor_id',
        'client_id',
        'seller_id',
        'buyer_id',
        'ad_id',
        'currency_key',
        'crypto_amount',
        'fiat_currency',
        'fiat_amount',
        'price',
        'fee',
        'ad_snapshot',
        'status',
        'is_disputed',
        'disputed_by',
        'escrow_status',
        'vendor_confirmed_at',
        'seller_paid_at',
        'escrow_received_at',
        'buyer_paid_at',
        'buyer_confirmed_escrow_at',
        'seller_received_at',
        'escrow_released_at',
        'completed_at',
        'cancelled_at',
        'escrow_address',
        'seller_address',
        'buyer_address',
        'escrow_tx_hash',
        'release_tx_hash',
        'vendor_first_response_at',
        'response_time_minutes',
    ];

    protected $casts = [
        'ad_snapshot' => 'array',
        'crypto_amount' => 'decimal:8',
        'fiat_amount' => 'decimal:2',
        'price' => 'decimal:2',
        'fee' => 'decimal:8',
        'is_disputed' => 'boolean',
        'vendor_confirmed_at' => 'datetime',
        'seller_paid_at' => 'datetime',
        'escrow_received_at' => 'datetime',
        'buyer_paid_at' => 'datetime',
        'buyer_confirmed_escrow_at' => 'datetime',
        'seller_received_at' => 'datetime',
        'escrow_released_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'vendor_first_response_at' => 'datetime',
        'response_time_minutes' => 'integer',
    ];

    // 关系定义
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function escrowLogs()
    {
        return $this->hasMany(EscrowLog::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // 辅助方法
    public function isVendor($userId)
    {
        return $this->vendor_id === $userId;
    }

    public function isClient($userId)
    {
        return $this->client_id === $userId;
    }

    public function isSeller($userId)
    {
        return $this->seller_id === $userId;
    }

    public function isBuyer($userId)
    {
        return $this->buyer_id === $userId;
    }

    public function isParticipant($userId, $checkAdmin = false)
    {
        // 检查是否是管理员
        $user = \App\Models\User::find($userId);
        if ($checkAdmin && $user && $user->is_admin) {
            return true;
        }

        // 检查是否是订单参与者
        return in_array($userId, [$this->vendor_id, $this->client_id]);
    }

    // 获取当前用户在订单中的角色
    public function getUserRole($userId)
    {
        if ($this->vendor_id === $userId) {
            return $this->seller_id === $userId ? 'seller' : 'buyer';
        }
        if ($this->client_id === $userId) {
            return $this->seller_id === $userId ? 'seller' : 'buyer';
        }
        return null;
    }

    // 状态检查
    public function canVendorConfirm()
    {
        return $this->escrow_status === 'order_initiated';
    }

    public function canSellerPay()
    {
        return $this->escrow_status === 'vendor_confirmed';
    }

    public function canBuyerConfirmEscrow()
    {
        return $this->escrow_status === 'escrow_received';
    }

    public function canBuyerPay()
    {
        return $this->escrow_status === 'buyer_confirmed_escrow';
    }

    public function canSellerConfirmReceipt()
    {
        return $this->escrow_status === 'buyer_paid';
    }

    // 生成订单号
    public static function generateOrderNo()
    {
        $date = now()->format('Ymd');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $orderNo = 'T' . $date . $random;

        // 确保唯一性
        while (self::where('order_no', $orderNo)->exists()) {
            $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $orderNo = 'T' . $date . $random;
        }

        return $orderNo;
    }

    // 争议相关方法
    public function isDisputed()
    {
        return $this->is_disputed;
    }

    public function canDispute($userId)
    {
        // 必须是参与方
        if (!$this->isParticipant($userId)) {
            return false;
        }

        // 订单未完成且未取消
        if ($this->status === 'completed' || $this->status === 'cancelled') {
            return false;
        }

        // 当前没有争议
        if ($this->is_disputed) {
            return false;
        }

        // 任何进行中的状态都可以发起争议
        return true;
    }

    public function canResolveDispute($userId)
    {
        // 发起争议的用户或管理员可以解除
        $user = \App\Models\User::find($userId);
        $isAdmin = $user && $user->is_admin;
        return $this->is_disputed && ($this->disputed_by === $userId || $isAdmin);
    }

    public function disputedByUser()
    {
        return $this->belongsTo(User::class, 'disputed_by');
    }

    // 获取可执行的操作
    public function getAvailableActions($userId)
    {
        $actions = [];

        // 如果订单已取消或完成，不返回任何可用操作
        if ($this->status === 'cancelled' || $this->status === 'completed') {
            return $actions;
        }

        // 正常流程的按钮（争议期间这些按钮会显示但被禁用）
        switch($this->escrow_status) {
            case 'order_initiated':
                if ($this->isVendor($userId)) {
                    $actions[] = 'vendor_confirm';
                }
                // 第1步：双方都能取消
                $actions[] = 'cancel';
                break;

            case 'vendor_confirmed':
                if ($this->isSeller($userId)) {
                    $actions[] = 'mark_seller_paid';
                }
                // 第2步：双方都能取消
                $actions[] = 'cancel';
                break;

            case 'seller_paid':
                // 第3步：只有买家能取消
                if ($this->isBuyer($userId)) {
                    $actions[] = 'cancel';
                }
                // 等待系统确认托管
                break;

            case 'escrow_received':
                if ($this->isBuyer($userId)) {
                    $actions[] = 'confirm_escrow';
                    // 第4步：只有买家能取消
                    $actions[] = 'cancel';
                }
                break;

            case 'buyer_confirmed_escrow':
                if ($this->isBuyer($userId)) {
                    $actions[] = 'mark_buyer_paid';
                    // 第5步：只有买家能取消
                    $actions[] = 'cancel';
                }
                break;

            case 'buyer_paid':
                // 第6步：双方都无法取消
                if ($this->isSeller($userId)) {
                    $actions[] = 'mark_seller_received';
                }
                break;

            case 'seller_received':
                // 第7步：等待系统释放
                break;

            case 'escrow_released':
                // 第8步：订单完成，无操作
                break;
        }

        // 争议相关操作
        if ($this->is_disputed && $this->canResolveDispute($userId)) {
            // 如果有争议且是发起人，可以解除争议
            $actions[] = 'resolve_dispute';
        } else if ($this->canDispute($userId)) {
            // 如果没有争议，可以发起争议
            $actions[] = 'create_dispute';
        }

        return $actions;
    }
}
