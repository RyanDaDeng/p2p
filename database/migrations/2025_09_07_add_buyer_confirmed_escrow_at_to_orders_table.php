<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 更新 escrow_status 枚举，在 escrow_received 后添加 buyer_confirmed_escrow
        DB::statement("ALTER TABLE orders MODIFY COLUMN escrow_status ENUM(
            'order_initiated',
            'vendor_confirmed',
            'seller_paid',
            'escrow_received',
            'buyer_confirmed_escrow',
            'buyer_paid',
            'seller_received',
            'escrow_released'
        ) DEFAULT 'order_initiated'");
        
        // 添加 buyer_confirmed_escrow_at 字段，放在 escrow_received_at 之后
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('buyer_confirmed_escrow_at')->nullable()->after('escrow_received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('buyer_confirmed_escrow_at');
        });
        
        // 恢复原来的枚举值
        DB::statement("ALTER TABLE orders MODIFY COLUMN escrow_status ENUM(
            'order_initiated',
            'vendor_confirmed',
            'seller_paid',
            'escrow_received',
            'buyer_paid',
            'seller_received',
            'escrow_released'
        ) DEFAULT 'order_initiated'");
    }
};