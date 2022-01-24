<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dealer_id')->index();
            $table->boolean('service')->default(false)->index();
            $table->boolean('sales')->default(false)->index();
            $table->string('name');
            $table->string('line_1');
            $table->string('line_2')->nullable();
            $table->string('town');
            $table->string('county');
            $table->string('postcode');
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->float('latitude', 9, 6);
            $table->float('longitude', 9, 6);

            $table->timestamps();

            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealer_locations', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
        });
        Schema::dropIfExists('dealer_locations');
    }
}
