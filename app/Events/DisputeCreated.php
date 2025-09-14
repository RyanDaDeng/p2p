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

class DisputeCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $reason;

    public function __construct(Order $order, string $reason)
    {
        $this->order = $order;
        $this->reason = $reason;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('order.' . $this->order->id);
    }

    public function broadcastAs()
    {
        return 'dispute.created';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'is_disputed' => true,
            'disputed_by' => $this->order->disputed_by,
            'reason' => $this->reason,
            'message' => '争议已发起：' . $this->reason
        ];
    }
}