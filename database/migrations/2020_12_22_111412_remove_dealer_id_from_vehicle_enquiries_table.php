<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDealerIdFromVehicleEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_enquiries', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
            $table->dropColumn('dealer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_enquiries', function (Blueprint $table) {
            $table->unsignedBigInteger('dealer_id')->index();
            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');
        });
    }
}
