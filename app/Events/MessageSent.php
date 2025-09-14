<?php

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $orderId;

    public function __construct(ChatMessage $message, ?User $user, $orderId)
    {
        $this->message = $message;
        $this->user = $user;
        $this->orderId = $orderId;
    }

    public function broadcastOn()
    {
        // 使用Presence频道，支持在线状态追踪和消息广播
        return new PresenceChannel('order.' . $this->orderId);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'type' => $this->message->type,
            'attachment' => $this->message->attachment,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ] : null,
            'created_at' => $this->message->created_at->toISOString(),
        ];
    }
}