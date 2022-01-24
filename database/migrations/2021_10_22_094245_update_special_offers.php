<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpecialOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_offers', function (Blueprint $table) {
            $table->boolean('link_feed_special_offers_caravans')->default(false);
            $table->boolean('link_feed_special_offers_motorhomes')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('special_offers', function (Blueprint $table) {
            $table->dropColumn('link_feed_special_offers_caravans');
            $table->dropColumn('link_feed_special_offers_motorhomes');
        });
    }
}
