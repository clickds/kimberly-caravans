<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeStockItemFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_stock_item_features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('motorhome_stock_item_id')->index();
            $table->string('name');
            $table->timestamps();

            $table->foreign('motorhome_stock_item_id')->references('id')->on('motorhome_stock_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhome_stock_item_features', function (Blueprint $table) {
            $table->dropForeign(['motorhome_stock_item_id']);
        });
        Schema::dropIfExists('motorhome_stock_item_features');
    }
}
