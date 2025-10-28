<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;

class PhoneNotificationController extends Controller
{
    protected $twilioClient;

    public function __construct()
    {
        $accountSid = config('twilio.account_sid');
        $authToken = config('twilio.auth_token');
        $this->twilioClient = new Client($accountSid, $authToken);
    }

    public function getStatus(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'enabled' => $user->phone_notifications_enabled ?? false,
        ]);
    }

    public function toggleNotifications(Request $request)
    {
        $request->validate([
            'enabled' => 'required|boolean',
        ]);

        $user = $request->user();

        $user->update([
            'phone_notifications_enabled' => $request->enabled,
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->enabled ? '短信通知已开启' : '短信通知已关闭',
        ]);
    }

    public function sendTestSMS(Request $request)
    {
        $user = $request->user();

        if (!$user->phone_number || !$user->phone_verified_at) {
            return response()->json([
                'success' => false,
                'message' => '请先验证您的手机号码',
            ], 400);
        }

        if (!$user->phone_notifications_enabled) {
            return response()->json([
                'success' => false,
                'message' => '请先开启短信通知功能',
            ], 400);
        }

        $fromNumber = env('TWILIO_FROM_NUMBER');

        if (!$fromNumber) {
            return response()->json([
                'success' => false,
                'message' => '系统未配置发送号码',
            ], 500);
        }

        try {
            // 使用普通 SMS 发送测试短信（不是验证码）
            $message = $this->twilioClient->messages->create(
                $user->phone_number,
                [
                    'from' => $fromNumber,
                    'body' => "[P2P平台] 这是一条测试短信，确认您的手机通知功能正常工作。"
                ]
            );

            return response()->json([
                'success' => true,
                'message' => '测试短信已发送',
            ]);
        } catch (RestException $e) {
            return response()->json([
                'success' => false,
                'message' => '发送失败：' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * 发送订单通知短信
     * 使用普通 SMS 服务发送订单相关通知
     */
    public function sendOrderNotification($user, $orderNo, $status)
    {
        if (!$user->phone_number || !$user->phone_verified_at) {
            return false;
        }

        // 检查用户是否开启了短信通知
        if (!$user->phone_notifications_enabled) {
            return false;
        }

        $fromNumber = env('TWILIO_FROM_NUMBER');
        if (!$fromNumber) {
            \Log::error('TWILIO_FROM_NUMBER not configured');
            return false;
        }

        $statusMessages = [
            'created' => '您有新的订单 #' . $orderNo,
            'paid' => '订单 #' . $orderNo . ' 已付款，请及时处理',
            'released' => '订单 #' . $orderNo . ' 已成功释放',
            'cancelled' => '订单 #' . $orderNo . ' 已取消',
            'disputed' => '订单 #' . $orderNo . ' 进入争议，请及时处理',
        ];

        $message = $statusMessages[$status] ?? '订单 #' . $orderNo . ' 状态已更新';

        try {
            $this->twilioClient->messages->create(
                $user->phone_number,
                [
                    'from' => $fromNumber,
                    'body' => "[P2P平台] " . $message
                ]
            );
            return true;
        } catch (RestException $e) {
            \Log::error('Order SMS notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 发送安全通知短信
     * 使用普通 SMS 服务发送安全相关通知
     */
    public function sendSecurityNotification($user, $message)
    {
        if (!$user->phone_number || !$user->phone_verified_at) {
            return false;
        }

        // 检查用户是否开启了短信通知
        if (!$user->phone_notifications_enabled) {
            return false;
        }

        $fromNumber = env('TWILIO_FROM_NUMBER');
        if (!$fromNumber) {
            \Log::error('TWILIO_FROM_NUMBER not configured');
            return false;
        }

        try {
            $this->twilioClient->messages->create(
                $user->phone_number,
                [
                    'from' => $fromNumber,
                    'body' => "[P2P平台安全提醒] " . $message
                ]
            );
            return true;
        } catch (RestException $e) {
            \Log::error('Security SMS notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 发送新消息通知短信
     * 使用普通 SMS 服务
     */
    public function sendMessageNotification($user, $senderName, $orderNo)
    {
        if (!$user->phone_number || !$user->phone_verified_at) {
            return false;
        }

        // 检查用户是否开启了短信通知
        if (!$user->phone_notifications_enabled) {
            return false;
        }

        $fromNumber = env('TWILIO_FROM_NUMBER');
        if (!$fromNumber) {
            \Log::error('TWILIO_FROM_NUMBER not configured');
            return false;
        }

        try {
            $this->twilioClient->messages->create(
                $user->phone_number,
                [
                    'from' => $fromNumber,
                    'body' => "[P2P平台] 您有来自 {$senderName} 的新消息，订单 #{$orderNo}"
                ]
            );
            return true;
        } catch (RestException $e) {
            \Log::error('Message SMS notification failed: ' . $e->getMessage());
            return false;
        }
    }
}