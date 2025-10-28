<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\Notification;
use App\Models\User;
use App\Services\TelegramService;
use App\Services\NotificationService;
use App\Services\ResponseTimeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ChatController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * 显示订单聊天页面
     */
    public function index($orderNo)
    {
        $order = Order::with(['vendor', 'client', 'seller', 'buyer', 'escrowLogs', 'review'])
            ->where('order_no', $orderNo)
            ->firstOrFail();

        // 验证用户是否有权访问此订单
        if (!$order->isParticipant(auth()->id(),true)) {
            abort(403, '无权访问此订单');
        }

        // Record vendor's first response when they enter the chat
        $responseTimeService = app(ResponseTimeService::class);
        $responseTimeService->recordFirstResponse($order, auth()->id());

        // 标记该订单的通知为已读
        $updated = Notification::where('user_id', auth()->id())
            ->where('order_id', $order->id)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        // 如果有更新通知，检查是否还有其他未读通知
        if ($updated > 0) {
            $hasOtherUnread = Notification::where('user_id', auth()->id())
                ->where('is_read', 0)
                ->exists();

            // 如果没有其他未读通知，清除用户的 unread 标记
            if (!$hasOtherUnread) {
                User::where('id', auth()->id())->update(['unread' => 0]);
            }
        }

        // 获取订单相关的聊天消息
        $messages = ChatMessage::with('user')
            ->where('order_id', $order->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'type' => $message->type,
                    'attachment' => $message->attachment,
                    'user' => $message->user ? [
                        'id' =>  $message->user->id,
                        'name' => $message->user->name,
                        'profile_photo_url' => $message->user->profile_photo_url,
                    ] : [],
                    'created_at' => $message->created_at->toISOString(),
                ];
            });

        // 获取对方用户信息
        $otherUser = null;
        if ($order->vendor_id === auth()->id()) {
            $otherUser = $order->client;
        } else {
            $otherUser = $order->vendor;
        }

        // 获取可用操作
        $availableActions = $order->getAvailableActions(auth()->id());

        // 获取加密货币配置信息
        $cryptocurrencies = config('cryptocurrencies.supported', []);
        $currencyInfo = $cryptocurrencies[$order->currency_key] ?? null;
        $currencyLabel = $currencyInfo ? $currencyInfo['label'] : strtoupper($order->currency_key);

        return Inertia::render('Trade/Chat', [
            'userHas2FA' => auth()->user()->two_factor_secret !== null,
            'order' => [
                'id' => $order->id,
                'order_no' => $order->order_no,
                'vendor_id' => $order->vendor_id,
                'client_id' => $order->client_id,
                'seller_id' => $order->seller_id,
                'buyer_id' => $order->buyer_id,
                'trade_type' => $order->trade_type,
                'crypto_currency' => $order->currency_key,
                'currency_key' => $order->currency_key,
                'currency_label' => $currencyLabel,
                'crypto_amount' => $order->crypto_amount,
                'fiat_currency' => $order->fiat_currency,
                'fiat_amount' => $order->fiat_amount,
                'price' => $order->price,
                'fee' => $order->fee,
                'status' => $order->status,
                'escrow_status' => $order->escrow_status,
                'escrow_address' => $order->escrow_address,
                'seller_address' => $order->seller_address,
                'escrow_tx_hash' => $order->escrow_tx_hash,
                'escrow_released_at' => $order->escrow_released_at,
                'release_tx_hash' => $order->release_tx_hash,
                'payment_methods' => $order->payment_methods,
                'created_at' => $order->created_at->toISOString(),
                'vendor_confirmed_at' => $order->vendor_confirmed_at,
                'seller_paid_at' => $order->seller_paid_at,
                'escrow_received_at' => $order->escrow_received_at,
                'buyer_paid_at' => $order->buyer_paid_at,
                'buyer_confirmed_escrow_at' => $order->buyer_confirmed_escrow_at,
                'seller_received_at' => $order->seller_received_at,
                'is_disputed' => $order->is_disputed,
                'disputed_by' => $order->disputed_by,
                'dispute_reason' => $order->dispute_reason,
                'vendor' => [
                    'id' => $order->vendor->id,
                    'name' => $order->vendor->name,
                    'avatar' => $order->vendor->profile_photo_url,
                    'rating' => $order->vendor->rating ?? 5.0,
                    'trades_count' => $order->vendor->trades_count ?? 0,
                    'is_verified' => $order->vendor->is_verified ?? false,
                    'last_seen' => $order->vendor->last_seen,
                    'response_time' => $order->vendor->response_time,
                    'completion_rate' => 98.5, // 暂时使用固定值
                ],
                'client' => [
                    'id' => $order->client->id,
                    'name' => $order->client->name,
                    'avatar' => $order->client->profile_photo_url,
                    'rating' => $order->client->rating ?? 5.0,
                    'trades_count' => $order->client->trades_count ?? 0,
                    'is_verified' => $order->client->is_verified ?? false,
                    'last_seen' => $order->client->last_seen,
                    'completion_rate' => 98.5, // 暂时使用固定值
                ],
                'buyer' => [
                    'id' => $order->buyer->id,
                    'name' => $order->buyer->name,
                ],
                'seller' => [
                    'id' => $order->seller->id,
                    'name' => $order->seller->name,
                ],
                'ad_snapshot' => $order->ad_snapshot,
                'has_review' => $order->review !== null,
                'review' => $order->review,
            ],
            'otherUser' => $otherUser ? [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'avatar' => $otherUser->profile_photo_url,
                'rating' => $otherUser->rating ?? 5.0,
                'trades_count' => $otherUser->trades_count ?? 0,
                'is_verified' => $otherUser->is_verified ?? false,
                'last_seen' => $otherUser->last_seen,
                'completion_rate' => 98.5, // 暂时使用固定值
            ] : null,
            'messages' => $messages,
            'currentUserId' => auth()->id(),
            'userRole' => $order->getUserRole(auth()->id()),
            'availableActions' => $availableActions,
        ]);
    }

    /**
     * 获取消息列表（支持获取离线期间的消息）
     */
    public function getMessages(Request $request, $orderNo)
    {
        $order = Order::where('order_no', $orderNo)->firstOrFail();

        // 检查用户权限
        if (!$order->isParticipant(auth()->id())) {
            abort(403, '无权访问');
        }

        $query = $order->chatMessages();

        // 如果提供了since参数，获取该时间戳之后的消息
        if ($request->has('since')) {
            $since = Carbon::createFromTimestamp($request->since);
            $query->where('created_at', '>', $since);
        }

        $messages = $query->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'type' => $message->type,
                    'attachment' => $message->attachment,
                    'user' => $message->user ? [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'profile_photo_url' => $message->user->profile_photo_url,
                    ] : null,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            });

        return response()->json($messages);
    }

    /**
     * 发送消息
     */
    public function sendMessage(Request $request, $orderNo)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'attachment' => 'nullable|array',
        ]);

        // 至少需要消息或附件
        if (empty($request->message) && empty($request->attachment)) {
            return response()->json([
                'success' => false,
                'message' => '消息内容不能为空'
            ], 422);
        }

        $order = Order::where('order_no', $orderNo)->firstOrFail();

        // 验证用户是否有权访问此订单
        if (!$order->isParticipant(auth()->id(), true)) {
            return response()->json([
                'success' => false,
                'message' => '无权访问此订单'
            ], 403);
        }

        // 检查交易是否已结束
        if (in_array($order->status, ['completed', 'cancelled']) || $order->escrow_status === 'escrow_released') {
            return response()->json([
                'success' => false,
                'message' => '交易已结束，无法发送消息'
            ], 403);
        }

        // 检查是否是管理员介入争议
        $isAdminIntervention = false;
        if (auth()->user()->is_admin && $order->is_disputed) {
            // 管理员且订单有争议，并且管理员不是订单的原始参与者
            if (auth()->id() !== $order->vendor_id && auth()->id() !== $order->client_id) {
                $isAdminIntervention = true;
            }
        }

        $message = ChatMessage::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'message' => $request->message ?? '',
            'attachment' => $request->attachment,
            'type' => $isAdminIntervention ? 'admin' : 'text',
        ]);

        // 如果对方不在线，处理通知
        if (!$request->input('recipient_online', true)) {
            $recipientId = $order->vendor_id === auth()->id()
                ? $order->client_id
                : $order->vendor_id;

            NotificationService::sendOrderNotification($recipientId, $order, $message, auth()->user());
        }

        // 广播消息到私有频道
        broadcast(new MessageSent($message, auth()->user(), $order->id))->toOthers();

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'attachment' => $message->attachment,
            'type' => $message->type,
            'user' => [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'profile_photo_url' => auth()->user()->profile_photo_url,
            ],
            'created_at' => $message->created_at->toISOString(),
        ]);
    }

    /**
     * 上传文件
     */
    public function uploadFile(Request $request, $orderNo)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,gif,webp|max:2048', // 2MB
        ], [
            'file.required' => '请选择要上传的文件',
            'file.file' => '上传的必须是有效文件',
            'file.mimes' => '文件格式不支持，仅支持 PDF、JPG、PNG、GIF、WebP',
            'file.max' => '文件大小不能超过 2MB',
        ]);

        $order = Order::where('order_no', $orderNo)->firstOrFail();

        // 验证用户是否有权访问此订单
        if (!$order->isParticipant(auth()->id(), true)) {
            return response()->json([
                'success' => false,
                'message' => '无权访问此订单'
            ], 403);
        }

        // 检查交易是否已结束
        if (in_array($order->status, ['completed', 'cancelled']) || $order->escrow_status === 'escrow_released') {
            return response()->json([
                'success' => false,
                'message' => '交易已结束，无法上传文件'
            ], 403);
        }

        $file = $request->file('file');
        $filename = uniqid() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs("orders/{$order->id}", $filename, 'public');

        return response()->json([
            'success' => true,
            'file' => [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'url' => \Storage::url($path),
            ]
        ]);
    }

    /**
     * 发送系统消息
     */
    public static function sendSystemMessage($orderId, $message, $userId = null)
    {
        // 如果没有传入 userId，尝试获取当前用户
        if ($userId === null) {
            $userId = auth()->id();
        }

        $chatMessage = ChatMessage::create([
            'order_id' => $orderId,
            'user_id' => $userId,
            'message' => $message,
            'type' => 'system',
        ]);

        // 广播系统消息，包含触发用户信息
        $user = $userId ? \App\Models\User::find($userId) : null;

        broadcast(new MessageSent($chatMessage, $user, $orderId));

        return $chatMessage;
    }

}
