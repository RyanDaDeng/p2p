<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 为现有的广告添加一些测试 notes
        DB::table('ads')->where('id', '>', 0)->update([
            'notes' => DB::raw("CASE 
                WHEN trade_type = 'sell' THEN '大额优惠 | 新手友好 | 秒放币'
                WHEN trade_type = 'buy' THEN '仅工作日交易 | 需KYC验证'
                ELSE '快速响应 | 专业服务'
            END")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('ads')->update(['notes' => null]);
    }
};