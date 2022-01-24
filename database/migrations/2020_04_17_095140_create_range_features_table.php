<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRangeFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->integer('position')->default(0);
            $table->unsignedBigInteger('site_id')->index();
            $table->boolean('key');
            $table->boolean('warranty');
            $table->unsignedBigInteger('vehicle_range_id');
            $table->string('vehicle_range_type');
            $table->timestamps();

            $table->unique(['vehicle_range_type', 'vehicle_range_id', 'name']);
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
        Schema::table('range_features', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
        });
        Schema::dropIfExists('range_features');
    }
}
