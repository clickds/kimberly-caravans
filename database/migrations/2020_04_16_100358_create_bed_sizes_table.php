<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBedSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('vehicle_type');
            $table->unsignedBigInteger('bed_description_id');
            $table->text('details');
            $table->timestamps();

            $table->unique(['vehicle_type', 'vehicle_id', 'bed_description_id']);
            $table->foreign('bed_description_id')->references('id')->on('bed_descriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bed_sizes', function (Blueprint $table) {
            $table->dropForeign(['bed_description_id']);
        });
        Schema::dropIfExists('bed_sizes');
    }
}
