<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DisputeResolved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('order.' . $this->order->id);
    }

    public function broadcastAs()
    {
        return 'dispute.resolved';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'is_disputed' => false,
            'disputed_by' => null,
            'escrow_status' => $this->order->escrow_status,
            'status' => $this->order->status,
            'available_actions' => $this->order->getAvailableActions(auth()->id()),
            'message' => '争议已解除，订单恢复正常'
        ];
    }
}