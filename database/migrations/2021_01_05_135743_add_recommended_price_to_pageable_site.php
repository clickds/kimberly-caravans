<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecommendedPriceToPageableSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pageable_site', function (Blueprint $table) {
            $table->unsignedDecimal('recommended_price', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pageable_site', function (Blueprint $table) {
            $table->dropColumn('recommended_price');
        });
    }
}
