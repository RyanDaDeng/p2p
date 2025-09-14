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
        // Remove payment fields from ads table
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_details']);
        });
        
        // Remove payment_methods from orders table  
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore payment fields to ads table
        Schema::table('ads', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('margin');
            $table->text('payment_details')->nullable()->after('payment_method');
        });
        
        // Restore payment_methods to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->json('payment_methods')->nullable()->after('fee');
        });
    }
};