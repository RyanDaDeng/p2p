<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique()->comment('订单号');
            
            // 用户关系
            $table->unsignedBigInteger('vendor_id')->comment('商家ID');
            $table->unsignedBigInteger('client_id')->comment('客户ID');
            $table->unsignedBigInteger('seller_id')->comment('卖方ID');
            $table->unsignedBigInteger('buyer_id')->comment('买方ID');
            $table->unsignedBigInteger('ad_id')->nullable()->comment('广告ID');
            
            // 交易信息
            $table->enum('trade_type', ['buy', 'sell'])->comment('交易类型：从客户视角');
            $table->string('crypto_currency', 10)->comment('加密货币类型');
            $table->decimal('crypto_amount', 20, 8)->comment('加密货币数量');
            $table->string('fiat_currency', 10)->comment('法币类型');
            $table->decimal('fiat_amount', 20, 2)->comment('法币金额');
            $table->decimal('price', 20, 2)->comment('单价');
            $table->decimal('fee', 20, 8)->default(0)->comment('手续费');
            
            // 广告快照（JSON存储完整广告信息）
            $table->json('ad_snapshot')->nullable()->comment('广告快照');
            
            // 支付方式快照
            $table->json('payment_methods')->nullable()->comment('支付方式快照');
            
            // 订单状态
            $table->enum('status', [
                'trading',      // 交易中
                'completed',    // 已完成
                'cancelled',    // 已取消
                'expired'       // 已过期
            ])->default('trading');
            
            // 托管状态
            $table->enum('escrow_status', [
                'order_initiated',      // 订单创建
                'vendor_confirmed',     // 商家确认
                'seller_paid',         // 卖家已转币
                'escrow_received',     // 托管已收到
                'buyer_paid',          // 买家已付款
                'seller_received',     // 卖家确认收款
                'escrow_released'      // 托管已释放
            ])->default('order_initiated');
            
            // 时间记录
            $table->timestamp('vendor_confirmed_at')->nullable();
            $table->timestamp('seller_paid_at')->nullable();
            $table->timestamp('escrow_received_at')->nullable();
            $table->timestamp('buyer_paid_at')->nullable();
            $table->timestamp('seller_received_at')->nullable();
            $table->timestamp('escrow_released_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // 托管地址
            $table->string('escrow_address')->nullable()->comment('托管钱包地址');
            $table->string('escrow_tx_hash')->nullable()->comment('托管交易哈希');
            $table->string('release_tx_hash')->nullable()->comment('释放交易哈希');
            
            $table->timestamps();
            
            // 索引
            $table->index('order_no');
            $table->index('vendor_id');
            $table->index('client_id');
            $table->index('seller_id');
            $table->index('buyer_id');
            $table->index('status');
            $table->index('escrow_status');
            $table->index(['status', 'escrow_status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};