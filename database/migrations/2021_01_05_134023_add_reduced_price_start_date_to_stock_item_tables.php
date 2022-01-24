<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReducedPriceStartDateToStockItemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caravan_stock_items', function (Blueprint $table) {
            $table->date('reduced_price_start_date')->nullable()->index();
        });

        Schema::table('motorhome_stock_items', function (Blueprint $table) {
            $table->date('reduced_price_start_date')->nullable()->index();
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
            $table->dropColumn('reduced_price_start_date');
        });

        Schema::table('motorhome_stock_items', function (Blueprint $table) {
            $table->dropColumn('reduced_price_start_date');
        });
    }
}
