<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeSpecialOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_special_offer', function (Blueprint $table) {
            $table->foreignId('motorhome_id');
            $table->foreignId('special_offer_id');

            $table->unique(['special_offer_id', 'motorhome_id']);
            $table->foreign('motorhome_id')->references('id')->on('motorhomes')->onDelete('cascade');
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
        Schema::create('motorhome_special_offer', function (Blueprint $table) {
            $table->dropForeign(['motorhome_id']);
            $table->dropForeign(['special_offer_id']);
        });
        Schema::dropIfExists('motorhome_special_offer');
    }
}
