<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Common\ApiController;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TelegramController extends ApiController
{
    protected TelegramService $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Test bot connection
     */
    public function testBot(Request $request)
    {
        $result = $this->telegram->getMe();
        
        if (!$result['success']) {
            return $this->sendError('Failed to connect to bot');
        }
        
        return $this->sendSuccess($result['data']);
    }
    
    /**
     * Get verification code for Telegram connection
     */
    public function getSettings(Request $request)
    {
        $user = $request->user();
        
        // Return error if already connected
        if ($user->telegram_chat_id) {
            return $this->sendError('Telegram 已连接', 400);
        }
        
        // Generate verification code for this user
        $verificationCode = $this->generateVerificationCode($user->id);
        
        return $this->sendSuccess([
            'verification_code' => $verificationCode,
        ]);
    }

    /**
     * Get latest updates from Telegram to find chat ID
     */
    public function getUpdates(Request $request)
    {
        $user = $request->user();
        $verificationCode = $this->generateVerificationCode($user->id);
        
        $result = $this->telegram->getUpdates();
        
        if (!$result['success']) {
            return $this->sendError('Failed to get updates');
        }
        
        $updates = $result['data'] ?? [];
        $chatId = null;
        $autoDetected = false;
        $telegramUser = null;
        $username = null;
        $firstName = null;
        $lastName = null;
        
        // Look for messages with our verification code
        foreach (array_reverse($updates) as $update) {
            if (isset($update['message']['text'])) {
                $text = trim($update['message']['text']);
                
                // Check if message is exactly the verification code (case-insensitive)
                if (strtoupper($text) === $verificationCode) {
                    $chatId = $update['message']['chat']['id'];
                    $autoDetected = true;
                    
                    // Get Telegram user info
                    $telegramUser = $update['message']['from'] ?? null;
                    
                    if ($telegramUser) {
                        $username = $telegramUser['username'] ?? null;
                        $firstName = $telegramUser['first_name'] ?? null;
                        $lastName = $telegramUser['last_name'] ?? null;
                    }
                    
                    // Check if this chat ID is already used by another user
                    $existingUser = \App\Models\User::where('telegram_chat_id', $chatId)
                        ->where('id', '!=', $user->id)
                        ->first();
                    
                    if ($existingUser) {
                        // This Telegram account is already bound to another user
                        $this->telegram->sendNotification(
                            $chatId,
                            '❌ 绑定失败',
                            "此 Telegram 账号已绑定到另一个用户: {$existingUser->email}\n\n如需更换绑定，请先在原账号中解除绑定。",
                            'error'
                        );
                        
                        return $this->sendError('此 Telegram 账号已被其他用户绑定', 400);
                    }
                    
                    // Auto-save the chat ID
                    $user->telegram_chat_id = $chatId;
                    $user->save();
                    
                    // Send confirmation message
                    $this->telegram->sendNotification(
                        $chatId,
                        '✅ 连接成功',
                        "您的 Telegram 已成功连接到 P2P 交易平台账号。\n\n用户: {$user->email}",
                        'success'
                    );
                    
                    // Clear the verification code from cache
                    Cache::forget('telegram_verify_' . $verificationCode);
                    Cache::forget('telegram_user_' . $user->id);
                    
                    break;
                }
            }
            
            // Fallback to any chat ID if no verification code found
            if (!$chatId) {
                $foundChatId = $this->telegram->getChatIdFromUpdate($update);
                if ($foundChatId) {
                    $chatId = $foundChatId;
                }
            }
        }
        
        // Build response with Telegram user info if available
        $response = [
            'chat_id' => $chatId,
            'auto_detected' => $autoDetected,
            'connected' => $autoDetected,
        ];
        
        // Add Telegram user info if we found it during verification
        if ($autoDetected && isset($telegramUser)) {
            $response['telegram_user'] = [
                'username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'display_name' => $this->formatTelegramDisplayName($username, $firstName, $lastName)
            ];
        }
        
        return $this->sendSuccess($response);
    }

    /**
     * Connect Telegram account
     */
    public function connect(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|string',
        ]);
        
        $chatId = $request->chat_id;
        $user = $request->user();
        
        // Check if this chat ID is already used by another user
        $existingUser = \App\Models\User::where('telegram_chat_id', $chatId)
            ->where('id', '!=', $user->id)
            ->first();
        
        if ($existingUser) {
            return $this->sendError('此 Telegram 账号已被其他用户绑定', 400);
        }
        
        $user->telegram_chat_id = $chatId;
        $user->save();
        
        // Send welcome message
        $this->telegram->sendNotification(
            $chatId,
            '连接成功',
            "您的 Telegram 已成功连接到 P2P 交易平台。\n您将在这里接收重要通知。",
            'success'
        );
        
        return $this->sendSuccess([], 'Telegram connected successfully');
    }

    /**
     * Disconnect Telegram
     */
    public function disconnect(Request $request)
    {
        $user = $request->user();
        
        if ($user->telegram_chat_id) {
            // Send goodbye message
            $this->telegram->sendNotification(
                $user->telegram_chat_id,
                '断开连接',
                "您的 Telegram 已从 P2P 交易平台断开连接。",
                'info'
            );
        }
        
        $user->telegram_chat_id = null;
        $user->save();
        
        return $this->sendSuccess([], 'Telegram disconnected');
    }

    /**
     * Send test message
     */
    public function sendTest(Request $request)
    {
        $user = $request->user();
        
        if (!$user->telegram_chat_id) {
            return $this->sendError('Telegram not connected', 400);
        }
        
        $result = $this->telegram->sendTestMessage($user->telegram_chat_id);
        
        if (!$result['success']) {
            return $this->sendError('Failed to send test message');
        }
        
        return $this->sendSuccess([], 'Test message sent');
    }



    /**
     * Handle webhook from Telegram
     */
    public function webhook(Request $request)
    {
        $update = $request->all();
        
        // Process the update (can be used for commands, etc.)
        $chatId = $this->telegram->getChatIdFromUpdate($update);
        
        if ($chatId && isset($update['message']['text'])) {
            $text = trim($update['message']['text']);
            
            // Check if message is a verification code (6 uppercase letters/numbers)
            if (preg_match('/^[A-Z0-9]{6}$/', strtoupper($text))) {
                $verificationCode = strtoupper($text);
                $userId = Cache::get('telegram_verify_' . $verificationCode);
                
                if ($userId) {
                    // Valid verification code - link the account
                    $user = \App\Models\User::find($userId);
                    if ($user) {
                        // Check if this chat ID is already used by another user
                        $existingUser = \App\Models\User::where('telegram_chat_id', $chatId)
                            ->where('id', '!=', $user->id)
                            ->first();
                        
                        if ($existingUser) {
                            $this->telegram->sendMessage(
                                $chatId,
                                "❌ <b>绑定失败</b>\n\n" .
                                "此 Telegram 账号已绑定到另一个用户:\n" .
                                "📧 {$existingUser->email}\n\n" .
                                "如需更换绑定，请先在原账号中解除绑定。"
                            );
                            
                            // Clear the verification code
                            Cache::forget('telegram_verify_' . $verificationCode);
                            Cache::forget('telegram_user_' . $userId);
                            return;
                        }
                        
                        $user->telegram_chat_id = $chatId;
                        $user->save();
                        
                        // Clear the verification code
                        Cache::forget('telegram_verify_' . $verificationCode);
                        Cache::forget('telegram_user_' . $userId);
                        
                        $this->telegram->sendMessage(
                            $chatId,
                            "✅ <b>连接成功！</b>\n\n" .
                            "您的 Telegram 已成功连接到 P2P 交易平台。\n\n" .
                            "👤 账号: {$user->email}\n" .
                            "🆔 Chat ID: <code>{$chatId}</code>\n\n" .
                            "您现在可以接收平台通知了！"
                        );
                    } else {
                        $this->telegram->sendMessage(
                            $chatId,
                            "❌ <b>验证失败</b>\n\n" .
                            "验证码无效或已过期，请重新获取。"
                        );
                    }
                } else {
                    // Not a valid verification code, check other commands
                    $this->handleCommand($chatId, $text, $update);
                }
            } else {
                // Handle regular commands
                $this->handleCommand($chatId, $text, $update);
            }
        }
        
        return response()->json(['ok' => true]);
    }
    
    /**
     * Handle bot commands
     */
    protected function handleCommand($chatId, $text, $update)
    {
        if ($text === '/start') {
            $this->telegram->sendMessage(
                $chatId,
                "欢迎使用 P2P 交易平台通知机器人！\n\n" .
                "您的 Chat ID 是: <code>{$chatId}</code>\n\n" .
                "请在平台通知设置页面输入验证码来连接您的账号。"
            );
        } elseif ($text === '/help') {
            $this->telegram->sendMessage(
                $chatId,
                "<b>可用命令:</b>\n\n" .
                "/start - 获取您的 Chat ID\n" .
                "/help - 显示帮助信息\n" .
                "/status - 查看连接状态\n\n" .
                "<b>连接方法:</b>\n" .
                "1. 在平台通知设置页面获取验证码\n" .
                "2. 直接发送验证码给机器人即可完成连接"
            );
        } elseif ($text === '/status') {
            // Check if this chat ID is connected to any user
            $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();
            
            if ($user) {
                $this->telegram->sendMessage(
                    $chatId,
                    "✅ 已连接\n\n" .
                    "账号: {$user->email}\n" .
                    "用户名: {$user->name}"
                );
            } else {
                $this->telegram->sendMessage(
                    $chatId,
                    "❌ 未连接\n\n" .
                    "请在平台通知设置页面获取验证码并发送给机器人。"
                );
            }
        }
    }
    
    /**
     * Generate verification code for user
     */
    protected function generateVerificationCode($userId)
    {
        // Check if we already have a code for this user
        $existingCode = Cache::get('telegram_user_' . $userId);
        if ($existingCode) {
            return $existingCode;
        }
        
        // Generate a new 6-character code
        $code = strtoupper(Str::random(6));
        
        // Store both directions in cache for 1 hour
        Cache::put('telegram_verify_' . $code, $userId, 3600);
        Cache::put('telegram_user_' . $userId, $code, 3600);
        
        return $code;
    }
    
    /**
     * Format Telegram display name
     */
    protected function formatTelegramDisplayName($username, $firstName, $lastName)
    {
        if ($username) {
            return '@' . $username;
        }
        
        $name = trim(($firstName ?? '') . ' ' . ($lastName ?? ''));
        return $name ?: 'Telegram User';
    }
}