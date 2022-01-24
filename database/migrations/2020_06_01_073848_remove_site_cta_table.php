<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSiteCtaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_cta', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropForeign(['cta_id']);
        });

        Schema::dropIfExists('site_cta');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('site_cta', function (Blueprint $table) {
            $table->bigInteger('site_id')->unsigned()->index();
            $table->bigInteger('cta_id')->unsigned()->index();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('cta_id')->references('id')->on('ctas')->onDelete('cascade');
        });
    }
}
