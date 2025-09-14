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
        Schema::table('orders', function (Blueprint $table) {
            // 争议相关字段
            $table->boolean('is_disputed')->default(false)->after('status');
            $table->unsignedBigInteger('disputed_by')->nullable()->after('is_disputed');
            
            // 添加外键约束
            $table->foreign('disputed_by')->references('id')->on('users')->nullOnDelete();
            
            // 添加索引以优化查询
            $table->index('is_disputed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['disputed_by']);
            $table->dropIndex(['is_disputed']);
            $table->dropColumn(['is_disputed', 'disputed_by']);
        });
    }
};