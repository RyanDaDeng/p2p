<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Order;
use App\Models\Review;
use App\Models\Address;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_verified',
        'rating',
        'trades_count',
        'last_seen',
        'avg_rating',
        'total_reviews',
        'telegram_chat_id',
        'response_time',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'rating' => 'decimal:2',
            'trades_count' => 'integer',
            'last_seen' => 'datetime',
            'avg_rating' => 'decimal:2',
            'total_reviews' => 'integer',
        ];
    }
    
    /**
     * Get orders where user is vendor
     */
    public function vendorOrders()
    {
        return $this->hasMany(Order::class, 'vendor_id');
    }
    
    /**
     * Get orders where user is client
     */
    public function clientOrders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }
    
    /**
     * Get reviews received by user
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewed_id');
    }
    
    /**
     * Get reviews written by user
     */
    public function writtenReviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * Get reviews received by this user (as vendor).
     */
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewed_id');
    }

    /**
     * Get reviews written by this user (as client).
     */
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }
    
    /**
     * Get user's cryptocurrency addresses.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
