<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageBannerPageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_banner_page', function (Blueprint $table) {
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('image_banner_id');

            $table->unique(['page_id', 'image_banner_id']);

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('image_banner_id')->references('id')->on('image_banners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_banner_page', function (Blueprint $table) {
            $table->dropForeign(['page_id']);
            $table->dropForeign(['image_banner_id']);
        });
        Schema::dropIfExists('image_banner_page');
    }
}
