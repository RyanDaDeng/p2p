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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating')->comment('Rating from 1 to 5');
            $table->text('comment')->nullable();
            $table->enum('trade_type', ['buy', 'sell'])->comment('Trade type from vendor perspective');
            $table->string('crypto_currency', 20);
            $table->decimal('fiat_amount', 20, 2)->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index('reviewed_id');
            $table->index('reviewer_id');
            $table->index('order_id');
            
            // Ensure one review per order
            $table->unique('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};