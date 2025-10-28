<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fireblocks_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->index()->nullable();
            $table->boolean('is_checked')->index()->default(false);
            $table->string('fireblocks_tx_id')->unique();
            $table->string('tx_hash')->nullable();
            $table->string('status');
            $table->string('asset');
            $table->string('quantity');
            $table->string('source')->nullable();
            $table->string('source_address')->nullable();
            $table->string('destination')->nullable();
            $table->string('destination_address')->nullable();
            $table->string('third_party_order_no')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->index('fireblocks_tx_id');
            $table->index('tx_hash');
            $table->index('status');
            $table->index('third_party_order_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fireblocks_transactions');
    }
};
