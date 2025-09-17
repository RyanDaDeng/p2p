<?php

use Illuminate\Support\Facades\Route;

Route::middleware([])->namespace('App\Http\Controllers\Webhook')->group(function () {
    Route::post('/secret-fi-b1Abgt', 'FireblocksWebhookWebController@receivePayment')->name('webhook.fireblocks');
});
