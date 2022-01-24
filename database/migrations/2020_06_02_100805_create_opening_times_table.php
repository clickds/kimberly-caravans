<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpeningTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opening_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->index();
            $table->string('day')->index();
            $table->string('opens_at');
            $table->string('closes_at');
            $table->timestamps();

            $table->unique(['site_id', 'day']);
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
        Schema::table('opening_times', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
        });

        Schema::dropIfExists('opening_times');
    }
}
