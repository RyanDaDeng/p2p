<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $apiBaseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->botToken = config('telegram.bot_token');
        $this->apiBaseUrl = config('telegram.api_base_url');
        $this->timeout = config('telegram.timeout', 30);
    }

    /**
     * Send a message to a specific chat
     */
    public function sendMessage(string $chatId, string $message, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ], $options);

        return $this->makeRequest('sendMessage', $params);
    }

    /**
     * Get updates (messages, etc.)
     */
    public function getUpdates(int $offset = 0): array
    {
        return $this->makeRequest('getUpdates', [
            'offset' => $offset,
            'timeout' => 0,  // Don't use long polling for immediate response
        ]);
    }

    /**
     * Get bot information
     */
    public function getMe(): array
    {
        return $this->makeRequest('getMe');
    }

    /**
     * Get chat information
     */
    public function getChat(string $chatId): array
    {
        return $this->makeRequest('getChat', [
            'chat_id' => $chatId,
        ]);
    }

    /**
     * Set webhook for receiving updates
     */
    public function setWebhook(string $url): array
    {
        return $this->makeRequest('setWebhook', [
            'url' => $url,
        ]);
    }

    /**
     * Delete webhook
     */
    public function deleteWebhook(): array
    {
        return $this->makeRequest('deleteWebhook');
    }

    /**
     * Send notification with formatting
     */
    public function sendNotification(string $chatId, string $title, string $content, string $type = 'info'): array
    {
        $emoji = $this->getEmojiForType($type);
        
        $message = "<b>{$emoji} {$title}</b>\n\n";
        $message .= $content;
        
        return $this->sendMessage($chatId, $message);
    }

    /**
     * Send test message
     */
    public function sendTestMessage(string $chatId): array
    {
        $message = "🔔 <b>测试通知</b>\n\n";
        $message .= "这是来自 P2P 交易平台的测试消息。\n";
        $message .= "如果您收到此消息，说明 Telegram 通知已成功配置！\n\n";
        $message .= "时间: " . now()->format('Y-m-d H:i:s');
        
        return $this->sendMessage($chatId, $message);
    }

    /**
     * Make API request to Telegram
     */
    protected function makeRequest(string $method, array $params = []): array
    {
        $url = $this->apiBaseUrl . $this->botToken . '/' . $method;
        
        try {
            // Use shorter timeout for getUpdates to avoid hanging
            $timeout = $method === 'getUpdates' ? 5 : $this->timeout;
            
            $response = Http::timeout($timeout)
                ->post($url, $params);
            
            $result = $response->json();
            
            if (!$result || !isset($result['ok']) || !$result['ok']) {
                Log::error('Telegram API error', [
                    'method' => $method,
                    'params' => $params,
                    'error' => $result['description'] ?? 'Unknown error',
                ]);
                
                return [
                    'success' => false,
                    'error' => $result['description'] ?? 'Unknown error',
                ];
            }
            
            return [
                'success' => true,
                'data' => $result['result'] ?? null,
            ];
            
        } catch (\Exception $e) {
            Log::error('Telegram request failed', [
                'method' => $method,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get emoji based on notification type
     */
    protected function getEmojiForType(string $type): string
    {
        return match($type) {
            'success' => '✅',
            'warning' => '⚠️',
            'error' => '❌',
            'order' => '📋',
            'payment' => '💰',
            'dispute' => '⚖️',
            'message' => '💬',
            default => '📢',
        };
    }

    /**
     * Format order notification
     */
    public function sendOrderNotification(string $chatId, array $orderData): array
    {
        $message = "<b>📋 新订单通知</b>\n\n";
        $message .= "订单号: <code>{$orderData['order_id']}</code>\n";
        $message .= "类型: {$orderData['type']}\n";
        $message .= "币种: {$orderData['cryptocurrency']}\n";
        $message .= "金额: {$orderData['amount']}\n";
        $message .= "价格: {$orderData['price']}\n";
        $message .= "状态: {$orderData['status']}\n";
        
        return $this->sendMessage($chatId, $message);
    }

    /**
     * Get chat ID from update
     */
    public function getChatIdFromUpdate(array $update): ?string
    {
        if (isset($update['message']['chat']['id'])) {
            return $update['message']['chat']['id'];
        }
        
        if (isset($update['callback_query']['message']['chat']['id'])) {
            return $update['callback_query']['message']['chat']['id'];
        }
        
        return null;
    }
}