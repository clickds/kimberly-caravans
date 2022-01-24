<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvailableDateAndDueInDateToMotorhomeStockItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorhome_stock_items', function (Blueprint $table) {
            $table->date('available_date')->nullable()->index();
            $table->date('due_in_date')->nullable()->index();
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
            $table->dropColumn('available_date');
            $table->dropColumn('due_in_date');
        });
    }
}
