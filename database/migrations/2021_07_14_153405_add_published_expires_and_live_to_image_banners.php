<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishedExpiresAndLiveToImageBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_banners', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable(true);
            $table->timestamp('expired_at')->nullable(true);
            $table->boolean('live')->default(true)->index();
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
            $table->dropColumn('published_at');
            $table->dropColumn('expired_at');
            $table->dropColumn('live');
        });
    }
}
