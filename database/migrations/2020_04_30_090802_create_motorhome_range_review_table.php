<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeRangeReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_range_review', function (Blueprint $table) {
            $table->unsignedBigInteger('motorhome_range_id');
            $table->unsignedBigInteger('review_id');

            $table->unique(['review_id', 'motorhome_range_id']);
            $table->foreign('motorhome_range_id')->references('id')->on('motorhome_ranges')->onDelete('cascade');
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
        Schema::table('motorhome_range_review', function (Blueprint $table) {
            $table->dropForeign(['motorhome_range_id']);
            $table->dropForeign(['review_id']);
        });
        Schema::dropIfExists('motorhome_range_review');
    }
}
