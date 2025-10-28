<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {

            $table->tinyInteger('status')->index()->after('address');
            $table->dateTime('last_checked')->index()->nullable()->after('address');
            $table->string('fireblocks_status')->index()->nullable()->after('address');
            $table->string('fireblocks_status_ref')->nullable()->after('address');
            $table->string('fireblocks_data')->nullable()->after('address');
            $table->longText('fireblocks_report')->nullable()->after('address');
//            $table->dropUnique('addresses_currency_key_address_unique');
            $table->index('address');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('last_checked');
            $table->dropColumn('status');
            $table->dropColumn('fireblocks_status');
            $table->dropColumn('fireblocks_status_ref');
            $table->dropColumn('fireblocks_data');
            $table->dropColumn('fireblocks_report');
//            $table->unique(['address','currency_key'], 'addresses_currency_key_address_unique');
            $table->dropIndex('address');
        });
    }
};
