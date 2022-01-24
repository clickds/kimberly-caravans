<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoVideoCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_video_category', function (Blueprint $table) {
            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('video_category_id');
            $table->unique(['video_id', 'video_category_id']);

            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->foreign('video_category_id')->references('id')->on('video_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_video_category', function (Blueprint $table) {
            $table->dropForeign(['video_id']);
            $table->dropForeign(['video_category_id']);
        });
        Schema::dropIfExists('video_video_category');
    }
}
