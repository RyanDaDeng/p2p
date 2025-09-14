<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class EscrowStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $previousStatus;
    public $newStatus;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, $previousStatus, $newStatus, $message)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('order.' . $this->order->id),
        ];
    }
    
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->newStatus,
            'escrow_status' => $this->order->escrow_status,
            'status' => $this->order->status,
            'is_disputed' => $this->order->is_disputed,
            'disputed_by' => $this->order->disputed_by,
            'message' => $this->message,
            'updated_at' => now()->toISOString()
        ];
    }
}