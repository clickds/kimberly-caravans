<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanRangePopUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_range_pop_up', function (Blueprint $table) {
            $table->unsignedBigInteger('caravan_range_id');
            $table->unsignedBigInteger('pop_up_id');

            $table->unique(['pop_up_id', 'caravan_range_id']);
            $table->foreign('caravan_range_id')->references('id')->on('caravan_ranges')->onDelete('cascade');
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
        Schema::table('caravan_range_pop_up', function (Blueprint $table) {
            $table->dropForeign(['caravan_range_id']);
            $table->dropForeign(['pop_up_id']);
        });
        Schema::dropIfExists('caravan_range_pop_up');
    }
}
