<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionalWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optional_weights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('motorhome_id')->index();
            $table->string('name');
            $table->text('value');
            $table->timestamps();

            $table->foreign('motorhome_id')->references('id')->on('motorhomes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('optional_weights', function (Blueprint $table) {
            $table->dropForeign(['motorhome_id']);
        });
        Schema::dropIfExists('optional_weights');
    }
}
