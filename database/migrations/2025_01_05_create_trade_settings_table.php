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
        Schema::create('trade_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->text('vendor_sell_toc')->nullable();
            $table->text('vendor_buy_toc')->nullable();
            $table->text('welcome_message')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_settings');
    }
};
