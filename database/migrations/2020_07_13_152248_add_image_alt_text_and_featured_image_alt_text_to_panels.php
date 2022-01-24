<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageAltTextAndFeaturedImageAltTextToPanels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panels', function (Blueprint $table) {
            $table->string('featured_image_alt_text')->nullable();
            $table->string('image_alt_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panels', function (Blueprint $table) {
            $table->dropColumn('featured_image_alt_text');
            $table->dropColumn('image_alt_text');
        });
    }
}
