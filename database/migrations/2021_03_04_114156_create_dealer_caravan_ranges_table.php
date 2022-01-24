<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerCaravanRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_caravan_ranges', function (Blueprint $table) {
            $table->unsignedBigInteger('dealer_id');
            $table->unsignedBigInteger('caravan_range_id');

            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');
            $table->foreign('caravan_range_id')->references('id')->on('caravan_ranges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealer_caravan_ranges', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
            $table->dropForeign(['caravan_range_id']);
        });

        Schema::dropIfExists('dealer_caravan_ranges');
    }
}
