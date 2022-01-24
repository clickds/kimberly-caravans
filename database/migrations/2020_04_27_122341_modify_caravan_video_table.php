<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCaravanVideoTable extends Migration
{
    public function up()
    {
        Schema::table('caravan_video', function (Blueprint $table) {
            $table->dropForeign(['caravan_id']);
            $table->dropColumn('caravan_id');
        });

        Schema::rename('caravan_video', 'caravan_range_videos');

        Schema::table('caravan_range_videos', function (Blueprint $table) {
            $table->unsignedBigInteger('caravan_range_id')->index();
            $table->foreign('caravan_range_id')->references('id')->on('caravan_ranges')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('caravan_range_videos', function (Blueprint $table) {
            $table->dropForeign(['caravan_range_id']);
            $table->dropColumn('caravan_range_id');
        });

        Schema::rename('caravan_range_videos', 'caravan_video');

        Schema::table('caravan_video', function (Blueprint $table) {
            $table->unsignedBigInteger('caravan_id')->index();
            $table->foreign('caravan_id')->references('id')->on('caravans')->onDelete('cascade');
        });
    }
}
