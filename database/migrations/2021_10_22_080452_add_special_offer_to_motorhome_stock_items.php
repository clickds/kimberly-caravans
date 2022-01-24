<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialOfferToMotorhomeStockItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorhome_stock_items', function (Blueprint $table) {
            $table->boolean('special_offer')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhome_stock_items', function (Blueprint $table) {
            $table->dropColumn('special_offer');
        });
    }
}
