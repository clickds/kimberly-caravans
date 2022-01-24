<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanRangeReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_range_review', function (Blueprint $table) {
            $table->unsignedBigInteger('caravan_range_id');
            $table->unsignedBigInteger('review_id');

            $table->unique(['review_id', 'caravan_range_id']);
            $table->foreign('caravan_range_id')->references('id')->on('caravan_ranges')->onDelete('cascade');
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_range_review', function (Blueprint $table) {
            $table->dropForeign(['caravan_range_id']);
            $table->dropForeign(['review_id']);
        });
        Schema::dropIfExists('caravan_range_review');
    }
}
