<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Order;
use App\Models\ChatMessage;

class NotificationService
{
    /**
     * Send order notification to user
     *
     * @param int $recipientId
     * @param Order $order
     * @param ChatMessage $message
     * @param User|null $sender
     */
    public static function sendOrderNotification($recipientId, Order $order, ChatMessage $message, User $sender = null)
    {
        // Check for existing unread notification to aggregate
        $notification = Notification::where('user_id', $recipientId)
            ->where('order_id', $order->id)
            ->where('type', 'order')
            ->where('is_read', false)
            ->first();

        if ($notification) {
            // Increment count for existing unread notification
            $notification->increment('count');
        } else {
            // Create new notification
            $messagePreview = $message->message ? mb_substr($message->message, 0, 50) : '发送了一个附件';
            $notification = Notification::create([
                'user_id' => $recipientId,
                'type' => 'order',
                'message' => '您有新的订单消息: ' . $messagePreview,
                'order_id' => $order->id,
                'count' => 1,
                'is_read' => false
            ]);

            // Send Telegram notification when creating new notification (not aggregating)
            self::sendTelegramNotification($recipientId, $order, $message, $sender);
        }

        // Set user unread flag (for both new and updated notifications)
        User::where('id', $recipientId)->update(['unread' => 1]);
    }

    /**
     * Send Telegram notification
     *
     * @param int $recipientId
     * @param Order $order
     * @param ChatMessage $message
     * @param User|null $sender
     */
    private static function sendTelegramNotification($recipientId, Order $order, ChatMessage $message, User $sender = null)
    {
        $recipient = User::find($recipientId);
        if (!$recipient || !$recipient->telegram_chat_id) {
            return;
        }

        $senderName = $sender ? $sender->name : '系统';
        $orderNo = $order->order_no;
        $messageText = $message->message ?: '发送了一个附件';

        $appUrl = config('app.url');
        $chatUrl = "{$appUrl}/trade/{$orderNo}/chat";

        $telegramMessage = "🔔 新消息提醒\n\n";
        $telegramMessage .= "订单号: {$orderNo}\n";
        $telegramMessage .= "来自: {$senderName}\n";
        $telegramMessage .= "消息: {$messageText}\n\n";
        $telegramMessage .= "👉 点击查看: {$chatUrl}\n\n";
        $telegramMessage .= "请及时查看并回复";

        try {
            $telegramService = app(TelegramService::class);
            $telegramService->sendMessage($recipient->telegram_chat_id, $telegramMessage);
        } catch (\Exception $e) {
            \Log::error('Failed to send Telegram notification', [
                'user_id' => $recipientId,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send system notification
     *
     * @param int $userId
     * @param string $message
     * @param string $type
     * @param array $data
     */
    public static function sendSystemNotification($userId, string $message, string $type = 'system', array $data = [])
    {
        // Extract order_id from data if it exists
        $notificationData = [
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'data' => $data,
            'is_read' => false
        ];

        // If order_id is in data array, also add it as a direct field
        if (isset($data['order_id'])) {
            $notificationData['order_id'] = $data['order_id'];
        }

        $notification = Notification::create($notificationData);

        // Set user unread flag
        User::where('id', $userId)->update(['unread' => 1]);

        // Send Telegram notification
        $user = User::find($userId);
        if ($user && $user->telegram_chat_id) {
            $appUrl = config('app.url');

            // Build Telegram message based on notification type
            $telegramMessage = "🔔 系统通知\n\n";
            $telegramMessage .= "{$message}\n\n";

            // Add link if order related
            if (isset($data['order_no'])) {
                $chatUrl = "{$appUrl}/trade/{$data['order_no']}/chat";
                $telegramMessage .= "👉 点击查看: {$chatUrl}\n\n";
            } elseif (isset($data['order_id'])) {
                // Try to get order number from order_id
                $order = Order::find($data['order_id']);
                if ($order) {
                    $chatUrl = "{$appUrl}/trade/{$order->order_no}/chat";
                    $telegramMessage .= "👉 点击查看: {$chatUrl}\n\n";
                }
            }

            $telegramMessage .= "请及时处理";

            try {
                $telegramService = app(TelegramService::class);
                $telegramService->sendMessage($user->telegram_chat_id, $telegramMessage);
            } catch (\Exception $e) {
                \Log::error('Failed to send Telegram notification', [
                    'user_id' => $userId,
                    'type' => $type,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $notification;
    }

    /**
     * Send trade notification
     *
     * @param int $userId
     * @param string $message
     * @param Order|null $order
     */
    public static function sendTradeNotification($userId, string $message, Order $order = null)
    {
        $data = [];
        if ($order) {
            $data['order_id'] = $order->id;
            $data['order_no'] = $order->order_no;
        }

        return self::sendSystemNotification($userId, $message, 'trade', $data);
    }

    /**
     * Mark notifications as read
     *
     * @param int $userId
     * @param array|null $notificationIds
     */
    public static function markAsRead($userId, array $notificationIds = null)
    {
        $query = Notification::where('user_id', $userId);

        if ($notificationIds) {
            $query->whereIn('id', $notificationIds);
        }

        $query->update(['is_read' => true]);

        // Check if user has any unread notifications left
        $hasUnread = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->exists();

        if (!$hasUnread) {
            User::where('id', $userId)->update(['unread' => 0]);
        }
    }

    /**
     * Get unread count for user
     *
     * @param int $userId
     * @return int
     */
    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->sum('count');
    }
}