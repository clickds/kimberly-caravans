<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_video', function (Blueprint $table) {
            $table->bigInteger('caravan_id')->unsigned()->index();
            $table->bigInteger('video_id')->unsigned()->index();

            $table->foreign('caravan_id')->references('id')->on('caravans')->onDelete('cascade');
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
        Schema::table('caravan_video', function (Blueprint $table) {
            $table->dropForeign(['caravan_id']);
            $table->dropForeign(['video_id']);
        });
        Schema::dropIfExists('caravan_video');
    }
}
