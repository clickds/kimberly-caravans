<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSpecialOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_offers', function (Blueprint $table) {
            $table->renameColumn('link_caravan_stock', 'link_used_caravan_stock');
            $table->renameColumn('link_motorhome_stock', 'link_used_motorhome_stock');
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
            $table->renameColumn('link_used_caravan_stock', 'link_caravan_stock');
            $table->renameColumn('link_used_motorhome_stock', 'link_motorhome_stock');
        });
    }
}
