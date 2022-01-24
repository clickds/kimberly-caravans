<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyVideosTable extends Migration
{
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->unsignedBigInteger('video_category_id')->after('id');
            $table->unsignedBigInteger('dealer_id')->after('video_category_id')->nullable();
            $table->string('type')->after('dealer_id')->index();

            $table->foreign('dealer_id')->references('id')->on('dealers');
            $table->foreign('video_category_id')->references('id')->on('video_categories');
        });
    }

    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['video_category_id']);
            $table->dropForeign(['dealer_id']);
            $table->dropColumn('video_category_id');
            $table->dropColumn('dealer_id');
            $table->dropColumn('type');
        });
    }
}
