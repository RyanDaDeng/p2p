<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AppRouteController extends Controller
{
    public function market(Request $request): Response
    {
        return Inertia::render('Market/Home', [
            'listings' => $this->getMockListings(),
        ]);
    }

    private function getMockListings(): array
    {
        return [
            [
                'id' => 1,
                'seller' => [
                    'username' => 'cryptoTrader88',
                    'rating' => 4.8,
                    'trades' => 156,
                    'isOnline' => true,
                    'isVerified' => true,
                ],
                'cryptocurrency' => 'BTC',
                'price' => 68542.50,
                'currency' => 'USD',
                'priceAboveMarket' => 2.5,
                'paymentMethod' => 'Bank Transfer',
                'limits' => [
                    'min' => 100,
                    'max' => 10000,
                ],
                'location' => 'United States',
                'description' => 'Fast and reliable service',
            ],
            [
                'id' => 2,
                'seller' => [
                    'username' => 'fastCrypto',
                    'rating' => 4.9,
                    'trades' => 342,
                    'isOnline' => true,
                    'isVerified' => true,
                ],
                'cryptocurrency' => 'ETH',
                'price' => 3456.78,
                'currency' => 'USD',
                'priceAboveMarket' => 1.8,
                'paymentMethod' => 'PayPal',
                'limits' => [
                    'min' => 50,
                    'max' => 5000,
                ],
                'location' => 'Europe',
                'description' => 'Instant payment confirmation',
            ],
            [
                'id' => 3,
                'seller' => [
                    'username' => 'bitcoinKing',
                    'rating' => 4.7,
                    'trades' => 89,
                    'isOnline' => false,
                    'isVerified' => true,
                ],
                'cryptocurrency' => 'BTC',
                'price' => 68320.00,
                'currency' => 'USD',
                'priceAboveMarket' => 2.1,
                'paymentMethod' => 'Cash Deposit',
                'limits' => [
                    'min' => 200,
                    'max' => 15000,
                ],
                'location' => 'Canada',
                'description' => 'ATM or branch deposit accepted',
            ],
            [
                'id' => 4,
                'seller' => [
                    'username' => 'ethMaster',
                    'rating' => 4.6,
                    'trades' => 234,
                    'isOnline' => true,
                    'isVerified' => false,
                ],
                'cryptocurrency' => 'ETH',
                'price' => 3489.99,
                'currency' => 'USD',
                'priceAboveMarket' => 2.8,
                'paymentMethod' => 'Wire Transfer',
                'limits' => [
                    'min' => 500,
                    'max' => 50000,
                ],
                'location' => 'Singapore',
                'description' => 'Large volume trades welcome',
            ],
            [
                'id' => 5,
                'seller' => [
                    'username' => 'cryptoQueen',
                    'rating' => 5.0,
                    'trades' => 512,
                    'isOnline' => true,
                    'isVerified' => true,
                ],
                'cryptocurrency' => 'USDT',
                'price' => 1.01,
                'currency' => 'USD',
                'priceAboveMarket' => 1.0,
                'paymentMethod' => 'Bank Transfer',
                'limits' => [
                    'min' => 100,
                    'max' => 100000,
                ],
                'location' => 'Hong Kong',
                'description' => 'Stable coin specialist, fast service',
            ],
            [
                'id' => 6,
                'seller' => [
                    'username' => 'flashTrader',
                    'rating' => 4.9,
                    'trades' => 873,
                    'isOnline' => true,
                    'isVerified' => true,
                ],
                'cryptocurrency' => 'BTC',
                'price' => 66950.00,
                'currency' => 'USD',
                'priceAboveMarket' => -3.5,
                'paymentMethod' => 'PayPal',
                'limits' => [
                    'min' => 50,
                    'max' => 3000,
                ],
                'location' => 'Europe',
                'description' => 'Best rates, instant release',
            ],
            [
                'id' => 7,
                'seller' => [
                    'username' => 'proExchange',
                    'rating' => 4.7,
                    'trades' => 445,
                    'isOnline' => true,
                    'isVerified' => true,
                ],
                'cryptocurrency' => 'ETH',
                'price' => 3380.00,
                'currency' => 'USD',
                'priceAboveMarket' => -1.8,
                'paymentMethod' => 'Wire Transfer',
                'limits' => [
                    'min' => 1000,
                    'max' => 50000,
                ],
                'location' => 'Singapore',
                'description' => 'Wholesale rates available',
            ],
        ];
    }


    public function orders(Request $request): Response
    {
        return Inertia::render('Orders/Index');
    }

    public function createOrder(Request $request): Response
    {
        $adId = $request->query('ad_id');
        $tradeType = $request->query('type', 'buy');

        $user = $request->user();
        $userAddresses = [];

        if ($user) {
            $userAddresses = $user->addresses()
                ->select('id', 'currency_key', 'currency', 'chain', 'chain_label', 'network', 'address')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($address) {
                    return [
                        'value' => $address->id,
                        'label' => $address->currency . ' (' . $address->network . ') - ' . $address->address,
                        'currency_key' => $address->currency_key,
                        'currency' => $address->currency,
                        'chain' => $address->chain,
                        'chain_label' => $address->chain_label,
                        'network' => $address->network,
                        'address' => $address->address,
                    ];
                });
        }

        return Inertia::render('Trade/CreateOrder', [
            'adId' => $adId,
            'tradeType' => $tradeType,
            'userAddresses' => $userAddresses,
        ]);
    }

    public function vendorApplication(Request $request): Response
    {
        return Inertia::render('Vendor/Application');
    }

    public function addressVerification(Request $request): Response
    {
        $user = $request->user();
        $addresses = $user ? $user->addresses()->orderBy('created_at', 'desc')->get() : [];

        return Inertia::render('Wallet/AddressVerification', [
            'addresses' => $addresses,
            'cryptocurrencies' => $this->getCryptocurrenciesForAddress(),
        ]);
    }

    private function getCryptocurrenciesForAddress(): array
    {
        // Get from config and convert chain_label to chainLabel for frontend compatibility
        $cryptos = array_values(config('cryptocurrencies.supported'));

        // Add chainLabel for backward compatibility with frontend
        return array_map(function($crypto) {
            $crypto['chainLabel'] = $crypto['chain_label'];
            return $crypto;
        }, $cryptos);
    }


    public function publish(Request $request): Response
    {
        $user = $request->user();
        $userAddresses = [];

        if ($user) {
            $userAddresses = $user->addresses()
                ->select('id', 'currency_key', 'currency', 'chain', 'address')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($address) {
                    // 截取地址显示前6位和后6位
                    $shortAddress = substr($address->address, 0, 6) . '...' . substr($address->address, -6);
                    return [
                        'value' => $address->id,
                        'label' => $address->currency . ' (' . $address->chain . ') - ' . $shortAddress,
                        'currency_key' => $address->currency_key,
                        'currency' => $address->currency,
                        'chain' => $address->chain,
                        'address' => $address->address,
                    ];
                });
        }

        return Inertia::render('Publish/Index', [
            'userAddresses' => $userAddresses,
        ]);
    }

    public function traderProfile(Request $request, $id = null): Response
    {
        // If no ID provided, show current user's trader profile
        $userId = $id ?: ($request->user() ? $request->user()->id : null);

        if (!$userId) {
            return redirect()->route('login');
        }

        $isOwnProfile = $request->user() && $request->user()->id == $userId;

        // Get user with statistics
        $user = \App\Models\User::withCount(['vendorOrders', 'clientOrders'])
            ->findOrFail($userId);

        // Calculate statistics
        $totalTrades = $user->vendor_orders_count + $user->client_orders_count;
        $completedTrades = \App\Models\Order::where(function($query) use ($userId) {
                $query->where('vendor_id', $userId)
                      ->orWhere('client_id', $userId);
            })
            ->where('escrow_status', 'escrow_released')
            ->count();

        $last30DaysTrades = \App\Models\Order::where(function($query) use ($userId) {
                $query->where('vendor_id', $userId)
                      ->orWhere('client_id', $userId);
            })
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Get unique trading partners
        $tradingPartners = \App\Models\Order::where(function($query) use ($userId) {
                $query->where('vendor_id', $userId)
                      ->orWhere('client_id', $userId);
            })
            ->where('escrow_status', 'escrow_released')
            ->get()
            ->map(function($order) use ($userId) {
                return $order->vendor_id == $userId ? $order->client_id : $order->vendor_id;
            })
            ->unique()
            ->count();

        // Reviews will be fetched via API, not needed here

        // Calculate rating statistics for tabs
        $ratingStats = \App\Models\Review::where('reviewed_id', $userId)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $avgRating = \App\Models\Review::where('reviewed_id', $userId)->avg('rating') ?: 0;
        $totalReviews = \App\Models\Review::where('reviewed_id', $userId)->count();

        return Inertia::render('Trader/Profile', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo_url' => $user->profile_photo_url ?? null,
                'online' => $user->last_seen && $user->last_seen->diffInMinutes(now()) < 5,
                'lastOnline' => $user->last_seen ? $user->last_seen->diffForHumans() : '未知',
                'memberSince' => $user->created_at->format('Y年m月d日'),
                'totalTrades' => $totalTrades,
                'completedTrades' => $completedTrades,
                'last30DaysTrades' => $last30DaysTrades,
                'usersTradedWith' => $tradingPartners,
                'rating' => round($avgRating, 2),
                'totalReviews' => $totalReviews,
                'completionRate' => $totalTrades > 0 ? round(($completedTrades / $totalTrades) * 100, 1) : 0,
                'responseTime' => $user->response_time,
                'timezone' => 'Asia/Shanghai',
                'languages' => ['中文', 'English'],
                'is_verified' => $user->hasVerifiedEmail(),
                'has_2fa' => $user->two_factor_secret !== null,
            ],
            'ratingStats' => $ratingStats,
            'isOwnProfile' => $isOwnProfile
        ]);
    }

    public function profile(Request $request): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $isAdmin = $user->is_admin ?? false;

        // Calculate active ads count
        $activeAdsCount = \App\Models\Ad::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        return Inertia::render('Profile/Index', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->profile_photo_url,
                'trades_count' => $user->trades_count ?? 0,
                'avg_rating' => $user->avg_rating ?? 0,
                'total_reviews' => $user->total_reviews ?? 0,
                'response_time' => $user->response_time,
                'active_ads' => $activeAdsCount,
                'is_verified' => $user->hasVerifiedEmail(),
                'has_2fa' => $user->two_factor_secret !== null,
            ],
            'isAdmin' => $isAdmin,
        ]);
    }

    public function notifications(Request $request): Response
    {
        // Check if user has any unread notifications
        $hasUnread = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->exists();

        // Only clear unread flag if no unread notifications exist
        if (!$hasUnread) {
            User::query()->where('id', Auth::id())->update(['unread' => 0]);
        }

        return Inertia::render('Notifications');
    }

    public function notificationSettings(Request $request): Response
    {
        return Inertia::render('Notifications/Settings');
    }

    public function phoneNotificationSettings(Request $request): Response
    {
        return Inertia::render('Notifications/PhoneSettings');
    }
}
