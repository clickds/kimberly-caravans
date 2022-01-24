<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeStockItemSpecialOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_stock_item_special_offer', function (Blueprint $table) {
            $table->unsignedBigInteger('motorhome_stock_item_id');
            $table->unsignedBigInteger('special_offer_id');

            $table->unique(['motorhome_stock_item_id', 'special_offer_id'], 'motorhome_offers');

            $table->foreign('motorhome_stock_item_id', 'offer_motorhome_item_motorhome_id')->references('id')->on('motorhome_stock_items')->cascadeOnDelete();
            $table->foreign('special_offer_id', 'offer_motorhome_item_offer_id')->references('id')->on('special_offers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhome_stock_item_special_offer', function (Blueprint $table) {
            $table->dropForeign('offer_motorhome_item_motorhome_id');
            $table->dropForeign('offer_motorhome_item_offer_id');
        });
        Schema::dropIfExists('motorhome_stock_item_special_offer');
    }
}
