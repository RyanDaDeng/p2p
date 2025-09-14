<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscrowLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'data',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // 关系定义
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 创建日志的静态方法
    public static function log($orderId, $action, $fromStatus, $toStatus, $data = [], $description = null)
    {
        return self::create([
            'order_id' => $orderId,
            'user_id' => auth()->id(),
            'action' => $action,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'data' => $data,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // 系统日志（无用户）
    public static function systemLog($orderId, $action, $fromStatus, $toStatus, $data = [], $description = null)
    {
        return self::create([
            'order_id' => $orderId,
            'user_id' => null,
            'action' => $action,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'data' => $data,
            'description' => $description,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'System',
        ]);
    }
}