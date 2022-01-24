<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMotorhomeVideoTable extends Migration
{
    public function up()
    {
        Schema::table('motorhome_video', function (Blueprint $table) {
            $table->dropForeign(['motorhome_id']);
            $table->dropColumn('motorhome_id');
        });

        Schema::rename('motorhome_video', 'motorhome_range_videos');

        Schema::table('motorhome_range_videos', function (Blueprint $table) {
            $table->unsignedBigInteger('motorhome_range_id')->index();
            $table->foreign('motorhome_range_id')->references('id')->on('motorhome_ranges')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('motorhome_range_videos', function (Blueprint $table) {
            $table->dropForeign(['motorhome_range_id']);
            $table->dropColumn('motorhome_range_id');
        });

        Schema::rename('motorhome_range_videos', 'motorhome_video');

        Schema::table('motorhome_video', function (Blueprint $table) {
            $table->unsignedBigInteger('motorhome_id')->index();
            $table->foreign('motorhome_id')->references('id')->on('motorhomes')->onDelete('cascade');
        });
    }
}
