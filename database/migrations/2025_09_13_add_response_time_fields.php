<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add response_time to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('response_time')->nullable()->comment('Average response time in minutes');
        });

        // Add response time fields to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('vendor_first_response_at')->nullable()->comment('Vendor first response timestamp');
            $table->integer('response_time_minutes')->nullable()->comment('Response time in minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('response_time');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['vendor_first_response_at', 'response_time_minutes']);
        });
    }
};