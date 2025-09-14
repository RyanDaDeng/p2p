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
            // Drop cryptocurrency column as we already have currency_key
            if (Schema::hasColumn('ads', 'cryptocurrency')) {
                $table->dropColumn('cryptocurrency');
            }
            
            // Rename chain to network
            if (Schema::hasColumn('ads', 'chain')) {
                $table->renameColumn('chain', 'network');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Add back cryptocurrency column
            if (!Schema::hasColumn('ads', 'cryptocurrency')) {
                $table->string('cryptocurrency')->nullable()->after('user_id');
            }
            
            // Rename back network to chain
            if (Schema::hasColumn('ads', 'network')) {
                $table->renameColumn('network', 'chain');
            }
        });
    }
};