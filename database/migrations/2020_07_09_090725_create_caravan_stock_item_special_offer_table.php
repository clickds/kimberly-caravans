<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanStockItemSpecialOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_stock_item_special_offer', function (Blueprint $table) {
            $table->unsignedBigInteger('caravan_stock_item_id');
            $table->unsignedBigInteger('special_offer_id');

            $table->unique(['caravan_stock_item_id', 'special_offer_id'], 'caravan_offers');

            $table->foreign('caravan_stock_item_id', 'offer_caravan_item_caravan_id')->references('id')->on('caravan_stock_items')->cascadeOnDelete();
            $table->foreign('special_offer_id', 'offer_caravan_item_offer_id')->references('id')->on('special_offers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_stock_item_special_offer', function (Blueprint $table) {
            $table->dropForeign('offer_caravan_item_caravan_id');
            $table->dropForeign('offer_caravan_item_offer_id');
        });
        Schema::dropIfExists('caravan_stock_item_special_offer');
    }
}
