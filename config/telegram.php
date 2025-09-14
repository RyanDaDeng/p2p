<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the P2P Crypto Trading Platform Telegram Bot
    |
    */

    'bot_token' => env('TELEGRAM_BOT_TOKEN', '8336561075:AAH7_rFlq1yWQxzIiSwZrhm9Oq9mBQLIxYo'),
    
    'bot_username' => env('TELEGRAM_BOT_USERNAME', 'P2PCryptoCoinBot'),
    
    'bot_link' => env('TELEGRAM_BOT_LINK', 'https://t.me/P2PCryptoCoinBot'),
    
    'webhook_url' => env('TELEGRAM_WEBHOOK_URL', null),
    
    'api_base_url' => 'https://api.telegram.org/bot',
    
    'timeout' => env('TELEGRAM_TIMEOUT', 30),
    
    'connect_timeout' => env('TELEGRAM_CONNECT_TIMEOUT', 10),
];