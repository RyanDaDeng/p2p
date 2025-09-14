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
        Schema::table('ads', function (Blueprint $table) {
            $table->string('trade_address')->nullable()->after('payment_time_limit');
            $table->string('currency_key')->nullable()->after('trade_address');
            $table->index('trade_address');
            $table->index('currency_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropIndex(['currency_key']);
            $table->dropIndex(['trade_address']);
            $table->dropColumn('currency_key');
            $table->dropColumn('trade_address');
        });
    }
};