<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockLinkBooleansToSpecialOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_offers', function (Blueprint $table) {
            $table->boolean('link_caravan_stock')->default(false)->index();
            $table->boolean('link_motorhome_stock')->default(false)->index();
            $table->boolean('link_managers_special_stock')->default(false)->index();
            $table->boolean('link_on_sale_stock')->default(false)->index();
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
            $table->dropColumn('link_caravan_stock');
            $table->dropColumn('link_motorhome_stock');
            $table->dropColumn('link_managers_special_stock');
            $table->dropColumn('link_on_sale_stock');
        });
    }
}
