<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_video', function (Blueprint $table) {
            $table->bigInteger('motorhome_id')->unsigned()->index();
            $table->bigInteger('video_id')->unsigned()->index();

            $table->foreign('motorhome_id')->references('id')->on('motorhomes')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhome_video', function (Blueprint $table) {
            $table->dropForeign(['motorhome_id']);
            $table->dropForeign(['video_id']);
        });
        Schema::dropIfExists('motorhome_video');
    }
}
