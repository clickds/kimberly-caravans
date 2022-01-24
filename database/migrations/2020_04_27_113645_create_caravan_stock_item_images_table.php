<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanStockItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_stock_item_images', function (Blueprint $table) {
            $table->unsignedBigInteger('caravan_id');
            $table->unsignedBigInteger('media_id');
            $table->unique(['caravan_id', 'media_id']);

            $table->foreign('caravan_id')->references('id')->on('caravans')->onDelete('cascade');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_stock_item_images', function (Blueprint $table) {
            $table->dropForeign(['caravan_id']);
            $table->dropForeign(['media_id']);
        });
        Schema::dropIfExists('caravan_stock_item_images');
    }
}
