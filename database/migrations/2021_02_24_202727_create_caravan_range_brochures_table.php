<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanRangeBrochuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_range_brochures', function (Blueprint $table) {
            $table->bigInteger('caravan_range_id')->unsigned()->index();
            $table->bigInteger('brochure_id')->unsigned()->index();

            $table->foreign('caravan_range_id')->references('id')->on('caravan_ranges')->onDelete('cascade');
            $table->foreign('brochure_id')->references('id')->on('brochures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_range_brochures', function (Blueprint $table) {
            $table->dropForeign(['caravan_range_id']);
            $table->dropForeign(['brochure_id']);
        });

        Schema::dropIfExists('caravan_range_brochures');
    }
}
