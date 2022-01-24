<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThemeColoursToCaravanAndMotorhomeRanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caravan_ranges', function (Blueprint $table) {
            $table->string('primary_theme_colour')->nullable();
            $table->string('secondary_theme_colour')->nullable();
        });

        Schema::table('motorhome_ranges', function (Blueprint $table) {
            $table->string('primary_theme_colour')->nullable();
            $table->string('secondary_theme_colour')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_ranges', function (Blueprint $table) {
            $table->dropColumn('primary_theme_colour');
            $table->dropColumn('secondary_theme_colour');
        });

        Schema::table('motorhome_ranges', function (Blueprint $table) {
            $table->dropColumn('primary_theme_colour');
            $table->dropColumn('secondary_theme_colour');
        });
    }
}
