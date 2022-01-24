<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('dealer_id')->after('id')->nullable();
            $table->unsignedBigInteger('event_location_id')->after('dealer_id')->nullable();
            $table->foreign('dealer_id')->references('id')->on('dealers');
            $table->foreign('event_location_id')->references('id')->on('event_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
            $table->dropForeign(['event_location_id']);
            $table->dropColumn('dealer_id');
            $table->dropColumn('event_location_id');
        });
    }
}
