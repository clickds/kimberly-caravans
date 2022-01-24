<?php

use App\Models\Review;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSiteIdFromReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('site_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('site_id');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
        Review::chunk(200, function ($reviews) {
            foreach ($reviews as $review) {
                $site = $review->sites->first();
                if ($site) {
                    $review->site_id = $site->id;
                    $review->save();
                }
            }
        });
    }
}
