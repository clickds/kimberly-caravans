<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropVideoCategoryIdFromVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['video_category_id']);
            $table->dropColumn('video_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->unsignedBigInteger('video_category_id')->nullable()->index();

            $table->foreign('video_category_id')->references('id')->on('video_categories')
                ->onDelete('set null');
        });
    }
}
