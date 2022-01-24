<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanSpecialOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_special_offer', function (Blueprint $table) {
            $table->foreignId('caravan_id');
            $table->foreignId('special_offer_id');

            $table->unique(['special_offer_id', 'caravan_id']);
            $table->foreign('caravan_id')->references('id')->on('caravans')->onDelete('cascade');
            $table->foreign('special_offer_id')->references('id')->on('special_offers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('caravan_special_offer', function (Blueprint $table) {
            $table->dropForeign(['caravan_id']);
            $table->dropForeign(['special_offer_id']);
        });

        Schema::dropIfExists('caravan_special_offer');
    }
}
