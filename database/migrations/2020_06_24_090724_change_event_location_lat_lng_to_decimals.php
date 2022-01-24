<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEventLocationLatLngToDecimals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_locations', function (Blueprint $table) {
            $table->decimal('latitude', 9, 6)->change();
            $table->decimal('longitude', 9, 6)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_locations', function (Blueprint $table) {
            $table->float('latitude', 9, 6)->change();
            $table->float('longitude', 9, 6)->change();
        });
    }
}
