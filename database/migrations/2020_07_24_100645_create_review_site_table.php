<?php

use App\Models\Review;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_site', function (Blueprint $table) {
            $table->foreignId('review_id');
            $table->foreignId('site_id');

            $table->unique(['site_id', 'review_id']);
            $table->foreign('site_id')->references('id')->on('sites')->cascadeOnDelete();
            $table->foreign('review_id')->references('id')->on('reviews')->cascadeOnDelete();
        });
        Review::chunk(200, function ($reviews) {
            foreach ($reviews as $review) {
                $review->sites()->attach($review->site_id);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('review_site', function (Blueprint $table) {
            $table->dropForeign(['review_id']);
            $table->dropForeign(['site_id']);
        });
        Schema::dropIfExists('review_site');
    }
}
