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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('chain');
            $table->string('currency');
            $table->string('currency_key');
            $table->string('address');
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
            
            // Unique constraint to prevent address theft  
            $table->unique(['currency_key', 'address']);
            $table->index(['user_id', 'currency_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};