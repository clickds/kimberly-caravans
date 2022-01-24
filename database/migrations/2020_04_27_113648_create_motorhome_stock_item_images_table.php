<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeStockItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_stock_item_images', function (Blueprint $table) {
            $table->unsignedBigInteger('motorhome_id');
            $table->unsignedBigInteger('media_id');
            $table->unique(['motorhome_id', 'media_id']);

            $table->foreign('motorhome_id')->references('id')->on('motorhomes')->onDelete('cascade');
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
        Schema::table('motorhome_stock_item_images', function (Blueprint $table) {
            $table->dropForeign(['motorhome_id']);
            $table->dropForeign(['media_id']);
        });
        Schema::dropIfExists('motorhome_stock_item_images');
    }
}
