<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRangeSpecificationSmallPrintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_specification_small_prints', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('vehicle_range_id');
            $table->string('vehicle_range_type');
            $table->integer('position')->default(0)->index();
            $table->timestamps();

            $table->unique(['vehicle_range_type', 'vehicle_range_id', 'site_id', 'name'], 'vehicle_range_spec_small_print');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('range_specification_small_prints', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
        });
        Schema::dropIfExists('range_specification_small_prints');
    }
}
