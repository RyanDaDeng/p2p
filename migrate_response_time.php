<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Add response_time to users table
if (!Schema::hasColumn('users', 'response_time')) {
    Schema::table('users', function (Blueprint $table) {
        $table->integer('response_time')->nullable()->comment('Average response time in minutes');
    });
    echo "Added response_time to users table\n";
}

// Add response time fields to orders table
if (!Schema::hasColumn('orders', 'vendor_first_response_at')) {
    Schema::table('orders', function (Blueprint $table) {
        $table->timestamp('vendor_first_response_at')->nullable()->comment('Vendor first response timestamp');
        $table->integer('response_time_minutes')->nullable()->comment('Response time in minutes');
    });
    echo "Added response time fields to orders table\n";
}

echo "Migration completed!\n";