<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelSpecialOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_special_offer', function (Blueprint $table) {
            $table->unsignedBigInteger('panel_id');
            $table->unsignedBigInteger('special_offer_id');

            $table->foreign('panel_id')->references('id')->on('panels')->onDelete('cascade');
            $table->foreign('special_offer_id')->references('id')->on('special_offers')->onDelete('cascade');
            $table->unique(['panel_id', 'special_offer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panel_special_offer', function (Blueprint $table) {
            $table->dropForeign(['panel_id']);
            $table->dropForeign(['special_offer_id']);
        });
        Schema::dropIfExists('panel_special_offer');
    }
}
