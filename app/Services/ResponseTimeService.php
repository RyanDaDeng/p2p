<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ResponseTimeService
{
    /**
     * Record vendor's first response to an order
     */
    public function recordFirstResponse(Order $order, int $userId): void
    {

        // Check if this is the vendor and hasn't responded yet
        if ($order->vendor_id == $userId && !$order->vendor_first_response_at) {
            // Calculate response time in minutes (positive value)
            $responseTime = $order->created_at->diffInMinutes(now());

            $order->vendor_first_response_at = now();
            $order->response_time_minutes = $responseTime;
            $order->save();

            // Update user's average response time
            $this->updateUserResponseTime($userId);
        }
    }

    /**
     * Update user's average response time based on last 30 orders
     */
    public function updateUserResponseTime(int $userId): void
    {
        // Calculate average response time from last 30 orders
        $avgResponseTime = Order::where('vendor_id', $userId)
            ->whereNotNull('response_time_minutes')
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->avg('response_time_minutes');

        if ($avgResponseTime !== null) {
            User::where('id', $userId)->update([
                'response_time' => round($avgResponseTime)
            ]);
        }
    }

    /**
     * Batch update all users' response times
     */
    public function updateAllUsersResponseTime(): void
    {
        $vendors = User::whereHas('vendorOrders', function($query) {
            $query->whereNotNull('response_time_minutes');
        })->pluck('id');

        foreach ($vendors as $vendorId) {
            $this->updateUserResponseTime($vendorId);
        }
    }

    /**
     * Get formatted response time for display
     */
    public function formatResponseTime(?int $minutes): string
    {
        if ($minutes === null) {
            return '暂无数据';
        }

        if ($minutes < 5) {
            return '< 5分钟';
        }

        if ($minutes < 60) {
            return $minutes . '分钟';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes == 0) {
            return $hours . '小时';
        }

        return $hours . '小时' . $remainingMinutes . '分钟';
    }
}
