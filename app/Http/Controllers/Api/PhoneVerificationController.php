<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;

class PhoneVerificationController extends Controller
{
    protected $twilioClient;
    protected $verifyServiceSid;

    public function __construct()
    {
        $accountSid = config('twilio.account_sid');
        $authToken = config('twilio.auth_token');
        $this->verifyServiceSid = config('twilio.verify_service_sid');

        $this->twilioClient = new Client($accountSid, $authToken);
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^\+?[1-9]\d{1,14}$/',
        ]);

        $phoneNumber = $request->phone_number;

        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = '+' . $phoneNumber;
        }

        try {
            $verification = $this->twilioClient->verify->v2
                ->services($this->verifyServiceSid)
                ->verifications
                ->create($phoneNumber, 'sms');

            $request->user()->update([
                'phone_number' => $phoneNumber,
            ]);

            return response()->json([
                'success' => true,
                'message' => '验证码已发送到您的手机',
                'status' => $verification->status,
            ]);
        } catch (RestException $e) {
            return response()->json([
                'success' => false,
                'message' => '发送验证码失败：' . $e->getMessage(),
            ], 400);
        }
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if (!$user->phone_number) {
            return response()->json([
                'success' => false,
                'message' => '请先发送验证码',
            ], 400);
        }

        try {
            $verificationCheck = $this->twilioClient->verify->v2
                ->services($this->verifyServiceSid)
                ->verificationChecks
                ->create([
                    'to' => $user->phone_number,
                    'code' => $request->code,
                ]);

            if ($verificationCheck->valid) {
                $user->update([
                    'phone_verified_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => '手机号码验证成功',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '验证码错误',
                ], 400);
            }
        } catch (RestException $e) {
            return response()->json([
                'success' => false,
                'message' => '验证失败：' . $e->getMessage(),
            ], 400);
        }
    }

    public function getStatus(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'phone_number' => $user->phone_number,
            'is_verified' => $user->phone_verified_at !== null,
            'verified_at' => $user->phone_verified_at,
        ]);
    }
}