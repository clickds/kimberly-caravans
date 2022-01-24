<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSpecialOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_special_offer', function (Blueprint $table) {
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('special_offer_id');

            $table->index(['site_id', 'special_offer_id']);
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
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
        Schema::table('site_special_offer', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropForeign(['special_offer_id']);
        });
        Schema::drop('site_special_offer');
    }
}
