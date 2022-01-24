<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeRangeBrochuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_range_brochures', function (Blueprint $table) {
            $table->bigInteger('motorhome_range_id')->unsigned()->index();
            $table->bigInteger('brochure_id')->unsigned()->index();

            $table->foreign('motorhome_range_id')->references('id')->on('motorhome_ranges')->onDelete('cascade');
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
        Schema::table('motorhome_range_brochures', function (Blueprint $table) {
            $table->dropForeign(['motorhome_range_id']);
            $table->dropForeign(['brochure_id']);
        });

        Schema::dropIfExists('motorhome_range_brochures');
    }
}
