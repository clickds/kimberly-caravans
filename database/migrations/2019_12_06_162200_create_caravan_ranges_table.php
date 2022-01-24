<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_ranges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('manufacturer_id')->nullable(true);
            $table->string('name');
            $table->text('overview')->nullable(true);
            $table->integer('position')->nullable(false)->default(0);
            $table->timestamps();

            $table->unique(['name', 'manufacturer_id']);
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_ranges', function (Blueprint $table) {
            $table->dropForeign(['manufacturer_id']);
        });
        Schema::dropIfExists('caravan_ranges');
    }
}
