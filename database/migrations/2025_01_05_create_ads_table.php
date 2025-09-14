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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('chain', 50);
            $table->string('cryptocurrency', 20);
            $table->enum('trade_type', ['buy', 'sell']);
            $table->decimal('min_limit', 20, 2);
            $table->decimal('max_limit', 20, 2);
            $table->decimal('price', 20, 2)->nullable();
            $table->enum('price_model', ['fixed', 'dynamic'])->default('fixed');
            $table->decimal('margin', 8, 2)->nullable();
            $table->string('payment_method', 100);
            $table->integer('payment_time_limit')->default(15);
            $table->string('country', 100);
            $table->text('vendor_sell_toc')->nullable();
            $table->text('vendor_buy_toc')->nullable();
            $table->text('welcome_message')->nullable();
            $table->string('status')->default('active');
            $table->integer('trades_count')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('chain');
            $table->index('cryptocurrency');
            $table->index('trade_type');
            $table->index('country');
            $table->index('status');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
