<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escrow_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('操作用户');
            
            $table->string('action')->comment('操作类型');
            $table->string('from_status')->nullable()->comment('原状态');
            $table->string('to_status')->nullable()->comment('新状态');
            
            $table->json('data')->nullable()->comment('额外数据');
            $table->text('description')->nullable()->comment('描述');
            
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escrow_logs');
    }
};