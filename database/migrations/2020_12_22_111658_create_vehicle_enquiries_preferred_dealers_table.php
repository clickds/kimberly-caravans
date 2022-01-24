<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleEnquiriesPreferredDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_enquiries_preferred_dealers', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_enquiry_id');
            $table->unsignedBigInteger('dealer_id');
            $table->timestamps();

            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');
            $table->foreign('vehicle_enquiry_id')->references('id')->on('vehicle_enquiries')->onDelete('cascade');
            $table->primary(['vehicle_enquiry_id', 'dealer_id'], 'vepd_veid_did_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_enquiries_preferred_dealers', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
            $table->dropForeign(['vehicle_enquiry_id']);
        });

        Schema::dropIfExists('vehicle_enquiries_preferred_dealers');
    }
}
