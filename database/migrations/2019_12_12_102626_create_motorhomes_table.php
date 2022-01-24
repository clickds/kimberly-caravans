<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('motorhome_range_id')->index();
            $table->unsignedBigInteger('layout_id')->index();
            $table->integer('position')->default(0)->index();
            $table->string('name');
            $table->unsignedInteger('berths');
            $table->string('engine_size')->nullable();
            $table->string('engine_power')->nullable();
            $table->string('engine_torque')->nullable();
            $table->string('transmission');
            $table->string('chassis_manufacturer');
            $table->string('fuel');
            $table->unsignedInteger('designated_seats');
            $table->unsignedInteger('year');
            $table->string('conversion');
            $table->unsignedDecimal('width', 8, 2)->nullable();
            $table->boolean('height_includes_aerial')->default(false);
            $table->unsignedDecimal('height', 8, 2)->nullable();
            $table->unsignedDecimal('length', 8, 2)->nullable();
            $table->unsignedInteger('mro');
            $table->unsignedInteger('mtplm');
            $table->unsignedInteger('payload');
            $table->boolean('exclusive')->default(false);
            $table->text('description');
            $table->text('small_print')->nullable();
            $table->timestamps();

            $table->unique(['name', 'year', 'motorhome_range_id']);
            $table->foreign('motorhome_range_id')->references('id')->on('motorhome_ranges')->onDelete('cascade');
            $table->foreign('layout_id')->references('id')->on('layouts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhomes', function (Blueprint $table) {
            $table->dropForeign(['motorhome_range_id']);
            $table->dropForeign(['layout_id']);
        });
        Schema::dropIfExists('motorhomes');
    }
}
