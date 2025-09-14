<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\TradeSettingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ArbitrationController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TelegramController;
use App\Http\Middleware\IsAdmin;

Route::get('/', function () {
    return view('landing');
});

Route::get('/terms', function () {
    return view('terms');
});

Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/trade-flow-guide', function () {
    return view('trade-flow-guide');
});

Route::get('/contact-support', function () {
    return view('contact-support');
});

Route::get('/dispute-resolution', function () {
    return view('dispute-resolution');
});

Route::get('/trading-fees', function () {
    return view('trading-fees');
});

Route::get('/kyc-verification', function () {
    return view('kyc-verification');
});

Route::middleware([])->namespace('App\Http\Controllers\Web')->name('web.')->group(function () {
    Route::get('/market', 'AppRouteController@market')->name('market');
    Route::get('/orders', 'AppRouteController@orders')->name('orders')->middleware('auth');
    Route::get('/trade/create-order', 'AppRouteController@createOrder')->name('trade.create-order');
    Route::get('/vendor/application', 'AppRouteController@vendorApplication')->name('vendor.application');
    Route::get('/wallet/address-verification', 'AppRouteController@addressVerification')->name('wallet.address-verification');
    Route::get('/publish', 'AppRouteController@publish')->name('publish')->middleware('auth');
    Route::get('/trader/profile/{id?}', 'AppRouteController@traderProfile')->name('trader.profile');
    Route::get('/profile', 'AppRouteController@profile')->name('profile')->middleware('auth');
    Route::get('/notifications', 'AppRouteController@notifications')->name('notifications')->middleware('auth');
    Route::get('/notifications/settings', 'AppRouteController@notificationSettings')->name('notifications.settings')->middleware('auth');
});

// Chat route (using the new controller)
Route::get('/trade/{orderNo}/chat', [ChatController::class, 'index'])->name('trade.chat')->middleware('auth');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/market');
    })->name('dashboard');
});

// Market Price API (公开接口，无需认证)
Route::prefix('web/api')->group(function () {
    Route::get('/market/prices', [MarketController::class, 'getPrices']);
    Route::get('/ads', [AdController::class, 'index']);
    // Config Routes (配置数据)
    Route::get('/config', [\App\Http\Controllers\Api\ConfigController::class, 'index']);
});

// 管理员路由
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/arbitration', [ArbitrationController::class, 'index'])->name('arbitration.index');
});

// API Routes for Ads (只需要auth认证)
Route::middleware(['auth'])->prefix('web/api')->group(function () {
    Route::get('/ads/my', [AdController::class, 'myAds']);
    Route::get('/ads/{id}', [AdController::class, 'show']);
    Route::post('/ads', [AdController::class, 'store']);
    Route::put('/ads/{id}', [AdController::class, 'update']);
    Route::post('/ads/{id}/archive', [AdController::class, 'archive']);
    Route::post('/ads/{id}/restore', [AdController::class, 'restore']);
    Route::post('/ads/{id}/toggle-status', [AdController::class, 'toggleStatus']);

    // Trade Settings
    Route::get('/trade-settings', [TradeSettingController::class, 'get']);
    Route::post('/trade-settings', [TradeSettingController::class, 'save']);

    // Telegram Notifications
    Route::get('/telegram/test-bot', [TelegramController::class, 'testBot']);
    Route::get('/telegram/settings', [TelegramController::class, 'getSettings']);
    Route::get('/telegram/updates', [TelegramController::class, 'getUpdates']);
    Route::post('/telegram/connect', [TelegramController::class, 'connect']);
    Route::post('/telegram/disconnect', [TelegramController::class, 'disconnect']);
    Route::post('/telegram/test', [TelegramController::class, 'sendTest']);

    // Order Routes (订单相关)
    Route::post('/orders', [OrderController::class, 'create']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::get('/orders/{id}/status', [OrderController::class, 'status']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);

    // Escrow Routes (托管流程)
    Route::post('/orders/{id}/vendor-confirm', [OrderController::class, 'vendorConfirm']);
    Route::post('/orders/{id}/seller-paid', [OrderController::class, 'sellerPaid']);
    Route::post('/orders/{id}/buyer-confirm-escrow', [OrderController::class, 'buyerConfirmEscrow']);
    Route::post('/orders/{id}/buyer-paid', [OrderController::class, 'buyerPaid']);
    Route::post('/orders/{id}/seller-received', [OrderController::class, 'sellerReceived']);
    Route::get('/orders/{id}/escrow-status', [OrderController::class, 'escrowStatus']);
    Route::get('/orders/{id}/escrow-logs', [OrderController::class, 'escrowLogs']);

    // Mock Routes (仅用于测试)
    Route::post('/orders/{id}/mock-escrow-received', [OrderController::class, 'mockEscrowReceived']);
    Route::post('/orders/{id}/mock-escrow-not-received', [OrderController::class, 'mockEscrowNotReceived']);

    // Chat Routes (订单聊天)
    Route::post('/orders/{orderNo}/chat/send', [ChatController::class, 'sendMessage']);
    Route::post('/orders/{orderNo}/chat/upload', [ChatController::class, 'uploadFile']);

    // Dispute Routes (争议相关)
    Route::post('/orders/{id}/dispute', [OrderController::class, 'dispute']);
    Route::post('/orders/{id}/resolve-dispute', [OrderController::class, 'resolveDispute']);

    // Review Routes (评价相关)
    Route::post('/orders/{id}/review', [ReviewController::class, 'store']);
    Route::get('/orders/{id}/review-status', [ReviewController::class, 'checkStatus']);
    Route::get('/users/{id}/reviews', [ReviewController::class, 'getUserReviews']);

    // Address Routes (地址管理)
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);

    // Notification Routes (通知管理)
    Route::get('/notifications', [NotificationController::class, 'index']);

});
