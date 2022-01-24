<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeRangePopUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_range_pop_up', function (Blueprint $table) {
            $table->unsignedBigInteger('motorhome_range_id');
            $table->unsignedBigInteger('pop_up_id');

            $table->unique(['pop_up_id', 'motorhome_range_id']);
            $table->foreign('motorhome_range_id')->references('id')->on('motorhome_ranges')->onDelete('cascade');
            $table->foreign('pop_up_id')->references('id')->on('pop_ups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhome_range_pop_up', function (Blueprint $table) {
            $table->dropForeign(['motorhome_range_id']);
            $table->dropForeign(['pop_up_id']);
        });
        Schema::dropIfExists('motorhome_range_pop_up');
    }
}
