<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteTestimonialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_testimonial', function (Blueprint $table) {
            $table->bigInteger('site_id')->unsigned()->index();
            $table->bigInteger('testimonial_id')->unsigned()->index();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('testimonial_id')->references('id')->on('testimonials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_testimonial', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropForeign(['testimonial_id']);
        });
        Schema::dropIfExists('site_testimonial');
    }
}
