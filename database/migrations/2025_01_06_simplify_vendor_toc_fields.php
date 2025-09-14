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
        // Update ads table
        Schema::table('ads', function (Blueprint $table) {
            $table->text('vendor_toc')->nullable()->after('payment_details');
        });
        
        // Migrate existing data - combine buy/sell toc
        DB::table('ads')->get()->each(function ($ad) {
            $toc = $ad->vendor_sell_toc ?: $ad->vendor_buy_toc;
            DB::table('ads')->where('id', $ad->id)->update(['vendor_toc' => $toc]);
        });
        
        // Remove old columns
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['vendor_sell_toc', 'vendor_buy_toc']);
        });
        
        // Update trade_settings table
        Schema::table('trade_settings', function (Blueprint $table) {
            $table->text('vendor_toc')->nullable()->after('user_id');
        });
        
        // Migrate existing trade settings data
        DB::table('trade_settings')->get()->each(function ($setting) {
            $toc = $setting->vendor_sell_toc ?: $setting->vendor_buy_toc;
            DB::table('trade_settings')->where('id', $setting->id)->update(['vendor_toc' => $toc]);
        });
        
        // Remove old columns
        Schema::table('trade_settings', function (Blueprint $table) {
            $table->dropColumn(['vendor_sell_toc', 'vendor_buy_toc']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore ads table
        Schema::table('ads', function (Blueprint $table) {
            $table->text('vendor_sell_toc')->nullable()->after('payment_details');
            $table->text('vendor_buy_toc')->nullable()->after('vendor_sell_toc');
        });
        
        // Migrate data back
        DB::table('ads')->get()->each(function ($ad) {
            DB::table('ads')->where('id', $ad->id)->update([
                'vendor_sell_toc' => $ad->vendor_toc,
                'vendor_buy_toc' => $ad->vendor_toc
            ]);
        });
        
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn('vendor_toc');
        });
        
        // Restore trade_settings table
        Schema::table('trade_settings', function (Blueprint $table) {
            $table->text('vendor_sell_toc')->nullable()->after('user_id');
            $table->text('vendor_buy_toc')->nullable()->after('vendor_sell_toc');
        });
        
        // Migrate data back
        DB::table('trade_settings')->get()->each(function ($setting) {
            DB::table('trade_settings')->where('id', $setting->id)->update([
                'vendor_sell_toc' => $setting->vendor_toc,
                'vendor_buy_toc' => $setting->vendor_toc
            ]);
        });
        
        Schema::table('trade_settings', function (Blueprint $table) {
            $table->dropColumn('vendor_toc');
        });
    }
};