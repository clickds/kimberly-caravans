<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturerSpecialOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturer_special_offer', function (Blueprint $table) {
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('special_offer_id');

            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
            $table->foreign('special_offer_id')->references('id')->on('special_offers')->onDelete('cascade');
            $table->unique(['manufacturer_id', 'special_offer_id'], 'manufacturers_special_offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacturer_special_offer', function (Blueprint $table) {
            $table->dropForeign(['manufacturer_id']);
            $table->dropForeign(['special_offer_id']);
        });
        Schema::dropIfExists('manufacturer_special_offer');
    }
}
