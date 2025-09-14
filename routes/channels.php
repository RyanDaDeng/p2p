<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// 订单 Presence Channel 授权 - 支持在线状态追踪
Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    $order = Order::find($orderId);

    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
