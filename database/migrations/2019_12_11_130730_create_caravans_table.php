<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('caravan_range_id')->index();
            $table->unsignedBigInteger('layout_id')->index();
            $table->integer('position')->default(0)->index();
            $table->string('name');
            $table->string('axles');
            $table->unsignedDecimal('width', 8, 2)->nullable();
            $table->unsignedDecimal('height', 8, 2)->nullable();
            $table->unsignedDecimal('length', 8, 2)->nullable();
            $table->unsignedInteger('mro');
            $table->unsignedInteger('mtplm');
            $table->unsignedInteger('payload');
            $table->unsignedInteger('berths');
            $table->unsignedInteger('year');
            $table->boolean('exclusive')->default(false);
            $table->boolean('height_includes_aerial')->default(false);
            $table->text('description');
            $table->text('small_print')->nullable();
            $table->timestamps();

            $table->unique(['name', 'year', 'caravan_range_id']);
            $table->foreign('caravan_range_id')->references('id')->on('caravan_ranges')->onDelete('cascade');
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
        Schema::table('caravans', function (Blueprint $table) {
            $table->dropForeign(['caravan_range_id']);
            $table->dropForeign(['layout_id']);
        });
        Schema::dropIfExists('caravans');
    }
}
