<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_site', function (Blueprint $table) {
            $table->bigInteger('caravan_id')->unsigned()->index();
            $table->bigInteger('site_id')->unsigned()->index();

            $table->unsignedDecimal('price', 8, 2)->nullable();
            $table->unsignedDecimal('recommended_price', 8, 2)->nullable();

            $table->unique(['caravan_id', 'site_id']);
            $table->foreign('caravan_id')->references('id')->on('caravans')->cascadeOnDelete();
            $table->foreign('site_id')->references('id')->on('sites')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_site', function (Blueprint $table) {
            $table->dropForeign(['caravan_id']);
            $table->dropForeign(['site_id']);
        });
        Schema::dropIfExists('caravan_site');
    }
}
