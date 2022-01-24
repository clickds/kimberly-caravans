<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRangeIdToMotorhomeStockItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorhome_stock_items', function (Blueprint $table) {
            $table->unsignedBigInteger('motorhome_range_id')->nullable();
            $table->foreign('motorhome_range_id')->references('id')->on('motorhome_ranges');
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
            $table->dropForeign(['motorhome_range_id']);
            $table->dropColumn('motorhome_range_id');
        });
    }
}
