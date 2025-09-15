<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Common\ApiController;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends ApiController
{
    /**
     * 创建评价
     */
    public function store(Request $request, $orderId)
    {
        $order = Order::with(['vendor', 'client'])->findOrFail($orderId);
        
        // 检查是否可以创建评价
        if (!Review::canCreateForOrder($order, Auth::user())) {
            return $this->sendError('无法创建评价', [
                'reason' => $this->getCannotReviewReason($order)
            ]);
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean'
        ]);
        
        DB::beginTransaction();
        try {
            // 获取ad_snapshot中的trade_type (已经是数组)
            $adSnapshot = $order->ad_snapshot;
            $tradeType = $adSnapshot['trade_type'] ?? 'buy';
            
            // 创建评价
            $review = Review::create([
                'order_id' => $order->id,
                'reviewer_id' => Auth::id(),
                'reviewed_id' => $order->vendor_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'trade_type' => $tradeType,
                'crypto_currency' => $order->currency_key,
                'fiat_amount' => $order->fiat_amount,
                'is_anonymous' => $request->is_anonymous ?? false
            ]);
            
            // 更新订单状态为已完成
            $order->update(['status' => 'completed']);
            
            DB::commit();
            
            return $this->sendSuccessWithNotify('评价提交成功', [
                'review' => $review->load('reviewer', 'reviewed')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('评价提交失败: ' . $e->getMessage());
        }
    }
    
    /**
     * 获取订单的评价状态
     */
    public function checkStatus($orderId)
    {
        $order = Order::with('review')->findOrFail($orderId);
        
        // 检查用户权限
        if (!$order->isParticipant(Auth::id())) {
            return $this->sendError('无权访问此订单');
        }
        
        $canReview = Review::canCreateForOrder($order, Auth::user());
        $hasReviewed = $order->review !== null;
        
        return $this->sendSuccess([
            'can_review' => $canReview,
            'has_reviewed' => $hasReviewed,
            'review' => $hasReviewed ? $order->review : null,
            'reason' => !$canReview ? $this->getCannotReviewReason($order) : null
        ]);
    }
    
    /**
     * 获取用户收到的评价列表
     */
    public function getUserReviews(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $query = Review::with(['reviewer', 'order'])
            ->forUser($userId);

        // Apply rating filter
        $ratingFilter = $request->query('rating', 'all');
        if ($ratingFilter === 'positive') {
            $query->whereIn('rating', [4, 5]);
        } elseif ($ratingFilter === 'neutral') {
            $query->where('rating', 3);
        } elseif ($ratingFilter === 'negative') {
            $query->whereIn('rating', [1, 2]);
        }

        $perPage = $request->query('per_page', 10);
        $reviews = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);
            
        // 处理匿名评价
        $reviewsCollection = $reviews->getCollection()->transform(function ($review) {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'trade_type' => $review->trade_type,
                'currency_key' => $review->currency_key,
                'crypto_currency' => $review->currency_key,
                'fiat_amount' => $review->fiat_amount,
                'created_at' => $review->created_at,
                'is_anonymous' => $review->is_anonymous,
                'reviewer' => [
                    'id' => $review->is_anonymous ? null : $review->reviewer_id,
                    'name' => $review->reviewer_name,
                    'avatar' => $review->reviewer_avatar
                ]
            ];
        });

        return $this->sendSuccess([
            'reviews' => $reviewsCollection,
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total()
            ],
            'statistics' => [
                'avg_rating' => $user->avg_rating,
                'total_reviews' => $user->total_reviews,
                'rating_distribution' => $this->getRatingDistribution($userId)
            ]
        ]);
    }
    
    /**
     * 获取评分分布
     */
    private function getRatingDistribution($userId)
    {
        $distribution = Review::forUser($userId)
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();
            
        // 确保所有评分都有值
        $result = [];
        for ($i = 1; $i <= 5; $i++) {
            $result[$i] = $distribution[$i] ?? 0;
        }
        
        return $result;
    }
    
    /**
     * 获取无法评价的原因
     */
    private function getCannotReviewReason(Order $order)
    {
        if ($order->escrow_status !== 'escrow_released') {
            return '订单尚未完成';
        }
        
        if ($order->client_id !== Auth::id()) {
            return '只有客户可以评价商家';
        }
        
        if (Review::where('order_id', $order->id)->exists()) {
            return '该订单已评价';
        }
        
        return '未知原因';
    }
}