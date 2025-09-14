<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'reviewer_id',
        'reviewed_id',
        'rating',
        'comment',
        'trade_type',
        'currency_key',
        'fiat_amount',
        'is_anonymous',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'fiat_amount' => 'decimal:2',
            'is_anonymous' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the order that owns the review.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the reviewer (customer who wrote the review).
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the reviewed user (vendor who received the review).
     */
    public function reviewed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_id');
    }

    /**
     * Scope to get reviews for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('reviewed_id', $userId);
    }

    /**
     * Scope to get reviews by a specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('reviewer_id', $userId);
    }

    /**
     * Get the reviewer's display name (handles anonymous reviews).
     */
    public function getReviewerNameAttribute()
    {
        if ($this->is_anonymous) {
            return '匿名用户';
        }
        return $this->reviewer->name;
    }

    /**
     * Get the reviewer's avatar (handles anonymous reviews).
     */
    public function getReviewerAvatarAttribute()
    {
        if ($this->is_anonymous) {
            return null;
        }
        return $this->reviewer->profile_photo_url;
    }

    /**
     * Check if the review can be created for the order.
     */
    public static function canCreateForOrder(Order $order, User $user): bool
    {
        // Order must be completed
        if ($order->escrow_status !== 'escrow_released') {
            return false;
        }

        // User must be the client (buyer)
        if ($order->client_id !== $user->id) {
            return false;
        }

        // Check if review already exists
        $existingReview = self::where('order_id', $order->id)->exists();
        if ($existingReview) {
            return false;
        }

        return true;
    }

    /**
     * Update user's rating statistics after review is saved.
     */
    protected static function booted(): void
    {
        static::created(function ($review) {
            $reviewed = $review->reviewed;
            
            // Calculate new average rating
            $allReviews = self::where('reviewed_id', $reviewed->id)->get();
            $avgRating = $allReviews->avg('rating');
            $totalReviews = $allReviews->count();
            
            // Update user statistics
            $reviewed->update([
                'avg_rating' => round($avgRating, 2),
                'total_reviews' => $totalReviews,
            ]);
        });

        static::deleted(function ($review) {
            $reviewed = $review->reviewed;
            
            // Recalculate rating after deletion
            $allReviews = self::where('reviewed_id', $reviewed->id)->get();
            $avgRating = $allReviews->avg('rating') ?: 5.00;
            $totalReviews = $allReviews->count();
            
            $reviewed->update([
                'avg_rating' => round($avgRating, 2),
                'total_reviews' => $totalReviews,
            ]);
        });
    }
}