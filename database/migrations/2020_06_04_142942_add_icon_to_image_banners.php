<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconToImageBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_banners', function (Blueprint $table) {
            $table->string('icon')->default('None');
            $table->string('content_text_colour')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_banners', function (Blueprint $table) {
            $table->dropColumn('icon');
            $table->string('content_text_colour')->nullable(false)->change();
        });
    }
}
