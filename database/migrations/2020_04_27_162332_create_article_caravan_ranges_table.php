<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleCaravanRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_caravan_range', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('caravan_range_id');

            $table->unique(['article_id', 'caravan_range_id']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
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
        Schema::table('article_caravan_range', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropForeign(['caravan_range_id']);
        });
        Schema::dropIfExists('article_caravan_range');
    }
}
