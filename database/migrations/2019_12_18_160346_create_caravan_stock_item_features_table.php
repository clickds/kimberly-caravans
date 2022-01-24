<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanStockItemFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_stock_item_features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('caravan_stock_item_id')->index();
            $table->string('name');
            $table->timestamps();

            $table->foreign('caravan_stock_item_id')->references('id')->on('caravan_stock_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_stock_item_features', function (Blueprint $table) {
            $table->dropForeign(['caravan_stock_item_id']);
        });
        Schema::dropIfExists('caravan_stock_item_features');
    }
}
