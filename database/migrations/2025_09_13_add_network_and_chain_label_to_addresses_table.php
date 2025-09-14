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
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('network', 50)->nullable()->after('currency_key')->comment('网络类型 如 ERC-20, TRC-20, BEP-20');
            $table->string('chain_label', 100)->nullable()->after('network')->comment('链显示名称 如 Ethereum (ERC-20)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['network', 'chain_label']);
        });
    }
};
