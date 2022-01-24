<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDealerIdToCaravanStockItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caravan_stock_items', function (Blueprint $table) {
            $table->unsignedBigInteger('dealer_id')->nullable(true);
            $table->foreign('dealer_id')->references('id')->on('dealers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_stock_items', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
            $table->dropColumn('dealer_id');
        });
    }
}
