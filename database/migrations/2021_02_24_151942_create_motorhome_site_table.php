<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_site', function (Blueprint $table) {
            $table->bigInteger('motorhome_id')->unsigned()->index();
            $table->bigInteger('site_id')->unsigned()->index();

            $table->unsignedDecimal('price', 8, 2)->nullable();
            $table->unsignedDecimal('recommended_price', 8, 2)->nullable();

            $table->unique(['motorhome_id', 'site_id']);
            $table->foreign('motorhome_id')->references('id')->on('motorhomes')->cascadeOnDelete();
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
        Schema::table('motorhome_site', function (Blueprint $table) {
            $table->dropForeign(['motorhome_id']);
            $table->dropForeign(['site_id']);
        });
        Schema::dropIfExists('motorhome_site');
    }
}
