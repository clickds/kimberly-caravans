<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRangeIdToCaravanStockItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caravan_stock_items', function (Blueprint $table) {
            $table->unsignedBigInteger('caravan_range_id')->nullable();
            $table->foreign('caravan_range_id')->references('id')->on('caravan_ranges');
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
            $table->dropForeign(['caravan_range_id']);
            $table->dropColumn('caravan_range_id');
        });
    }
}
